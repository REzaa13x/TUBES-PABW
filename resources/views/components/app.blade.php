@props(['title' => 'DonasiKita - Platform Kebaikan'])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    {{-- CDN Tailwind & FontAwesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: '#2563EB', // Blue 600
                        secondary: '#3B82F6', // Blue 500
                        accent: '#F59E0B', // Amber 500
                        dark: '#0F172A', // Slate 900
                        softblue: '#EFF6FF', // Blue 50
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 15px rgba(37, 99, 235, 0.3)',
                    },
                    animation: {
                        'slide-up': 'slideUp 0.4s ease-out forwards',
                        'shake': 'shake 0.5s cubic-bezier(.36,.07,.19,.97) both',
                    },
                    keyframes: {
                        slideUp: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' },
                        },
                        shake: {
                            '10%, 90%': { transform: 'translate3d(-1px, 0, 0)' },
                            '20%, 80%': { transform: 'translate3d(2px, 0, 0)' },
                            '30%, 50%, 70%': { transform: 'translate3d(-4px, 0, 0)' },
                            '40%, 60%': { transform: 'translate3d(4px, 0, 0)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Staggered Animation Delays */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
</head>

<body class="bg-slate-50 text-slate-600 font-sans antialiased flex flex-col min-h-screen selection:bg-blue-100 selection:text-blue-900">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 w-full z-40 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-white/50" id="navbar">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2 transition transform hover:scale-105 active:scale-95">
                <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonasiKita" class="h-10">
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ url('/') }}#donasi" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-primary hover:bg-blue-50 rounded-full transition-all">Donasi</a>
                <a href="{{ route('volunteer.campaigns.index') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-primary hover:bg-blue-50 rounded-full transition-all">Relawan</a>
                <a href="{{ url('/') }}#cara-kerja" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-primary hover:bg-blue-50 rounded-full transition-all">Cara Kerja</a>

                <div class="h-6 w-px bg-slate-200 mx-2"></div>

                {{-- NOTIFICATION BELL SYSTEM (HANYA JIKA LOGIN) --}}
                @auth
                    <div class="relative mr-5">
                        <button id="notifButton" class="relative p-2 text-slate-600 hover:text-primary transition-colors focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            
                            {{-- Titik Merah (Hanya muncul jika ada notif belum dibaca) --}}
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span id="notifBadge" class="absolute top-1 right-1 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                            @endif
                        </button>

                        {{-- Dropdown Notifikasi --}}
                        <div id="notifDropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 overflow-hidden transform origin-top-right transition-all">
                            <div class="px-4 py-3 border-b border-slate-50 flex justify-between items-center">
                                <p class="text-sm font-bold text-slate-800">Notifikasi</p>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold">
                                        {{ auth()->user()->unreadNotifications->count() }} Baru
                                    </span>
                                @endif
                            </div>

                            <div class="max-h-[300px] overflow-y-auto no-scrollbar">
                                @forelse(auth()->user()->notifications as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/40' }}">
                                        <div class="flex gap-3">
                                            <div class="mt-1 flex-shrink-0">
                                                {{-- Icon Dinamis dari Database --}}
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }} {{ $notification->data['color'] ?? 'text-blue-500' }}"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-700 leading-tight">{{ $notification->data['title'] }}</p>
                                                <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">{{ $notification->data['message'] }}</p>
                                                <p class="text-[10px] text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center text-slate-400">
                                        <i class="fas fa-bell-slash text-2xl mb-2 opacity-50"></i>
                                        <p class="text-xs">Belum ada notifikasi</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            @if(auth()->user()->notifications->count() > 0)
                                <div class="border-t border-slate-50 p-2 text-center bg-slate-50/50">
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-primary hover:text-blue-700 transition-colors">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endauth

                @auth
                    {{-- Profile Dropdown --}}
                    <div class="relative ml-2">
                        <button id="profileButton" class="flex items-center gap-2 px-1 py-1 pr-3 rounded-full border border-slate-200 hover:border-blue-300 hover:bg-blue-50 transition-all focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md ring-2 ring-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-semibold text-slate-700 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                        {{-- Dropdown Content --}}
                        <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transform origin-top-right transition-all">
                            <div class="px-5 py-3 border-b border-slate-50 mb-2">
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold">Akun Masuk</p>
                                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                           {{-- PERBAIKAN DI SINI: href="#" diganti route('profiles.index') --}}
                            <a href="{{ route('profiles.index') }}" class="group flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors mx-2 rounded-xl">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-primary flex items-center justify-center mr-3 transition-colors">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                Profil & Riwayat
                            </a>
                            <div class="border-t border-slate-50 mt-2 pt-2 mx-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="group flex w-full items-center px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 group-hover:bg-red-100 flex items-center justify-center mr-3 transition-colors">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Guest Buttons --}}
                    <div class="flex items-center gap-3 ml-4">
                        <button onclick="openModal('login')" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-primary transition-colors cursor-pointer">
                            Masuk
                        </button>
                        <button onclick="openModal('register')" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-full shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                            Daftar Sekarang
                        </button>
                    </div>
                @endauth
            </div>
            
            {{-- Mobile Button --}}
            <button id="mobileMenuBtn" class="md:hidden p-2 text-slate-600 hover:text-primary transition-colors">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-slate-100 absolute w-full shadow-lg">
            <div class="flex flex-col p-4 space-y-3">
                <a href="{{ url('/') }}#donasi" class="px-4 py-3 rounded-xl hover:bg-blue-50 font-medium text-slate-700">Donasi</a>
                <a href="{{ route('volunteer.campaigns.index') }}" class="px-4 py-3 rounded-xl hover:bg-blue-50 font-medium text-slate-700">Relawan</a>
                @auth
                        <a href="{{ route('profiles.index') }}" class="group flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors mx-2 rounded-xl">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-primary flex items-center justify-center mr-3 transition-colors">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            Profil & Riwayat
                        </a>                    
                        <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-4 py-3 rounded-xl text-red-500 font-bold hover:bg-red-50">Keluar</button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <button onclick="openModal('login')" class="text-center py-3 rounded-xl border border-slate-200 font-bold text-slate-600">Masuk</button>
                        <button onclick="openModal('register')" class="text-center py-3 rounded-xl bg-primary text-white font-bold">Daftar</button>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow pt-20">
        {{ $slot }}
    </main>

    {{-- FOOTER PREMIUM --}}
    <footer class="bg-[#0B1120] text-gray-300 pt-20 pb-10 border-t border-slate-800/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                {{-- Brand Column --}}
                <div class="space-y-6">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        {{-- Filter brightness-0 invert membuat logo hitam menjadi putih --}}
                        <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonasiKita" class="h-9 brightness-0 invert opacity-90">
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Platform crowdfunding terpercaya yang menghubungkan kebaikan hati para donatur dengan ribuan cerita yang membutuhkan bantuan nyata.
                    </p>
                    <div class="flex gap-4 pt-2">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:-translate-y-1 transition-all duration-300 ring-1 ring-slate-700 hover:ring-primary"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:-translate-y-1 transition-all duration-300 ring-1 ring-slate-700 hover:ring-primary"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:-translate-y-1 transition-all duration-300 ring-1 ring-slate-700 hover:ring-primary"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                {{-- Links 1 --}}
                <div>
                    <h3 class="font-bold text-white text-lg mb-6">Jelajahi</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="#donasi" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Donasi Sekarang</a></li>
                        <li><a href="{{ route('volunteer.campaigns.index') }}" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Jadi Relawan</a></li>
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Kisah Sukses</a></li>
                    </ul>
                </div>

                {{-- Links 2 --}}
                <div>
                    <h3 class="font-bold text-white text-lg mb-6">Dukungan</h3>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-primary hover:pl-2 transition-all duration-300 flex items-center gap-2"><i class="fas fa-chevron-right text-xs opacity-50"></i> Kebijakan Privasi</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700/50">
                    <h3 class="font-bold text-white mb-2">Kabar Kebaikan</h3>
                    <p class="text-xs text-slate-400 mb-4">Dapatkan update penyaluran donasi setiap minggunya.</p>
                    <form action="#" class="space-y-2">
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-3 text-slate-500"></i>
                            <input type="email" placeholder="Alamat email..." class="w-full pl-9 pr-4 py-2.5 rounded-xl text-sm bg-slate-900 border border-slate-700 text-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder:text-slate-600">
                        </div>
                        <button class="w-full py-2.5 bg-primary hover:bg-blue-600 text-white text-sm font-bold rounded-xl transition-colors shadow-lg shadow-blue-900/20">Berlangganan</button>
                    </form>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-slate-500">&copy; {{ date('Y') }} DonasiKita. Hak Cipta Dilindungi.</p>
                <div class="flex gap-6 text-sm text-slate-500 font-medium">
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition-colors">Privasi</a>
                </div>
            </div>
        </div>
    </footer>
    
    {{-- Backdrop Shared --}}
    <div id="authBackdrop" class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-md hidden opacity-0 transition-opacity duration-300"></div>

    {{-- 1. LOGIN MODAL --}}
    <div id="loginModal" class="fixed inset-0 z-[101] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div id="loginPanel" class="relative w-full max-w-md transform rounded-3xl bg-white p-8 text-left shadow-2xl shadow-blue-900/20 transition-all opacity-0 scale-95 border border-white/50 ring-1 ring-slate-900/5">
                
                {{-- Decorative Blob Background --}}
                <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                <button onclick="closeAllModals()" class="absolute top-5 right-5 text-slate-300 hover:text-slate-500 transition-colors z-10">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div class="text-center mb-8 relative z-10 animate-slide-up">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 text-primary mb-4 shadow-inner ring-1 ring-blue-100">
                        <i class="fas fa-sign-in-alt text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">Selamat Datang Kembali</h3>
                    <p class="text-slate-500 text-sm mt-2">Masuk untuk melanjutkan kebaikan Anda.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5 relative z-10 animate-slide-up delay-100">
                    @csrf
                    
                    {{-- Email Input with Icon --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium @error('email') border-red-300 bg-red-50 focus:ring-red-100 animate-shake @enderror"
                                placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1.5 ml-1 flex items-center gap-1 font-bold"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password Input with Icon --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" name="password" required
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-primary focus:ring-4 focus:ring-blue-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium @error('password') border-red-300 bg-red-50 animate-shake @enderror"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary transition-colors">
                            <span class="ml-2 text-sm text-slate-600 font-medium group-hover:text-primary transition-colors">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-slate-500 hover:text-primary font-bold transition-colors">Lupa Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 flex items-center justify-center gap-2">
                        <span>Masuk Sekarang</span>
                        <i class="fas fa-arrow-right text-sm opacity-70"></i>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center animate-slide-up delay-200">
                    <p class="text-sm text-slate-600">
                        Belum memiliki akun? 
                        <button onclick="switchModal('login', 'register')" class="text-primary font-bold hover:text-indigo-600 hover:underline focus:outline-none transition-colors">
                            Daftar disini
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. REGISTER MODAL --}}
    <div id="registerModal" class="fixed inset-0 z-[101] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4">
            <div id="registerPanel" class="relative w-full max-w-md transform rounded-3xl bg-white p-8 text-left shadow-2xl shadow-indigo-900/20 transition-all opacity-0 scale-95 border border-white/50 ring-1 ring-slate-900/5">
                
                <div class="absolute top-0 left-0 -mt-8 -ml-8 w-32 h-32 bg-indigo-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                <button onclick="closeAllModals()" class="absolute top-5 right-5 text-slate-300 hover:text-slate-500 transition-colors z-10">
                    <i class="fas fa-times text-xl"></i>
                </button>

                <div class="text-center mb-8 relative z-10 animate-slide-up">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 text-indigo-600 mb-4 shadow-inner ring-1 ring-indigo-100">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">Buat Akun Baru</h3>
                    <p class="text-slate-500 text-sm mt-2">Bergabunglah dengan komunitas kebaikan kami.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4 relative z-10 animate-slide-up delay-100">
                    @csrf
                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium @error('name') border-red-300 bg-red-50 animate-shake @enderror"
                                placeholder="Nama Lengkap">
                        </div>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1.5 ml-1 font-bold">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium @error('email') border-red-300 bg-red-50 animate-shake @enderror"
                                placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1.5 ml-1 font-bold">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" name="password" required
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium @error('password') border-red-300 bg-red-50 animate-shake @enderror"
                                placeholder="Min. 8 karakter">
                        </div>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1.5 ml-1 font-bold">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-2 ml-1">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-lock-open"></i>
                            </div>
                            <input type="password" name="password_confirmation" required
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 placeholder:text-slate-400 font-medium"
                                placeholder="Ulangi password">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transform hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 mt-2">
                        Daftar Akun
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center animate-slide-up delay-200">
                    <p class="text-sm text-slate-600">
                        Sudah punya akun? 
                        <button onclick="switchModal('register', 'login')" class="text-indigo-600 font-bold hover:text-purple-600 hover:underline focus:outline-none transition-colors">
                            Masuk disini
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>

