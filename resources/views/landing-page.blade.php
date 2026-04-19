@extends('main-layout')

@section('content')
    <div class="bg-white min-h-screen overflow-hidden relative">
        <div class="relative h-screen overflow-hidden">
            <div class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                    alt="Mountain Vista" class="rellax w-full h-full object-cover scale-110" data-rellax-speed="-4"
                    id="heroImage">
            </div>

            <div class="absolute inset-0 pointer-events-none"
                style="background: linear-gradient(to bottom, rgba(27, 73, 101, 0.1) 0%, transparent 40%, rgba(27, 73, 101, 0.85) 100%);">
            </div>

            <div class="absolute top-6 right-6 z-20">
                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl px-4 py-2.5 shadow-2xl">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        <span class="text-white text-sm font-semibold tracking-wide">3.339 MDPL</span>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 z-20 pb-32">
                <div class="px-8 space-y-6">
                    <!-- Status Badge -->
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center gap-2 bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 rounded-full px-4 py-1.5">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-white text-xs font-medium tracking-wide">BUKA</span>
                        </div>
                        <div
                            class="flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-4 py-1.5">
                            <span class="text-white text-xs font-medium">☀️ CERAH</span>
                        </div>
                    </div>

                    <!-- Mountain Name - Tipografi Tegas -->
                    <div class="space-y-2">
                        <h1 class="text-white text-5xl font-bold tracking-tight leading-none drop-shadow-2xl">
                            Gunung<br>Arjuna
                        </h1>
                        <p class="text-white/80 text-lg font-light tracking-wide">
                            Jawa Timur, Indonesia
                        </p>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 animate-bounce">
                <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </div>
        </div>

        <!-- Content Section - Swiss Grid Layout -->
        <div class="px-8 py-12 space-y-16 pb-56">
            <!-- Quick Stats & About Combined -->
            <section class="space-y-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-3 gap-2">
                    <div
                        class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-2xl p-4 text-center border border-emerald-100">
                        <div class="text-2xl font-bold text-gray-900">15°</div>
                        <div class="text-xs text-gray-600 font-medium uppercase tracking-wide mt-1">Suhu</div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-2xl p-4 text-center border border-amber-100">
                        <div class="text-2xl font-bold text-gray-900">142</div>
                        <div class="text-xs text-gray-600 font-medium uppercase tracking-wide mt-1">Pendaki</div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-2xl p-4 text-center border border-purple-100">
                        <div class="text-2xl font-bold text-gray-900">4.8</div>
                        <div class="text-xs text-gray-600 font-medium uppercase tracking-wide mt-1">Rating</div>
                    </div>
                </div>

                <!-- About -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-0.5 bg-gray-900"></div>
                        <h2 class="text-sm font-bold text-gray-900 tracking-wider uppercase">Tentang</h2>
                    </div>
                    <p class="text-gray-700 leading-loose text-base font-light">
                        Nikmati tantangan mendaki salah satu puncak tertinggi di Jawa Timur.
                        Pemandangan spektakuler dan pengalaman tak terlupakan menanti Anda di ketinggian 3.339 meter.
                    </p>
                </div>
            </section>

            <!-- Pricing & Regulation Preview -->
            <section class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-0.5 bg-gray-900"></div>
                    <h2 class="text-sm font-bold text-gray-900 tracking-wider uppercase">Informasi Pendakian</h2>
                </div>

                <div class="space-y-3">
                    <!-- Pricing Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs text-blue-600 font-semibold uppercase tracking-wide mb-1">Tiket Masuk
                                </div>
                                <div class="text-2xl font-bold text-gray-900">Rp 35.000</div>
                                <div class="text-xs text-gray-600 mt-1">per orang / hari</div>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info Cards -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white border border-gray-200 rounded-2xl p-4">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Status</div>
                                    <div class="text-sm font-semibold text-gray-900 mt-0.5">Buka Normal</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-2xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Durasi</div>
                                    <div class="text-sm font-semibold text-gray-900 mt-0.5">2-3 Hari</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Gallery Section - Image Focused -->
            <section class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-0.5 bg-gray-900"></div>
                        <h2 class="text-sm font-bold text-gray-900 tracking-wider uppercase">Galeri</h2>
                    </div>
                    <button
                        class="text-xs font-semibold text-gray-900 hover:text-blue-600 transition-colors uppercase tracking-wide">
                        Lihat Semua →
                    </button>
                </div>

                <!-- Masonry-style Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <button
                        onclick="openImageModal('https://gelorajatim.com/wp-content/uploads/2024/08/IMG-20240828-WA0020-scaled.jpg')"
                        class="relative h-48 rounded-2xl overflow-hidden group focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <img src="https://gelorajatim.com/wp-content/uploads/2024/08/IMG-20240828-WA0020-scaled.jpg"
                            alt="Pemandangan Gunung Bromo 1"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                    </button>

                    <button
                        onclick="openImageModal('https://gelorajatim.com/wp-content/uploads/2024/08/IMG-20240828-WA0020-scaled.jpg')"
                        class="relative h-48 rounded-2xl overflow-hidden group focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <img src="https://gelorajatim.com/wp-content/uploads/2024/08/IMG-20240828-WA0020-scaled.jpg"
                            alt="Pemandangan Gunung Bromo 2"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                    </button>

                    <button
                        onclick="openImageModal('https://gelorajatim.com/wp-content/uploads/2024/08/IMG-20240828-WA0020-scaled.jpg')"
                        class="relative h-32 rounded-2xl overflow-hidden group focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <img src="https://gelorajatim.com/wp-content/uploads/2024/08/IMG-20240828-WA0020-scaled.jpg"
                            alt="Pemandangan Gunung Bromo 3"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                    </button>

                    <button
                        onclick="openImageModal('https://gelorajatim.com/wp-content/uploads/2024/08/IMG-20240828-WA0020-scaled.jpg')"
                        class="relative h-32 rounded-2xl overflow-hidden group focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <img src="https://gelorajatim.com/wp-content/uploads/2024/08/IMG-20240828-WA0020-scaled.jpg"
                            alt="Pemandangan Gunung Bromo 4"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                    </button>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-0.5 bg-gray-900"></div>
                    <h2 class="text-sm font-bold text-gray-900 tracking-wider uppercase">FAQ</h2>
                </div>

                <div class="space-y-3">
                    <!-- FAQ 1 -->
                    <details class="group bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <summary
                            class="flex items-center justify-between p-5 cursor-pointer hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900 text-sm">Apa saja yang perlu dibawa?</span>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-5 pb-5 pt-0 text-sm text-gray-600 leading-relaxed">
                            Bawa jaket tebal, sleeping bag, tenda, makanan, air minum, obat-obatan pribadi, dan dokumen
                            identitas. Pastikan juga membawa headlamp dan powerbank.
                        </div>
                    </details>

                    <!-- FAQ 2 -->
                    <details class="group bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <summary
                            class="flex items-center justify-between p-5 cursor-pointer hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900 text-sm">Apakah ada guide lokal?</span>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-5 pb-5 pt-0 text-sm text-gray-600 leading-relaxed">
                            Ya, tersedia guide bersertifikat yang siap mendampingi perjalanan Anda. Biaya guide mulai dari
                            Rp 150.000/hari dan dapat dipesan saat booking.
                        </div>
                    </details>

                    <!-- FAQ 3 -->
                    <details class="group bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <summary
                            class="flex items-center justify-between p-5 cursor-pointer hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900 text-sm">Bagaimana jika cuaca buruk?</span>
                            <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="px-5 pb-5 pt-0 text-sm text-gray-600 leading-relaxed">
                            Pendakian akan ditunda atau dibatalkan demi keselamatan. Anda dapat reschedule tanpa biaya
                            tambahan atau refund 80% dari total pembayaran.
                        </div>
                    </details>
                </div>
            </section>

            <!-- Contact Section - Minimal Cards -->
            <section class="space-y-6 pb-12">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-0.5 bg-gray-900"></div>
                    <h2 class="text-sm font-bold text-gray-900 tracking-wider uppercase">Kontak</h2>
                </div>

                <div class="grid gap-3">
                    <a href="mailto:info@gunungarjuna.id"
                        class="group bg-white border border-gray-200 rounded-2xl p-5 hover:border-blue-400 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center group-hover:bg-blue-100 transition-colors flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-0.5">Email</div>
                                <div class="text-sm font-semibold text-gray-900 truncate">info@gunungarjuna.id</div>
                            </div>
                        </div>
                    </a>

                    <a href="https://wa.me/6281234567890" target="_blank"
                        class="group bg-white border border-gray-200 rounded-2xl p-5 hover:border-green-400 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center group-hover:bg-green-100 transition-colors flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-0.5">WhatsApp
                                </div>
                                <div class="text-sm font-semibold text-gray-900">+62 812 3456 7890</div>
                            </div>
                        </div>
                    </a>

                    <a href="https://instagram.com/gunungarjuna_official" target="_blank"
                        class="group bg-white border border-gray-200 rounded-2xl p-5 hover:border-pink-400 hover:shadow-lg transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-pink-50 rounded-full flex items-center justify-center group-hover:bg-pink-100 transition-colors flex-shrink-0">
                                <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-0.5">Instagram
                                </div>
                                <div class="text-sm font-semibold text-gray-900 truncate">@gunungarjuna_official</div>
                            </div>
                        </div>
                    </a>
                </div>
            </section>
        </div>

        <!-- Floating CTA (Mobile Only) - Swiss Style -->
        <div id="floatingCTA"
            class="fixed bottom-24 left-1/2 -translate-x-1/2 z-40 w-full px-6 transition-all duration-500 transform translate-y-full opacity-0 max-w-lg">
            <a href="/booking"
                class="group block w-full bg-[#1B4965] text-white p-1 rounded-full shadow-2xl overflow-hidden">
                <div class="relative flex items-center justify-between px-6 py-3">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-medium text-white/70 uppercase tracking-widest">Start from</span>
                        <span class="text-lg font-bold text-white">Rp 35.000</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider">Book Now</span>
                        <div
                            class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center group-hover:bg-white/20 transition-colors">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1.5"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Image Modal - Redesigned -->
        <div id="imageModal"
            class="fixed inset-0 bg-black/95 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
            <div class="flex items-center justify-center min-h-screen p-6">
                <div class="relative max-w-4xl w-full">
                    <img id="modalImage" src="" alt="Foto penuh"
                        class="w-full h-auto object-contain rounded-3xl shadow-2xl">
                    <button onclick="closeImageModal()"
                        class="absolute -top-4 -right-4 w-12 h-12 bg-white rounded-full flex items-center justify-center text-gray-900 hover:bg-gray-100 transition-all shadow-xl hover:scale-110 focus:outline-none focus:ring-2 focus:ring-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <script>
            let currentImageIndex = 0;
            let currentImages = [];

            // Initialize Rellax for smooth parallax
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Rellax with custom settings
                if (typeof Rellax !== 'undefined') {
                    try {
                        var rellax = new Rellax('.rellax', {
                            speed: -2,
                            center: false,
                            wrapper: null,
                            round: true,
                            vertical: true,
                            horizontal: false,
                            breakpoints: [576, 768, 1201]
                        });
                    } catch (e) {
                        console.warn('Rellax initialization failed:', e);
                    }
                } else {
                    console.warn('Rellax library not loaded');
                }
            });

            // Smooth modal animations
            function openImageModal(imageSrc) {
                const modal = document.getElementById('imageModal');
                document.getElementById('modalImage').src = imageSrc;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                }, 10);
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                modal.classList.add('opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }, 300);
            }

            function changeImage(direction) {
                currentImageIndex += direction;
                if (currentImageIndex < 0) currentImageIndex = currentImages.length - 1;
                if (currentImageIndex >= currentImages.length) currentImageIndex = 0;

                updateCarouselImage();
            }

            // Close modals when clicking outside
            document.getElementById('imageModal').addEventListener('click', function(e) {
                if (e.target === this || e.target.closest('.flex.items-center.justify-center.min-h-screen') === e
                    .target) {
                    closeImageModal();
                }
            });

            // Intersection Observer for fade-in animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const fadeInObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        // Unobserve after animation to improve performance
                        fadeInObserver.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all sections for animation
            window.addEventListener('DOMContentLoaded', function() {
                const sections = document.querySelectorAll('section');
                sections.forEach((section, index) => {
                    section.style.opacity = '0';
                    section.style.transform = 'translateY(20px)';
                    section.style.transition =
                        `opacity 0.6s ease-out ${index * 0.1}s, transform 0.6s ease-out ${index * 0.1}s`;
                    fadeInObserver.observe(section);
                });
            });

            // Floating CTA Show/Hide on Scroll - Smooth Animation
            let fabTicking = false;
            let fabVisible = false;
            const floatingCTA = document.getElementById('floatingCTA');
            const scrollThreshold = 400; // Show FAB after scrolling 400px (after hero)
            const hideDelay = 100; // Small delay before hiding
            const footer = document.querySelector('footer'); // Assuming there is a footer tag

            function updateFloatingCTA() {
                const currentScroll = window.scrollY;
                const shouldShow = currentScroll > scrollThreshold;

                // Check for footer intersection
                if (footer) {
                    const footerRect = footer.getBoundingClientRect();
                    const windowHeight = window.innerHeight;

                    // If footer is entering viewport, adjust FAB
                    if (footerRect.top < windowHeight) {
                        const offset = windowHeight - footerRect.top + 24; // 24px padding
                        floatingCTA.style.bottom = `${offset}px`;
                    } else {
                        floatingCTA.style.bottom = '6rem'; // Default bottom-24 (96px)
                    }
                }

                // Only update if state changed to prevent unnecessary reflows
                if (shouldShow !== fabVisible) {
                    fabVisible = shouldShow;

                    if (shouldShow) {
                        // Show FAB with smooth slide up and subtle scale
                        floatingCTA.style.transform = 'translateX(-50%) translateY(0) scale(1)';
                        floatingCTA.style.opacity = '1';
                    } else {
                        // Hide FAB with smooth slide down and scale
                        setTimeout(() => {
                            floatingCTA.style.transform = 'translateX(-50%) translateY(100%) scale(0.95)';
                            floatingCTA.style.opacity = '0';
                        }, hideDelay);
                    }
                }

                fabTicking = false;
            }

            // Throttled scroll handler using requestAnimationFrame
            window.addEventListener('scroll', function() {
                if (!fabTicking) {
                    window.requestAnimationFrame(updateFloatingCTA);
                    fabTicking = true;
                }
            }, {
                passive: true
            });

            // Initial check on page load
            window.addEventListener('DOMContentLoaded', function() {
                // Ensure FAB starts hidden
                floatingCTA.style.transform = 'translateX(-50%) translateY(100%)';
                floatingCTA.style.opacity = '0';
                floatingCTA.style.bottom = '6rem'; // Default bottom-24

                // Check scroll position after a short delay
                setTimeout(updateFloatingCTA, 100);
            });
        </script>
    @endsection
