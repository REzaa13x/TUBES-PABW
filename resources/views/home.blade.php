<x-app title="DonGiv - Platform Donasi & Relawan #1 Indonesia">

    {{-- AOS (Animate On Scroll) Library --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    {{-- GSAP for premium animations --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    {{-- ===================== CUSTOM STYLES ===================== --}}
    <style>
        /* Blob Animations */
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }

        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        /* Floating Animation */
        @keyframes floatY {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-float { animation: floatY 5s ease-in-out infinite; }
        .animate-float-slow { animation: floatY 7s ease-in-out infinite; }
        .animate-float-delay { animation: floatY 6s ease-in-out infinite; animation-delay: 1.5s; }

        /* Particle Canvas */
        #particleCanvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        /* Gradient text shimmer */
        .text-gradient-animate {
            background-size: 200% auto;
            animation: textShimmer 3s linear infinite;
        }
        @keyframes textShimmer {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        /* Progress bar animation */
        .progress-bar-animated {
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0%;
        }
        .progress-bar-animated.animated { /* Set via JS on scroll */ }

        /* Card lift on hover */
        .card-hover-lift {
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover-lift:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.15), 0 0 0 1px rgba(37, 99, 235, 0.05);
        }

        /* Glassmorphism */
        .glass-premium {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        /* Parallax depth layers */
        .parallax-slow { will-change: transform; }
        .parallax-fast { will-change: transform; }

        /* Counter animation */
        .counter-value { display: inline-block; }

        /* Image reveal */
        .img-reveal {
            clip-path: inset(0 100% 0 0);
            transition: clip-path 1.2s cubic-bezier(0.77, 0, 0.175, 1);
        }
        .img-reveal.revealed { clip-path: inset(0 0 0 0); }

        /* Stagger children animation */
        .stagger-children > * {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .stagger-children.in-view > *:nth-child(1) { transition-delay: 0.05s; opacity: 1; transform: translateY(0); }
        .stagger-children.in-view > *:nth-child(2) { transition-delay: 0.15s; opacity: 1; transform: translateY(0); }
        .stagger-children.in-view > *:nth-child(3) { transition-delay: 0.25s; opacity: 1; transform: translateY(0); }
        .stagger-children.in-view > *:nth-child(4) { transition-delay: 0.35s; opacity: 1; transform: translateY(0); }
        .stagger-children.in-view > *:nth-child(5) { transition-delay: 0.45s; opacity: 1; transform: translateY(0); }
        .stagger-children.in-view > *:nth-child(6) { transition-delay: 0.55s; opacity: 1; transform: translateY(0); }

        /* Underline animation */
        .underline-animated {
            position: relative;
        }
        .underline-animated::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0%;
            height: 3px;
            border-radius: 2px;
            background: linear-gradient(90deg, #2563EB, #6366F1);
            transition: width 0.4s ease;
        }
        .underline-animated:hover::after { width: 100%; }

        /* Pulse glow on CTA */
        .pulse-glow {
            animation: pulseGlow 2.5s ease-in-out infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
            50% { box-shadow: 0 0 0 12px rgba(37, 99, 235, 0); }
        }

        /* Smooth section transitions */
        section { position: relative; }

        /* Scroll indicator */
        .scroll-indicator {
            animation: scrollBounce 2s infinite;
        }
        @keyframes scrollBounce {
            0%, 100% { transform: translateY(0); opacity: 1; }
            50% { transform: translateY(10px); opacity: 0.5; }
        }

        /* FAQ accordion smooth */
        details[open] summary ~ * {
            animation: slideDown 0.3s ease-out;
        }
        @keyframes slideDown {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Tilt card 3D */
        .tilt-3d {
            transform-style: preserve-3d;
            perspective: 1000px;
        }
        .tilt-3d-child {
            transform: translateZ(30px);
        }

        /* Ribbon badge */
        .ribbon-badge {
            position: absolute;
            top: 16px;
            right: -8px;
            z-index: 20;
        }
        .ribbon-badge::after {
            content: '';
            position: absolute;
            right: 0;
            bottom: -6px;
            border-left: 8px solid transparent;
            border-top: 6px solid #1e40af;
        }
    </style>

    {{-- ===================== 1. HERO SECTION ===================== --}}
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-transparent">

        {{-- Particle Canvas Background --}}
        <canvas id="particleCanvas"></canvas>

        {{-- Animated Background Blobs --}}
        <div class="absolute top-[-100px] right-[-100px] w-[700px] h-[700px] bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob pointer-events-none"></div>
        <div class="absolute top-[200px] left-[-150px] w-[500px] h-[500px] bg-cyan-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000 pointer-events-none"></div>
        <div class="absolute bottom-[-100px] right-[30%] w-[400px] h-[400px] bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000 pointer-events-none"></div>

        <div class="container max-w-7xl mx-auto px-6 relative z-10 flex flex-col md:flex-row items-center gap-12 lg:gap-20 py-10">

            {{-- Text Content (Left) --}}
            <div class="w-full md:w-1/2 space-y-7" data-aos="fade-right" data-aos-duration="1000">

                {{-- Live Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-premium shadow-sm text-blue-600 text-sm font-semibold border border-blue-100/80" data-aos="fade-down" data-aos-delay="200">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                    </span>
                    Platform Kebaikan & Relawan #1 di Indonesia
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 leading-[1.1] tracking-tight">
                    Sebar Kebaikan,<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 text-gradient-animate">Tuai Senyuman.</span>
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg md:text-xl text-gray-500 leading-relaxed max-w-lg" data-aos="fade-up" data-aos-delay="300">
                    Platform donasi dan kegiatan relawan transparan yang menghubungkan ribuan <strong class="text-gray-700">#OrangBaik</strong> dengan mereka yang membutuhkan uluran tanganmu hari ini.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-2" data-aos="fade-up" data-aos-delay="400">
                    <a href="#donasi"
                        class="group px-8 py-4 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-lg shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 transition-all duration-300 transform hover:-translate-y-1 text-center pulse-glow flex items-center justify-center gap-2">
                        <span>Mulai Donasi</span>
                        <i class="fas fa-arrow-right text-sm transition-transform group-hover:translate-x-1"></i>
                    </a>
                    <a href="{{ route('volunteer.landing') }}"
                        class="px-8 py-4 rounded-full glass-premium hover:bg-blue-50/80 text-blue-600 border border-blue-200/80 font-bold text-lg shadow-sm hover:shadow-lg transition-all duration-300 text-center flex items-center justify-center gap-2 hover:-translate-y-1">
                        <i class="fas fa-hands-helping text-blue-500"></i> Gabung Relawan
                    </a>
                </div>

                {{-- Social Proof --}}
                <div class="flex items-center gap-4 pt-2" data-aos="fade-up" data-aos-delay="500">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-white shadow-sm ring-1 ring-blue-100" src="https://i.pravatar.cc/100?img=1" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-white shadow-sm ring-1 ring-blue-100" src="https://i.pravatar.cc/100?img=2" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-white shadow-sm ring-1 ring-blue-100" src="https://i.pravatar.cc/100?img=3" alt="User">
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white shadow-sm">+2K</div>
                    </div>
                    <p class="text-sm text-gray-500 font-medium"><strong class="text-gray-800">2.000+</strong> Donatur & Relawan aktif</p>
                </div>
            </div>

            {{-- Hero Visual (Right) - 3D Tilt Card --}}
            <div class="w-full md:w-1/2 relative perspective-1000" data-aos="fade-left" data-aos-duration="1200">

                {{-- Floating Badge: Donatur Count --}}
                <div class="absolute -top-5 -left-5 z-20 glass-premium p-3.5 rounded-2xl shadow-xl flex items-center gap-3 animate-float border border-white/90" data-aos="zoom-in" data-aos-delay="600">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-base shadow-lg shadow-blue-500/30">
                        <i class="fas fa-heart text-pink-200"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-xs font-black text-gray-800">2.000+ Donatur</p>
                        </div>
                        <p class="text-[10px] text-gray-500 font-semibold">Tergabung Aktif</p>
                    </div>
                </div>

                {{-- Floating Badge: Verified --}}
                <div class="absolute -top-4 -right-4 z-20 glass-premium p-3 rounded-2xl shadow-xl flex items-center gap-2.5 animate-float-delay border border-white/90" data-aos="zoom-in" data-aos-delay="700">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white text-sm shadow-lg shadow-amber-400/30">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black text-gray-800">100% Amanah</p>
                        <p class="text-[10px] text-emerald-600 font-bold flex items-center gap-1"><i class="fas fa-check-circle text-[8px]"></i> Terverifikasi</p>
                    </div>
                </div>

                {{-- Main Hero Image Card --}}
                <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white/80 card-3d-tilt" data-tilt data-tilt-max="8" data-tilt-speed="400" data-tilt-glare="true" data-tilt-max-glare="0.15">
                    <img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070&auto=format&fit=crop"
                        class="w-full h-[420px] md:h-[500px] object-cover transform transition duration-700 hover:scale-105"
                        alt="Hero Kebaikan">

                    {{-- Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent pointer-events-none"></div>

                    {{-- Bottom Impact Card --}}
                    <div class="absolute bottom-6 left-6 right-6 glass-premium p-5 rounded-2xl shadow-2xl border border-white/60">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-0.5">Total Donasi Tersalurkan</p>
                                <p class="text-2xl sm:text-3xl font-black text-blue-600">Rp 12.5 Milyar+</p>
                            </div>
                            <div class="h-12 w-12 bg-gradient-to-br from-emerald-400 to-green-500 rounded-2xl flex items-center justify-center text-white text-lg shadow-lg shadow-green-500/30">
                                <i class="fas fa-arrow-trend-up"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Floating Badge: Bottom Left --}}
                <div class="absolute -bottom-4 -left-4 z-20 glass-premium px-4 py-3 rounded-2xl shadow-xl animate-float-slow border border-white/90" data-aos="zoom-in" data-aos-delay="800">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-400/30">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-800">Dana Tersalurkan</p>
                            <p class="text-[10px] text-emerald-600 font-bold">100% Transparan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 scroll-indicator" data-aos="fade-up" data-aos-delay="1000">
            <div class="flex flex-col items-center gap-2 text-gray-400">
                <span class="text-xs font-semibold tracking-wider uppercase">Scroll</span>
                <div class="w-6 h-10 rounded-full border-2 border-gray-300 flex justify-center pt-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== 2. STATISTIK SECTION ===================== --}}
    <section class="py-16 bg-transparent border-b border-blue-50/50 relative z-10 overflow-hidden">
        {{-- Subtle background pattern --}}
        <div class="absolute inset-0 opacity-[0.02]" style="background-image: radial-gradient(#2563EB 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="max-w-7xl mx-auto px-6 relative">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 stagger-children" id="statsSection">
                {{-- Stat 1 --}}
                <div class="card-hover-lift p-7 rounded-2xl bg-gradient-to-br from-blue-50/80 to-white border border-blue-100/50 text-center group" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-blue-100/60 text-blue-600 flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:shadow-blue-500/20 group-hover:scale-110">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <p class="text-3xl md:text-4xl font-extrabold text-blue-600 counter-value" data-target="150">0</p>
                    <p class="text-xl font-extrabold text-blue-600">+</p>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mt-2">Mitra Yayasan</p>
                </div>
                {{-- Stat 2 --}}
                <div class="card-hover-lift p-7 rounded-2xl bg-gradient-to-br from-indigo-50/80 to-white border border-indigo-100/50 text-center group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-indigo-100/60 text-indigo-600 flex items-center justify-center text-2xl group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:shadow-indigo-500/20 group-hover:scale-110">
                        <i class="fas fa-donate"></i>
                    </div>
                    <p class="text-3xl md:text-4xl font-extrabold text-indigo-600"><span class="counter-value" data-target="12000">0</span></p>
                    <p class="text-xl font-extrabold text-indigo-600">+</p>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mt-2">Donasi Masuk</p>
                </div>
                {{-- Stat 3 --}}
                <div class="card-hover-lift p-7 rounded-2xl bg-gradient-to-br from-emerald-50/80 to-white border border-emerald-100/50 text-center group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-emerald-100/60 text-emerald-600 flex items-center justify-center text-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:shadow-emerald-500/20 group-hover:scale-110">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <p class="text-3xl md:text-4xl font-extrabold text-emerald-600 counter-value" data-target="100">0</p>
                    <p class="text-xl font-extrabold text-emerald-600">%</p>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mt-2">Transparan</p>
                </div>
                {{-- Stat 4 --}}
                <div class="card-hover-lift p-7 rounded-2xl bg-gradient-to-br from-cyan-50/80 to-white border border-cyan-100/50 text-center group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-cyan-100/60 text-cyan-600 flex items-center justify-center text-2xl group-hover:bg-cyan-600 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:shadow-cyan-500/20 group-hover:scale-110">
                        <i class="fas fa-headset"></i>
                    </div>
                    <p class="text-3xl md:text-4xl font-extrabold text-cyan-600">24/7</p>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mt-2">Layanan Support</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== 3. KATEGORI ===================== --}}
    <section class="py-16 bg-transparent overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider mb-3 border border-blue-100/50">Kategori</span>
                <h3 class="text-3xl font-extrabold text-gray-800">Peduli Apa Hari Ini?</h3>
                <p class="text-gray-500 mt-2 max-w-lg mx-auto">Pilih kategori kebaikan yang ingin kamu dukung</p>
            </div>
            <div class="flex flex-wrap justify-center gap-8 md:gap-10 stagger-children" id="kategoriSection">
                @php
                    $categories = [
                        ['icon' => 'fas fa-stethoscope', 'label' => 'Medis', 'color' => 'blue'],
                        ['icon' => 'fas fa-graduation-cap', 'label' => 'Pendidikan', 'color' => 'indigo'],
                        ['icon' => 'fas fa-house-damage', 'label' => 'Bencana', 'color' => 'red'],
                        ['icon' => 'fas fa-mosque', 'label' => 'Rumah Ibadah', 'color' => 'emerald'],
                        ['icon' => 'fas fa-baby', 'label' => 'Balita', 'color' => 'pink'],
                    ];
                @endphp
                @foreach($categories as $cat)
                <a href="#donasi" class="group flex flex-col items-center gap-3 w-28" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="w-18 h-18 w-[72px] h-[72px] rounded-full bg-{{ $cat['color'] }}-50 text-{{ $cat['color'] }}-600 flex items-center justify-center text-2xl group-hover:bg-{{ $cat['color'] }}-600 group-hover:text-white transition-all duration-300 shadow-md group-hover:shadow-xl group-hover:shadow-{{ $cat['color'] }}-500/20 group-hover:scale-110 border border-{{ $cat['color'] }}-100/50">
                        <i class="{{ $cat['icon'] }}"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-600 group-hover:text-{{ $cat['color'] }}-600 transition-colors">{{ $cat['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== 4. KAMPANYE MENDESAK ===================== --}}
    <section class="py-20 bg-transparent overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center gap-3 mb-10" data-aos="fade-right">
                <div class="w-1.5 h-10 bg-gradient-to-b from-red-500 to-red-600 rounded-full"></div>
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-800">Kampanye Mendesak</h2>
                    <p class="text-gray-500 text-sm mt-1">Mereka sangat membutuhkan bantuanmu sekarang</p>
                </div>
            </div>

            @if($urgentCampaigns->count() > 0)
                @foreach($urgentCampaigns as $urgentCampaign)
                    @php
                        $urgentPercent = $urgentCampaign->target_amount > 0 ? min(100, ($urgentCampaign->current_amount / $urgentCampaign->target_amount) * 100) : 0;
                    @endphp
                    <div class="bg-white rounded-3xl shadow-xl hover:shadow-2xl overflow-hidden flex flex-col md:flex-row border border-blue-100/50 mb-8 last:mb-0 transition-all duration-500 card-hover-lift" data-aos="fade-up">
                        {{-- Image --}}
                        <div class="md:w-1/2 h-64 md:h-auto relative overflow-hidden group">
                            @if($urgentCampaign->image && !filter_var($urgentCampaign->image, FILTER_VALIDATE_URL))
                                <img src="{{ asset('storage/' . ltrim($urgentCampaign->image, '/')) }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $urgentCampaign->title }}">
                            @else
                                <img src="{{ $urgentCampaign->image ?? 'https://placehold.co/600x400?text=Campaign+Image' }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $urgentCampaign->title }}">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent pointer-events-none md:hidden"></div>
                            <span class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-4 py-1.5 rounded-full animate-pulse shadow-lg shadow-red-500/30 flex items-center gap-1.5">
                                <i class="fas fa-clock"></i> Sisa <span class="days-remaining" data-end-date="{{ \Carbon\Carbon::parse($urgentCampaign->end_date)->format('Y-m-d') }}">{{ \Carbon\Carbon::parse($urgentCampaign->end_date)->diffInDays() > 0 ? \Carbon\Carbon::parse($urgentCampaign->end_date)->diffInDays() : 0 }}</span> Hari
                            </span>
                        </div>
                        {{-- Content --}}
                        <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-600 text-xs font-bold rounded-full">{{ $urgentCampaign->kategori ?? 'Umum' }}</span>
                                <span class="text-gray-400 text-xs flex items-center gap-1"><i class="fas fa-map-marker-alt"></i> Indonesia</span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $urgentCampaign->title }}</h3>
                            <p class="text-gray-500 mb-6 line-clamp-3 leading-relaxed">
                                {{ \Illuminate\Support\Str::limit($urgentCampaign->description, 150) }}
                            </p>

                            {{-- Progress --}}
                            <div class="mb-6">
                                <div class="flex justify-between text-sm font-semibold mb-2">
                                    <span class="text-blue-600">Rp {{ number_format($urgentCampaign->current_amount, 0, ',', '.') }}</span>
                                    <span class="text-gray-400">dari Rp {{ number_format($urgentCampaign->target_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full progress-bar-animated transition-all duration-1000" data-width="{{ $urgentPercent }}%" style="width: {{ $urgentPercent }}%"></div>
                                </div>
                                <p class="text-right text-xs text-gray-400 mt-1 font-semibold">{{ number_format($urgentPercent, 0) }}% terkumpul</p>
                            </div>

                            <div class="flex gap-4">
                                <a href="{{ route('donations.details', ['slug' => $urgentCampaign->slug]) }}"
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 text-center hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <i class="fas fa-heart text-sm"></i> Donasi Sekarang
                                </a>
                                <a href="{{ route('donations.details', ['slug' => $urgentCampaign->slug]) }}"
                                    class="px-5 py-3.5 border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-blue-200 text-gray-500 hover:text-blue-600 transition-all duration-300">
                                    <i class="fas fa-share-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white rounded-3xl shadow-xl p-12 text-center border border-blue-100/50" data-aos="fade-up">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-4xl text-amber-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak Ada Kampanye Mendesak</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Saat ini tidak ada kampanye yang tergolong mendesak. Silakan cek kembali nanti.</p>
                    <a href="{{ route('campaigns.all') }}"
                        class="inline-block px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/20">
                        Lihat Semua Kampanye
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== 5. DAFTAR DONASI ===================== --}}
    <section id="donasi" class="py-20 bg-transparent overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-14" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider mb-3 border border-blue-100/50">Kampanye Donasi</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Pilihan Kebaikan</h2>
                <p class="text-gray-500 mt-3 max-w-lg mx-auto">Pilih kampanye yang ingin kamu dukung hari ini dan jadilah bagian dari perubahan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @if (isset($campaigns) && !empty($campaigns))
                    @foreach ($campaigns->take(4) as $campaign)
                        @php
                            $campPercent = $campaign->target_amount > 0 ? min(100, ($campaign->current_amount / $campaign->target_amount) * 100) : 0;
                        @endphp
                        <div class="group bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden transition-all duration-500 h-full flex flex-col card-hover-lift card-3d-tilt" data-tilt data-tilt-max="8" data-tilt-scale="1.02" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

                            {{-- Image Area --}}
                            <div class="relative h-48 overflow-hidden">
                                @if ($campaign->image && !filter_var($campaign->image, FILTER_VALIDATE_URL))
                                    <img src="{{ asset('storage/' . ltrim($campaign->image, '/')) }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                        alt="{{ $campaign->title }}">
                                @else
                                    <img src="{{ $campaign->image ?? 'https://placehold.co/600x400?text=Campaign+Image' }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                        alt="{{ $campaign->title }}">
                                @endif

                                {{-- Status Badge --}}
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold text-blue-600 shadow-sm border border-blue-50 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    {{ $campaign->status }}
                                </div>

                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-blue-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                            </div>

                            {{-- Content Area --}}
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="text-xs font-bold text-blue-600/70 mb-2 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fas fa-tag text-[10px]"></i>
                                    {{ $campaign->kategori ?? 'Umum' }}
                                </div>

                                <h3 class="font-bold text-lg text-gray-900 mb-3 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors duration-300">
                                    <a href="{{ route('donations.details', ['slug' => $campaign->slug]) }}">
                                        {{ $campaign->title }}
                                    </a>
                                </h3>

                                {{-- Progress Bar --}}
                                <div class="mt-auto">
                                    <div class="w-full bg-blue-50 rounded-full h-2 mb-3 overflow-hidden">
                                        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-2 rounded-full transition-all duration-1000"
                                            style="width: {{ $campPercent }}%">
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <div>
                                            <p class="text-gray-400 text-xs font-medium">Terkumpul</p>
                                            <p class="font-bold text-blue-600">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-gray-400 text-xs font-medium">Sisa Hari</p>
                                            <p class="font-bold text-gray-700 flex items-center gap-1">
                                                <i class="far fa-clock text-gray-400 text-xs"></i>
                                                <span class="days-remaining" data-end-date="{{ \Carbon\Carbon::parse($campaign->end_date)->format('Y-m-d') }}">
                                                    {{ \Carbon\Carbon::parse($campaign->end_date)->diffInDays() > 0 ? \Carbon\Carbon::parse($campaign->end_date)->diffInDays() : 0 }}
                                                </span>
                                                <span class="text-xs font-normal">Hari</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer Button --}}
                            <div class="p-4 border-t border-gray-50">
                                <a href="{{ route('donations.details', ['slug' => $campaign->slug]) }}"
                                    class="block w-full text-center py-2.5 rounded-xl text-blue-600 font-bold border-2 border-blue-100 hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 hover:text-white hover:border-transparent transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                                    Donasi Sekarang
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-16 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-inbox text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada kampanye aktif.</p>
                    </div>
                @endif
            </div>

            <div class="text-center mt-14" data-aos="fade-up">
                <a href="{{ route('campaigns.all') }}"
                    class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 font-bold transition-all duration-300 hover:shadow-lg border border-gray-200/50 hover:border-blue-200 group">
                    Lihat Semua Kampanye <i class="fas fa-arrow-right text-sm transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== 6. CARA KERJA ===================== --}}
    <section id="cara-kerja" class="py-20 bg-transparent overflow-hidden relative">
        {{-- Decorative Elements --}}
        <div class="absolute top-20 right-10 w-20 h-20 bg-blue-100 rounded-full opacity-30 animate-float pointer-events-none"></div>
        <div class="absolute bottom-20 left-10 w-16 h-16 bg-indigo-100 rounded-full opacity-30 animate-float-delay pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-6 relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 rounded-full bg-blue-100 text-blue-600 text-xs font-bold uppercase tracking-wider mb-3">Mudah & Cepat</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Semudah Ini Berbagi</h2>
                <p class="text-gray-500 mt-3 max-w-md mx-auto">Kebaikan tidak perlu rumit. Cukup 3 langkah mudah untuk membuat perbedaan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                {{-- Connecting Line (Desktop) --}}
                <div class="hidden md:block absolute top-12 left-[20%] right-[20%] h-0.5 bg-gradient-to-r from-blue-200 via-blue-300 to-blue-200 border-t-2 border-dashed border-blue-300/50 -z-0"></div>

                @php
                    $steps = [
                        ['icon' => 'fas fa-heart', 'title' => '1. Pilih Campaign', 'desc' => 'Pilih cerita yang menggerakkan hatimu dari daftar kampanye terverifikasi.', 'color' => 'blue', 'delay' => 0],
                        ['icon' => 'fas fa-wallet', 'title' => '2. Transfer Donasi', 'desc' => 'Transfer via Bank, E-Wallet, atau QRIS dengan aman dan terenkripsi.', 'color' => 'indigo', 'delay' => 200],
                        ['icon' => 'fas fa-smile', 'title' => '3. Terima Laporan', 'desc' => 'Dapatkan update penggunaan dana secara transparan via notifikasi.', 'color' => 'emerald', 'delay' => 400],
                    ];
                @endphp

                @foreach($steps as $step)
                <div class="relative z-10 flex flex-col items-center text-center card-hover-lift" data-aos="fade-up" data-aos-delay="{{ $step['delay'] }}">
                    <div class="w-24 h-24 bg-white rounded-full shadow-xl flex items-center justify-center text-3xl text-{{ $step['color'] }}-600 mb-6 border-4 border-{{ $step['color'] }}-50 group-hover:border-{{ $step['color'] }}-200 transition-all duration-300 relative">
                        <i class="{{ $step['icon'] }}"></i>
                        <div class="absolute -inset-2 rounded-full border-2 border-dashed border-{{ $step['color'] }}-200 opacity-50 animate-spin" style="animation-duration: 20s;"></div>
                    </div>
                    <h4 class="text-xl font-extrabold text-gray-800 mb-2">{{ $step['title'] }}</h4>
                    <p class="text-gray-500 text-sm px-4 leading-relaxed max-w-xs">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== 7. TRANSPARANSI PENYALURAN ===================== --}}
    <section class="py-20 bg-transparent overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                {{-- Left Visual --}}
                <div class="w-full lg:w-1/2 relative" data-aos="fade-right" data-aos-duration="1000">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?q=80&w=2070&auto=format&fit=crop"
                             class="w-full h-[400px] object-cover" alt="Transparansi">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 to-transparent"></div>

                        {{-- Overlay Stats --}}
                        <div class="absolute bottom-0 left-0 right-0 p-8">
                            <div class="grid grid-cols-3 gap-4 text-white text-center">
                                <div>
                                    <p class="text-2xl font-black">100%</p>
                                    <p class="text-xs opacity-80 mt-1">Tersalurkan</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-black">Real-time</p>
                                    <p class="text-xs opacity-80 mt-1">Laporan</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-black">Teraudit</p>
                                    <p class="text-xs opacity-80 mt-1">Berkala</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating card --}}
                    <div class="absolute -top-6 -right-6 glass-premium p-4 rounded-2xl shadow-xl border border-white/80 animate-float hidden md:block" data-aos="zoom-in" data-aos-delay="400">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-file-invoice-dollar text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-800">Laporan Keuangan</p>
                                <p class="text-xs text-emerald-600 font-bold">Terbuka untuk Publik</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Content --}}
                <div class="w-full lg:w-1/2" data-aos="fade-left" data-aos-duration="1000">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold uppercase tracking-wider mb-4 border border-emerald-100/50">Transparansi</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-6 leading-tight">Setiap Rupiah <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Tercatat & Tersalurkan</span></h2>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        Kami berkomitmen menjaga kepercayaan Anda dengan sistem pelaporan yang terbuka dan transparan. Setiap donasi dilaporkan secara real-time dan diaudit secara berkala.
                    </p>

                    <div class="space-y-5">
                        @php
                            $features = [
                                ['icon' => 'fas fa-chart-line', 'title' => 'Dashboard Laporan Real-Time', 'desc' => 'Pantau penyaluran donasi secara langsung dari dashboard Anda.'],
                                ['icon' => 'fas fa-shield-alt', 'title' => 'Verifikasi Validator Independen', 'desc' => 'Setiap kampanye diverifikasi oleh tim validator independen.'],
                                ['icon' => 'fas fa-bell', 'title' => 'Notifikasi Progress Update', 'desc' => 'Dapatkan notifikasi setiap kali ada update dari kampanye Anda.'],
                            ];
                        @endphp
                        @foreach($features as $feature)
                        <div class="flex items-start gap-4 p-4 rounded-xl hover:bg-blue-50/50 transition-colors duration-300 group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="w-12 h-12 rounded-xl bg-blue-100/60 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                <i class="{{ $feature['icon'] }}"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-1">{{ $feature['title'] }}</h4>
                                <p class="text-sm text-gray-500">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('reviews.board', ['fullPage' => false])

    {{-- ===================== 9. VOLUNTEER CTA ===================== --}}
    <section class="py-24 relative overflow-hidden">
        {{-- Background --}}
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?q=80&w=2074&auto=format&fit=crop"
                class="w-full h-full object-cover" alt="Volunteer">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/85 via-blue-800/80 to-indigo-900/85"></div>
        </div>

        {{-- Animated particles overlay --}}
        <div class="absolute inset-0 z-[1] pointer-events-none opacity-10">
            <div class="absolute w-2 h-2 bg-white rounded-full animate-float" style="top: 20%; left: 15%;"></div>
            <div class="absolute w-3 h-3 bg-white rounded-full animate-float-slow" style="top: 60%; left: 80%;"></div>
            <div class="absolute w-1.5 h-1.5 bg-white rounded-full animate-float-delay" style="top: 40%; left: 50%;"></div>
            <div class="absolute w-2 h-2 bg-white rounded-full animate-float" style="top: 70%; left: 30%;"></div>
        </div>

        <div class="container max-w-5xl mx-auto px-6 relative z-10 text-center text-white">
            <span class="inline-block py-1.5 px-4 rounded-full bg-white/15 border border-white/25 text-xs font-bold uppercase tracking-wider mb-5 backdrop-blur-sm" data-aos="fade-down">
                <i class="fas fa-users mr-1"></i> Komunitas Relawan
            </span>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight" data-aos="fade-up" data-aos-delay="100">
                Punya Waktu Luang?
            </h2>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                Bergabunglah menjadi relawan dan rasakan pengalaman turun langsung ke lapangan. Tenagamu sangat berarti bagi mereka.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('volunteer.campaigns.index') }}"
                    class="group px-10 py-4 bg-white text-blue-900 font-extrabold rounded-full shadow-2xl hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i class="fas fa-hand-holding-heart"></i> Daftar Relawan
                    <i class="fas fa-arrow-right text-sm transition-transform group-hover:translate-x-1"></i>
                </a>
                <a href="#cara-kerja"
                    class="px-10 py-4 border-2 border-white/40 text-white font-bold rounded-full hover:bg-white/10 transition-all duration-300 backdrop-blur-sm flex items-center justify-center gap-2">
                    <i class="fas fa-play-circle"></i> Pelajari Lebih
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== 10. FAQ ===================== --}}
    <section class="py-20 bg-transparent overflow-hidden">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-14" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider mb-3 border border-blue-100/50">FAQ</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800">Pertanyaan Umum</h2>
                <p class="text-gray-500 mt-3">Temukan jawaban dari pertanyaan yang sering diajukan</p>
            </div>

            <div class="space-y-4">
                @php
                    $faqs = [
                        ['q' => 'Apakah donasi saya aman?', 'a' => 'Sangat aman. Kami menggunakan payment gateway terverifikasi dan setiap transaksi tercatat dalam sistem yang transparan dan terenkripsi.'],
                        ['q' => 'Apa saja metode pembayarannya?', 'a' => 'Kami menerima Transfer Bank (BCA, Mandiri, BRI, BNI), E-Wallet (GoPay, OVO, Dana), dan QRIS.'],
                        ['q' => 'Bagaimana saya memantau penyaluran dana?', 'a' => 'Setiap donatur akan mendapatkan notifikasi real-time tentang progress kampanye dan laporan penggunaan dana yang transparan.'],
                        ['q' => 'Apakah saya bisa menjadi relawan?', 'a' => 'Tentu! Anda bisa mendaftar sebagai relawan melalui halaman Relawan dan memilih kegiatan yang sesuai dengan minat Anda.'],
                    ];
                @endphp
                @foreach($faqs as $faq)
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:border-blue-200 hover:shadow-md transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <details class="group">
                        <summary class="flex justify-between items-center p-6 cursor-pointer list-none select-none">
                            <span class="font-bold text-gray-800 group-open:text-blue-600 transition-colors flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 group-open:bg-blue-600 group-open:text-white transition-all text-sm">
                                    <i class="fas fa-question"></i>
                                </span>
                                {{ $faq['q'] }}
                            </span>
                            <span class="ml-4 flex-shrink-0 w-8 h-8 rounded-full bg-gray-50 group-open:bg-blue-50 flex items-center justify-center transition-all group-open:rotate-180">
                                <i class="fas fa-chevron-down text-sm text-gray-400 group-open:text-blue-600 transition-colors"></i>
                            </span>
                        </summary>
                        <div class="px-6 pb-6 pt-0">
                            <p class="text-gray-500 leading-relaxed pl-11 border-l-2 border-blue-200 ml-0.5">
                                {{ $faq['a'] }}
                            </p>
                        </div>
                    </details>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== SCRIPTS ===================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Initialize AOS
            AOS.init({
                once: true,
                duration: 800,
                easing: 'ease-out-cubic',
                offset: 50,
            });

            // 2. Animated Counter (IntersectionObserver)
            const counterEls = document.querySelectorAll('.counter-value');
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const target = parseInt(el.getAttribute('data-target'));
                        if (!target) return;
                        let current = 0;
                        const increment = target / 60;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                el.textContent = target >= 1000 ? (target / 1000).toFixed(0) + 'K' : target;
                                clearInterval(timer);
                            } else {
                                el.textContent = target >= 1000 ? Math.floor(current / 1000) + 'K' : Math.floor(current);
                            }
                        }, 25);
                        counterObserver.unobserve(el);
                    }
                });
            }, { threshold: 0.5 });
            counterEls.forEach(el => counterObserver.observe(el));

            // 3. Stagger Animation (IntersectionObserver)
            const staggerSections = document.querySelectorAll('.stagger-children');
            const staggerObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                        staggerObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });
            staggerSections.forEach(el => staggerObserver.observe(el));

            // 4. Particle Canvas (Lightweight floating particles)
            const canvas = document.getElementById('particleCanvas');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                let particles = [];
                const PARTICLE_COUNT = 35;

                function resizeCanvas() {
                    const section = canvas.parentElement;
                    canvas.width = section.offsetWidth;
                    canvas.height = section.offsetHeight;
                }

                function createParticle() {
                    return {
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height,
                        size: Math.random() * 3 + 1,
                        speedX: (Math.random() - 0.5) * 0.5,
                        speedY: (Math.random() - 0.5) * 0.3,
                        opacity: Math.random() * 0.3 + 0.1,
                        hue: Math.random() * 40 + 210, // blue range
                    };
                }

                function initParticles() {
                    particles = [];
                    for (let i = 0; i < PARTICLE_COUNT; i++) {
                        particles.push(createParticle());
                    }
                }

                function drawParticles() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    particles.forEach(p => {
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                        ctx.fillStyle = `hsla(${p.hue}, 70%, 60%, ${p.opacity})`;
                        ctx.fill();

                        // Move
                        p.x += p.speedX;
                        p.y += p.speedY;

                        // Wrap around
                        if (p.x < 0) p.x = canvas.width;
                        if (p.x > canvas.width) p.x = 0;
                        if (p.y < 0) p.y = canvas.height;
                        if (p.y > canvas.height) p.y = 0;
                    });

                    // Draw connections
                    for (let i = 0; i < particles.length; i++) {
                        for (let j = i + 1; j < particles.length; j++) {
                            const dx = particles[i].x - particles[j].x;
                            const dy = particles[i].y - particles[j].y;
                            const dist = Math.sqrt(dx * dx + dy * dy);
                            if (dist < 150) {
                                ctx.beginPath();
                                ctx.moveTo(particles[i].x, particles[i].y);
                                ctx.lineTo(particles[j].x, particles[j].y);
                                ctx.strokeStyle = `rgba(37, 99, 235, ${0.05 * (1 - dist / 150)})`;
                                ctx.lineWidth = 0.5;
                                ctx.stroke();
                            }
                        }
                    }

                    requestAnimationFrame(drawParticles);
                }

                resizeCanvas();
                initParticles();
                drawParticles();
                window.addEventListener('resize', () => { resizeCanvas(); initParticles(); });
            }

            // 5. GSAP Parallax (only if available)
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);

                // Parallax on floating badges
                gsap.utils.toArray('.animate-float, .animate-float-slow, .animate-float-delay').forEach(el => {
                    gsap.to(el, {
                        y: -20,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: el,
                            start: 'top bottom',
                            end: 'bottom top',
                            scrub: 1,
                        }
                    });
                });
            }

            // 6. Navbar active state on scroll
            const sections = document.querySelectorAll('section[id]');
            window.addEventListener('scroll', () => {
                const scrollY = window.pageYOffset;
                sections.forEach(section => {
                    const sectionTop = section.offsetTop - 200;
                    const sectionHeight = section.offsetHeight;
                    const sectionId = section.getAttribute('id');
                    const navLink = document.querySelector(`nav a[href*="${sectionId}"]`);
                    if (navLink) {
                        if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                            navLink.classList.add('text-blue-600');
                        } else {
                            navLink.classList.remove('text-blue-600');
                        }
                    }
                });
            });
        });
    </script>

</x-app>
