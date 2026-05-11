@extends('validator.layouts.validator')

@section('title', 'Riwayat Penyaluran')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Penyaluran Dana</h1>
                <p class="text-slate-400 text-sm font-medium">Daftar semua laporan yang telah Anda kirimkan.</p>
            </div>
            <a href="{{ route('validator.upload', $token) }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Laporan Baru
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="text-left py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jumlah</th>
                        <th class="text-left py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan</th>
                        <th class="text-center py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="text-center py-4 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reports as $report)
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6 px-4">
                                <p class="text-sm font-black text-slate-800">{{ $report->created_at->format('d M Y') }}</p>
                                <p class="text-[10px] text-slate-400 font-bold">{{ $report->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="py-6 px-4">
                                <p class="text-sm font-black text-blue-600">Rp {{ number_format($report->amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-6 px-4">
                                <p class="text-sm text-slate-600 font-medium line-clamp-1">{{ $report->description }}</p>
                            </td>
                            <td class="py-6 px-4 text-center">
                                <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full {{ $report->status == 'verified' ? 'bg-green-100 text-green-600' : ($report->status == 'rejected' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="py-6 px-4 text-center">
                                <button class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-folder-open text-3xl text-slate-200"></i>
                                </div>
                                <p class="text-slate-400 font-bold">Belum ada riwayat penyaluran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
