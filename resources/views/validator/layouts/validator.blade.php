<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Verifikasi & Transparansi - DonGiv</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <i class="fas fa-hand-holding-heart text-lg"></i>
                </div>
                <div>
                    <span class="text-lg font-black tracking-tight text-slate-800 block leading-tight">DonGiv</span>
                    <span class="block text-[8px] font-black uppercase tracking-widest text-blue-600">Portal Validator</span>
                </div>
            </div>

            <!-- Task Selector Dropdown (Space-saving replacement of sidebar) -->
            @if(isset($campaign))
            <div class="relative" id="taskSelectorDropdown">
                <button onclick="toggleTaskDropdown()" class="inline-flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200/60 rounded-xl text-[10px] font-black text-slate-700 hover:bg-slate-100 transition-colors">
                    <i class="fas fa-tasks text-blue-500"></i>
                    Pilih Kampanye
                    <i class="fas fa-chevron-down text-[8px] text-slate-400"></i>
                </button>
                
                <div id="taskDropdownMenu" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-2xl border border-slate-100 shadow-2xl py-2 z-50">
                    <div class="px-4 py-2.5 border-b border-slate-100">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Kampanye Saat Ini</p>
                        <p class="text-xs font-bold text-blue-600 mt-1 truncate">{{ $campaign->title }}</p>
                    </div>
                    
                    @php
                        // Load sibling tasks from helper
                        $allActive = [];
                        $allCompleted = [];
                        if (isset($campaign) && $campaign->validator_phone) {
                            $allTasks = \App\Models\Campaign::where('validator_phone', $campaign->validator_phone)
                                ->where('id', '!=', $campaign->id)
                                ->get();
                            foreach($allTasks as $task) {
                                if ($task->status == 'verified' && $task->current_amount >= $task->target_amount) {
                                    $allCompleted[] = $task;
                                } else {
                                    $allActive[] = $task;
                                }
                            }
                        }
                    @endphp

                    @if(count($allActive) > 0)
                    <div class="px-2 py-2 max-h-[160px] overflow-y-auto border-b border-slate-100">
                        <p class="px-2 pb-1 text-[8px] font-black text-slate-400 uppercase tracking-widest">Kampanye Aktif Lainnya</p>
                        @foreach($allActive as $task)
                            <a href="{{ route('validator.dashboard', $task->distribution_token) }}" class="block px-2 py-2 hover:bg-slate-50 rounded-lg text-xs font-bold text-slate-700 truncate">
                                {{ $task->title }}
                            </a>
                        @endforeach
                    </div>
                    @endif

                    @if(count($allCompleted) > 0)
                    <div class="px-2 py-2 max-h-[160px] overflow-y-auto">
                        <p class="px-2 pb-1 text-[8px] font-black text-slate-400 uppercase tracking-widest">Telah Diverifikasi</p>
                        @foreach($allCompleted as $task)
                            <a href="{{ route('validator.dashboard', $task->distribution_token) }}" class="block px-2 py-2 hover:bg-slate-50 rounded-lg text-xs font-bold text-slate-500 truncate">
                                <span class="text-emerald-600 mr-1">✓</span> {{ $task->title }}
                            </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Validator Profile Info -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="text-right hidden sm:block">
                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Sesi Aktif</p>
                    <p class="text-xs font-black text-blue-600">{{ isset($campaign) ? ($campaign->validator_name ?: 'Validator Lapangan') : 'Validator Lapangan' }}</p>
                </div>
                <div class="w-9 h-9 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 border border-blue-100">
                    <i class="fas fa-user-shield text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Horizontal scrollable links bar -->
        <div class="border-t border-slate-100 bg-slate-50/40">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex items-center gap-2 overflow-x-auto no-scrollbar">
                <a href="{{ route('validator.dashboard', $token) }}" class="flex-shrink-0 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all {{ Route::is('validator.dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fas fa-list mr-1.5"></i> Daftar Kampanye Saya
                </a>
                @if(isset($campaign) && (Route::is('validator.campaign') || Route::is('validator.upload')))
                <a href="{{ route('validator.campaign', $token) }}" class="flex-shrink-0 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all {{ Route::is('validator.campaign') || Route::is('validator.upload') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                    <i class="fas fa-chart-line mr-1.5"></i> Transparansi & Detail: {{ Str::limit($campaign->title, 30) }}
                </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Centered Content Area -->
    <main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Alerts Notification Container -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-xs">
                <i class="fas fa-check-circle text-lg text-emerald-500"></i>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center gap-3 shadow-xs">
                <i class="fas fa-exclamation-circle text-lg text-rose-500"></i>
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-8 p-4 bg-blue-50 border border-blue-200 text-blue-700 rounded-2xl flex items-center gap-3 shadow-xs">
                <i class="fas fa-info-circle text-lg text-blue-500"></i>
                <p class="font-bold text-sm">{{ session('info') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer footer block -->
    <footer class="bg-white border-t border-slate-100 py-6 mt-12">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">© 2026 DonGiv Foundation • Portal Validator Khusus</p>
        </div>
    </footer>

    <script>
        function toggleTaskDropdown() {
            const menu = document.getElementById('taskDropdownMenu');
            if(menu) {
                menu.classList.toggle('hidden');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('taskSelectorDropdown');
            const menu = document.getElementById('taskDropdownMenu');
            if (dropdown && !dropdown.contains(event.target) && menu) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
