@extends('dashboard.layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Pendaki Aktif</h5>
                    <button class="btn btn-sm btn-primary" id="refreshList"><i class="ri-refresh-line me-1"></i>
                        Refresh</button>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover" id="hikerTable">
                        <thead>
                            <tr>
                                <th>Nama Pendaki</th>
                                <th>Telepon</th>
                                <th>Periode Pendakian</th>
                                <th>Jumlah Tim</th>
                                <th>Koordinat Terakhir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
                        <div class="modal-header text-white">
                            <h5 class="modal-title fw-semibold" id="mapModalLabel">Peta Lokasi Pendaki</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0 position-relative">
                            <div id="mapContainer" style="width:100%;height:550px;">
                                <div id="hikerMap" style="width:100%;height:100%;"></div>
                                <div id="infoOverlay"
                                    class="position-absolute top-0 start-0 m-3 bg-white bg-opacity-75 p-3 rounded shadow-sm"
                                    style="z-index:999;">
                                    <h6 class="fw-bold mb-1 text-dark">Info Pendaki</h6>
                                    <div id="infoContent" class="text-muted small">Klik marker untuk melihat detail</div>
                                </div>
                            </div>
                            <div class="p-3 border-top bg-light" id="logDetails"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol/ol.css" />
    <style>
        #mapModal .modal-body {
            background-color: #f8f9fa;
        }

        .ol-popup {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
            padding: 8px 12px;
            border: 1px solid #ddd;
            font-size: 13px;
            line-height: 1.5;
        }

        .ol-popup:after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 40px;
            border-width: 10px 10px 0;
            border-style: solid;
            border-color: white transparent;
        }

        .ol-zoom,
        .ol-attribution {
            display: none;
        }
    </style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let logsCache = {}
    let activeBookingId = null
    let mapInstance = null
    let overlayPopup = null

    function loadHikers() {
        $('#hikerTable tbody').html(
            '<tr><td colspan="6" class="text-center text-muted p-3">Memuat data...</td></tr>')
        $.ajax({
            url: '{{ route('mountain_hikers.list') }}',
            method: 'GET',
            success: function(res) {
                const hikers = res.data || []
                if (hikers.length === 0) {
                    $('#hikerTable tbody').html(
                        '<tr><td colspan="6" class="text-center text-muted p-3">Tidak ada pendaki aktif.</td></tr>')
                    return
                }
                let html = ''
                $.each(hikers, function(_, h) {
                    html += `
                        <tr>
                            <td>${h.user_name}</td>
                            <td>${h.phone ?? '-'}</td>
                            <td><small>${h.hike_date} - ${h.return_date}</small></td>
                            <td>${h.team_size}</td>
                            <td>${h.latitude} - ${h.longitude}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-map" data-id="${h.booking_id}">
                                    Lihat
                                </button>
                            </td>
                        </tr>`
                })
                $('#hikerTable tbody').html(html)
            }
        })
    }

    $(document).on('click', '.view-map', function() {
        activeBookingId = $(this).data('id')
        $('#logDetails').html('<p class="text-muted m-3">Memuat data lokasi...</p>')
        $('#mapModal').modal('show')
    })

    $('#mapModal').on('shown.bs.modal', function() {
        if (!activeBookingId) return
        if (logsCache[activeBookingId]) renderMap(logsCache[activeBookingId])
        else {
            $.ajax({
                url: '{{ route('mountain_hikers.logs') }}',
                method: 'GET',
                data: { id: activeBookingId },
                success: function(res) {

                    let logs = res.logs || {}

                    // pastikan menjadi array agar map bekerja
                    logs = Array.isArray(logs) ? logs : [logs]

                    logsCache[activeBookingId] = logs
                    renderMap(logs)
                },
                error: function() {
                    $('#logDetails').html(
                        '<div class="alert alert-danger m-3">Gagal memuat data.</div>')
                }
            })
        }
    })

    function renderMap(logs) {

        if (mapInstance) {
            mapInstance.setTarget(null);
            mapInstance = null
        }

        if (!logs || logs.length === 0 || !logs[0].latitude || !logs[0].longitude) {
            $('#logDetails').html(
                '<div class="alert alert-warning m-3">Tidak ada data lokasi ditemukan.</div>')
            $('#infoContent').html('Tidak ada titik ditemukan.')
            return
        }

        const first = logs[0]

        const features = logs.map(log => new ol.Feature({
            geometry: new ol.geom.Point(
                ol.proj.fromLonLat([
                    parseFloat(log.longitude),
                    parseFloat(log.latitude)
                ])
            ),
            data: log
        }))

        const vectorSource = new ol.source.Vector({ features })
        const vectorLayer = new ol.layer.Vector({
            source: vectorSource,
            style: new ol.style.Style({
                image: new ol.style.Icon({
                    anchor: [0.5, 1],
                    src: 'https://cdn-icons-png.flaticon.com/512/535/535239.png',
                    scale: 0.06
                })
            })
        })

        mapInstance = new ol.Map({
            target: 'hikerMap',
            layers: [
                new ol.layer.Tile({ source: new ol.source.OSM() }),
                vectorLayer
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([
                    parseFloat(first.longitude),
                    parseFloat(first.latitude)
                ]),
                zoom: 13
            }),
            controls: []
        })

        const extent = vectorSource.getExtent()
        mapInstance.getView().fit(extent, {
            size: mapInstance.getSize(),
            padding: [100, 100, 100, 100],
            duration: 800,
            maxZoom: 14
        })

        const popup = document.createElement('div')
        popup.className = 'ol-popup'
        overlayPopup = new ol.Overlay({
            element: popup,
            autoPan: true,
            autoPanAnimation: { duration: 250 }
        })
        mapInstance.addOverlay(overlayPopup)

        mapInstance.on('click', function(evt) {
            const feature = mapInstance.forEachFeatureAtPixel(evt.pixel, f => f)
            if (feature) {
                const d = feature.get('data')
                popup.innerHTML = `
                    <b>${d.timestamp}</b><br>
                    ❤️ BPM: ${d.heart_rate ?? '-'}<br>
                    🩸 SpO₂: ${d.spo2 ?? '-'}<br>
                    😌 Stres: ${d.stress_level ?? '-'}
                `
                overlayPopup.setPosition(feature.getGeometry().getCoordinates())
                $('#infoContent').html(`
                    <b>Waktu:</b> ${d.timestamp}<br>
                    <b>BPM:</b> ${d.heart_rate ?? '-'} | <b>SpO₂:</b> ${d.spo2 ?? '-'}<br>
                    <b>Stres:</b> ${d.stress_level ?? '-'}
                `)
            } else {
                overlayPopup.setPosition(undefined)
            }
        })

        $('#logDetails').html(`Total titik: ${logs.length}`)
        $('#infoContent').html('Klik marker untuk melihat detail data kesehatan.')
    }

    $('#refreshList').click(loadHikers)
    loadHikers()
})
</script>

@endpush
