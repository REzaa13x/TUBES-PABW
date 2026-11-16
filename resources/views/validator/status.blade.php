@extends('validator.layouts.validator')

@section('title', 'Hasil Laporan Penyaluran Kampanye')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    
    {{-- Back & Action Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('validator.campaign', $token) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-500 hover:text-blue-600 bg-white border border-slate-200/60 px-4 py-2.5 rounded-xl transition-all shadow-xs">
            <i class="fas fa-arrow-left"></i> Kembali ke Detail Kampanye
        </a>

        <a href="{{ route('validator.upload', $token) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Unggah Laporan Baru
        </a>
    </div>

    {{-- Main Container Card --}}
    <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-8 border-b border-slate-100">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 mb-2">
                    <i class="fas fa-check-double text-xs"></i> Laporan Resmi Lapangan
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">Hasil Laporan & Dokumentasi Penyaluran</h1>
                <p class="text-slate-400 text-xs sm:text-sm mt-1">Transparansi penuh penyaluran dana untuk kampanye: <strong class="text-slate-700">{{ $campaign->title }}</strong></p>
            </div>

            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Dana Disalurkan</p>
                <h3 class="text-xl font-black text-blue-600">Rp {{ number_format($totalPenyaluran ?? $reports->where('status', 'verified')->sum('amount'), 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Daftar Kartu Laporan --}}
        <div class="space-y-6 mt-8">
            @forelse($reports as $report)
                <div class="bg-slate-50/80 rounded-3xl p-6 sm:p-8 border border-slate-100 transition-all hover:shadow-xl hover:shadow-slate-200/50">
                    <div class="flex flex-col md:flex-row gap-6 sm:gap-8">
                        
                        <!-- Proof Image -->
                        <div class="w-full md:w-56 h-56 rounded-2xl overflow-hidden shadow-md flex-shrink-0 bg-slate-200 relative group">
                            <img src="{{ $report->proof_image }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Bukti Dokumentasi Penyaluran">
                            <a href="{{ $report->proof_image }}" target="_blank" class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-bold transition-opacity">
                                <i class="fas fa-search-plus mr-1"></i> Buka Foto Penuh
                            </a>
                        </div>

                        <!-- Details -->
                        <div class="flex-1 space-y-3.5 flex flex-col justify-between">
                            <div>
                                <div class="flex flex-wrap items-start justify-between gap-3 mb-2">
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $report->created_at->format('d M Y, H:i') }} WIB</p>
                                        <h3 class="text-2xl font-black text-slate-900 mt-0.5">Rp {{ number_format($report->amount, 0, ',', '.') }}</h3>
                                    </div>
                                    <span class="px-3.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-full shadow-xs
                                        {{ $report->status == 'verified' ? 'bg-emerald-600 text-white' : ($report->status == 'rejected' ? 'bg-rose-600 text-white' : 'bg-amber-500 text-white') }}">
                                        {{ $report->status == 'verified' ? '✓ Terverifikasi Resmi' : ucfirst($report->status) }}
                                    </span>
                                </div>

                                {{-- Penerima Manfaat --}}
                                @if($report->recipient)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 mb-2">
                                    <i class="fas fa-user-tag text-[10px]"></i>
                                    <span>Penerima: {{ $report->recipient }}</span>
                                </div>
                                @endif

                                {{-- Narasi Hasil Kegiatan --}}
                                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-xs text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
                                    "{{ $report->description }}"
                                </div>
                            </div>

                            @if($report->admin_note)
                                <div class="p-3.5 bg-blue-50/60 rounded-xl border border-blue-100 flex items-start gap-2.5">
                                    <div class="w-6 h-6 rounded-md bg-blue-600 text-white flex items-center justify-center flex-shrink-0 text-[10px] mt-0.5">
                                        <i class="fas fa-comment-dots"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-blue-700 uppercase tracking-widest">Catatan Tambahan</p>
                                        <p class="text-xs text-slate-600 font-semibold">{{ $report->admin_note }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="pt-2 text-right">
                                <a href="{{ $report->proof_image }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                    <i class="fas fa-external-link-alt text-[10px]"></i> Unduh / Lihat Dokumentasi Asli
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-300">
                        <i class="fas fa-clipboard-list text-3xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-700">Belum Ada Laporan Hasil Kampanye</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Laporan penyaluran yang dibuat admin atau diunggah validator akan langsung tercantum di sini.</p>
                    <a href="{{ route('validator.upload', $token) }}" class="mt-5 inline-flex items-center gap-1.5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Unggah Laporan Pertama</span>
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
