<x-app title="DonGiv - Bergabung sebagai Relawan">

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
        #particleCanvasVolunteer {
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
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        /* Pulse glow on CTA */
        .pulse-glow {
            animation: pulseGlow 2.5s ease-in-out infinite;
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
            50% { box-shadow: 0 0 0 12px rgba(37, 99, 235, 0); }
        }

        .pattern-grid {
            background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }

        .text-glow {
            text-shadow: 0 0 20px rgba(59, 130, 246, 0.25);
        }
    </style>

    {{-- HERO SECTION: SPLIT LAYOUT (Design Mirip Home dengan Gradasi, Blobs & DonGiv Watermark) --}}
    <section class="relative min-h-[92vh] flex items-center pt-24 pb-20 overflow-hidden bg-transparent">

        {{-- Particle Canvas Background --}}
        <canvas id="particleCanvasVolunteer"></canvas>

        {{-- Ambient Typographic Watermark DonGiv (Mirip Home & Elegan) --}}
        <div class="absolute top-10 left-1/2 -translate-x-1/2 select-none pointer-events-none opacity-[0.045] font-black text-[13vw] tracking-wider text-indigo-900 whitespace-nowrap z-0">
            DONGIV
        </div>

        {{-- Animated Background Blobs (Sama persis seperti Home) --}}
        <div class="absolute top-[-80px] right-[-80px] w-[650px] h-[650px] bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-35 animate-blob pointer-events-none"></div>
        <div class="absolute top-[180px] left-[-120px] w-[500px] h-[500px] bg-cyan-100 rounded-full mix-blend-multiply filter blur-3xl opacity-35 animate-blob animation-delay-2000 pointer-events-none"></div>
        <div class="absolute bottom-[-100px] right-[25%] w-[420px] h-[420px] bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-25 animate-blob animation-delay-4000 pointer-events-none"></div>

        <div class="container max-w-7xl mx-auto px-6 relative z-10 py-10">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

                {{-- KOLOM KIRI: Teks & Call to Action --}}
                <div class="w-full lg:w-1/2 text-left space-y-7" data-aos="fade-right" data-aos-duration="1000">

                    {{-- Live Badge (Mirip Home) --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-premium shadow-sm text-blue-600 text-sm font-semibold border border-blue-100/80" data-aos="fade-down" data-aos-delay="200">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                        </span>
                        Komunitas Relawan #1 di Indonesia
                    </div>

                    {{-- Headline with Shimmer Gradient (Sama seperti Home) --}}
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 leading-[1.1] tracking-tight">
                        Ubah Niat Baik,<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 text-gradient-animate">Menjadi Dampak Nyata.</span>
                    </h1>

                    <p class="text-lg md:text-xl text-gray-500 leading-relaxed max-w-lg" data-aos="fade-up" data-aos-delay="300">
                        Jangan biarkan kepedulian hanya berhenti di pikiran. DonGiv menyediakan ekosistem terstruktur agar waktumu menghasilkan perubahan yang terukur bagi sesama.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 pt-2" data-aos="fade-up" data-aos-delay="400">
                        <a href="{{ route('volunteer.campaigns.index') }}"
                            class="group px-8 py-4 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-lg shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 transition-all duration-300 transform hover:-translate-y-1 text-center pulse-glow flex items-center justify-center gap-2">
                            <span>Daftar Sekarang</span>
                            <i class="fas fa-arrow-right text-sm transition-transform group-hover:translate-x-1"></i>
                        </a>
                        <a href="#roles"
                            class="px-8 py-4 rounded-full glass-premium hover:bg-blue-50/80 text-blue-600 border border-blue-200/80 font-bold text-lg shadow-sm hover:shadow-lg transition-all duration-300 text-center flex items-center justify-center gap-2 hover:-translate-y-1">
                            <i class="fas fa-search text-blue-500"></i> Pelajari Posisi
                        </a>
                    </div>

                    {{-- Social Proof & Stats (Mirip Home) --}}
                    <div class="flex flex-wrap items-center gap-6 pt-3 border-t border-slate-200/60" data-aos="fade-up" data-aos-delay="500">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-3">
                                <img class="w-10 h-10 rounded-full border-2 border-white shadow-sm ring-1 ring-blue-100" src="https://i.pravatar.cc/100?img=12" alt="Volunteer">
                                <img class="w-10 h-10 rounded-full border-2 border-white shadow-sm ring-1 ring-blue-100" src="https://i.pravatar.cc/100?img=33" alt="Volunteer">
                                <img class="w-10 h-10 rounded-full border-2 border-white shadow-sm ring-1 ring-blue-100" src="https://i.pravatar.cc/100?img=5" alt="Volunteer">
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white shadow-sm">+1.2K</div>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">1.200+ Relawan</p>
                                <p class="text-xs text-gray-400">Tergabung Aktif</p>
                            </div>
                        </div>

                        <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                        <div class="flex items-center gap-6">
                            <div>
                                <p class="text-2xl font-black text-blue-600">150+</p>
                                <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider">Misi Selesai</p>
                            </div>
                            <div>
                                <p class="text-2xl font-black text-indigo-600">34</p>
                                <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider">Provinsi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Visual Hero Modern dengan Floating Badges (Persis Seperti Home) --}}
                <div class="w-full lg:w-1/2 relative perspective-1000" data-aos="fade-left" data-aos-duration="1200">

                    {{-- Floating Badge Kiri Atas: Volunteer Badge --}}
                    <div class="absolute -top-5 -left-5 z-20 glass-premium p-3.5 rounded-2xl shadow-xl flex items-center gap-3 animate-float border border-white/90" data-aos="zoom-in" data-aos-delay="600">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-base shadow-lg shadow-blue-500/30">
                            <i class="fas fa-hand-holding-heart text-pink-200"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <p class="text-xs font-black text-gray-800">1.200+ Relawan</p>
                            </div>
                            <p class="text-[10px] text-gray-500 font-semibold">Siap Beraksi</p>
                        </div>
                    </div>

                    {{-- Floating Badge Kanan Atas: Verified Badge --}}
                    <div class="absolute -top-4 -right-4 z-20 glass-premium p-3 rounded-2xl shadow-xl flex items-center gap-2.5 animate-float-delay border border-white/90" data-aos="zoom-in" data-aos-delay="700">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center text-white text-sm shadow-lg shadow-emerald-400/30">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-800">E-Sertifikat</p>
                            <p class="text-[10px] text-emerald-600 font-bold flex items-center gap-1"><i class="fas fa-check-circle text-[8px]"></i> Resmi Terverifikasi</p>
                        </div>
                    </div>

                    {{-- Main Hero Image Card dengan 3D Tilt --}}
                    <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white/80 card-3d-tilt" data-tilt data-tilt-max="8" data-tilt-speed="400" data-tilt-glare="true" data-tilt-max-glare="0.15">
                        <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?q=80&w=1200&auto=format&fit=crop"
                            class="w-full h-[420px] md:h-[500px] object-cover transform transition duration-700 hover:scale-105"
                            alt="Volunteer Action">

                        {{-- Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/15 to-transparent pointer-events-none"></div>

                        {{-- Bottom Impact Card --}}
                        <div class="absolute bottom-6 left-6 right-6 glass-premium p-5 rounded-2xl shadow-2xl border border-white/70">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-0.5">Total Jam Pengabdian</p>
                                    <p class="text-2xl sm:text-3xl font-black text-blue-600">45.000+ Jam</p>
                                </div>
                                <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-lg shadow-lg shadow-blue-500/30">
                                    <i class="fas fa-hands-helping"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Badge Kiri Bawah --}}
                    <div class="absolute -bottom-4 -left-4 z-20 glass-premium px-4 py-3 rounded-2xl shadow-xl animate-float-slow border border-white/90" data-aos="zoom-in" data-aos-delay="800">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-400/30">
                                <i class="fas fa-award"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-gray-800">SKPI & Portofolio</p>
                                <p class="text-[10px] text-amber-600 font-bold">100% Diakui Kampus</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    {{-- BENEFITS SECTION (Desain Transparan Glassmorphism agar Gradasi Galaksi & DonGiv Terlihat) --}}
    <section class="py-20 bg-transparent relative border-y border-white/50">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-premium rounded-3xl p-8 border border-white/80 shadow-lg hover:shadow-2xl transition-all duration-300 card-hover-lift" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl mb-6 shadow-lg shadow-blue-500/25">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Impact Analytics</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Lihat seberapa besar kontribusi jam kerjamu terhadap keberhasilan proyek secara real-time dan terukur.</p>
                </div>
                <div class="glass-premium rounded-3xl p-8 border border-white/80 shadow-lg hover:shadow-2xl transition-all duration-300 card-hover-lift" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white text-2xl mb-6 shadow-lg shadow-indigo-500/25">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Professional Network</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Akses eksklusif ke komunitas mentor, praktisi NGO berpengalaman, dan sesama relawan aktif se-Indonesia.</p>
                </div>
                <div class="glass-premium rounded-3xl p-8 border border-white/80 shadow-lg hover:shadow-2xl transition-all duration-300 card-hover-lift" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center text-white text-2xl mb-6 shadow-lg shadow-cyan-500/25">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Certified Experience</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Sertifikat digital resmi ber-barcode yang valid untuk melengkapi portofolio karir, beasiswa, dan SKPI kampus.</p>
                </div>
            </div>
        </div>
    </section>

    <script src="//unpkg.com/alpinejs" defer></script>

    <section id="roles" class="py-24 bg-transparent relative overflow-hidden" x-data="{ activeTab: 'all' }">
        {{-- Subtle background blobs for roles section --}}
        <div class="absolute top-1/4 -left-32 w-80 h-80 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-35 animate-blob pointer-events-none"></div>
        <div class="absolute bottom-10 -right-32 w-96 h-96 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-35 animate-blob animation-delay-2000 pointer-events-none"></div>

        <div class="container mx-auto px-6 max-w-7xl relative z-10">

            {{-- HEADER --}}
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-3 border border-indigo-100/60">Peluang Relawan</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
                    Temukan Misi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Kebaikanmu</span>
                </h2>
                <p class="text-lg text-slate-600 leading-relaxed">
                    Bergabung dengan ribuan relawan DonGiv lainnya. Pilih peran yang menantang keahlianmu.
                </p>
            </div>

            {{-- FILTER TABS --}}
            <div class="flex justify-center mb-12" data-aos="fade-up" data-aos-delay="100">
                <div class="inline-flex glass-premium p-1.5 rounded-full shadow-lg shadow-slate-200/50 border border-white/80 flex-wrap justify-center gap-2 md:gap-0">
                    <button @click="activeTab = 'all'"
                        :class="activeTab === 'all' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'text-slate-600 hover:text-indigo-600 hover:bg-white/80'"
                        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300">
                        Semua Posisi
                    </button>
                    <button @click="activeTab = 'onsite'"
                        :class="activeTab === 'onsite' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'text-slate-600 hover:text-indigo-600 hover:bg-white/80'"
                        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300">
                        Lapangan (On-Site)
                    </button>
                    <button @click="activeTab = 'remote'"
                        :class="activeTab === 'remote' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md' : 'text-slate-600 hover:text-indigo-600 hover:bg-white/80'"
                        class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300">
                        Remote (WFH)
                    </button>
                </div>
            </div>

            {{-- GRID CARD DINAMIS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">

                @forelse($campaigns as $campaign)
                {{-- Logika Filter --}}
                @php
                $isRemote = stripos($campaign->lokasi, 'Remote') !== false || stripos($campaign->lokasi, 'Online') !== false || stripos($campaign->lokasi, 'WFH') !== false;
                $filterCategory = $isRemote ? 'remote' : 'onsite';
                @endphp

                <div x-show="activeTab === 'all' || activeTab === '{{ $filterCategory }}'"
                    x-transition.opacity.duration.500ms
                    class="group bg-white/85 backdrop-blur-xl rounded-3xl p-8 border border-white/90 shadow-lg shadow-indigo-950/5 hover:shadow-2xl hover:border-indigo-200/80 hover:-translate-y-2 transition-all duration-500 relative flex flex-col h-full card-hover-lift"
                    data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">

                    {{-- Badge Urgent --}}
                    @if(($campaign->kuota_total - $campaign->kuota_terisi) < 5)
                        <div class="absolute top-8 right-8">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 border border-red-100 rounded-full text-[10px] font-bold text-red-600 uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Urgent
                        </span>
                </div>
                @else
                <div class="absolute top-8 right-8">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 border border-blue-100 rounded-full text-[10px] font-bold text-blue-600 uppercase tracking-wider">
                        {{ $campaign->kategori }}
                    </span>
                </div>
                @endif

                {{-- Campaign Image --}}
                @if($campaign->image)
                    <div class="w-full h-40 rounded-xl overflow-hidden mb-4">
                        <img src="{{ $campaign->image }}"
                             alt="{{ $campaign->judul }}"
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x200?text=No+Image';">
                    </div>
                @else
                    <div class="w-full h-40 rounded-xl bg-gray-100 mb-4 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                    </div>
                @endif

                {{-- Icon --}}
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 text-2xl mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    @if($campaign->kategori == 'Pendidikan') <i class="fas fa-chalkboard-teacher"></i>
                    @elseif($campaign->kategori == 'Kesehatan') <i class="fas fa-heartbeat"></i>
                    @elseif($campaign->kategori == 'Bencana') <i class="fas fa-house-damage"></i>
                    @elseif($campaign->kategori == 'Lingkungan') <i class="fas fa-leaf"></i>
                    @else <i class="fas fa-hand-holding-heart"></i>
                    @endif
                </div>

                {{-- Judul --}}
                <h3 class="text-2xl font-bold text-slate-900 mb-3 line-clamp-1" title="{{ $campaign->judul }}">
                    {{ $campaign->judul }}
                </h3>
                <p class="text-slate-500 mb-6 leading-relaxed line-clamp-2 flex-grow">
                    {{ Str::limit($campaign->deskripsi, 80) }}
                </p>

                {{-- Progress Bar --}}
                <div class="mb-6 w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $campaign->progress }}%"></div>
                </div>

                {{-- Footer --}}
                <div class="space-y-4 pt-6 border-t border-slate-50 mt-auto">
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                            @if($isRemote) <i class="fas fa-laptop-house"></i> @else <i class="fas fa-map-marker-alt"></i> @endif
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Lokasi</p>
                            <p class="font-bold text-slate-800">{{ $campaign->lokasi }}</p>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <a href="{{ route('volunteer.campaigns.show', $campaign->slug) }}" class="block w-full py-3 px-4 bg-white border-2 border-slate-100 text-slate-900 font-bold rounded-xl text-center hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                        Daftar Sekarang <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            @empty
            {{-- Kosong --}}
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
                <div class="inline-flex w-16 h-16 bg-slate-100 rounded-full items-center justify-center mb-4">
                    <i class="fas fa-folder-open text-slate-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700">Belum ada kampanye aktif</h3>
            </div>
            @endforelse

        </div>

        {{-- NEW SECTION: Tombol Lihat Semua --}}
        <div class="flex flex-col items-center justify-center pt-8 border-t border-slate-200/60">
            <p class="text-slate-500 mb-4 text-sm font-medium">Masih belum menemukan peran yang cocok?</p>
            <a href="#" class="group inline-flex items-center gap-3 px-8 py-4 bg-slate-900 text-white rounded-full font-bold shadow-lg hover:bg-slate-800 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                Lihat Semua Divisi & Peran
                <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-all">
                    <i class="fas fa-arrow-right text-xs"></i>
                </div>
            </a>
        </div>

        </div>
    </section>

    <section class="py-24 bg-transparent relative overflow-hidden">
        {{-- Subtle background decoration --}}
        <div class="absolute top-1/2 -left-40 w-80 h-80 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob pointer-events-none"></div>

        <div class="container mx-auto px-6 max-w-7xl relative z-10">

            {{-- Header Section: Centered & Clean --}}
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-3 border border-indigo-100/60">Wall of Love</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">
                    Cerita Nyata Relawan
                </h2>
                <p class="text-slate-500 text-lg leading-relaxed">
                    Ribuan jam telah didonasikan. Inilah dampak yang dirasakan langsung oleh mereka yang turun tangan.
                </p>
            </div>

            {{-- MASONRY LAYOUT --}}
            <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">

                {{-- Card 1: Pendek --}}
                <div class="break-inside-avoid bg-white/85 backdrop-blur-xl p-6 rounded-2xl border border-white/80 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 card-hover-lift" data-aos="fade-up">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://i.pravatar.cc/150?img=32" class="w-10 h-10 rounded-full bg-slate-100">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Sarah Amalia</h4>
                            <p class="text-xs text-slate-500">Relawan Pengajar • 2024</p>
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        "Awalnya ragu karena jadwal kuliah padat. Tapi sistem shift di DonGiv sangat fleksibel. Melihat anak-anak bisa membaca adalah bayaran termahal."
                    </p>
                    <div class="pt-4 border-t border-slate-50 flex items-center gap-2 text-xs font-semibold text-indigo-600">
                        <i class="fas fa-clock"></i> Total 45 Jam Kontribusi
                    </div>
                </div>

                {{-- Card 2: Panjang (Highlight) --}}
                <div class="break-inside-avoid bg-white/90 backdrop-blur-xl p-8 rounded-2xl border-t-4 border-indigo-500 border-x border-b border-white/80 shadow-lg hover:shadow-2xl transition-all duration-300 card-hover-lift" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?img=11" class="w-12 h-12 rounded-full bg-slate-100 border-2 border-white shadow-sm">
                            <div>
                                <h4 class="font-bold text-slate-900">Dimas Pratama</h4>
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-slate-500">Logistik Bencana</span>
                                    <i class="fas fa-check-circle text-blue-500 text-[10px]" title="Verified"></i>
                                </div>
                            </div>
                        </div>
                        <span class="text-4xl text-indigo-200 font-serif">"</span>
                    </div>

                    <h5 class="text-lg font-bold text-slate-800 mb-3">Pengalaman yang mengubah hidup.</h5>
                    <p class="text-slate-600 leading-relaxed mb-6">
                        "Manajemen krisis di lapangan benar-benar menguji mental. Skill leadership saya tumbuh pesat di sini. Saya belajar bahwa menjadi relawan bukan soal 'memberi', tapi soal seberapa banyak kita 'menerima' pelajaran hidup dari mereka yang kuat."
                    </p>

                    <div class="flex gap-2">
                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase rounded-lg border border-indigo-100">Leadership</span>
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase rounded-lg border border-blue-100">Crisis Mgmt</span>
                    </div>
                </div>

                {{-- Card 3: Medium --}}
                <div class="break-inside-avoid bg-white/85 backdrop-blur-xl p-6 rounded-2xl border border-white/80 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 card-hover-lift" data-aos="fade-up" data-aos-delay="150">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://i.pravatar.cc/150?img=5" class="w-10 h-10 rounded-full bg-slate-100">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Rina Kartika</h4>
                            <p class="text-xs text-slate-500">Fundraiser • 2023</p>
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        "Platform DonGiv sangat transparan. Kita bisa lihat impact donasi secara real-time, itu yang bikin saya percaya diri mengajak mitra korporat."
                    </p>
                </div>

                {{-- Card 4: Panjang (Story) --}}
                <div class="break-inside-avoid bg-white/85 backdrop-blur-xl p-6 rounded-2xl border border-white/80 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 card-hover-lift" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://i.pravatar.cc/150?img=60" class="w-10 h-10 rounded-full bg-slate-100">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Budi Santoso</h4>
                            <p class="text-xs text-slate-500">Tech Support • 2024</p>
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        "Sebagai programmer introver, saya pikir sulit berkontribusi. Ternyata DonGiv butuh maintenance website. Coding untuk kebaikan rasanya beda banget!"
                    </p>
                    <div class="w-full bg-indigo-50/70 border border-indigo-100/50 rounded-xl p-3 text-center">
                        <p class="text-xs text-slate-500 font-medium">Project Terlibat</p>
                        <p class="text-sm font-bold text-indigo-600">Website Revamp 2.0</p>
                    </div>
                </div>

                {{-- Card 5: Pendek --}}
                <div class="break-inside-avoid bg-white/85 backdrop-blur-xl p-6 rounded-2xl border border-white/80 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 card-hover-lift" data-aos="fade-up" data-aos-delay="250">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="https://i.pravatar.cc/150?img=45" class="w-10 h-10 rounded-full bg-slate-100">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Anita Wijaya</h4>
                            <p class="text-xs text-slate-500">Event Staff • 2024</p>
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        "Capek? Pasti. Tapi melihat senyum para lansia saat acara charity kemarin? Worth every second."
                    </p>
                </div>

                {{-- Card 6: Impact --}}
                <div class="break-inside-avoid bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-600 p-8 rounded-2xl shadow-xl text-white text-center flex flex-col items-center justify-center min-h-[220px] card-hover-lift" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mb-4 backdrop-blur-md shadow-inner">
                        <i class="fas fa-heart text-2xl text-pink-200"></i>
                    </div>
                    <h4 class="font-bold text-2xl mb-2">Gabung Sekarang</h4>
                    <p class="text-indigo-100 text-sm mb-6 leading-relaxed">Jadilah bagian dari cerita perubahan selanjutnya bersama DonGiv.</p>
                    <a href="{{ route('volunteer.campaigns.index') }}" class="px-7 py-3 bg-white text-indigo-700 text-sm font-bold rounded-full hover:bg-slate-50 shadow-lg hover:shadow-white/20 transition-all transform hover:scale-105">
                        Daftar Relawan <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- ALUR PENDAFTARAN --}}
    <section class="py-24 bg-transparent relative border-t border-white/40">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider mb-3 border border-blue-100/60">Langkah Mudah</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Alur Pendaftaran Relawan</h2>
                <p class="text-gray-500 mt-2">Hanya butuh beberapa langkah mudah untuk memulai misi kebaikanmu.</p>
            </div>
            <div class="relative">
                <div class="hidden md:block absolute top-8 left-[10%] right-[10%] h-1 bg-gradient-to-r from-blue-200 via-indigo-200 to-purple-200 rounded-full"></div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    @foreach(['Daftar Akun', 'Pilih Program', 'Briefing/Seleksi', 'Mulai Aksi'] as $index => $step)
                    <div class="relative flex flex-col items-center text-center group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="w-16 h-16 rounded-2xl glass-premium border-2 border-white shadow-lg flex items-center justify-center text-xl font-black text-indigo-600 mb-6 relative z-10 group-hover:border-indigo-500 group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-110 transition-all duration-300">
                            {{ $index + 1 }}
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $step }}</h3>
                        <p class="text-sm text-gray-500 px-4">Langkah mudah untuk memulai dampak besarmu.</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: FAQ ACCORDION --}}
    <section class="py-24 bg-transparent relative border-t border-white/40">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-3 border border-indigo-100/60">Bantuan & FAQ</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mb-4">Sering Ditanyakan</h2>
                <p class="text-slate-500">Jawaban cepat untuk pertanyaan umum calon relawan.</p>
            </div>

            <div class="space-y-4">
                {{-- FAQ Item 1 --}}
                <div class="bg-white/85 backdrop-blur-xl rounded-2xl border border-white/80 shadow-sm overflow-hidden" x-data="{ open: false }" data-aos="fade-up" data-aos-delay="50">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-bold text-slate-900 text-lg">Apakah saya perlu pengalaman sebelumnya?</span>
                        <span :class="open ? 'rotate-180 bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500'" class="w-8 h-8 rounded-full flex items-center justify-center transition-transform duration-300">
                            <i class="fas fa-chevron-down text-sm"></i>
                        </span>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100/60 pt-4">
                        Tidak wajib untuk semua posisi! Posisi seperti "Relawan Pengajar" atau "Logistik" terbuka untuk pemula. Kami menyediakan sesi briefing dan pelatihan singkat sebelum terjun ke lapangan. Namun, posisi spesifik seperti "Medis" memerlukan sertifikasi.
                    </div>
                </div>

                {{-- FAQ Item 2 --}}
                <div class="bg-white/85 backdrop-blur-xl rounded-2xl border border-white/80 shadow-sm overflow-hidden" x-data="{ open: false }" data-aos="fade-up" data-aos-delay="100">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-bold text-slate-900 text-lg">Apakah ada sertifikat setelah kegiatan?</span>
                        <span :class="open ? 'rotate-180 bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500'" class="w-8 h-8 rounded-full flex items-center justify-center transition-transform duration-300">
                            <i class="fas fa-chevron-down text-sm"></i>
                        </span>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100/60 pt-4">
                        Ya, tentu saja. Setiap relawan yang menyelesaikan misinya dengan baik akan mendapatkan E-Sertifikat resmi dari DonGiv yang mencantumkan detail peran dan jumlah jam kontribusi. Sangat berguna untuk portofolio atau SKPI kampus.
                    </div>
                </div>

                {{-- FAQ Item 3 --}}
                <div class="bg-white/85 backdrop-blur-xl rounded-2xl border border-white/80 shadow-sm overflow-hidden" x-data="{ open: false }" data-aos="fade-up" data-aos-delay="150">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-6 text-left focus:outline-none">
                        <span class="font-bold text-slate-900 text-lg">Berapa lama durasi satu program?</span>
                        <span :class="open ? 'rotate-180 bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500'" class="w-8 h-8 rounded-full flex items-center justify-center transition-transform duration-300">
                            <i class="fas fa-chevron-down text-sm"></i>
                        </span>
                    </button>
                    <div x-show="open" x-collapse class="px-6 pb-6 text-slate-600 leading-relaxed border-t border-slate-100/60 pt-4">
                        Durasi bervariasi tergantung proyek. Ada yang bersifat *one-time event* (1 hari), ada juga program dampingan rutin selama 1-3 bulan. Anda bisa memfilter durasi ini saat memilih peran di dashboard relawan nanti.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: CALL TO ACTION & ID CARD PASS --}}
    <section class="py-24 bg-transparent relative">
        <div class="container mx-auto px-6 max-w-7xl">

            {{-- Main Container: Cosmic Gradient Banner (Mirip Home) --}}
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-[2.5rem] p-8 md:p-12 relative overflow-hidden shadow-2xl shadow-indigo-500/25" data-aos="zoom-in" data-aos-duration="1000">

                {{-- Ambient Watermark Teks DonGiv di dalam banner --}}
                <div class="absolute -right-10 -bottom-10 select-none pointer-events-none opacity-10 font-black text-[12vw] tracking-wider text-white whitespace-nowrap">
                    DONGIV
                </div>

                {{-- Background Decoration --}}
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 2px, transparent 2px); background-size: 32px 32px;"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-white opacity-15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">

                    {{-- KIRI: Copywriting --}}
                    <div class="w-full md:w-1/2 text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-bold uppercase tracking-wider mb-4 border border-white/30">
                            <i class="fas fa-id-badge"></i> Kartu Identitas Digital
                        </div>
                        <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 tracking-tight leading-tight">
                            Resmikan Niat Baikmu Sekarang.
                        </h2>
                        <p class="text-indigo-100 text-lg mb-8 leading-relaxed max-w-md mx-auto md:mx-0">
                            Dapatkan identitas resmi sebagai relawan DonGiv, akses pelatihan eksklusif, dan perluas jaringan kebaikanmu di seluruh penjuru negeri.
                        </p>

                        <div class="flex flex-col sm:flex-row items-center gap-4 justify-center md:justify-start">
                            <a href="{{ route('volunteer.campaigns.index') }}" class="px-8 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-lg hover:shadow-white/25 hover:bg-slate-50 hover:-translate-y-1 transition-all duration-300 flex items-center gap-2 group">
                                Gabung Sekarang <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                    {{-- KANAN: ID Card Visual --}}
                    <div class="w-full md:w-1/2 flex justify-center md:justify-end relative h-[380px] items-center">

                        {{-- Tali Lanyard --}}
                        <div class="absolute top-0 right-[25%] md:right-[22%] w-2 h-32 bg-indigo-900/50 z-0"></div>

                        {{-- Container Kartu --}}
                        <div class="relative z-10 animate-float">

                            {{-- Clip Besi --}}
                            <div class="mx-auto w-16 h-6 bg-slate-300 rounded-t-lg border-x-2 border-t-2 border-slate-400 relative z-20 shadow-md">
                                <div class="w-12 h-1 bg-slate-400 mx-auto mt-2 rounded-full"></div>
                            </div>

                            {{-- Fisik Kartu --}}
                            <div class="w-[300px] bg-white rounded-xl shadow-2xl overflow-hidden relative border-t-8 border-indigo-500 transform rotate-2 hover:rotate-0 transition-transform duration-500">

                                {{-- Lubang Gantungan --}}
                                <div class="absolute top-2 left-1/2 -translate-x-1/2 w-8 h-1.5 bg-slate-200 rounded-full z-20 shadow-inner"></div>

                                {{-- Header Kartu --}}
                                <div class="bg-slate-50 p-6 pb-4 border-b border-slate-100 text-center pt-8">
                                    <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonGiv Logo" class="h-8 w-auto mx-auto mb-2 object-contain">
                                    <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold">Volunteer Pass</p>
                                </div>

                                {{-- Body Kartu --}}
                                <div class="p-6 text-center">
                                    {{-- Foto Profil --}}
                                    <div class="w-24 h-24 mx-auto bg-slate-200 rounded-full p-1 border-2 border-indigo-100 mb-4 shadow-sm">
                                        <img src="https://i.pravatar.cc/150?img=12" class="w-full h-full rounded-full object-cover">
                                    </div>

                                    <h3 class="text-xl font-bold text-slate-900 leading-tight">Nama Kamu</h3>
                                    <p class="text-xs text-slate-500 font-medium mb-4">Relawan DonGiv</p>

                                    {{-- Info Grid --}}
                                    <div class="grid grid-cols-2 gap-2 text-left bg-indigo-50/50 p-3 rounded-lg border border-indigo-50 mb-4">
                                        <div>
                                            <p class="text-[9px] text-slate-400 uppercase font-bold">ID Number</p>
                                            <p class="text-xs font-mono font-bold text-indigo-700">DG-8821</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] text-slate-400 uppercase font-bold">Valid Thru</p>
                                            <p class="text-xs font-mono font-bold text-slate-700">12/26</p>
                                        </div>
                                    </div>

                                    {{-- Barcode --}}
                                    <div class="flex justify-between items-center opacity-60">
                                        <div class="h-6 w-32 bg-repeat-x" style="background-image: linear-gradient(90deg, #333 0, #333 1px, transparent 1px, transparent 3px);"></div>
                                        <i class="fas fa-qrcode text-2xl text-slate-800"></i>
                                    </div>
                                </div>

                                {{-- Hologram Strip (Footer) --}}
                                <div class="h-2 w-full bg-gradient-to-r from-indigo-400 via-purple-400 to-indigo-400 opacity-60"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== SCRIPT INTERAKTIF & PARTICLE ===================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Initialize AOS (Animate on Scroll)
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    once: true,
                    duration: 800,
                    easing: 'ease-out-cubic',
                    offset: 80
                });
            }

            // 2. Interactive Particle Canvas for Volunteer Hero (Identik dengan Home)
            const canvas = document.getElementById('particleCanvasVolunteer');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                let particles = [];
                const PARTICLE_COUNT = 35;

                function resizeCanvas() {
                    const section = canvas.parentElement;
                    if (section) {
                        canvas.width = section.offsetWidth;
                        canvas.height = section.offsetHeight;
                    }
                }

                function createParticle() {
                    return {
                        x: Math.random() * (canvas.width || window.innerWidth),
                        y: Math.random() * (canvas.height || window.innerHeight),
                        size: Math.random() * 3 + 1,
                        speedX: (Math.random() - 0.5) * 0.45,
                        speedY: (Math.random() - 0.5) * 0.3,
                        opacity: Math.random() * 0.35 + 0.12,
                        hue: Math.random() * 40 + 215, // Biru ke Indigo range
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
                        ctx.fillStyle = `hsla(${p.hue}, 75%, 60%, ${p.opacity})`;
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

                    // Draw connections between nearby particles
                    for (let i = 0; i < particles.length; i++) {
                        for (let j = i + 1; j < particles.length; j++) {
                            const dx = particles[i].x - particles[j].x;
                            const dy = particles[i].y - particles[j].y;
                            const dist = Math.sqrt(dx * dx + dy * dy);
                            if (dist < 140) {
                                ctx.beginPath();
                                ctx.moveTo(particles[i].x, particles[i].y);
                                ctx.lineTo(particles[j].x, particles[j].y);
                                ctx.strokeStyle = `rgba(37, 99, 235, ${0.07 * (1 - dist / 140)})`;
                                ctx.lineWidth = 0.6;
                                ctx.stroke();
                            }
                        }
                    }

                    requestAnimationFrame(drawParticles);
                }

                resizeCanvas();
                initParticles();
                drawParticles();
                window.addEventListener('resize', () => {
                    resizeCanvas();
                    initParticles();
                });
            }

            // 3. GSAP Parallax on Badges (jika tersedia)
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);
                gsap.utils.toArray('.animate-float, .animate-float-slow, .animate-float-delay').forEach(el => {
                    gsap.to(el, {
                        y: -15,
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
        });
    </script>
</x-app>