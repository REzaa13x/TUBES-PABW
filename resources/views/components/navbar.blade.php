{{-- ====================================================================
     DONGIV UNIFIED NAVBAR COMPONENT
     Tertata rapi, konsisten, responsif, & mendukung server/API auth
==================================================================== --}}
<nav class="fixed top-0 w-full z-40 transition-all duration-300 bg-white/85 backdrop-blur-md border-b border-slate-100/90" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        {{-- 1. Logo Brand --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 transition transform hover:scale-105 active:scale-95 group focus:outline-none" title="DonGiv - Beranda">
            <img src="{{ asset('images/dongiv-logo.png') }}" alt="DonGiv" class="h-9 md:h-10 w-auto object-contain">
        </a>

        {{-- 2. Desktop Navigation Menu --}}
        <div class="hidden md:flex items-center gap-1.5 lg:gap-2">
            {{-- Nav Links --}}
            <a href="{{ url('/') }}"
               class="px-3.5 py-2 text-sm font-semibold rounded-full transition-all duration-200 {{ request()->is('/') ? 'text-primary font-bold bg-blue-50/80 shadow-xs' : 'text-slate-600 hover:text-primary hover:bg-blue-50/60' }}">
                Beranda
            </a>

            <a href="{{ url('/') }}#donasi"
               class="px-3.5 py-2 text-sm font-semibold rounded-full transition-all duration-200 {{ request()->is('campaigns*') || request()->is('donation*') ? 'text-primary font-bold bg-blue-50/80 shadow-xs' : 'text-slate-600 hover:text-primary hover:bg-blue-50/60' }}">
                Donasi
            </a>

            <a href="{{ route('volunteer.landing') }}"
               class="px-3.5 py-2 text-sm font-semibold rounded-full transition-all duration-200 {{ request()->is('relawan*') || request()->is('volunteer*') ? 'text-primary font-bold bg-blue-50/80 shadow-xs' : 'text-slate-600 hover:text-primary hover:bg-blue-50/60' }}">
                Relawan
            </a>

            <a href="{{ url('/') }}#cara-kerja"
               class="px-3.5 py-2 text-sm font-semibold rounded-full transition-all duration-200 text-slate-600 hover:text-primary hover:bg-blue-50/60">
                Cara Kerja
            </a>

            {{-- Divider --}}
            <div class="h-5 w-px bg-slate-200 mx-2" aria-hidden="true"></div>

            {{-- 2.A. GUEST AUTH STATE (Belum Login Server) --}}
            @guest
                <div id="guestElements" class="flex items-center gap-2">
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-semibold rounded-full transition-all duration-200 {{ request()->routeIs('login') ? 'text-primary font-bold bg-blue-50/80' : 'text-slate-700 hover:text-primary hover:bg-blue-50/60' }}"
                       id="loginLink">
                        Masuk
                    </a>

                    <a href="{{ route('register') }}"
                       class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-full shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/35 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center"
                       id="registerLink">
                        Daftar Sekarang
                    </a>
                </div>
            @else
                {{-- 2.B. SERVER AUTH STATE (Sudah Login Server) --}}
                <div class="flex items-center gap-3">
                    {{-- Notifikasi Server --}}
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                        $unreadRecommendationCount = \App\Models\RecommendationNotification::where('user_id', auth()->id())
                            ->where('is_read', false)
                            ->where('is_dismissed', false)
                            ->count();
                        $totalUnreadCount = $unreadCount + $unreadRecommendationCount;
                    @endphp
                    <div id="serverNotifContainer" class="relative">
                        <button id="notifButton" class="relative p-2.5 text-slate-600 hover:text-primary hover:bg-blue-50/60 rounded-full transition-all duration-200 focus:outline-none flex items-center justify-center hover:scale-105 active:scale-95" aria-label="Notifikasi">
                            <i class="fas fa-bell text-lg"></i>
                            @if($totalUnreadCount > 0)
                                <span class="absolute top-1 right-1 flex h-4 min-w-[16px] px-1 items-center justify-center rounded-full bg-red-500 text-[9px] font-extrabold text-white ring-2 ring-white animate-pulse">
                                    {{ $totalUnreadCount > 99 ? '99+' : $totalUnreadCount }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown Notifikasi Server --}}
                        <div id="notifDropdown" class="hidden absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-xl border border-slate-100 py-0 z-50 overflow-hidden transform origin-top-right transition-all">
                            <div class="px-4 py-3.5 border-b border-slate-100 flex justify-between items-center bg-slate-50/70">
                                <span class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-bell text-primary"></i> Notifikasi
                                </span>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.markAllRead') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-primary hover:text-blue-700 transition-colors flex items-center gap-1">
                                            <i class="fas fa-check-double text-[10px]"></i> Tandai Semua Dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if($unreadRecommendationCount > 0)
                                <div class="px-4 py-2.5 bg-gradient-to-r from-indigo-500/10 via-indigo-500/5 to-transparent border-b border-indigo-100/50 flex items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-indigo-800 flex items-center gap-1.5">
                                        <i class="fas fa-magic text-indigo-600 animate-pulse"></i> Ada {{ $unreadRecommendationCount }} rekomendasi kampanye!
                                    </span>
                                    <a href="{{ route('notifications.index', ['filter' => 'rekomendasi']) }}" class="text-[10px] font-bold text-indigo-600 bg-white border border-indigo-200 px-2 py-0.5 rounded-md hover:bg-indigo-50/50 transition-all">Lihat</a>
                                </div>
                            @endif

                            <div class="max-h-[350px] overflow-y-auto no-scrollbar divide-y divide-slate-50">
                                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                    <div class="relative px-4 py-3.5 hover:bg-slate-50/80 transition-colors group flex gap-3 {{ $notification->read_at ? 'opacity-65' : 'bg-blue-50/20' }}">
                                        <div class="mt-0.5 flex-shrink-0">
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} {{ $notification->data['color'] ?? 'text-blue-500' }} text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <p class="text-sm font-bold text-slate-800 leading-snug truncate">{{ $notification->data['title'] ?? 'Pemberitahuan' }}</p>
                                                @if(!$notification->read_at)
                                                    <span class="h-2 w-2 rounded-full bg-blue-600 flex-shrink-0 mt-1.5" title="Belum dibaca"></span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                                            <div class="flex items-center gap-2 mt-1.5">
                                                <span class="text-[10px] text-slate-400 font-medium"><i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                                                @if(!$notification->read_at)
                                                    <span class="text-[9px] bg-blue-50 text-blue-600 px-1.5 py-0.2 rounded font-bold">Baru</span>
                                                @endif
                                            </div>
                                        </div>
                                        <a href="{{ $notification->data['url'] ?? '#' }}" class="absolute inset-0 z-10"></a>
                                    </div>
                                @empty
                                    <div class="px-4 py-12 text-center text-slate-400">
                                        <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-bell-slash text-xl text-slate-300"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-700">Belum Ada Notifikasi</p>
                                        <p class="text-xs text-slate-400 mt-1">Aktivitas donasi, kampanye, dan relawan Anda akan muncul di sini.</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="border-t border-slate-100 p-3 text-center bg-slate-50/50 hover:bg-slate-100/50 transition-colors">
                                <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-primary hover:text-blue-700 transition-colors flex items-center justify-center gap-1">
                                    Lihat Semua Notifikasi <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Server Profile Dropdown --}}
                    <div id="serverAuthElements" class="relative">
                        <button id="profileButton" class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full border border-slate-200/80 hover:border-blue-300 hover:bg-blue-50/50 bg-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-100 shadow-xs">
                            @if(auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                     class="w-8 h-8 rounded-full object-cover ring-2 ring-white shadow-xs"
                                     onerror="this.onerror=null; this.src='https://placehold.co/32x32?text={{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}';">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs ring-2 ring-white shadow-xs">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <span class="text-sm font-semibold text-slate-700 max-w-[110px] truncate">{{ auth()->user()->name ?? 'User' }}</span>
                            <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>

                        <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transform origin-top-right transition-all">
                            <div class="px-5 py-3.5 border-b border-slate-100 mb-1 bg-slate-50/50">
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Akun Masuk</p>
                                <div class="flex items-center gap-3 mt-2">
                                    @if(auth()->user()->photo)
                                        <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                             class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-xs"
                                             onerror="this.onerror=null; this.src='https://placehold.co/40x40?text={{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}';">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-sm ring-2 ring-white shadow-xs">
                                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? 'email@example.com' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-4 py-2.5 text-sm text-indigo-600 hover:bg-indigo-50 font-bold transition-colors mx-2 rounded-xl">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mr-3 transition-colors">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    Panel Admin
                                </a>
                            @endif

                            <a href="{{ route('profiles.index') }}" class="group flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors mx-2 rounded-xl">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-primary flex items-center justify-center mr-3 transition-colors">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                Profil & Riwayat
                            </a>

                            <a href="{{ route('certificates.index') }}" class="group flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors mx-2 rounded-xl">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-primary flex items-center justify-center mr-3 transition-colors">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                Sertifikat Saya
                            </a>

                            <div class="border-t border-slate-100 mt-2 pt-2 mx-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="group flex w-full items-center px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-xl transition-colors font-medium">
                                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 group-hover:bg-red-100 flex items-center justify-center mr-3 transition-colors">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest

            {{-- 2.C. CLIENT/API AUTH STATE (Managed by JavaScript jika ada auth_token di localStorage) --}}
            <div id="apiAuthElements" class="hidden flex items-center gap-3">
                {{-- API Notification --}}
                <div id="apiNotificationContainer" class="relative hidden">
                    <button id="apiNotifButton" class="relative p-2.5 text-slate-600 hover:text-primary hover:bg-blue-50/60 rounded-full transition-all focus:outline-none flex items-center justify-center" aria-label="Notifikasi">
                        <i class="fas fa-bell text-lg"></i>
                        <span id="apiNotifBadge" class="absolute top-1 right-1 h-2.5 w-2.5 bg-red-500 rounded-full ring-2 ring-white animate-pulse hidden"></span>
                    </button>
                    <div id="apiNotifDropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 overflow-hidden transform origin-top-right transition-all">
                        <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/70">
                            <p class="text-sm font-bold text-slate-800">Notifikasi</p>
                            <span id="apiNotifCount" class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold"></span>
                        </div>
                        <div id="apiNotifList" class="max-h-[300px] overflow-y-auto no-scrollbar">
                            <!-- Injected by JS -->
                        </div>
                        <div class="border-t border-slate-100 p-2 text-center bg-slate-50/50">
                            <button id="markAllReadBtn" class="text-xs font-bold text-primary hover:text-blue-700 transition-colors">
                                Tandai semua dibaca
                            </button>
                        </div>
                    </div>
                </div>

                {{-- API Profile Dropdown --}}
                <div id="profileDropdownContainer" class="relative">
                    <button id="apiProfileButton" class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full border border-slate-200 hover:border-blue-300 hover:bg-blue-50/50 bg-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-100 shadow-xs">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs ring-2 ring-white shadow-xs" id="profileInitial">
                            U
                        </div>
                        <span class="text-sm font-semibold text-slate-700 max-w-[110px] truncate" id="profileName">User</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>

                    <div id="apiProfileDropdown" class="hidden absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 transform origin-top-right transition-all">
                        <div class="px-5 py-3.5 border-b border-slate-100 mb-1 bg-slate-50/50">
                            <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">Akun Masuk</p>
                            <p class="text-sm font-bold text-slate-800 truncate" id="dropdownName">User</p>
                            <p class="text-xs text-slate-500 truncate" id="dropdownEmail">user@example.com</p>
                        </div>
                        <a href="{{ route('profiles.index') }}" class="group flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors mx-2 rounded-xl">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-primary flex items-center justify-center mr-3 transition-colors">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            Profil & Riwayat
                        </a>
                        <a href="{{ route('certificates.index') }}" class="group flex items-center px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors mx-2 rounded-xl">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 group-hover:bg-blue-100 group-hover:text-primary flex items-center justify-center mr-3 transition-colors">
                                <i class="fas fa-certificate"></i>
                            </div>
                            Sertifikat Saya
                        </a>
                        <div class="border-t border-slate-100 mt-2 pt-2 mx-2">
                            <button id="apiLogoutBtn" class="group flex w-full items-center px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-xl transition-colors font-medium">
                                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 group-hover:bg-red-100 flex items-center justify-center mr-3 transition-colors">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                Keluar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Mobile Hamburger Button --}}
        <button id="mobileMenuBtn" class="md:hidden p-2 text-slate-600 hover:text-primary hover:bg-blue-50/60 rounded-xl transition-colors focus:outline-none" aria-label="Buka Menu">
            <i class="fas fa-bars text-2xl"></i>
        </button>
    </div>

    {{-- 4. Mobile Drawer Menu --}}
    <div id="mobileMenu" class="hidden md:hidden bg-white/98 backdrop-blur-lg border-b border-slate-100 shadow-xl absolute top-full left-0 w-full z-40 transition-all duration-300">
        <div class="p-4 space-y-1">
            <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->is('/') ? 'text-primary bg-blue-50 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="fas fa-home w-5 text-center text-slate-400"></i> Beranda
            </a>
            <a href="{{ url('/') }}#donasi" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->is('campaigns*') || request()->is('donation*') ? 'text-primary bg-blue-50 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="fas fa-hand-holding-heart w-5 text-center text-slate-400"></i> Donasi
            </a>
            <a href="{{ route('volunteer.landing') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->is('relawan*') || request()->is('volunteer*') ? 'text-primary bg-blue-50 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="fas fa-hands-helping w-5 text-center text-slate-400"></i> Relawan
            </a>
            <a href="{{ url('/') }}#cara-kerja" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all text-slate-600 hover:bg-slate-50">
                <i class="fas fa-info-circle w-5 text-center text-slate-400"></i> Cara Kerja
            </a>

            @auth
                <div class="pt-3 mt-3 border-t border-slate-100 space-y-1">
                    <div class="px-4 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Akun Saya</div>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-indigo-600 hover:bg-indigo-50 transition-colors">
                            <i class="fas fa-shield-alt w-5 text-center text-indigo-500"></i> Panel Admin
                        </a>
                    @endif
                    <a href="{{ route('profiles.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors">
                        <i class="fas fa-user-circle w-5 text-center text-slate-400"></i> Profil & Riwayat
                    </a>
                    <a href="{{ route('certificates.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors">
                        <i class="fas fa-certificate w-5 text-center text-slate-400"></i> Sertifikat Saya
                    </a>
                    <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors">
                        <i class="fas fa-bell w-5 text-center text-slate-400"></i> Notifikasi
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold text-sm text-red-500 hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt w-5 text-center text-red-400"></i> Keluar
                        </button>
                    </form>
                </div>
            @else
                <div id="mobileAuthButtons" class="pt-4 mt-2 border-t border-slate-100 grid grid-cols-2 gap-3">
                    <a href="{{ route('login') }}" class="text-center py-2.5 rounded-full border border-slate-200 font-bold text-sm text-slate-600 hover:bg-slate-50 transition-all" id="mobileLoginLink">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="text-center py-2.5 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm shadow-md shadow-blue-500/25 hover:shadow-lg transition-all" id="mobileRegisterLink">
                        Daftar Sekarang
                    </a>
                </div>

                {{-- Mobile Profile Menu for API Auth --}}
                <div id="mobileProfileContainer" class="hidden pt-3 mt-3 border-t border-slate-100 space-y-1">
                    <a href="{{ route('profiles.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors" id="mobileProfileName">
                        <i class="fas fa-user-circle w-5 text-center text-slate-400"></i> Profil & Riwayat
                    </a>
                    <a href="{{ route('certificates.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold text-sm text-slate-600 hover:bg-blue-50 hover:text-primary transition-colors">
                        <i class="fas fa-certificate w-5 text-center text-slate-400"></i> Sertifikat Saya
                    </a>
                    <button id="mobileApiLogoutBtn" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl font-semibold text-sm text-red-500 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt w-5 text-center text-red-400"></i> Keluar
                    </button>
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- Navbar Handler Script --}}
<script>
    (function() {
        if (window.__dongivNavbarInitialized) return;
        window.__dongivNavbarInitialized = true;

        window.updateAuthUI = function() {
            const token = localStorage.getItem('auth_token');
            const userData = localStorage.getItem('user');

            const serverAuthElements = document.getElementById('serverAuthElements');
            const serverNotifContainer = document.getElementById('serverNotifContainer');
            const guestElements = document.getElementById('guestElements');
            const apiAuthElements = document.getElementById('apiAuthElements');
            const apiNotificationContainer = document.getElementById('apiNotificationContainer');
            const mobileAuthButtons = document.getElementById('mobileAuthButtons');
            const mobileProfileContainer = document.getElementById('mobileProfileContainer');

            if (token && userData) {
                try {
                    const user = JSON.parse(userData);

                    if (serverAuthElements) serverAuthElements.classList.add('hidden');
                    if (serverNotifContainer) serverNotifContainer.classList.add('hidden');
                    if (guestElements) guestElements.classList.add('hidden');

                    if (apiAuthElements) {
                        apiAuthElements.classList.remove('hidden');
                        const profileInitial = document.getElementById('profileInitial');
                        const profileName = document.getElementById('profileName');
                        const dropdownName = document.getElementById('dropdownName');
                        const dropdownEmail = document.getElementById('dropdownEmail');

                        if (profileInitial) profileInitial.textContent = user.name ? user.name.charAt(0).toUpperCase() : 'U';
                        if (profileName) profileName.textContent = user.name || 'User';
                        if (dropdownName) dropdownName.textContent = user.name || 'User';
                        if (dropdownEmail) dropdownEmail.textContent = user.email || 'user@example.com';
                    }

                    if (apiNotificationContainer) apiNotificationContainer.classList.remove('hidden');

                    if (mobileAuthButtons) mobileAuthButtons.classList.add('hidden');
                    if (mobileProfileContainer) {
                        mobileProfileContainer.classList.remove('hidden');
                        const mobileProfileName = document.getElementById('mobileProfileName');
                        if (mobileProfileName) mobileProfileName.textContent = (user.name || 'User') + ' - Profil & Riwayat';
                    }
                    return user;
                } catch (e) {
                    console.error('Error parsing user data:', e);
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('user');
                    return null;
                }
            } else {
                if (serverAuthElements && !serverAuthElements.classList.contains('hidden')) {
                    if (apiAuthElements) apiAuthElements.classList.add('hidden');
                    if (apiNotificationContainer) apiNotificationContainer.classList.add('hidden');
                    if (mobileAuthButtons) mobileAuthButtons.classList.add('hidden');
                    if (mobileProfileContainer) mobileProfileContainer.classList.add('hidden');
                } else {
                    if (serverAuthElements) serverAuthElements.classList.add('hidden');
                    if (serverNotifContainer) serverNotifContainer.classList.add('hidden');
                    if (guestElements) guestElements.classList.remove('hidden');
                    if (apiAuthElements) apiAuthElements.classList.add('hidden');
                    if (apiNotificationContainer) apiNotificationContainer.classList.add('hidden');
                    if (mobileAuthButtons) mobileAuthButtons.classList.remove('hidden');
                    if (mobileProfileContainer) mobileProfileContainer.classList.add('hidden');
                }
            }
        };

        window.apiLogout = async function() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                try {
                    await fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + token,
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });
                } catch (e) {
                    console.error('Logout error:', e);
                }
            }
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            window.location.reload();
        };

        function initNavbar() {
            window.updateAuthUI();

            // Set up API logout buttons
            const apiLogoutBtn = document.getElementById('apiLogoutBtn');
            const mobileApiLogoutBtn = document.getElementById('mobileApiLogoutBtn');
            if (apiLogoutBtn) apiLogoutBtn.addEventListener('click', window.apiLogout);
            if (mobileApiLogoutBtn) mobileApiLogoutBtn.addEventListener('click', window.apiLogout);

            // 1. Mobile Menu Toggle
            const mobileBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            if (mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // 2. Dropdown Elements
            const notifBtn = document.getElementById('notifButton');
            const notifDropdown = document.getElementById('notifDropdown');
            const profileBtn = document.getElementById('profileButton');
            const profileDropdown = document.getElementById('profileDropdown');

            const apiNotifBtn = document.getElementById('apiNotifButton');
            const apiNotifDropdown = document.getElementById('apiNotifDropdown');
            const apiProfileBtn = document.getElementById('apiProfileButton');
            const apiProfileDropdown = document.getElementById('apiProfileDropdown');

            if (notifBtn && notifDropdown) {
                notifBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (profileDropdown) profileDropdown.classList.add('hidden');
                    notifDropdown.classList.toggle('hidden');
                });
            }

            if (profileBtn && profileDropdown) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (notifDropdown) notifDropdown.classList.add('hidden');
                    profileDropdown.classList.toggle('hidden');
                });
            }

            if (apiNotifBtn && apiNotifDropdown) {
                apiNotifBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (apiProfileDropdown) apiProfileDropdown.classList.add('hidden');
                    apiNotifDropdown.classList.toggle('hidden');
                });
            }

            if (apiProfileBtn && apiProfileDropdown) {
                apiProfileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (apiNotifDropdown) apiNotifDropdown.classList.add('hidden');
                    apiProfileDropdown.classList.toggle('hidden');
                });
            }

            // 3. Global Click to Close Dropdowns & Mobile Menu
            document.addEventListener('click', (e) => {
                if (notifBtn && notifDropdown && !notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                    notifDropdown.classList.add('hidden');
                }
                if (profileBtn && profileDropdown && !profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                }
                if (apiNotifBtn && apiNotifDropdown && !apiNotifBtn.contains(e.target) && !apiNotifDropdown.contains(e.target)) {
                    apiNotifDropdown.classList.add('hidden');
                }
                if (apiProfileBtn && apiProfileDropdown && !apiProfileBtn.contains(e.target) && !apiProfileDropdown.contains(e.target)) {
                    apiProfileDropdown.classList.add('hidden');
                }
                if (mobileBtn && mobileMenu && !mobileBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });

            // 4. Navbar Scroll Shadow Effect
            const navbar = document.getElementById('navbar');
            if (navbar) {
                const handleScroll = () => {
                    if (window.scrollY > 12) {
                        navbar.classList.add('shadow-md', 'bg-white/95');
                        navbar.classList.remove('bg-white/85');
                    } else {
                        navbar.classList.remove('shadow-md', 'bg-white/95');
                        navbar.classList.add('bg-white/85');
                    }
                };
                window.addEventListener('scroll', handleScroll, { passive: true });
                handleScroll();
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNavbar);
        } else {
            initNavbar();
        }
    })();
</script>
