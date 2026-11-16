<x-app title="Sertifikat Saya - Dongiv">
    <div class="py-12 bg-transparent min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Breadcrumb & Title --}}
            <nav class="flex mb-8 text-sm text-slate-500" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="hover:text-primary transition-colors flex items-center gap-1.5 font-medium">
                            <i class="fas fa-home text-xs"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-[10px] mx-2 text-slate-400"></i>
                            <a href="{{ route('profiles.index') }}" class="hover:text-primary transition-colors font-medium">Profil</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-[10px] mx-2 text-slate-400"></i>
                            <span class="text-slate-400 font-medium">Sertifikat Saya</span>
                        </div>
                    </li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 text-sm">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3 text-sm">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                        🎓 Sertifikat Relawan
                    </h1>
                    <p class="text-slate-500 text-sm mt-1">Hargai kontribusi sosial Anda dengan sertifikat digital resmi yang dapat diverifikasi secara publik.</p>
                </div>
                
                {{-- Level Card --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl px-6 py-4 shadow-xl shadow-blue-500/20 flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-2xl shadow-inner backdrop-blur-md">
                        {{ $levelIcon }}
                    </div>
                    <div>
                        <div class="text-[10px] text-blue-100 uppercase tracking-widest font-extrabold">Tingkat Kontribusi</div>
                        <div class="font-extrabold text-lg">{{ $level }}</div>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {{-- Stat 1 --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-award"></i></div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sertifikat</p>
                        <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalCertificates }}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $totalActive }} aktif diunduh</p>
                    </div>
                </div>
                {{-- Stat 2 --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-hands-helping"></i></div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kampanye</p>
                        <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalCampaigns }}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Kampanye relawan</p>
                    </div>
                </div>
                {{-- Stat 3 --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-clock"></i></div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jam Berkontribusi</p>
                        <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ $totalHours }} Jam</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Akumulasi jam aktif</p>
                    </div>
                </div>
                {{-- Stat 4 --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl"><i class="fas fa-certificate"></i></div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Akun</p>
                        <h3 class="text-lg font-black text-slate-800 mt-0.5">Terverifikasi</h3>
                        <span class="inline-flex items-center gap-1 mt-0.5 px-2 py-0.5 bg-green-50 text-green-600 rounded text-[10px] font-bold border border-green-100"><i class="fas fa-check-circle text-[10px]"></i> Active</span>
                    </div>
                </div>
            </div>

            {{-- Main List --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Daftar Sertifikat Digital</h2>
                        <p class="text-xs text-slate-400">Gunakan QR Code pada sertifikat untuk validasi keaslian portofolio Anda.</p>
                    </div>
                </div>

                @if($certificates->isEmpty())
                    <div class="text-center py-20 px-4">
                        <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-6"><i class="fas fa-certificate"></i></div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada Sertifikat</h3>
                        <p class="text-slate-400 text-sm max-w-md mx-auto mb-8">Anda akan menerima sertifikat digital secara otomatis setelah bergabung dan menyelesaikan kampanye relawan yang disetujui admin.</p>
                        <a href="{{ route('volunteer.campaigns.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/20">
                            <i class="fas fa-search"></i> Cari Kegiatan Relawan
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100">
                                    <th class="py-4 px-8">No. Sertifikat</th>
                                    <th class="py-4 px-6">Nama Kampanye / Kegiatan</th>
                                    <th class="py-4 px-6">Kategori</th>
                                    <th class="py-4 px-6">Lokasi</th>
                                    <th class="py-4 px-6">Tanggal Rilis</th>
                                    <th class="py-4 px-6 text-center">Status</th>
                                    <th class="py-4 px-8 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($certificates as $cert)
                                    <tr class="hover:bg-slate-50/50 transition-all duration-200">
                                        <td class="py-5 px-8">
                                            <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">{{ $cert->certificate_number }}</span>
                                        </td>
                                        <td class="py-5 px-6">
                                            <div class="font-bold text-slate-800 text-sm line-clamp-1 max-w-[280px]" title="{{ $cert->campaign_name }}">{{ $cert->campaign_name }}</div>
                                            <div class="flex items-center gap-2 text-slate-400 text-[10px] mt-1 font-medium">
                                                <span class="flex items-center gap-1"><i class="fas fa-clock text-slate-300"></i> {{ $cert->total_volunteer_hours }} Jam Kerja</span>
                                                <span>·</span>
                                                <span class="flex items-center gap-1"><i class="fas fa-medal text-amber-400"></i> {{ $cert->volunteer_level }}</span>
                                            </div>
                                        </td>
                                        <td class="py-5 px-6">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">{{ $cert->category ?? 'Relawan' }}</span>
                                        </td>
                                        <td class="py-5 px-6 text-slate-500 text-xs font-medium">
                                            {{ $cert->location ?? '-' }}
                                        </td>
                                        <td class="py-5 px-6 text-slate-500 text-xs font-medium whitespace-nowrap">
                                            {{ optional($cert->issue_date)->format('d M Y') ?? '-' }}
                                        </td>
                                        <td class="py-5 px-6 text-center">
                                            @php
                                                $badgeColor = match($cert->status) {
                                                    'active'  => 'bg-green-50 text-green-600 border-green-100',
                                                    'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                                    'revoked' => 'bg-red-50 text-red-600 border-red-100',
                                                    default   => 'bg-slate-50 text-slate-600 border-slate-100',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeColor }} uppercase tracking-wider">{{ $cert->status_label }}</span>
                                        </td>
                                        <td class="py-5 px-8 text-right">
                                            <div class="flex gap-2 justify-end items-center">
                                                <a href="{{ route('certificates.show', $cert->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-bold transition-all" title="Buka Detail">
                                                    <i class="fas fa-eye text-[11px]"></i> Lihat
                                                </a>
                                                @if($cert->is_downloadable)
                                                    <a href="{{ route('certificates.download', $cert->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 hover:bg-green-100 text-green-600 rounded-lg text-xs font-bold transition-all" target="_blank" title="Unduh PDF">
                                                        <i class="fas fa-download text-[11px]"></i> Unduh PDF
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-xs font-bold cursor-not-allowed" title="Sertifikat dapat diunduh setelah kampanye selesai dilaksanakan.">
                                                        <i class="fas fa-lock text-[10px]"></i> PDF Locked
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app>
