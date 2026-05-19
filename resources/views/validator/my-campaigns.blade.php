@extends('validator.layouts.validator')

@section('title', 'Riwayat Kampanye Saya')

@section('content')
<div class="space-y-8">
    <!-- Portal Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-[2.5rem] p-10 text-white shadow-xl shadow-blue-500/10 relative overflow-hidden">
        <div class="relative z-10 space-y-4 max-w-2xl">
            <span class="px-4 py-1.5 bg-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-full backdrop-blur-md">
                Validator Portal
            </span>
            <h1 class="text-4xl font-black tracking-tight leading-tight">Selamat Datang di Portal Transparansi Anda</h1>
            <p class="text-blue-100 text-sm leading-relaxed font-medium">Di bawah ini adalah seluruh kampanye donasi lapangan yang ditugaskan kepada Anda. Anda dapat memeriksa rincian transaksi masuk (donasi) dan penarikan keluar (yayasan/validator) untuk menjamin transparansi 100%.</p>
        </div>
        <i class="fas fa-user-shield absolute -bottom-10 -right-6 text-[14rem] text-white/5 rotate-12"></i>
    </div>

    <!-- Active Tasks and History Container -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-folder-open text-blue-600"></i>
                Daftar Kampanye yang Anda Kelola
            </h3>
            <span class="px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-black uppercase rounded-full">
                {{ count($myCampaigns) }} Kampanye
            </span>
        </div>

        <!-- Campaign Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($myCampaigns as $task)
                @php
                    // Calculate progress percentage
                    $percentage = $task->target_amount > 0 ? min(round(($task->current_amount / $task->target_amount) * 100), 100) : 0;
                @endphp
                <div class="bg-white rounded-[2.5rem] p-6 shadow-xs border border-slate-100/80 hover:shadow-xl hover:shadow-blue-500/5 hover:border-blue-200 transition-all flex flex-col h-full group">
                    
                    <!-- Cover Image & Badge -->
                    <div class="relative h-48 w-full rounded-[2rem] overflow-hidden mb-6 shadow-sm">
                        <img src="{{ $task->image }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $task->title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                        
                        <span class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-xs text-blue-600 text-[8px] font-black uppercase tracking-wider rounded-full shadow-xs">
                            {{ $task->kategori ?: 'Sosial' }}
                        </span>
                        
                        <span class="absolute top-4 right-4 px-3 py-1 text-[8px] font-black uppercase tracking-wider rounded-full shadow-xs
                            @if(strtolower($task->status) === 'verified') bg-emerald-600 text-white @else bg-amber-500 text-white @endif">
                            {{ $task->status }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="flex-1 flex flex-col space-y-4">
                        <h4 class="text-lg font-black text-slate-800 leading-tight group-hover:text-blue-600 transition-colors line-clamp-2">
                            {{ $task->title }}
                        </h4>
                        
                        <!-- Financial Progress -->
                        <div class="space-y-2 pt-2">
                            <div class="flex justify-between text-[10px] font-bold">
                                <span class="text-slate-400">Terkumpul: <strong class="text-slate-800">Rp {{ number_format($task->current_amount, 0, ',', '.') }}</strong></span>
                                <span class="text-blue-600">{{ $percentage }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="flex justify-between text-[9px] text-slate-400 font-semibold">
                                <span>Target: Rp {{ number_format($task->target_amount, 0, ',', '.') }}</span>
                                <span>{{ $task->end_date ? $task->end_date->format('d M Y') : '-' }}</span>
                            </div>
                        </div>

                        <!-- Card Action -->
                        <div class="pt-4 border-t border-slate-50 mt-auto">
                            <a href="{{ route('validator.campaign', $task->distribution_token) }}" class="w-full py-4 bg-slate-50 hover:bg-blue-50 text-slate-600 hover:text-blue-600 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all inline-flex items-center justify-center gap-2 border border-slate-100 hover:border-blue-100">
                                <i class="fas fa-chart-line"></i> Lihat Detail & Transparansi
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 text-center py-16 bg-white rounded-[2.5rem] border border-dashed border-slate-200/80 shadow-xs">
                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-500">
                        <i class="fas fa-folder-open text-2xl"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-500">Tidak ada kampanye yang ditugaskan kepada nomor Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