{{-- 1. CDN SweetAlert2 (WAJIB ADA UNTUK POPUP) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 2. MAIN SCRIPT --}}
    <script>
        // --- A. HELPER FUNCTIONS & MODAL LOGIC (Global Scope) ---
        
        function getAuthElements() {
            return {
                backdrop: document.getElementById('authBackdrop'),
                loginModal: document.getElementById('loginModal'),
                loginPanel: document.getElementById('loginPanel'),
                registerModal: document.getElementById('registerModal'),
                registerPanel: document.getElementById('registerPanel')
            };
        }

        function showElement(el) { if(el) el.classList.remove('hidden'); }
        function hideElement(el) { if(el) el.classList.add('hidden'); }
        
        // Versi Debug Tanpa Animasi
        function animateIn(backdrop, panel) {
            if(backdrop) backdrop.classList.remove('hidden');
            if(panel) panel.classList.remove('hidden');
            // Hapus kelas animasi sementara
            if(backdrop) backdrop.classList.remove('opacity-0'); 
            if(panel) {
                panel.classList.remove('opacity-0', 'scale-95');
                panel.classList.add('opacity-100', 'scale-100');
            }
        }

        function animateOut(backdrop, panel, callback) {
            if(backdrop) backdrop.classList.add('hidden');
            if(panel) panel.classList.add('hidden');
            if(callback) callback();
        }

        // Fungsi Global untuk dipanggil via HTML onclick="..."
        window.openModal = function(type) {
            const els = getAuthElements();
            showElement(els.backdrop);
            
            if (type === 'login') {
                showElement(els.loginModal);
                animateIn(els.backdrop, els.loginPanel);
            } else if (type === 'register') {
                showElement(els.registerModal);
                animateIn(els.backdrop, els.registerPanel);
            }
        };

        window.closeAllModals = function() {
            const els = getAuthElements();
            
            if (els.loginModal && !els.loginModal.classList.contains('hidden')) {
                animateOut(els.backdrop, els.loginPanel, () => {
                    hideElement(els.loginModal);
                    hideElement(els.backdrop);
                });
            } else if (els.registerModal && !els.registerModal.classList.contains('hidden')) {
                animateOut(els.backdrop, els.registerPanel, () => {
                    hideElement(els.registerModal);
                    hideElement(els.backdrop);
                });
            }
        };

        window.switchModal = function(from, to) {
            const els = getAuthElements();

            if (from === 'login') {
                hideElement(els.loginModal);
                if(els.loginPanel) {
                    els.loginPanel.classList.add('opacity-0', 'scale-95');
                    els.loginPanel.classList.remove('opacity-100', 'scale-100');
                }
                
                showElement(els.registerModal);
                setTimeout(() => {
                    if(els.registerPanel) {
                        els.registerPanel.classList.remove('opacity-0', 'scale-95');
                        els.registerPanel.classList.add('opacity-100', 'scale-100');
                    }
                }, 50);
            } else {
                hideElement(els.registerModal);
                if(els.registerPanel) {
                    els.registerPanel.classList.add('opacity-0', 'scale-95');
                    els.registerPanel.classList.remove('opacity-100', 'scale-100');
                }

                showElement(els.loginModal);
                setTimeout(() => {
                    if(els.loginPanel) {
                        els.loginPanel.classList.remove('opacity-0', 'scale-95');
                        els.loginPanel.classList.add('opacity-100', 'scale-100');
                    }
                }, 50);
            }
        };

        // --- B. EVENT LISTENERS (Dijalankan setelah halaman siap) ---
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Setup Modal Backdrop Click (Tutup modal saat klik background gelap)
            const backdrop = document.getElementById('authBackdrop');
            if (backdrop) {
                backdrop.addEventListener('click', window.closeAllModals);
            }

            // 2. Auto Open Modal on Error (Jika ada error validasi Laravel)
            @if ($errors->any())
                @if(old('name')) 
                    window.openModal('register');
                @else
                    window.openModal('login');
                @endif
            @endif

            // 3. Notification Dropdown Logic
            const notifBtn = document.getElementById('notifButton');
            const notifMenu = document.getElementById('notifDropdown');
            const notifBadge = document.getElementById('notifBadge');

            if (notifBtn && notifMenu) {
                notifBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    notifMenu.classList.toggle('hidden');
                    
                    // UX: Sembunyikan titik merah saat dibuka
                    if (!notifMenu.classList.contains('hidden') && notifBadge) {
                        notifBadge.style.display = 'none';
                    }
                });
            }

            // 4. Profile Dropdown Logic
            const profileBtn = document.getElementById('profileButton');
            const profileMenu = document.getElementById('profileDropdown');
            
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                });
            }

            // 5. Global Click Handler (Tutup dropdown saat klik di luar area)
            document.addEventListener('click', (e) => {
                if (notifBtn && notifMenu && !notifBtn.contains(e.target) && !notifMenu.contains(e.target)) {
                    notifMenu.classList.add('hidden');
                }
                if (profileBtn && profileMenu && !profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                }
            });

            // 6. Mobile Menu Logic
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // 7. Navbar Scroll Effect
            const navbar = document.getElementById('navbar');
            if (navbar) {
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 10) {
                        navbar.classList.add('shadow-soft');
                    } else {
                        navbar.classList.remove('shadow-soft');
                    }
                });
            }

            // --- C. SWEETALERT POPUP LOGIC ---
            
            // Popup Sukses
            @if(session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{!! session('success') !!}",
                    imageUrl: 'https://cdn-icons-png.flaticon.com/512/148/148767.png', // Icon Checklist
                    imageWidth: 100,
                    imageHeight: 100,
                    imageAlt: 'Success Icon',
                    showConfirmButton: true,
                    confirmButtonText: 'Oke, Mengerti',
                    confirmButtonColor: '#4f46e5',
                    allowOutsideClick: false,
                    backdrop: `rgba(0,0,123,0.4)`,
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl',
                        title: 'text-2xl font-bold text-slate-800',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
                    }
                });
            @endif

            // Popup Error
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Mohon Maaf',
                    text: "{!! session('error') !!}",
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#ef4444',
                    customClass: {
                        popup: 'rounded-3xl shadow-xl',
                        confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
                    }
                });
            @endif

            // Popup Info
            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: "{!! session('info') !!}",
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'rounded-3xl' }
                });
            @endif
        });
    </script>
</body>
</html>