@extends('admin.layouts.master')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 italic border-b-2 border-blue-600 inline-block pb-2">
        Detail Laporan Penyaluran
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Info Laporan -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-black text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-600"></i>
                Informasi Penyaluran
            </h3>
            
            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kampanye</p>
                    <p class="text-sm font-black text-gray-800">{{ $report->campaign->title }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jumlah Disalurkan</p>
                    <p class="text-xl font-black text-blue-600">Rp {{ number_format($report->amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keterangan</p>
                    <p class="text-sm text-gray-600 leading-relaxed italic">"{{ $report->description }}"</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Waktu Laporan</p>
                    <p class="text-sm font-bold text-gray-800">{{ $report->created_at->format('d M Y, H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Saat Ini</p>
                    <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full {{ $report->status == 'verified' ? 'bg-green-100 text-green-600' : ($report->status == 'rejected' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                        {{ $report->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Bukti Foto -->
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-black text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-camera text-blue-600"></i>
                Bukti Dokumentasi
            </h3>
            <div class="rounded-2xl overflow-hidden border border-gray-200">
                <img src="{{ $report->proof_image }}" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-500" alt="Bukti Penyaluran">
            </div>
            <a href="{{ $report->proof_image }}" target="_blank" class="block text-center mt-4 text-blue-600 text-xs font-bold hover:underline">
                <i class="fas fa-external-link-alt mr-1"></i> Lihat Gambar Penuh
            </a>
        </div>
    </div>

    <!-- Form Verifikasi -->
    <div class="mt-8 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-black text-gray-800 mb-6">Tindakan Verifikasi</h3>
        <form action="{{ route('admin.distribution.update', $report->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Admin (Opsional)</label>
                <textarea name="admin_note" rows="3" class="w-full px-4 py-3 rounded-xl border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-0 text-sm" placeholder="Contoh: Bukti foto sudah sesuai, terima kasih.">{{ $report->admin_note }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" name="status" value="verified" class="flex-1 py-4 bg-green-600 hover:bg-green-700 text-white font-black uppercase tracking-widest rounded-xl shadow-lg shadow-green-500/20 transition-all">
                    <i class="fas fa-check-circle mr-2"></i> Setujui Laporan
                </button>
                <button type="submit" name="status" value="rejected" class="flex-1 py-4 bg-red-600 hover:bg-red-700 text-white font-black uppercase tracking-widest rounded-xl shadow-lg shadow-red-500/20 transition-all">
                    <i class="fas fa-times-circle mr-2"></i> Tolak Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
