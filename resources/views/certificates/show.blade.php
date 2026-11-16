<x-app title="Sertifikat {{ $certificate->certificate_number }} - Dongiv">
    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Back Button --}}
            <a href="{{ route('certificates.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary transition-colors text-sm font-bold mb-6">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Sertifikat
            </a>

            {{-- Status Banner --}}
            @php
                $bannerClass = match($certificate->status) {
                    'active'  => 'bg-green-50 border-green-200 text-green-700',
                    'pending' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
                    'revoked' => 'bg-red-50 border-red-200 text-red-700',
                    default   => 'bg-slate-50 border-slate-200 text-slate-700',
                };
                $bannerIcon = match($certificate->status) {
                    'active'  => 'fa-check-circle',
                    'pending' => 'fa-clock',
                    'revoked' => 'fa-ban',
                    default   => 'fa-info-circle',
                };
            @endphp
            <div class="p-4 rounded-2xl border {{ $bannerClass }} flex items-start gap-3 text-sm mb-8">
                <i class="fas {{ $bannerIcon }} text-lg mt-0.5"></i>
                <div>
                    <span class="font-extrabold">Status Sertifikat: {{ $certificate->status_label }}</span>
                    @if($certificate->status === 'pending')
                        <p class="mt-1 text-xs text-yellow-600/90 font-medium">Sertifikat dapat diunduh dalam format PDF setelah kampanye selesai dilaksanakan pada {{ optional($certificate->end_date)->format('d M Y') }}.</p>
                    @elseif($certificate->status === 'active')
                        <p class="mt-1 text-xs text-green-600/90 font-medium">Sertifikat ini aktif dan sah. Dapat diunduh sebagai PDF resmi dan diverifikasi secara publik.</p>
                    @endif
                </div>
            </div>

            {{-- ── Certificate Preview Card ──────────────────────────── --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-blue-500/5 overflow-hidden mb-8 relative">
                
                {{-- Border design --}}
                <div class="absolute inset-4 border-2 border-amber-500/30 rounded-2xl pointer-events-none z-10"></div>
                <div class="absolute inset-5 border border-amber-500/10 rounded-2xl pointer-events-none z-10"></div>
                
                {{-- Decorative Corners --}}
                <div class="absolute top-6 left-6 w-12 h-12 border-t-2 border-l-2 border-amber-500 rounded-tl pointer-events-none z-10"></div>
                <div class="absolute top-6 right-6 w-12 h-12 border-t-2 border-r-2 border-amber-500 rounded-tr pointer-events-none z-10"></div>
                <div class="absolute bottom-6 left-6 w-12 h-12 border-b-2 border-l-2 border-amber-500 rounded-bl pointer-events-none z-10"></div>
                <div class="absolute bottom-6 right-6 w-12 h-12 border-b-2 border-r-2 border-amber-500 rounded-br pointer-events-none z-10"></div>

                {{-- Watermark --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none opacity-[0.02] z-0">
                    <span class="text-[180px] font-black text-blue-600 rotate-[-25deg]">DGV</span>
                </div>

                {{-- Certificate Body --}}
                <div class="px-6 py-12 sm:px-16 sm:py-16 relative z-20 text-center">
                    
                    {{-- Logo --}}
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-xl shadow-md">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <span class="text-2xl font-black text-slate-800 tracking-tight">Dongiv</span>
                    </div>

                    {{-- Gold Stars Divider --}}
                    <div class="flex items-center gap-4 max-w-xs mx-auto mb-6">
                        <div class="flex-1 h-px bg-gradient-to-r from-transparent to-amber-500"></div>
                        <span class="text-amber-500 text-xs">★ ★ ★</span>
                        <div class="flex-1 h-px bg-gradient-to-l from-transparent to-amber-500"></div>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-800 tracking-wide uppercase">Sertifikat Penghargaan</h1>
                    <p class="text-[10px] text-amber-600 tracking-[0.2em] uppercase font-bold mt-1">Certificate of Appreciation</p>

                    <p class="text-xs sm:text-sm text-slate-400 font-medium italic mt-8">Dengan bangga mempersembahkan kepada</p>
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold text-blue-600 tracking-wide mt-4">{{ $certificate->volunteer_name }}</h2>
                    <div class="w-24 h-0.5 bg-blue-100 mx-auto mt-4 mb-6"></div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-xl mx-auto">
                        Sebagai bentuk apresiasi atas dedikasi dan kontribusi nyata sebagai relawan sosial dalam menyukseskan kampanye kemanusiaan:
                        <br>
                        <span class="text-base sm:text-lg font-bold text-slate-800 block mt-2 font-serif">"{{ $certificate->campaign_name }}"</span>
                    </p>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-1 bg-slate-100 border border-slate-100 rounded-2xl overflow-hidden my-8 max-w-2xl mx-auto">
                        <div class="bg-slate-50/50 p-4">
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Kategori</p>
                            <p class="text-xs font-black text-slate-800 mt-0.5">{{ $certificate->category ?? 'Relawan' }}</p>
                        </div>
                        <div class="bg-slate-50/50 p-4">
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Lokasi</p>
                            <p class="text-xs font-black text-slate-800 mt-0.5 line-clamp-1">{{ $certificate->location ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50/50 p-4 col-span-2 sm:col-span-1">
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Periode</p>
                            <p class="text-[10px] font-black text-slate-800 mt-0.5 whitespace-nowrap">
                                {{ optional($certificate->start_date)->format('d M Y') }} - {{ optional($certificate->end_date)->format('d M Y') }}
                            </p>
                        </div>
                        <div class="bg-slate-50/50 p-4">
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Tingkat Relawan</p>
                            <p class="text-xs font-black text-slate-800 mt-0.5">{{ $certificate->volunteer_level }}</p>
                        </div>
                        <div class="bg-slate-50/50 p-4">
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Total Jam Kerja</p>
                            <p class="text-xs font-black text-slate-800 mt-0.5">{{ $certificate->total_volunteer_hours }} Jam</p>
                        </div>
                        <div class="bg-slate-50/50 p-4 col-span-2 sm:col-span-1">
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Penyelenggara</p>
                            <p class="text-xs font-black text-slate-800 mt-0.5 line-clamp-1">{{ $certificate->organizer_name }}</p>
                        </div>
                    </div>

                    {{-- Footer Row --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-6 mt-10 pt-8 border-t border-slate-100">
                        {{-- Cert ID & Info --}}
                        <div class="text-left order-3 sm:order-1">
                            <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Nomor Sertifikat</p>
                            <p class="text-xs font-mono font-bold text-blue-600 mt-0.5 bg-blue-50 px-2 py-0.5 rounded">{{ $certificate->certificate_number }}</p>
                            <p class="text-[9px] text-slate-400 mt-1">Diterbitkan: {{ optional($certificate->issue_date)->format('d M Y') }}</p>
                        </div>

                        {{-- QR Code --}}
                        <div class="order-1 sm:order-2">
                            <div class="p-2 bg-slate-50 rounded-xl border border-slate-100 inline-block shadow-inner">
                                <img src="{{ $certificate->qr_url }}" class="w-16 h-16" alt="QR Code Verifikasi" onerror="this.style.display='none'">
                            </div>
                            <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-widest font-extrabold">Pindai Verifikasi</p>
                        </div>

                        {{-- Signature --}}
                        <div class="order-2 sm:order-3 text-center sm:text-right">
                            <div class="w-36 border-b border-slate-300 mx-auto sm:ml-auto mb-2"></div>
                            <p class="text-xs font-bold text-slate-800">Tim Verifikasi Dongiv</p>
                            <p class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Social Impact Platform</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Action Panel --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm mb-8">
                <h3 class="font-extrabold text-slate-800 text-sm mb-4"><i class="fas fa-share-alt text-blue-500 mr-1.5"></i> Unduh & Bagikan Portofolio</h3>
                <div class="flex flex-wrap gap-3">
                    @if($certificate->is_downloadable)
                        <a href="{{ route('certificates.download', $certificate->id) }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-blue-500/20" target="_blank">
                            <i class="fas fa-download"></i> Unduh PDF Resmi
                        </a>
                        <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition-all">
                            <i class="fas fa-print"></i> Cetak Halaman
                        </button>
                    @else
                        <span class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 text-slate-400 font-bold rounded-xl text-sm cursor-not-allowed">
                            <i class="fas fa-lock text-xs"></i> Unduh PDF (Menunggu Selesai)
                        </span>
                    @endif

                    @php
                        $shareText = urlencode('Saya baru saja menyelesaikan kegiatan relawan "' . $certificate->campaign_name . '" di Dongiv! Cek sertifikat verifikasi digital saya di sini:');
                        $shareUrl = urlencode($certificate->verify_url);
                    @endphp
                    <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-green-500/10">
                        <i class="fab fa-whatsapp text-base"></i> WhatsApp
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareText }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-3 bg-[#0a66c2] hover:bg-[#0956a3] text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-blue-600/10">
                        <i class="fab fa-linkedin text-base"></i> LinkedIn
                    </a>
                </div>
            </div>

            {{-- Verification Info --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
                <h3 class="font-extrabold text-slate-800 text-sm mb-4"><i class="fas fa-shield-alt text-green-500 mr-1.5"></i> Informasi Keaslian Sertifikat</h3>
                <div class="divide-y divide-slate-100 text-sm">
                    <div class="py-3 flex justify-between gap-4">
                        <span class="text-slate-400 font-medium">Nomor Sertifikat</span>
                        <span class="font-bold text-slate-800">{{ $certificate->certificate_number }}</span>
                    </div>
                    <div class="py-3 flex justify-between gap-4">
                        <span class="text-slate-400 font-medium">ID Sertifikat (UUID)</span>
                        <span class="font-mono text-xs text-slate-500 break-all select-all">{{ $certificate->certificate_id }}</span>
                    </div>
                    <div class="py-3 flex justify-between gap-4">
                        <span class="text-slate-400 font-medium">URL Verifikasi Publik</span>
                        <a href="{{ $certificate->verify_url }}" target="_blank" class="text-blue-600 hover:underline text-xs truncate break-all font-medium select-all">{{ $certificate->verify_url }}</a>
                    </div>
                    <div class="py-3 flex justify-between gap-4">
                        <span class="text-slate-400 font-medium">Instansi Penerbit</span>
                        <span class="font-bold text-slate-800">{{ $certificate->organizer_name }} & Dongiv Team</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app>
