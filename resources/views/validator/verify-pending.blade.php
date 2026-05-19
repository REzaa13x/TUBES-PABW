@extends('validator.layouts.validator')

@section('title', 'Verifikasi Kampanye Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    
    <!-- Main Approval Callout -->
    <div class="bg-blue-600 rounded-[2.5rem] p-10 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">
                    <i class="fas fa-shield-check"></i>
                </div>
                <h3 class="text-2xl font-black tracking-tight">Verifikasi Kelayakan Kampanye</h3>
            </div>
            <p class="text-blue-100 mb-8 leading-relaxed font-medium">Kampanye di bawah ini baru saja didaftarkan dan memerlukan tinjauan serta persetujuan Anda sebelum dipublikasikan ke publik.</p>
            
            <form action="{{ route('validator.verify', $token) }}" method="POST">
                @csrf
                <div class="flex items-start gap-4 mb-8">
                    <input type="checkbox" id="declaration-check" required class="mt-1 w-6 h-6 rounded-lg border-white/20 bg-white/10 text-white focus:ring-0 cursor-pointer">
                    <label for="declaration-check" class="text-xs text-blue-100 font-bold leading-relaxed italic cursor-pointer select-none">
                        "Saya menyatakan telah meninjau kampanye ini secara langsung/lapangan dan bertanggung jawab penuh atas kebenaran serta kevalidan data yang dilaporkan untuk dipublikasikan."
                    </label>
                </div>
                <button type="submit" class="w-full py-5 bg-white text-blue-600 hover:bg-blue-50 font-black uppercase tracking-widest rounded-2xl shadow-xl transition-all transform hover:-translate-y-1">
                    Setujui & Aktifkan Kampanye
                </button>
            </form>
        </div>
        <i class="fas fa-shield-halved absolute -bottom-10 -right-10 text-[12rem] text-white/5 rotate-12"></i>
    </div>

    <!-- Campaign Details Preview Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-8">
        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="w-full md:w-56 h-56 rounded-[2rem] overflow-hidden flex-shrink-0 shadow-md">
                <img src="{{ $campaign->image }}" class="w-full h-full object-cover" alt="{{ $campaign->title }}">
            </div>
            <div class="flex-1 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-full">
                        {{ $campaign->kategori ?: 'Sosial' }}
                    </span>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-600 text-[10px] font-black uppercase tracking-widest rounded-full">
                        Menunggu Verifikasi
                    </span>
                </div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tighter leading-tight">{{ $campaign->title }}</h1>
                
                <div class="grid grid-cols-2 gap-6 pt-2">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Target Dana</p>
                        <p class="text-xl font-black text-slate-800">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Penanggung Jawab</p>
                        <p class="text-lg font-black text-slate-800 truncate">{{ $campaign->yayasan ?: 'DonGiv Foundation' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <!-- Deskripsi -->
        <div class="space-y-4">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-align-left text-blue-500"></i> Detail Deskripsi Kampanye
            </h4>
            <div class="text-slate-600 leading-relaxed text-sm bg-slate-50 p-6 rounded-2xl border border-slate-100">
                {!! nl2br(e($campaign->description)) !!}
            </div>
        </div>

        <!-- Rincian Distribusi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm flex-shrink-0">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Lokasi Penyaluran</p>
                    <p class="text-sm font-black text-slate-800">{{ $campaign->lokasi ?: 'Tidak ditentukan' }}</p>
                </div>
            </div>
            <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm flex-shrink-0">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div>
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Kontak HP WhatsApp</p>
                    <p class="text-sm font-black text-slate-800">{{ $campaign->whatsapp ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
