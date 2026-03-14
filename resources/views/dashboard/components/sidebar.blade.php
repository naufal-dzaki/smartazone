<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ Auth::user()->user_type == 'superadmin' ? route('superadmin.mountains.index') : route('dashboard.index') }}"
            class="app-brand-link">
            <span class="app-brand-logo demo me-1">
                <span class="text-primary">
                    <img src="../assets/img/icons/brands/logo.png" alt="SmartAzone Logo" class="w-px-40 h-auto">
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-semibold ms-2">SmartAzone</span>
        </a>

        {{-- <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
        </a> --}}
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @if (Auth::user() && Auth::user()->user_type === 'admin')
            <li class="menu-item">
                <a href="{{ route('dashboard.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-bar-chart-box-line"></i>
                    <div data-i18n="Data Analitik">Data Analitik</div>
                    <div class="badge rounded-pill bg-label-success fs-tiny ms-auto">📈</div>
                </a>
            </li>

            <li class="menu-item {{ Route::is('bookings.*') ? 'active' : '' }}">
                <a href="{{ route('bookings.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-calendar-check-line"></i>
                    <div data-i18n="Pemesanan">Pemesanan Tiket</div>
                    <div class="badge rounded-pill bg-label-primary fs-tiny ms-auto">📅</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('hikers.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-group-line"></i>
                    <div data-i18n="List Pendaki">Pendaki</div>
                    <div class="badge rounded-pill bg-label-info fs-tiny ms-auto">🧍‍♂️</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('health.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-heart-pulse-line"></i>
                    <div data-i18n="Monitor Kesehatan">Monitor Kesehatan</div>
                    <div class="badge rounded-pill bg-label-warning fs-tiny ms-auto">🔬</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('hiker-history.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-history-line"></i>
                    <div data-i18n="Riwayat Pendakian">Riwayat Pendakian</div>
                    <div class="badge rounded-pill bg-label-secondary fs-tiny ms-auto">📜</div>
                </a>
            </li>

            {{-- <li class="menu-item">
                <a href="{{ route('complaints.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-feedback-line"></i>
                    <div data-i18n="List Pengaduan">Pengaduan</div>
                    <div class="badge rounded-pill bg-label-danger fs-tiny ms-auto">📄</div>
                </a>
            </li> --}}

            <li class="menu-item">
                <a href="{{ route('equipment-rentals.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-tools-line"></i>
                    <div data-i18n="List Peminjaman Alat">Peminjaman Alat</div>
                    <div class="badge rounded-pill bg-label-warning fs-tiny ms-auto">🧰</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('feedback.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-star-line"></i>
                    <div data-i18n="List Feedback & Rating">Feedback & Rating</div>
                    <div class="badge rounded-pill bg-label-success fs-tiny ms-auto">🌟</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('mountain_hikers.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-map-pin-line"></i>
                    <div data-i18n="Pendaki Aktif">Pendaki Aktif</div>
                    <div class="badge rounded-pill bg-label-info fs-tiny ms-auto">🗺️</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('hiker.link') }}" class="menu-link">
                    <i class="menu-icon icon-base ri ri-smartphone-line"></i>
                    <div data-i18n="Hiker Devices">Hiker Devices</div>
                    <div class="badge rounded-pill bg-label-primary fs-tiny ms-auto">📱</div>
                </a>
            </li>
<li class="menu-item">
    <a href="{{ route('sos.index') }}" class="menu-link">
        <i class="menu-icon icon-base ri ri-alarm-warning-line"></i>
        <div data-i18n="SOS Monitoring">SOS Monitoring</div>
        <div class="badge rounded-pill bg-label-danger fs-tiny ms-auto">‼️</div>
    </a>
</li>

        @elseif(Auth::user() && Auth::user()->user_type === 'superadmin')
            <li class="menu-item">
                <a href="{{ route('superadmin.mountains.index') }}" class="menu-link">
                    <i class="menu-icon icon-base bi bi-bezier2"></i>
                    <div data-i18n="Manajemen Gunung (Superadmin)">Manajemen Gunung</div>
                    <div class="badge rounded-pill bg-label-dark fs-tiny ms-auto">🗻</div>
                </a>
            </li>
        @endif
    </ul>

</aside>
