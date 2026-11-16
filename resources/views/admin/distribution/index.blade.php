@extends('admin.layouts.master')

@section('title', 'Laporan Penyaluran Dana')

@section('content')
<div class="container px-6 mx-auto grid pb-16">
    
    {{-- HEADER --}}
    <div class="mb-8 mt-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200 flex-shrink-0">
                    <i class="fas fa-clipboard-check text-lg"></i>
                </div>
                <span>Laporan Penyaluran Dana Kampanye</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 ml-14">Kelola dokumentasi penyaluran dana yang dipantau oleh validator lapangan secara transparan.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.distribution.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-200 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                <i class="fas fa-plus"></i>
                <span>Buat Laporan Penyaluran</span>
            </a>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <i class="fas fa-check text-xs"></i>
            </div>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 p-1">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
    @endif

    {{-- STATS SUMMARY --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Laporan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $stats['total_reports'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dana Disalurkan</p>
                <h3 class="text-lg font-black text-emerald-600 mt-0.5">Rp {{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terverifikasi</p>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $stats['verified_reports'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Tinjau</p>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $stats['pending_reports'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    {{-- FILTER & SEARCH BAR --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.distribution.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            {{-- Search --}}
            <div class="relative flex-1 min-w-[200px]">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari penerima, judul kampanye, atau uraian..." 
                       class="w-full pl-9 pr-4 py-2 text-xs font-medium rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:border-blue-500 transition-all">
            </div>

            {{-- Filter Status --}}
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs font-medium rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none">
                <option value="">Semua Status</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                Filter
            </button>

            @if(request()->hasAny(['search', 'status', 'campaign_id']))
                <a href="{{ route('admin.distribution.index') }}" class="px-3 py-2 text-xs font-bold text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            @endif
        </form>
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="w-full overflow-hidden rounded-2xl shadow-sm border border-slate-100 bg-white">
        <div class="w-full overflow-x-auto">
            <table class="w-full divide-y divide-gray-100">
                <thead>
                    <tr class="text-xs font-bold tracking-wider text-left text-gray-400 uppercase bg-slate-50/60">
                        <th class="px-6 py-4">Kampanye & Penerima</th>
                        <th class="px-6 py-4 text-right">Dana Disalurkan</th>
                        <th class="px-6 py-4 text-center">Foto Bukti</th>
                        <th class="px-6 py-4">Waktu Laporan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi & Validator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white text-slate-700">
                    @forelse($reports as $report)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        
                        {{-- Kampanye & Penerima --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-11 w-11 flex-shrink-0 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 shadow-sm">
                                    <img class="h-full w-full object-cover" 
                                         src="{{ $report->campaign->image ?: asset('images/placeholder.jpg') }}" 
                                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';" alt="">
                                </div>
                                <div class="ml-3.5 max-w-sm">
                                    <a href="{{ route('admin.campaigns.show', $report->campaign_id) }}" class="text-xs font-bold text-slate-900 hover:text-blue-600 line-clamp-1 transition-colors">
                                        {{ $report->campaign->title }}
                                    </a>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700">
                                            <i class="fas fa-user-tag text-[9px]"></i> {{ $report->recipient ?: 'Penerima Manfaat' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-1 italic">
                                        "{{ $report->description }}"
                                    </p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Jumlah Disalurkan --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-black text-emerald-600">
                                Rp {{ number_format($report->amount, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Bukti Foto Preview --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($report->proof_image)
                                <a href="{{ $report->proof_image }}" target="_blank" class="inline-block relative group" title="Klik untuk lihat foto penuh">
                                    <img src="{{ $report->proof_image }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 group-hover:scale-105 transition-transform shadow-xs">
                                    <div class="absolute inset-0 bg-blue-600/30 rounded-xl opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs transition-opacity">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </a>
                            @else
                                <span class="text-slate-300 text-xs italic">-</span>
                            @endif
                        </td>
                        
                        {{-- Tanggal Laporan --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-xs font-bold text-slate-800 block">{{ $report->created_at->format('d M Y') }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $report->created_at->diffForHumans() }}</span>
                        </td>
                        
                        {{-- Status Badge --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold border
                                @if($report->status == 'verified') text-emerald-700 bg-emerald-50 border-emerald-200
                                @elseif($report->status == 'rejected') text-rose-700 bg-rose-50 border-rose-200
                                @else text-amber-700 bg-amber-50 border-amber-200 @endif">
                                {{ $report->status == 'verified' ? '✓ Terverifikasi' : ucfirst($report->status) }}
                            </span>
                        </td>
                        
                        {{-- Aksi & Validator Link --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Tombol Periksa Admin --}}
                                <a href="{{ route('admin.distribution.show', $report->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-xs transition-all">
                                    <i class="fas fa-eye text-[10px]"></i>
                                    <span>Periksa</span>
                                </a>

                                {{-- Tombol Cek di Portal Validator --}}
                                @if($report->campaign->distribution_token)
                                    <a href="{{ route('validator.campaign', $report->campaign->distribution_token) }}" 
                                       target="_blank"
                                       title="Lihat bagaimana hasil laporan ini tampil di portal validator"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200/60 rounded-lg text-xs font-bold transition-all">
                                        <i class="fas fa-shield-alt text-[10px]"></i>
                                        <span>Validator</span>
                                    </a>
                                @endif

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.distribution.destroy', $report->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan penyaluran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors" title="Hapus laporan">
                                        <i class="far fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-3 text-blue-500">
                                    <i class="fas fa-clipboard-list text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800">Belum Ada Laporan Penyaluran</h3>
                                <p class="text-xs text-slate-400 mt-1 max-w-sm">Mulai buat laporan penyaluran dana kampanye agar validator dapat memantau dokumentasi hasil program.</p>
                                <a href="{{ route('admin.distribution.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                    <i class="fas fa-plus"></i>
                                    <span>Buat Laporan Sekarang</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 flex justify-center">
            {{ $reports->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
