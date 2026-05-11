@extends('validator.layouts.validator')

@section('title', 'Dashboard Validator')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Stats -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Verification Panel (Mode 1: Pending) -->
        @if(strtolower($campaign->status) === 'pending')
        <div class="bg-blue-600 rounded-[2.5rem] p-10 text-white shadow-xl shadow-blue-500/20 mb-8 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <h3 class="text-2xl font-black tracking-tight">Verifikasi Kelayakan Kampanye</h3>
                </div>
                <p class="text-blue-100 mb-8 leading-relaxed font-medium">Kampanye ini baru saja dibuat dan memerlukan persetujuan Anda sebelum bisa tampil di halaman publik dan menerima donasi.</p>
                
                <form action="{{ route('validator.verify', $token) }}" method="POST">
                    @csrf
                    <div class="flex items-start gap-4 mb-8">
                        <input type="checkbox" required class="mt-1 w-6 h-6 rounded-lg border-white/20 bg-white/10 text-white focus:ring-0">
                        <p class="text-xs text-blue-100 font-bold leading-relaxed italic">
                            "Saya menyatakan telah meninjau kampanye ini dan bertanggung jawab atas kevalidan data untuk dipublikasikan."
                        </p>
                    </div>
                    <button type="submit" class="w-full py-5 bg-white text-blue-600 hover:bg-blue-50 font-black uppercase tracking-widest rounded-2xl shadow-xl transition-all transform hover:-translate-y-1">
                        Setujui & Aktifkan Kampanye
                    </button>
                </form>
            </div>
            <!-- Decorative Icon -->
            <i class="fas fa-shield-halved absolute -bottom-10 -right-10 text-[12rem] text-white/5 rotate-12"></i>
        </div>
        @endif

        <!-- Campaign Card -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="w-full md:w-48 h-48 rounded-[2rem] overflow-hidden flex-shrink-0">
                    <img src="{{ $campaign->image }}" class="w-full h-full object-cover" alt="{{ $campaign->title }}">
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-black uppercase tracking-widest rounded-full">
                            {{ $campaign->kategori ?: 'Sosial' }}
                        </span>
                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full 
                            @if(strtolower($campaign->status) == 'verified') bg-green-100 text-green-600 @else bg-yellow-100 text-yellow-600 @endif">
                            {{ $campaign->status }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tighter mb-4">{{ $campaign->title }}</h1>
                    <p class="text-slate-500 text-sm line-clamp-2 mb-6">{{ Str::limit($campaign->description, 150) }}</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Terkumpul</p>
                            <p class="text-xl font-black text-blue-600">Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Disalurkan</p>
                            <p class="text-xl font-black text-green-600">Rp {{ number_format($totalPenyaluran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Semua Kampanye yang Dikelola -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Daftar Kampanye yang Anda Kelola</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-600 text-[10px] font-black uppercase rounded-full">
                    {{ count($activeTasks) + 1 }} Kampanye Aktif
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kartu Kampanye Saat Ini --}}
                <div class="bg-blue-600 rounded-[2rem] p-8 text-white shadow-lg shadow-blue-500/20 border-2 border-blue-400 relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2 py-0.5 bg-white/20 text-white text-[8px] font-black uppercase rounded-full backdrop-blur-sm">Sedang Dibuka</span>
                        </div>
                        <h4 class="text-xl font-black leading-tight mb-4">{{ $campaign->title }}</h4>
                        <div class="flex items-center gap-4 mt-auto pt-4 border-t border-white/10">
                            <a href="{{ route('validator.campaign', $token) }}" class="flex-1 text-center py-3 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                <i class="fas fa-info-circle mr-1"></i> Detail
                            </a>
                            <a href="{{ route('validator.upload', $token) }}" class="flex-1 text-center py-3 bg-white text-blue-600 hover:bg-blue-50 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg">
                                <i class="fas fa-plus-circle mr-1"></i> Lapor
                            </a>
                        </div>
                    </div>
                    <i class="fas fa-hand-holding-heart absolute -bottom-4 -right-4 text-7xl text-white/10 rotate-12 group-hover:scale-110 transition-transform"></i>
                </div>

                {{-- Kartu Kampanye Lainnya --}}
                @foreach($activeTasks as $task)
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-500/5 hover:border-blue-100 transition-all group relative overflow-hidden flex flex-col h-full">
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[8px] font-black uppercase rounded-full group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">Tugas Lainnya</span>
                        </div>
                        <h4 class="text-lg font-black text-slate-800 leading-tight mb-4 group-hover:text-blue-600 transition-colors">{{ $task->title }}</h4>
                        <div class="flex items-center gap-4 mt-auto pt-4 border-t border-slate-50">
                            <a href="{{ route('validator.dashboard', $task->distribution_token) }}" class="flex-1 text-center py-3 bg-slate-50 hover:bg-slate-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 transition-all">
                                <i class="fas fa-info-circle mr-1"></i> Detail
                            </a>
                            <a href="{{ route('validator.upload', $task->distribution_token) }}" class="flex-1 text-center py-3 bg-blue-50 hover:bg-blue-100 rounded-xl text-[10px] font-black uppercase tracking-widest text-blue-600 transition-all">
                                <i class="fas fa-plus-circle mr-1"></i> Lapor
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Riwayat Terbaru</h3>
                <a href="{{ route('validator.history', $token) }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-6">
                @forelse($reports as $report)
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-slate-800">{{ Str::limit($report->description, 50) }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $report->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-slate-800">Rp {{ number_format($report->amount, 0, ',', '.') }}</p>
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full {{ $report->status == 'verified' ? 'bg-green-100 text-green-600' : ($report->status == 'rejected' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600') }}">
                                {{ $report->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-folder-open text-2xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-400">Belum ada laporan penyaluran.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar Stats -->
    <div class="space-y-8">
        <!-- Quick Actions -->
        <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-500/20">
            <h3 class="text-lg font-black mb-6 tracking-tight">Aksi Cepat</h3>
            <div class="space-y-4">
                <a href="{{ route('validator.upload', $token) }}" class="flex items-center gap-4 p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all border border-white/10 group">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black">Upload Bukti</p>
                        <p class="text-[10px] text-white/60">Lapor penyaluran dana</p>
                    </div>
                </a>
                <a href="{{ route('validator.campaign', $token) }}" class="flex items-center gap-4 p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all border border-white/10 group">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black">Detail Kebutuhan</p>
                        <p class="text-[10px] text-white/60">Info kampanye lengkap</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
            <h3 class="text-lg font-black text-slate-800 mb-6 tracking-tight">Detail Penyaluran</h3>
            <div class="space-y-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                        <p class="text-sm font-black text-slate-800">{{ $campaign->lokasi ?: 'Tidak ditentukan' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Target Penerima</p>
                        <p class="text-sm font-black text-slate-800">{{ $campaign->jenis_penerima ?: 'Umum' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kontak</p>
                        <p class="text-sm font-black text-slate-800">{{ $campaign->whatsapp ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
