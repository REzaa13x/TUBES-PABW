<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validator Dashboard - DonGiv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .sidebar-link {
            transition: all 0.3s;
        }
        .sidebar-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }
        .sidebar-link.active {
            background-color: #2563eb;
            color: white;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 flex-shrink-0 hidden md:flex flex-col">
        <div class="p-6">
            <a href="#" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                    <i class="fas fa-hand-holding-heart text-xl"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter text-slate-800">DonGiv</span>
            </a>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="{{ route('validator.dashboard', $token) }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ Route::is('validator.dashboard') ? 'active' : 'text-slate-500' }}">
                <i class="fas fa-th-large w-5"></i>
                Dashboard
            </a>
            <a href="{{ route('validator.campaign', $token) }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ Route::is('validator.campaign') ? 'active' : 'text-slate-500' }}">
                <i class="fas fa-bullhorn w-5"></i>
                Detail Kampanye
            </a>
            <a href="{{ route('validator.upload', $token) }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ Route::is('validator.upload') ? 'active' : 'text-slate-500' }}">
                <i class="fas fa-cloud-upload-alt w-5"></i>
                Upload Bukti
            </a>
            <a href="{{ route('validator.history', $token) }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ Route::is('validator.history') ? 'active' : 'text-slate-500' }}">
                <i class="fas fa-history w-5"></i>
                Riwayat Penyaluran
            </a>
            <a href="{{ route('validator.status', $token) }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl font-semibold {{ Route::is('validator.status') ? 'active' : 'text-slate-500' }}">
                <i class="fas fa-check-circle w-5"></i>
                Status Verifikasi
            </a>

            <div class="pt-4 pb-2 px-4">
                <hr class="border-slate-100 mb-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Tugas Anda</p>
                
                {{-- Tugas Anda --}}
                <div class="space-y-2 mb-6">
                    {{-- Kampanye Saat Ini --}}
                    <div class="p-3 bg-blue-600 rounded-xl shadow-md shadow-blue-200 border-l-4 border-blue-400">
                        <p class="text-[10px] font-bold text-white truncate">{{ $campaign->title }}</p>
                        <p class="text-[8px] text-blue-100 mt-1 font-black uppercase tracking-widest italic">Sedang Dibuka</p>
                    </div>

                    {{-- Tugas Aktif Lainnya --}}
                    @if(isset($activeTasks) && count($activeTasks) > 0)
                        @foreach($activeTasks as $task)
                        <a href="{{ route('validator.dashboard', $task->distribution_token) }}" class="block p-3 bg-white hover:bg-blue-50 border border-slate-100 rounded-xl transition-all group">
                            <p class="text-[10px] font-bold text-slate-800 group-hover:text-blue-600 truncate">{{ $task->title }}</p>
                            <p class="text-[8px] text-slate-400 mt-1 uppercase tracking-tight">{{ $task->status }}</p>
                        </a>
                        @endforeach
                    @endif
                </div>

                {{-- Riwayat --}}
                @if(isset($completedTasks) && count($completedTasks) > 0)
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Riwayat Selesai</p>
                    <div class="space-y-2">
                        @foreach($completedTasks as $task)
                        <a href="{{ route('validator.dashboard', $task->distribution_token) }}" class="block p-3 bg-slate-50 hover:bg-green-50 rounded-xl transition-all group opacity-60 hover:opacity-100 border-l-2 border-green-400">
                            <p class="text-[10px] font-bold text-slate-800 group-hover:text-green-600 truncate">{{ $task->title }}</p>
                            <p class="text-[9px] text-green-600 mt-1 uppercase"><i class="fas fa-check mr-1"></i> Terverifikasi</p>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </nav>

        <div class="p-6 border-t border-slate-100">
            <div class="bg-blue-50 p-4 rounded-2xl">
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Validator Portal</p>
                <p class="text-[10px] text-blue-400 font-medium leading-relaxed">Akses terbatas via link khusus.</p>
            </div>
        </div>
    </aside>

    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-3 flex justify-between items-center z-50 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
        <a href="{{ route('validator.dashboard', $token) }}" class="flex flex-col items-center gap-1 {{ Route::is('validator.dashboard') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="fas fa-th-large text-lg"></i>
            <span class="text-[9px] font-bold uppercase">Dash</span>
        </a>
        <a href="{{ route('validator.upload', $token) }}" class="flex flex-col items-center gap-1 {{ Route::is('validator.upload') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="fas fa-plus-circle text-lg"></i>
            <span class="text-[9px] font-bold uppercase">Lapor</span>
        </a>
        <button onclick="toggleMobileTasks()" class="flex flex-col items-center gap-1 text-slate-400 relative">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white -mt-8 border-4 border-slate-50 shadow-lg">
                <i class="fas fa-tasks text-sm"></i>
            </div>
            <span class="text-[9px] font-bold uppercase mt-1">Tugas</span>
        </button>
        <a href="{{ route('validator.history', $token) }}" class="flex flex-col items-center gap-1 {{ Route::is('validator.history') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="fas fa-history text-lg"></i>
            <span class="text-[9px] font-bold uppercase">Riwayat</span>
        </a>
        <a href="{{ route('validator.status', $token) }}" class="flex flex-col items-center gap-1 {{ Route::is('validator.status') ? 'text-blue-600' : 'text-slate-400' }}">
            <i class="fas fa-check-circle text-lg"></i>
            <span class="text-[9px] font-bold uppercase">Status</span>
        </a>
    </div>

    {{-- Overlay Tugas Mobile --}}
    <div id="mobileTasksOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] hidden transition-opacity duration-300 opacity-0" onclick="toggleMobileTasks()">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[32px] p-6 animate-slide-up" onclick="event.stopPropagation()">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-tasks text-blue-600"></i>
                Daftar Tugas Anda
            </h3>
            
            <div class="space-y-3 max-h-[60vh] overflow-y-auto no-scrollbar pb-10">
                {{-- Tugas Aktif --}}
                <div class="p-4 bg-blue-600 rounded-2xl text-white shadow-lg shadow-blue-200">
                    <p class="text-xs font-black uppercase tracking-widest opacity-70 mb-1">Sedang Dibuka</p>
                    <p class="font-bold">{{ $campaign->title }}</p>
                </div>

                @if(isset($activeTasks) && count($activeTasks) > 0)
                    @foreach($activeTasks as $task)
                    <a href="{{ route('validator.dashboard', $task->distribution_token) }}" class="block p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                        <p class="text-sm font-bold text-slate-800">{{ $task->title }}</p>
                        <p class="text-[10px] text-slate-400 uppercase mt-1">{{ $task->status }}</p>
                    </a>
                    @endforeach
                @endif

                @if(isset($completedTasks) && count($completedTasks) > 0)
                    <div class="pt-4 pb-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tugas Selesai</p>
                    </div>
                    @foreach($completedTasks as $task)
                    <a href="{{ route('validator.dashboard', $task->distribution_token) }}" class="block p-4 bg-slate-50 border border-slate-100 rounded-2xl opacity-60">
                        <p class="text-sm font-bold text-slate-800">{{ $task->title }}</p>
                        <p class="text-[10px] text-green-600 font-bold uppercase mt-1">Selesai</p>
                    </a>
                    @endforeach
                @endif
            </div>
            
            <button onclick="toggleMobileTasks()" class="w-full py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl mt-4">Tutup</button>
        </div>
    </div>

    <script>
        function toggleMobileTasks() {
            const overlay = document.getElementById('mobileTasksOverlay');
            if (overlay.classList.contains('hidden')) {
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
            } else {
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }
    </script>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 bg-slate-50 overflow-y-auto">
        <!-- Top Bar -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <div class="md:hidden w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-800">@yield('title', 'Validator Panel')</h2>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sesi Aktif</p>
                    <p class="text-sm font-black text-blue-600">{{ $campaign->validator_name ?: 'Validator Lapangan' }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 border border-blue-200">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </header>

        <div class="p-8 pb-24 md:pb-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-600 rounded-2xl flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle"></i>
                    <p class="font-bold text-sm">{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>
