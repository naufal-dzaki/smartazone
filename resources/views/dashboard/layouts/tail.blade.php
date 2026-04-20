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
