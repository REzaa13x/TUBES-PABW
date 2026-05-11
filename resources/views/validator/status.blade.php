@extends('validator.layouts.validator')

@section('title', 'Status Verifikasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
        <h1 class="text-2xl font-black text-slate-800 tracking-tight mb-10">Status Laporan Penyaluran</h1>

        <div class="space-y-6">
            @forelse($reports as $report)
                <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100 transition-all hover:shadow-lg hover:shadow-slate-200/50">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Proof Image -->
                        <div class="w-full md:w-40 h-40 rounded-2xl overflow-hidden shadow-md flex-shrink-0">
                            <img src="{{ $report->proof_image }}" class="w-full h-full object-cover" alt="Bukti Penyaluran">
                        </div>

                        <!-- Details -->
                        <div class="flex-1 space-y-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $report->created_at->format('d M Y, H:i') }} WIB</p>
                                    <h3 class="text-xl font-black text-slate-800">Rp {{ number_format($report->amount, 0, ',', '.') }}</h3>
                                </div>
                                <span class="px-4 py-1.5 text-xs font-black uppercase tracking-widest rounded-full {{ $report->status == 'verified' ? 'bg-green-500 text-white shadow-lg shadow-green-500/20' : ($report->status == 'rejected' ? 'bg-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-yellow-400 text-slate-800 shadow-lg shadow-yellow-400/20') }}">
                                    {{ $report->status }}
                                </span>
                            </div>

                            <p class="text-sm text-slate-600 font-medium italic">"{{ $report->description }}"</p>

                            @if($report->admin_note)
                                <div class="mt-4 p-4 bg-white rounded-xl border border-slate-100 flex gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-comment-dots text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Catatan Admin</p>
                                        <p class="text-xs text-slate-500 font-bold leading-relaxed">{{ $report->admin_note }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <i class="fas fa-search text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-slate-400 font-bold">Belum ada laporan untuk dipantau statusnya.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
