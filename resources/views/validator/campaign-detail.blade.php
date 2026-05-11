@extends('validator.layouts.validator')

@section('title', 'Detail Kampanye')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header Image -->
    <div class="relative h-96 rounded-[3rem] overflow-hidden shadow-2xl">
        <img src="{{ $campaign->image }}" class="w-full h-full object-cover" alt="{{ $campaign->title }}">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>
        <div class="absolute bottom-12 left-12 right-12">
            <span class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                {{ $campaign->kategori ?: 'Sosial' }}
            </span>
            <h1 class="text-4xl font-black text-white mt-4 tracking-tighter">{{ $campaign->title }}</h1>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Target</p>
            <p class="text-xl font-black text-slate-800">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Terkumpul</p>
            <p class="text-xl font-black text-blue-600">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status</p>
            <span class="px-3 py-1 bg-green-100 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-full">
                {{ $campaign->status }}
            </span>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deadline</p>
            <p class="text-lg font-black text-slate-800">{{ $campaign->end_date->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
        <h3 class="text-xl font-black text-slate-800 mb-6 tracking-tight flex items-center gap-3">
            <i class="fas fa-align-left text-blue-600"></i>
            Deskripsi Kampanye
        </h3>
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed italic">
            {!! nl2br(e($campaign->description)) !!}
        </div>
    </div>

    <!-- Need Details -->
    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
        <h3 class="text-xl font-black text-slate-800 mb-6 tracking-tight flex items-center gap-3">
            <i class="fas fa-list-check text-blue-600"></i>
            Kebutuhan Penyaluran
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Lokasi Distribusi</p>
                <p class="font-black text-slate-800">{{ $campaign->lokasi ?: 'Nasional / Belum ditentukan' }}</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Penerima Manfaat</p>
                <p class="font-black text-slate-800">{{ $campaign->jenis_penerima ?: 'Umum' }}</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Yayasan / Penanggung Jawab</p>
                <p class="font-black text-slate-800">{{ $campaign->yayasan ?: 'DonGiv Foundation' }}</p>
            </div>
            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">WhatsApp Official</p>
                <p class="font-black text-blue-600">{{ $campaign->whatsapp ?: '-' }}</p>
            </div>
        </div>
    </div>

    <div class="flex justify-center pt-8">
        <a href="{{ route('validator.upload', $token) }}" class="px-12 py-5 bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-500/20 transition-all transform hover:-translate-y-1">
            Mulai Lapor Penyaluran
        </a>
    </div>
</div>
@endsection
