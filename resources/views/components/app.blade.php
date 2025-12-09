<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'DonasiKita - Platform Kebaikan' }}</title>

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
                        dark: '#0F172A', // Slate 900 (Warna Footer Baru)
                        softblue: '#EFF6FF', // Blue 50
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body
    class="bg-white text-slate-600 font-sans antialiased flex flex-col min-h-screen selection:bg-blue-100 selection:text-blue-900">

    {{-- NAVBAR --}}
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-white/50"
        id="navbar">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            {{-- Logo Area (Hanya Gambar) --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 transition transform hover:scale-105">
                <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonasiKita" class="h-10">
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ url('/') }}#donasi"
                    class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-primary hover:bg-blue-50 rounded-full transition-all">Donasi</a>
                <a href="{{ route('volunteer.index') }}"
                    class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-primary hover:bg-blue-50 rounded-full transition-all">Relawan</a>
                <a href="{{ url('/') }}#cara-kerja"
                    class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-primary hover:bg-blue-50 rounded-full transition-all">Cara
                    Kerja</a>

                <div class="h-6 w-px bg-slate-200 mx-2"></div>

                @auth
                    {{-- Profile Dropdown --}}
                    <div class="relative ml-2">
                        <button id="profileButton"
                            class="flex items-center gap-2 px-1 py-1 pr-3 rounded-full border border-slate-200 hover:border-blue-300 hover:bg-blue-50 transition-all focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span
                                class="text-sm font-semibold text-slate-700 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>

                        <div id="profileDropdown"
                            class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transform origin-top-right">
                            <div class="px-4 py-3 border-b border-slate-50 mb-2">
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold">Akun Saya</p>
                                <p class="text-sm font-medium text-slate-800 truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <a href="{{ route('profiles.index') }}"
                                class="flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors">
                                <i class="fas fa-user-circle w-5 mr-2 text-slate-400"></i> Profil & Riwayat
                            </a>

                            <div class="border-t border-slate-50 mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt w-5 mr-2"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 ml-4">
                        <a href="{{ route('login') }}"
                            class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-primary transition-colors">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="px-6 py-2.5 text-sm font-bold text-white bg-primary hover:bg-blue-700 rounded-full shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-0.5">Daftar
                            Sekarang</a>
                    </div>
                @endauth
            </div>

            <button id="mobileMenuBtn" class="md:hidden p-2 text-slate-600 hover:text-primary focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-slate-100 absolute w-full shadow-lg">
            <div class="flex flex-col p-4 space-y-3">
                <a href="{{ url('/') }}#donasi"
                    class="px-4 py-3 rounded-xl hover:bg-blue-50 font-medium text-slate-700">Donasi</a>
                <a href="{{ route('volunteer.index') }}"
                    class="px-4 py-3 rounded-xl hover:bg-blue-50 font-medium text-slate-700">Relawan</a>
                @auth
                    <a href="{{ route('profiles.index') }}"
                        class="px-4 py-3 rounded-xl bg-blue-50 font-bold text-primary">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="w-full text-left px-4 py-3 rounded-xl text-red-500 font-bold hover:bg-red-50">Keluar</button>
                    </form>
                @else
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <a href="{{ route('login') }}"
                            class="text-center py-3 rounded-xl border border-slate-200 font-bold text-slate-600">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="text-center py-3 rounded-xl bg-primary text-white font-bold">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow pt-20">
        {{ $slot }}
    </main>

    {{-- FOOTER (KEMBALI KE WARNA GELAP/BIRU TUA) --}}
    <footer class="bg-dark text-gray-300 pt-16 pb-8 mt-auto border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                <div class="space-y-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonasiKita"
                            class="h-8 brightness-0 invert opacity-90">
                        {{-- Text Logo dihapus di sini juga, atau jika mau putih: --}}
                        {{-- <span class="font-bold text-xl text-white">DonasiKita</span> --}}
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Jembatan kebaikan digital yang menghubungkan ribuan #OrangBaik dengan mereka yang membutuhkan,
                        secara transparan dan amanah.
                    </p>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all duration-300">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-white mb-6">Jelajahi</h3>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="#donasi"
                                class="hover:text-primary transition-colors flex items-center gap-2">Donasi Sekarang</a>
                        </li>
                        <li><a href="{{ route('volunteer.index') }}"
                                class="hover:text-primary transition-colors flex items-center gap-2">Jadi Relawan</a>
                        </li>
                        <li><a href="#"
                                class="hover:text-primary transition-colors flex items-center gap-2">Galang Dana</a>
                        </li>
                        <li><a href="#"
                                class="hover:text-primary transition-colors flex items-center gap-2">Cerita Sukses</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-white mb-6">Dukungan</h3>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-primary transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Pusat Bantuan (FAQ)</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-white mb-6">Tetap Terhubung</h3>
                    <div class="bg-gray-800/50 rounded-2xl p-6 border border-gray-800">
                        <p class="text-sm text-gray-400 mb-4 font-medium">Dapatkan kabar terbaru via email.</p>
                        <form action="#" class="space-y-2">
                            <input type="email" placeholder="Email kamu..."
                                class="w-full px-4 py-2 rounded-lg text-sm bg-gray-900 border border-gray-700 text-white focus:ring-2 focus:ring-primary focus:border-transparent">
                            <button
                                class="w-full py-2 bg-primary hover:bg-blue-600 text-white text-sm font-bold rounded-lg transition-colors">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500 font-medium">
                    &copy; {{ date('Y') }} DonasiKita Foundation. All rights reserved.
                </p>
                <div class="flex gap-6 text-sm text-gray-500 font-medium">
                    <span class="flex items-center gap-2"><i class="fas fa-lock text-xs"></i> Pembayaran Aman</span>
                    <span class="flex items-center gap-2"><i class="fas fa-shield-alt text-xs"></i>
                        Terverifikasi</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile Dropdown
            const profileBtn = document.getElementById('profileButton');
            const profileMenu = document.getElementById('profileDropdown');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', (e) => {
                    if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                        profileMenu.classList.add('hidden');
                    }
                });
            }

            // Mobile Menu
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');

            if (mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Navbar Scroll Effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 10) {
                    navbar.classList.add('shadow-soft');
                } else {
                    navbar.classList.remove('shadow-soft');
                }
            });
        });
    </script>
</body>

</html>
