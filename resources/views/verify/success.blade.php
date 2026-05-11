<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Verifikasi - DonGiv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: #f8fafc; }
        .glass-card { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUp 0.6s ease-out forwards; }
    </style>
</head>
<body class="min-h-screen py-16 px-4">

    <div class="max-w-4xl mx-auto">
        <!-- Success Banner -->
        <div class="text-center mb-16 animate-slide-up">
            <div class="w-24 h-24 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6 border border-green-500/30 shadow-2xl shadow-green-500/10">
                <i class="fas fa-check-circle text-4xl text-green-400"></i>
            </div>
            <h1 class="text-4xl font-black tracking-tighter mb-4">Verifikasi Berhasil!</h1>
            <p class="text-slate-400 font-medium max-w-lg mx-auto">Terima kasih atas kontribusi Anda. Kampanye tersebut telah resmi aktif dan dapat menerima donasi dari publik secara transparan.</p>
            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ url('/') }}" class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl border border-white/10 transition-all">Kembali ke Beranda</a>
            </div>
        </div>

        <!-- Validator Identity -->
        <div class="glass-card rounded-3xl p-8 mb-12 flex flex-col md:flex-row items-center justify-between gap-6 border-l-4 border-l-blue-500 animate-slide-up" style="animation-delay: 0.1s">
            <div class="flex items-center gap-6 text-center md:text-left">
                <div class="w-16 h-16 rounded-2xl bg-blue-600 flex flex-shrink-0 items-center justify-center text-2xl font-black shadow-lg shadow-blue-500/20">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-black text-white leading-none mb-2">{{ $user->name }}</h2>
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Official Validator Badge</p>
                </div>
            </div>
            <div class="text-center md:text-right">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Kontribusi</p>
                <p class="text-3xl font-black text-white tracking-tighter">{{ $history->count() }} <span class="text-sm font-medium text-slate-500 uppercase">Kampanye</span></p>
            </div>
        </div>

        <!-- History Section -->
        <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.3em] mb-6 flex items-center gap-3 animate-slide-up" style="animation-delay: 0.2s">
            <i class="fas fa-history text-blue-500"></i>
            Riwayat Verifikasi Saya
        </h3>

        @if($history->isEmpty())
            <div class="glass-card rounded-3xl p-20 text-center border-dashed animate-slide-up" style="animation-delay: 0.3s">
                <i class="fas fa-folder-open text-5xl text-slate-700 mb-6"></i>
                <p class="text-slate-500 font-medium">Belum ada kampanye yang diverifikasi.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-slide-up" style="animation-delay: 0.3s">
                @foreach($history as $item)
                <div class="glass-card group hover:border-blue-500/50 transition-all duration-300 rounded-3xl p-6 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-colors"></div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/5 flex flex-shrink-0 items-center justify-center border border-white/5 group-hover:border-blue-500/20">
                            <i class="fas fa-hand-holding-heart text-blue-400"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-white line-clamp-1 mb-2 group-hover:text-blue-300 transition-colors">{{ $item->title }}</h4>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-500 bg-white/5 px-2 py-1 rounded-md">
                                    <i class="far fa-calendar-check mr-1"></i> {{ $item->verified_at ? $item->verified_at->format('d M Y') : '-' }}
                                </span>
                                <span class="text-[9px] font-black uppercase tracking-widest text-green-400 bg-green-500/10 px-2 py-1 rounded-md">
                                    <i class="fas fa-check-double mr-1"></i> Published
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        <div class="mt-20 pt-10 border-t border-white/5 text-center animate-slide-up" style="animation-delay: 0.4s">
            <p class="text-slate-600 text-[10px] font-black uppercase tracking-widest">
                &copy; {{ date('Y') }} DonGiv Integrated System &bull; Validator Portal
            </p>
        </div>
    </div>

</body>
</html>
