<audio id="alert-sound" src="{{ asset('assets/sound/alert.mp3') }}" preload="auto" loop></audio>

    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script>
        $(document).ready(function () {
            const audio = document.getElementById('alert-sound');

            $(document).one('click', function () {
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                }).catch(() => {});
                console.log('🔊 Audio unlocked by user interaction');
            });

            Pusher.logToConsole = false;
            const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
                forceTLS: true
            });

            const mountainId = "{{ auth()->user()->mountain_id }}";
            const channel = pusher.subscribe(`mountain-sos.${mountainId}`);

            channel.bind('sos.created', function (data) {
                console.log('📡 SOS event received:', data);

                if (typeof window.showIncomingSOS === 'function') {
                    window.showIncomingSOS(data.sosData);
                }
            });

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

                if (typeof loadSOSData === 'function' && typeof loadSOSStats === 'function') {
                    loadSOSData();
                    loadSOSStats();
                }
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
        });
    </script>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script async="async" defer="defer" src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
