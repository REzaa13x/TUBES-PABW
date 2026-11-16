<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | @yield('title', 'DonGiv')</title>

    {{-- Tailwind & FontAwesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        // Palet Biru Modern (Lebih Cerah & Fresh)
                        sidebar: {
                            start: '#3b82f6', // Blue 500
                            end: '#2563eb', // Blue 600
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f3f6fc;
        }

        /* Modern Sidebar Gradient */
        .bright-sidebar {
            background: linear-gradient(180deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 4px 0 20px rgba(37, 99, 235, 0.15);
        }

        /* Group Label Styling */
        .nav-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            padding-left: 1.25rem;
        }

        /* Menu Item Base Style */
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.25rem;
            margin-bottom: 0.25rem;
            margin-left: 0.75rem;
            margin-right: 0.75rem;
            border-radius: 0.75rem;
            /* Rounded corners modern */
            font-weight: 500;
            font-size: 0.9rem;
            color: white;
            transition: all 0.2s ease-in-out;
        }

        /* Hover Effect: Transparan Putih */
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(4px);
        }

        /* ACTIVE STATE: White Pill Style (Kunci tampilan modern) */
        .nav-item.active {
            background-color: #ffffff;
            color: #2563eb;
            /* Teks jadi biru */
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .nav-item.active i {
            color: #2563eb;
            /* Icon juga jadi biru */
        }
        
        /* Custom Scrollbar for Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        body.galaxy-theme {
            background-color: #f8faff;
            background-image: 
                radial-gradient(ellipse 80% 50% at 10% 5%, rgba(99, 102, 241, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 70% 50% at 90% 8%, rgba(217, 70, 239, 0.10) 0%, transparent 55%),
                radial-gradient(ellipse 80% 60% at 15% 75%, rgba(139, 92, 246, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 70% 50% at 85% 85%, rgba(37, 99, 235, 0.12) 0%, transparent 60%),
                linear-gradient(180deg, #f0f4ff 0%, #faf5ff 35%, #f0f9ff 70%, #f5f3ff 100%);
            background-attachment: fixed;
        }
    </style>
</head>

<body class="galaxy-theme flex h-screen overflow-hidden">

    <aside class="bright-sidebar w-72 h-screen fixed left-0 top-0 flex flex-col z-50 text-white">

        <div class="h-24 flex flex-col justify-center px-8">
            <h1 class="text-3xl font-extrabold tracking-tight flex items-center gap-2">
                DonGiv<span class="text-blue-200">.</span>
            </h1>
            <p class="text-xs text-blue-100 opacity-80 mt-1 font-medium tracking-wide">Admin Dashboard Panel</p>
        </div>

        <div class="flex-1 overflow-y-auto py-2 pr-2 custom-scrollbar">

            <div class="nav-label">Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large w-6 text-center text-lg mr-2"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-label">Manajemen Donasi</div>
            <a href="{{ route('admin.donations.index') }}" class="nav-item {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar w-6 text-center text-lg mr-2"></i>
                <span>Verifikasi Donasi</span>
            </a>
            
            <a href="{{ route('admin.withdrawals.index') }}" class="nav-item {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave w-6 text-center text-lg mr-2"></i>
                <span>Penyaluran Dana</span>
            </a>
            
            <a href="{{ route('admin.campaigns.index') }}" class="nav-item {{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-heart w-6 text-center text-lg mr-2"></i>
                <span>Kampanye Donasi</span>
            </a>

            <a href="{{ route('admin.distribution.index') }}" class="nav-item {{ request()->routeIs('admin.distribution.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check w-6 text-center text-lg mr-2"></i>
                <span>Laporan Penyaluran</span>
            </a>

            <a href="{{ route('admin.validator-contacts.index') }}" class="nav-item {{ request()->routeIs('admin.validator-contacts.*') ? 'active' : '' }}">
                <i class="fas fa-address-book w-6 text-center text-lg mr-2"></i>
                <span>Data Validator</span>
            </a>

            <div class="nav-label">Manajemen Relawan</div>
            <a href="{{ route('admin.relawan.index') }}" class="nav-item {{ request()->routeIs('admin.relawan.*') ? 'active' : '' }}">                <i class="fas fa-hands-helping w-6 text-center text-lg mr-2"></i>
                <span>Kampanye Relawan</span>
            </a>
            <a href="{{ route('admin.verifikasi-relawan.index') }}" class="nav-item {{ request()->routeIs('admin.verifikasi-relawan.*') ? 'active' : '' }}">
                <i class="fas fa-users w-6 text-center text-lg mr-2"></i>
                <span>Pendaftar Relawan</span>
            </a>

            <div class="nav-label">Sistem & Akun</div>
            <a href="{{ route('admin.notifications.index') }}" class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell w-6 text-center text-lg mr-2"></i>
                <span>Notifikasi</span>
                @php $unreadAdminNotif = \App\Models\AdminNotification::where('is_read', false)->count(); @endphp
                @if($unreadAdminNotif > 0)
                    <span style="background:#ef4444;color:#fff;border-radius:999px;font-size:.68rem;padding:.05rem .45rem;margin-left:auto;font-weight:700;">{{ $unreadAdminNotif }}</span>
                @endif
            </a>
            <a href="{{ route('admin.profiles.index') }}" class="nav-item {{ request()->routeIs('admin.profiles.*') ? 'active' : '' }}">
                <i class="fas fa-user-circle w-6 text-center text-lg mr-2"></i>
                <span>Kelola User</span>
            </a>

        </div>

        <div class="p-6 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 bg-blue-800/30 hover:bg-white hover:text-red-600 rounded-xl transition-all duration-300 group">
                    <i class="fas fa-sign-out-alt w-6 text-lg mr-2 text-blue-200 group-hover:text-red-500 transition-colors"></i>
                    <span class="font-bold text-sm">Keluar Sistem</span>
                </button>
            </form>
        </div>

    </aside>

    <main class="flex-1 ml-72 overflow-x-hidden overflow-y-auto p-8">
        @yield('content')
    </main>

    {{-- Bootstrap, ChartJS & SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>