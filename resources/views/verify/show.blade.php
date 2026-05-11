<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Resmi - {{ $campaign->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: #f8fafc; }
        .glass-card { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="min-h-screen py-12 px-4 flex flex-col items-center">

    <div class="w-full max-w-3xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-3 bg-blue-500/10 text-blue-400 px-6 py-2 rounded-full border border-blue-500/20 mb-4 shadow-lg shadow-blue-500/5">
                <i class="fas fa-shield-halved animate-pulse"></i>
                <span class="text-xs font-black uppercase tracking-[0.2em]">Official Verification Portal</span>
            </div>
            <h1 class="text-4xl font-black tracking-tighter">Verifikasi Kampanye</h1>
        </div>

        @if(session('success'))
            <div class="glass-card bg-green-500/10 border-green-500/20 p-8 rounded-[2.5rem] mb-8 text-center animate-bounce">
                <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4 border border-green-500/30">
                    <i class="fas fa-check-double text-3xl text-green-400"></i>
                </div>
                <h3 class="text-xl font-black text-green-400 mb-2">Berhasil!</h3>
                <p class="text-green-100/70 font-medium">{{ session('success') }}</p>
                <a href="{{ url('/') }}" class="inline-block mt-6 px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition-all">Kembali ke Beranda</a>
            </div>
        @elseif(session('error'))
            <div class="glass-card bg-red-500/10 border-red-500/20 p-8 rounded-[2.5rem] mb-8 text-center">
                <i class="fas fa-exclamation-circle text-4xl text-red-500 mb-4"></i>
                <h3 class="text-xl font-black text-red-400 mb-2">Akses Ditolak</h3>
                <p class="text-red-100/70 font-medium">{{ session('error') }}</p>
            </div>
        @else
            <!-- Campaign Info -->
            <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl mb-8">
                <!-- Cover Image -->
                <div class="relative h-80">
                    <img src="{{ $campaign->image ? asset('storage/' . $campaign->image) : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1000' }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/20 to-transparent"></div>
                    <div class="absolute bottom-8 left-8 right-8">
                        <span class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                            {{ $campaign->kategori ?: 'Sosial' }}
                        </span>
                        <h2 class="text-3xl font-black mt-4 leading-tight tracking-tight">{{ $campaign->title }}</h2>
                    </div>
                </div>

                <div class="p-10">
                    <!-- Grid Stats -->
                    <div class="grid grid-cols-2 gap-6 mb-10">
                        <div class="bg-white/5 p-6 rounded-3xl border border-white/5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Target Donasi</p>
                            <p class="text-2xl font-black text-white">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/5 p-6 rounded-3xl border border-white/5">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Batas Waktu</p>
                            <p class="text-2xl font-black text-white">{{ \Carbon\Carbon::parse($campaign->end_date)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-10">
                        <h4 class="text-xs font-black text-blue-400 uppercase tracking-widest mb-4 border-b border-white/10 pb-4">Deskripsi Kampanye</h4>
                        <div class="text-slate-400 text-sm leading-relaxed italic">
                            {!! nl2br(e($campaign->description)) !!}
                        </div>
                    </div>

                    <!-- Verification Panel -->
                    <div class="mt-12 pt-10 border-t border-white/10">
                        @if(strtolower($user->role) === 'validator')
                            <div class="bg-blue-600/10 border border-blue-500/20 rounded-[2rem] p-8">
                                <div class="flex items-center gap-4 mb-8 pb-6 border-b border-white/5">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center font-black text-xl shadow-lg shadow-blue-500/20">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-blue-400 uppercase tracking-widest">Validator Aktif</p>
                                        <h5 class="text-xl font-black">{{ $user->name }}</h5>
                                    </div>
                                </div>

                                <form action="{{ route('verify.confirm', $campaign->verification_token) }}" method="POST">
                                    @csrf
                                    <div class="flex items-start gap-4 mb-8">
                                        <input type="checkbox" required class="mt-1 w-6 h-6 rounded-lg border-white/20 bg-white/5 text-blue-600 focus:ring-0">
                                        <p class="text-xs text-slate-400 font-medium leading-relaxed italic">
                                            "Saya menyatakan telah meninjau kelayakan kampanye ini secara seksama dan bertanggung jawab penuh atas kevalidan data yang diverifikasi untuk dipublikasikan."
                                        </p>
                                    </div>

                                    <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-500/20 transition-all transform hover:-translate-y-1">
                                        Setujui & Terbitkan Sekarang
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-red-500/10 border border-red-500/20 rounded-[2rem] p-8 text-center">
                                <i class="fas fa-lock text-3xl text-red-500 mb-4"></i>
                                <h5 class="text-lg font-black text-white mb-2 tracking-tight">Role Tidak Sesuai</h5>
                                <p class="text-sm text-red-200/60 mb-6 font-medium">Akun Anda saat ini adalah <b>Donatur</b>. Verifikasi hanya dapat diproses oleh akun <b>Validator</b>.</p>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-black uppercase tracking-widest text-white border-b-2 border-white/10 hover:border-red-500 transition-all">Ganti Akun</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <p class="text-center text-slate-600 text-[10px] font-bold uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} DonGiv Integrated Verification System
        </p>
    </div>

</body>
</html>
