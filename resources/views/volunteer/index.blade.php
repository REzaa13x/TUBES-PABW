<x-app title="DonGiv - Bergabung sebagai Relawan">
    {{-- Custom Style untuk halaman ini --}}
    <style>
        /* Style untuk panah di <details> (FAQ) */
        summary {
            list-style: none;
        }

        summary::-webkit-details-marker {
            display: none;
        }

        summary::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            float: right;
            transition: transform 0.2s ease;
        }

        details[open] summary::after {
            transform: rotate(180deg);
        }

        /* Animasi Ombak */
        @keyframes waveMove {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .wave-1 {
            animation: waveMove 12s linear infinite;
        }

        .wave-2 {
            animation: waveMove 18s linear infinite;
        }
    </style>

    {{-- HERO SECTION --}}
    <section
        class="relative h-[65vh] md:h-[85vh] flex items-center justify-center text-white pt-20 overflow-hidden bg-gradient-to-b from-primary to-primary/70">
        <div class="absolute inset-0 bg-primary/60 z-10"></div>

        <img src="https://images.unsplash.com/photo-1593113598332-cd288d649414?q=80&w=2070&auto=format&fit=crop"
            class="absolute inset-0 w-full h-full object-cover opacity-20" alt="Tim Relawan DonGiv Beraksi">

        <div class="relative z-20 text-center max-w-4xl mx-auto px-4">
            <span
                class="inline-block bg-white/20 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-white/20">
                Gabung #PasukanKebaikan
            </span>

            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4 drop-shadow-lg">
                Waktu Luangmu, Harapan Baru Mereka.
            </h1>

            <p class="text-lg md:text-xl mb-8 max-w-2xl mx-auto drop-shadow-md text-blue-100">
                Kami bukan cuma nyari relawan. Kami nyari kamu yang mau jadi bagian dari cerita perubahan. Jadilah
                tangan, mata, dan hati kebaikan bersama DonGiv.
            </p>

            <a href="{{ route('volunteer.register') }}"
                class="bg-accent hover:bg-amber-500 text-white font-bold py-3 px-8 rounded-full text-lg transition-transform hover:scale-105 shadow-lg inline-block">
                Gabung Sekarang
            </a>
        </div>

        <div class="absolute bottom-0 left-0 w-full h-[120px] overflow-hidden z-20">
            <svg class="wave-1 absolute bottom-0 left-0 w-[200%] h-full opacity-70" viewBox="0 0 1440 320"
                preserveAspectRatio="none">
                <path fill="#ffffff"
                    d="M0,288L48,272C96,256,192,224,288,197.3C384,171,480,149,576,149.3C672,149,768,171,864,192C960,213,1056,235,1152,229.3C1248,224,1344,192,1392,176L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                </path>
            </svg>
            <svg class="wave-2 absolute bottom-0 left-0 w-[200%] h-full opacity-40" viewBox="0 0 1440 320"
                preserveAspectRatio="none">
                <path fill="#ffffff"
                    d="M0,224L48,218.7C96,213,192,203,288,181.3C384,160,480,128,576,144C672,160,768,224,864,229.3C960,235,1056,181,1152,176C1248,171,1344,213,1392,234.7L1440,256L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
                </path>
            </svg>
        </div>
    </section>

    {{-- STATS SECTION --}}
    <section class="bg-white py-16 border-b border-gray-100" id="stats-section">
        <div class="max-w-6xl mx-auto px-6">
            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-gray-100">
                <div class="py-4 md:py-0">
                    <p class="text-4xl md:text-5xl font-extrabold text-primary" data-scramble-target="1,200+">0,000+</p>
                    <p class="text-lg text-gray-500 mt-2 font-medium">Relawan Terdaftar</p>
                </div>
                <div class="py-4 md:py-0">
                    <p class="text-4xl md:text-5xl font-extrabold text-primary" data-scramble-target="8,500+">0,000+</p>
                    <p class="text-lg text-gray-500 mt-2 font-medium">Jam Kontribusi</p>
                </div>
                <div class="py-4 md:py-0">
                    <p class="text-4xl md:text-5xl font-extrabold text-primary" data-scramble-target="300+">000+</p>
                    <p class="text-lg text-gray-500 mt-2 font-medium">Program Terselesaikan</p>
                </div>
            </div>
        </div>
    </section>

    {{-- IMPACT SECTION --}}
    <section class="py-24 bg-softblue relative overflow-hidden">
        {{-- Background blobs for modern feel --}}
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
        </div>

        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20">
                <span class="text-primary font-bold tracking-wide uppercase text-sm mb-3 block">Mengapa
                    Bergabung?</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Lebih dari Sekadar Membantu</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto text-lg">Ini bukan cuma soal ngasih waktu, tapi soal apa
                    yang kamu dapetin kembali untuk pengembangan dirimu.</p>
            </div>

            {{-- Poin 1: Dampak Nyata --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24">
                <div class="relative group">
                    <div
                        class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl opacity-20 group-hover:opacity-40 blur-lg transition duration-200">
                    </div>
                    <img src="https://images.unsplash.com/photo-1518395689561-9c603f0474b3?q=80&w=1974&auto=format&fit=crop"
                        alt="Relawan Menyalurkan Bantuan"
                        class="relative rounded-2xl shadow-2xl w-full h-full object-cover transform transition duration-500 group-hover:scale-[1.02]">
                </div>
                <div class="pl-0 md:pl-10">
                    <div
                        class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-primary text-2xl mb-6">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Rasakan Dampak Nyata</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed text-lg">Kamu akan terlibat langsung di lapangan,
                        melihat senyum penerima manfaat, dan jadi saksi bahwa bantuanmu sampai tepat sasaran. Pengalaman
                        emosional yang tak ternilai harganya.</p>
                    <a href="#gabung-relawan"
                        class="inline-flex items-center font-semibold text-primary hover:text-blue-700 transition group">
                        Lihat program kami <i
                            class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            {{-- Poin 2: Jaringan & Skill --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="md:order-last relative group">
                    <div
                        class="absolute -inset-4 bg-gradient-to-r from-blue-600 to-teal-400 rounded-2xl opacity-20 group-hover:opacity-40 blur-lg transition duration-200">
                    </div>
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop"
                        alt="Komunitas Relawan DonGiv"
                        class="relative rounded-2xl shadow-2xl w-full h-full object-cover transform transition duration-500 group-hover:scale-[1.02]">
                </div>
                <div class="pr-0 md:pr-10">
                    <div
                        class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-primary text-2xl mb-6">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Keluarga & Keahlian Baru</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed text-lg">Bertemu orang-orang se-frekuensi, bangun
                        jaringan profesional, dan asah *skill* baru seperti leadership, komunikasi, dan manajemen acara
                        yang tidak diajarkan di dalam kelas.</p>
                    <a href="#testimonial"
                        class="inline-flex items-center font-semibold text-primary hover:text-blue-700 transition group">
                        Dengar cerita mereka <i
                            class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- STORY & TESTIMONIAL --}}
    <section id="story-and-testimonial" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-wide uppercase text-sm mb-3 block">Testimoni</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Cerita & Suara #PasukanKebaikan</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Kenapa kami ada, dan kenapa mereka bertahan.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                <div>
                    <span
                        class="inline-block px-3 py-1 bg-blue-100 text-primary rounded-full text-xs font-bold mb-4">The
                        Origin Story</span>
                    <h3 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">Bukan Sekadar Hashtag.<br>Ini Cerita
                        Kita.</h3>
                    <div class="prose prose-lg text-gray-600 max-w-none space-y-4">
                        <p>Jujur? <strong>#PasukanKebaikan</strong> itu lahir dari obrolan warteg 5 mahasiswa yang bosen
                            cuma "amin-in" doa di medsos tiap ada bencana.</p>
                        <p>Nama <strong>"Pasukan"</strong> kami pilih karena kami mau gerak cepet, taktis, dan
                            terorganisir. Nama <strong>"Kebaikan"</strong> kami pilih karena itu satu-satunya misi kami.
                        </p>
                        <p>Dari 5 orang di tahun 2023, sekarang kita jadi keluarga besar <span
                                class="text-primary font-bold">1,200+</span> relawan. Ini bukan cuma *platform* donasi,
                            ini adalah gerakan. Dan lo, adalah bagian penting selanjutnya dari cerita ini.</p>
                    </div>
                    <a href="#gabung-relawan"
                        class="mt-8 inline-block bg-primary hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition-all transform hover:-translate-y-1 shadow-lg shadow-blue-200">
                        Jadi Bagian dari Cerita
                    </a>
                </div>

                <div
                    class="relative w-full bg-softblue rounded-3xl shadow-xl overflow-hidden min-h-[450px] flex items-center border border-blue-100">
                    <div class="absolute top-0 right-0 p-8 opacity-10 text-primary">
                        <i class="fas fa-quote-right text-9xl"></i>
                    </div>

                    <div id="testimonial-slider" class="flex transition-transform duration-700 ease-in-out w-full">
                        {{-- Slide 1 --}}
                        <div class="min-w-full p-12 flex flex-col items-center text-center relative z-10">
                            <div class="w-20 h-20 bg-white p-1 rounded-full shadow-md mb-6">
                                <img src="https://i.pravatar.cc/150?img=11"
                                    class="w-full h-full rounded-full object-cover" alt="Relawan">
                            </div>
                            <p class="text-gray-700 italic text-xl mb-8 leading-relaxed font-medium">"Gabung DonGiv
                                ngubah cara pandang gue. Gue kira gue mau bantu orang, ternyata gue yang lebih banyak
                                dibantu: jadi lebih sabar dan bersyukur."</p>
                            <div>
                                <p class="font-bold text-gray-900 text-lg">Aisha Putri</p>
                                <p class="text-sm text-primary font-semibold">Relawan (Tim Edukasi)</p>
                            </div>
                        </div>
                        {{-- Slide 2 --}}
                        <div class="min-w-full p-12 flex flex-col items-center text-center relative z-10">
                            <div class="w-20 h-20 bg-white p-1 rounded-full shadow-md mb-6">
                                <img src="https://i.pravatar.cc/150?img=32"
                                    class="w-full h-full rounded-full object-cover" alt="Relawan">
                            </div>
                            <p class="text-gray-700 italic text-xl mb-8 leading-relaxed font-medium">"Awalnya ragu,
                                takut repot. Ternyata sistemnya jelas, timnya asik, dan liat anak-anak panti senyum pas
                                kita dateng... worth it banget!"</p>
                            <div>
                                <p class="font-bold text-gray-900 text-lg">Budi Santoso</p>
                                <p class="text-sm text-primary font-semibold">Relawan (Tim Lapangan)</p>
                            </div>
                        </div>
                        {{-- Slide 3 --}}
                        <div class="min-w-full p-12 flex flex-col items-center text-center relative z-10">
                            <div class="w-20 h-20 bg-white p-1 rounded-full shadow-md mb-6">
                                <img src="https://i.pravatar.cc/150?img=26"
                                    class="w-full h-full rounded-full object-cover" alt="Relawan">
                            </div>
                            <p class="text-gray-700 italic text-xl mb-8 leading-relaxed font-medium">"Sebagai desainer,
                                gue seneng banget bisa nyumbang skill buat bikin materi kampanye. Gak harus turun
                                langsung, tapi dampaknya kerasa."</p>
                            <div>
                                <p class="font-bold text-gray-900 text-lg">Citra Lestari</p>
                                <p class="text-sm text-primary font-semibold">Relawan (Tim Kreatif)</p>
                            </div>
                        </div>
                    </div>

                    {{-- Dots --}}
                    <div id="slider-dots" class="absolute bottom-8 left-0 right-0 flex justify-center space-x-3">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ROLES SECTION --}}
    <section class="py-24 bg-softblue">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-wide uppercase text-sm mb-3 block">Posisi Tersedia</span>
                <h2 class="text-3xl font-bold text-gray-900">Cari Peran yang Paling 'Kamu Banget'</h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Gak semua harus ke lapangan. Kontribusimu bisa dalam
                    banyak bentuk sesuai keahlianmu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Role Card Template --}}
                @foreach ([['icon' => 'hands-helping', 'title' => 'Relawan Lapangan', 'desc' => 'Turun langsung menyalurkan bantuan, asesmen kebutuhan, dan interaksi dengan penerima manfaat.'], ['icon' => 'camera', 'title' => 'Tim Kreatif', 'desc' => 'Bantu bikin desain visual, video, dan konten media sosial yang nendang.'], ['icon' => 'chalkboard-teacher', 'title' => 'Pengajar & Mentor', 'desc' => 'Berbagi ilmu dan keahlianmu di program-program edukasi kami.'], ['icon' => 'laptop-code', 'title' => 'Dukungan Teknis', 'desc' => 'Punya skill IT, web, atau data? Bantuanmu sangat kami butuhkan!'], ['icon' => 'bullhorn', 'title' => 'Penggalang Dana', 'desc' => 'Bantu kami menjangkau lebih banyak donatur dan partner kebaikan.'], ['icon' => 'calendar-check', 'title' => 'Event & Logistik', 'desc' => 'Si paling jago ngatur acara dan memastikan semua berjalan mulus.']] as $role)
                    <div
                        class="bg-white rounded-2xl shadow-sm hover:shadow-xl p-8 transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 group">
                        <div
                            class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-primary text-xl mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                            <i class="fas fa-{{ $role['icon'] }}"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $role['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed text-sm">{{ $role['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- STEPS SECTION --}}
    <section id="gabung-relawan" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-wide uppercase text-sm mb-3 block">Cara Bergabung</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">3 Langkah Jadi #PasukanKebaikan</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center relative">
                {{-- Connector Line --}}
                <div
                    class="hidden md:block absolute top-12 left-[20%] right-[20%] h-0.5 bg-gray-200 border-t-2 border-dashed border-gray-300 -z-10">
                </div>

                <div class="relative group">
                    <div
                        class="w-24 h-24 mx-auto bg-white rounded-full border-4 border-white shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <div
                            class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-2xl font-bold text-primary">
                            1</div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Isi Formulir</h3>
                    <p class="text-gray-600 text-sm px-4">Klik 'Daftar Sekarang' dan lengkapi data dirimu. Prosesnya
                        cepat, cuma 5 menit.</p>
                </div>

                <div class="relative group">
                    <div
                        class="w-24 h-24 mx-auto bg-white rounded-full border-4 border-white shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <div
                            class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-2xl font-bold text-primary">
                            2</div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Seleksi & Wawancara</h3>
                    <p class="text-gray-600 text-sm px-4">Tim kami akan menghubungimu untuk ngobrol santai mengenai
                        minat dan ketersediaanmu.</p>
                </div>

                <div class="relative group">
                    <div
                        class="w-24 h-24 mx-auto bg-white rounded-full border-4 border-white shadow-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <div
                            class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center text-2xl font-bold text-primary">
                            3</div>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Selamat Bergabung!</h3>
                    <p class="text-gray-600 text-sm px-4">Kamu resmi jadi bagian keluarga, diundang ke grup koordinasi,
                        dan siap beraksi.</p>
                </div>
            </div>

            <div class="text-center mt-20">
                <a href="{{ route('volunteer.register') }}"
                    class="bg-primary hover:bg-blue-700 text-white font-bold py-4 px-12 rounded-full text-lg transition-all transform hover:-translate-y-1 shadow-lg hover:shadow-blue-500/30">
                    Daftar Sekarang
                </a>
                <p class="mt-4 text-sm text-gray-500">Gratis dan terbuka untuk umum.</p>
            </div>
        </div>
    </section>

    {{-- REWARDS SECTION --}}
    <section class="py-24 bg-primary text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10">
        </div>
        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-extrabold mb-6">Ini yang Kamu Bawa Pulang</h2>
            <p class="text-lg text-blue-100 mb-16 max-w-2xl mx-auto leading-relaxed">Kami percaya kontribusimu sangat
                berharga. Ini bentuk apresiasi kecil dari kami, selain hati yang (pastinya) gembira.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    class="bg-white/10 p-8 rounded-2xl backdrop-blur-md border border-white/10 hover:bg-white/20 transition-all group">
                    <div class="text-accent text-4xl mb-6 group-hover:scale-110 transition-transform inline-block"><i
                            class="fas fa-certificate"></i></div>
                    <h3 class="text-xl font-bold mb-3">Sertifikat Profesional</h3>
                    <p class="text-sm text-blue-100 opacity-80 leading-relaxed">Sertifikat resmi yang valid untuk
                        portofolio CV atau LinkedIn kamu.</p>
                </div>
                <div
                    class="bg-white/10 p-8 rounded-2xl backdrop-blur-md border border-white/10 hover:bg-white/20 transition-all group">
                    <div class="text-accent text-4xl mb-6 group-hover:scale-110 transition-transform inline-block"><i
                            class="fas fa-users"></i></div>
                    <h3 class="text-xl font-bold mb-3">Jaringan & Mentoring</h3>
                    <p class="text-sm text-blue-100 opacity-80 leading-relaxed">Akses ke komunitas profesional dan
                        mentor dari berbagai industri.</p>
                </div>
                <div
                    class="bg-white/10 p-8 rounded-2xl backdrop-blur-md border border-white/10 hover:bg-white/20 transition-all group">
                    <div class="text-accent text-4xl mb-6 group-hover:scale-110 transition-transform inline-block"><i
                            class="fas fa-star"></i></div>
                    <h3 class="text-xl font-bold mb-3">Pengembangan Skill</h3>
                    <p class="text-sm text-blue-100 opacity-80 leading-relaxed">Belajar leadership, project management,
                        dan soft-skill lainnya.</p>
                </div>
                <div
                    class="bg-white/10 p-8 rounded-2xl backdrop-blur-md border border-white/10 hover:bg-white/20 transition-all group">
                    <div class="text-accent text-4xl mb-6 group-hover:scale-110 transition-transform inline-block"><i
                            class="fas fa-shirt"></i></div>
                    <h3 class="text-xl font-bold mb-3">Merchandise Eksklusif</h3>
                    <p class="text-sm text-blue-100 opacity-80 leading-relaxed">Kit eksklusif #PasukanKebaikan (kaos,
                        stiker, dll) tanda kebanggaan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ SECTION --}}
    <section class="py-24 bg-softblue">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-wide uppercase text-sm mb-3 block">FAQ</span>
                <h2 class="text-3xl font-bold text-gray-900">Masih Ragu?</h2>
                <p class="text-gray-600 mt-4">Kami jawab pertanyaan yang paling sering bikin penasaran.</p>
            </div>

            <div class="space-y-4">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group transition-all duration-300 hover:shadow-md">
                    <details class="group p-6 cursor-pointer">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none text-lg">
                            Apakah saya harus punya keahlian khusus?
                            <span class="transition-transform duration-300 group-open:rotate-180 text-primary"><i
                                    class="fas fa-chevron-down"></i></span>
                        </summary>
                        <p class="text-gray-600 mt-4 leading-relaxed pl-4 border-l-4 border-primary/20">Niat adalah
                            keahlian utamamu! Kami punya banyak peran. Untuk peran teknis, keahlian spesifik akan jadi
                            nilai plus. Tapi untuk Tim Lapangan, yang penting semangat!</p>
                    </details>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group transition-all duration-300 hover:shadow-md">
                    <details class="group p-6 cursor-pointer">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none text-lg">
                            Berapa banyak waktu yang harus saya luangkan?
                            <span class="transition-transform duration-300 group-open:rotate-180 text-primary"><i
                                    class="fas fa-chevron-down"></i></span>
                        </summary>
                        <p class="text-gray-600 mt-4 leading-relaxed pl-4 border-l-4 border-primary/20">Fleksibel! Kami
                            ada program berbasis proyek (sekali acara) dan ada yang rutin mingguan. Kamu bisa pilih yang
                            paling sesuai dengan jadwalmu.</p>
                    </details>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group transition-all duration-300 hover:shadow-md">
                    <details class="group p-6 cursor-pointer">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none text-lg">
                            Apakah saya akan dapat sertifikat?
                            <span class="transition-transform duration-300 group-open:rotate-180 text-primary"><i
                                    class="fas fa-chevron-down"></i></span>
                        </summary>
                        <p class="text-gray-600 mt-4 leading-relaxed pl-4 border-l-4 border-primary/20">Tentu saja!
                            Kami akan memberikan e-sertifikat resmi sebagai tanda apresiasi atas kontribusi waktu dan
                            tenagamu setelah program selesai.</p>
                    </details>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group transition-all duration-300 hover:shadow-md">
                    <details class="group p-6 cursor-pointer">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none text-lg">
                            Saya di luar kota, apakah bisa gabung?
                            <span class="transition-transform duration-300 group-open:rotate-180 text-primary"><i
                                    class="fas fa-chevron-down"></i></span>
                        </summary>
                        <p class="text-gray-600 mt-4 leading-relaxed pl-4 border-l-4 border-primary/20">Sangat bisa!
                            Banyak peran di Tim Kreatif, Dukungan Teknis, atau Penggalang Dana yang bisa dilakukan 100%
                            secara *remote* (online) dari mana saja.</p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- SCRAMBLE ANIMATION ---
            const scramble = (element) => {
                const targetText = element.getAttribute('data-scramble-target');
                const chars = '0123456789';
                let iteration = 0;
                if (element.scrambleInterval) clearInterval(element.scrambleInterval);
                element.scrambleInterval = setInterval(() => {
                    element.innerText = element.innerText.split('').map((char, index) => {
                        if (index < iteration) return targetText[index];
                        if (targetText[index] === ',' || targetText[index] === '+')
                            return targetText[index];
                        return chars[Math.floor(Math.random() * chars.length)];
                    }).join('');
                    if (iteration >= targetText.length) {
                        clearInterval(element.scrambleInterval);
                        element.innerText = targetText;
                    }
                    iteration += targetText.length / 15;
                }, 50);
            };

            const statsSection = document.getElementById('stats-section');
            const scrambleElements = document.querySelectorAll('[data-scramble-target]');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        scrambleElements.forEach(el => scramble(el));
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.4
            });

            if (statsSection) observer.observe(statsSection);

            // --- TESTIMONIAL SLIDER ---
            const slider = document.getElementById("testimonial-slider");
            if (slider) {
                const slideCount = slider.children.length;
                const dotsContainer = document.getElementById("slider-dots");
                let index = 0;
                for (let i = 0; i < slideCount; i++) {
                    const dot = document.createElement("div");
                    dot.className =
                        "w-3 h-3 bg-gray-300 rounded-full cursor-pointer transition-all hover:bg-blue-400";
                    if (i === 0) dot.classList.add("bg-primary", "w-6"); // Active dot is wider
                    dot.onclick = () => moveTo(i);
                    dotsContainer.appendChild(dot);
                }
                const dots = dotsContainer.children;

                function moveTo(i) {
                    index = i;
                    slider.style.transform = `translateX(-${i * 100}%)`;
                    [...dots].forEach(d => {
                        d.classList.remove("bg-primary", "w-6");
                        d.classList.add("bg-gray-300", "w-3");
                    });
                    dots[i].classList.remove("bg-gray-300", "w-3");
                    dots[i].classList.add("bg-primary", "w-6");
                }
                setInterval(() => {
                    index = (index + 1) % slideCount;
                    moveTo(index);
                }, 6000); // Slower interval for better reading time
            }
        });
    </script>
</x-app>
