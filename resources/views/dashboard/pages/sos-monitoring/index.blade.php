@extends('dashboard.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row gy-6">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 id="totalSOS">0</h5><small>Total SOS</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 id="pendingSOS">0</h5><small>Pending</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 id="resolvedSOS">0</h5><small>Resolved</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 id="avgResponseTime">0</h5><small>Avg Response</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between">
                    <h5>Recent SOS (Last 24 Hours)</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshSOSAlerts()">Refresh</button>
                </div>
                <div class="card-body" id="recentSOSContainer">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between">
                    <h5>SOS Signal Monitor</h5>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search..."
                        style="width: 250px;">
                </div>

                <div class="table-responsive">
                    <table class="table" id="sosTable">
                        <thead>
                            <tr>
                                <th>Priority</th>
                                <th>Hiker</th>
                                <th>Mountain</th>
                                <th>Location</th>
                                <th>Time</th>
                                <th>Battery</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div id="loadingSpinner" class="text-center p-4" style="display:none;">
                    <div class="spinner-border text-primary"></div>
                </div>

                <div id="noDataMessage" class="text-center p-4" style="display:none;">
                    <p class="text-muted">No SOS signals found</p>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6"><span id="tableInfo"></span></div>
                        <div class="col-md-6">
                            <ul class="pagination justify-content-end" id="pagination"></ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="modal fade" id="sosDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>SOS Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="sosDetails"></div>
            </div>
        </div>
    </div>

   <div class="modal fade" id="sosMapModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- HEADER -->
            <div class="modal-header text-white mb-3">
                <h5 class="modal-title fw-semibold">
                    <i class="bi bi-geo-alt-fill me-2"></i> SOS Location Map
                </h5>
                <button type="button"
                        class="btn-close btn-close-white shadow-sm"
                        data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-0">
                <div id="sosMapContainer" style="width:100%;height:520px;">
                    <div id="sosMap" style="width:100%;height:100%;"></div>
                </div>

                <!-- FOOTER INFO -->
                <div class="p-3 bg-light border-top small text-muted d-flex align-items-center">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    <span id="sosMapInfo">Click marker for details</span>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="incomingSosModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-danger border-2">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    🚨 SOS ALERT MASUK
                </h5>
            </div>

            <div class="modal-body text-center">
                <h4 id="incomingSosMessage">Loading...</h4>
                <p id="incomingSosLocation"></p>

                <div id="incomingSosMap" style="height:350px;"></div>

                <div class="mt-3 d-flex justify-content-center gap-2">

                    <button onclick="stopSosAlarm()"
                        class="btn btn-warning px-4">
                        Stop Alarm
                    </button>

                    <button onclick="closeSosModal()"
                        class="btn btn-secondary px-4">
                        Close
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/ol/dist/ol.js"></script>

    <script>
        $(document).ready(function() {
            let currentPage = 1;
            let itemsPerPage = 10;
            let searchTerm = '';
            let totalRecords = 0;

            let sosMap = null;
            let sosOverlay = null;

            let incomingMap = null;
            let incomingVectorLayer = null;

            window.showIncomingSOS = function(data) {
                const audio = document.getElementById('alert-sound');

                audio.loop = true;
                audio.currentTime = 0;

                audio.play().catch(() => {
                    console.warn('Audio butuh interaksi user dulu');
                });

                $('#incomingSosMessage').text(
                    data.message || 'Pendaki Membutuhkan Bantuan!'
                );

                $('#incomingSosLocation').text(
                    `📍 ${data.latitude}, ${data.longitude}`
                );

                const modal = new bootstrap.Modal(document.getElementById('incomingSosModal'));
                modal.show();

                $('#incomingSosModal').on('shown.bs.modal', function () {
                    initIncomingMap(data.latitude, data.longitude);
                });

                loadSOSData();
                loadSOSStats();
            };

            window.stopSosAlarm = function() {
                const audio = document.getElementById('alert-sound');

                audio.pause();
                audio.currentTime = 0;

                console.log('🔇 Alarm stopped');
            };

            window.closeSosModal = function() {
                const modalEl = document.getElementById('incomingSosModal');
                const modal = bootstrap.Modal.getInstance(modalEl);

                modal.hide();
            };

            function initIncomingMap(lat, lon) {
                const lonFloat = parseFloat(lon);
                const latFloat = parseFloat(lat);

                if (incomingMap) {
                    incomingMap.setTarget(null);
                    incomingMap = null;
                }

                const feature = new ol.Feature({
                    geometry: new ol.geom.Point(
                        ol.proj.fromLonLat([lonFloat, latFloat])
                    )
                });

                incomingVectorLayer = new ol.layer.Vector({
                    source: new ol.source.Vector({ features: [feature] }),
                    style: new ol.style.Style({
                        image: new ol.style.Icon({
                            src: "https://cdn-icons-png.flaticon.com/512/535/535239.png",
                            anchor: [0.5, 1],
                            scale: 0.08
                        })
                    })
                });

                incomingMap = new ol.Map({
                    target: 'incomingSosMap',
                    layers: [
                        new ol.layer.Tile({ source: new ol.source.OSM() }),
                        incomingVectorLayer
                    ],
                    view: new ol.View({
                        center: ol.proj.fromLonLat([lonFloat, latFloat]),
                        zoom: 15
                    }),
                    controls: []
                });
            }

            loadSOSData();
            loadSOSStats();
            loadRecentSOSAlerts();

            $('#searchInput').on('keyup', function() {
                searchTerm = $(this).val();
                currentPage = 1;
                loadSOSData();
            });

            function loadSOSData() {
                $.ajax({
                    url: '{{ route('sos.getData') }}',
                    data: {
                        search: searchTerm,
                        start: (currentPage - 1) * itemsPerPage,
                        length: itemsPerPage
                    },
                    success: function(res) {
                        totalRecords = res.recordsTotal;
                        if (res.data.length > 0) {
                            renderTable(res.data);
                            renderPagination();
                        } else {
                            $('#sosTable tbody').html('');
                        }
                    }
                });
            }

            function loadSOSStats() {
                $.ajax({
                    url: '{{ route('sos.stats') }}',
                    success: function(r) {
                        $('#totalSOS').text(r.total_sos);
                        $('#pendingSOS').text(r.pending_sos);
                        $('#resolvedSOS').text(r.resolved_sos);
                        $('#avgResponseTime').text(r.avg_response_time);
                        renderRecentSOS(r.recent_sos);
                    }
                });
            }

            function loadRecentSOSAlerts() {
                loadSOSStats();
            }

            function renderRecentSOS(list) {
                let html = '';
                if (list.length === 0) {
                    html = `<div class="alert alert-success text-center">No SOS in last 24 hours</div>`;
                } else {
                    list.forEach(s => {
                        html += `
                    <div class="alert alert-warning">
                        <strong>${s.hiker_name}</strong> at ${s.mountain_name}<br>
                        <small>${timeAgo(s.timestamp)} ago</small>
                    </div>`;
                    });
                }
                $('#recentSOSContainer').html(html);
            }

            function renderTable(data) {
                let html = '';
                data.forEach(s => {
                    html += `
            <tr>
                <td>${s.priority}</td>
                <td>${s.hiker_name}<br><small>${s.phone}</small></td>
                <td>${s.mountain_name}</td>
                <td>${s.latitude}, ${s.longitude}</td>
                <td>${s.timestamp}</td>
                <td>${s.battery_level ?? 'N/A'}%</td>
                <td><button class="btn btn-primary btn-sm" onclick="viewSOS(${s.sos_id})">View</button></td>
            </tr>`;
                });
                $('#sosTable tbody').html(html);
            }

            function renderPagination() {
                const totalPages = Math.ceil(totalRecords / itemsPerPage);
                let html = '';
                for (let i = 1; i <= totalPages; i++) {
                    html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                     <a class="page-link" href="#" onclick="changePage(${i})">${i}</a></li>`;
                }
                $('#pagination').html(html);
            }

            function changePage(p) {
                currentPage = p;
                loadSOSData();
            }

            function viewSOS(id) {
                $.ajax({
                    url: `/dashboard/sos/${id}`,
                    success: function(r) {
                        let s = r.sos_signal;

                        $('#sosMapInfo').html('Loading map...');
                        $('#sosMapModal').modal('show');

                        setTimeout(() => initSOSMap(s), 300);
                    }
                });
            }

            function initSOSMap(s) {
                if (!s.lattitude || !s.longitude) {
                    $('#sosMapInfo').html('No valid location data.');
                    return;
                }

                if (sosMap) {
                    sosMap.setTarget(null);
                    sosMap = null;
                }

                const lon = parseFloat(s.longitude);
                const lat = parseFloat(s.lattitude);

                const feature = new ol.Feature({
                    geometry: new ol.geom.Point(
                        ol.proj.fromLonLat([lon, lat])
                    ),
                    data: s
                });

                const vectorSource = new ol.source.Vector({
                    features: [feature]
                });

                const vectorLayer = new ol.layer.Vector({
                    source: vectorSource,
                    style: new ol.style.Style({
                        image: new ol.style.Icon({
                            src: "https://cdn-icons-png.flaticon.com/512/535/535239.png",
                            anchor: [0.5, 1],
                            scale: 0.07
                        })
                    })
                });

                sosMap = new ol.Map({
                    target: 'sosMap',
                    layers: [
                        new ol.layer.Tile({
                            source: new ol.source.OSM()
                        }),
                        vectorLayer
                    ],
                    view: new ol.View({
                        center: ol.proj.fromLonLat([lon, lat]),
                        zoom: 13
                    }),
                    controls: []
                });

                const popup = document.createElement('div');
                popup.className = "ol-popup";

                sosOverlay = new ol.Overlay({
                    element: popup,
                    autoPan: true,
                    autoPanAnimation: {
                        duration: 200
                    }
                });

                sosMap.addOverlay(sosOverlay);

                sosMap.on('click', function(evt) {
                    const f = sosMap.forEachFeatureAtPixel(evt.pixel, x => x);
                    if (f) {
                        let d = f.get('data');
                        popup.innerHTML = `
                    <b>${d.hiker_name}</b><br>
                    ${d.mountain_name}<br>
                    ${d.timestamp}<br>
                    📍 ${d.lattitude}, ${d.longitude}
                `;
                        sosOverlay.setPosition(f.getGeometry().getCoordinates());

                        $('#sosMapInfo').html(`
                    <b>${d.hiker_name}</b><br>
                    ${d.mountain_name}<br>
                    Time: ${d.timestamp}<br>
                    Location: ${d.lattitude}, ${d.longitude}
                `);
                    } else {
                        sosOverlay.setPosition(undefined);
                    }
                });
            }

            function timeAgo(t) {
                let now = new Date();
                let past = new Date(t);
                let diff = now - past;
                let mins = Math.floor(diff / 60000);
                let hrs = Math.floor(mins / 60);
                mins = mins % 60;
                return hrs > 0 ? `${hrs}h ${mins}m` : `${mins}m`;
            }

            function refreshSOSAlerts() {
                loadRecentSOSAlerts();
            }

            window.changePage = changePage;
            window.viewSOS = viewSOS;
            window.refreshSOSAlerts = refreshSOSAlerts;
        });
    </script>
@endpush
