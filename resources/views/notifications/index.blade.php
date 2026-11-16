<x-app title="Notifikasi - DonGiv">
    <div class="py-12 bg-transparent min-h-[80vh]">
        <div class="max-w-5xl mx-auto px-6">
            
            {{-- BREADCRUMB --}}
            <nav class="flex mb-8 text-sm" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-slate-500 font-medium">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="hover:text-primary transition-colors flex items-center gap-1.5">
                            <i class="fas fa-home text-xs"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-[10px] mx-1 text-slate-400"></i>
                            <span class="text-slate-400">Notifikasi</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10 pb-8 border-b border-slate-200">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            🔔 Notifikasi
                        </span>
                    </h1>
                    <p class="text-slate-500 mt-2 text-base">Pantau seluruh aktivitas akun, kampanye, dan donasi Anda.</p>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    {{-- Generate Demo Button --}}
                    <a href="{{ route('notifications.seedDemo') }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-200 hover:border-blue-300 hover:bg-blue-50 rounded-full shadow-soft transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fas fa-magic text-blue-500"></i> Buat Notifikasi Demo
                    </a>

                    @if($counts['all'] > 0 && auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-full shadow-md shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- TABS FILTER --}}
            <div class="mb-8 overflow-x-auto no-scrollbar pb-2">
                <div class="flex space-x-2 min-w-max p-1 bg-slate-200/50 rounded-2xl border border-slate-200/30">
                    {{-- Tab: Semua --}}
                    <a href="{{ route('notifications.index', ['filter' => 'all']) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ $filter === 'all' ? 'bg-white text-blue-600 shadow-soft' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100' }}">
                        <i class="fas fa-list-ul"></i> Semua 
                        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold {{ $filter === 'all' ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-600' }}">
                            {{ $counts['all'] }}
                        </span>
                    </a>

                    {{-- Tab: Donasi --}}
                    <a href="{{ route('notifications.index', ['filter' => 'donasi']) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ $filter === 'donasi' ? 'bg-white text-blue-600 shadow-soft' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100' }}">
                        💰 Donasi 
                        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold {{ $filter === 'donasi' ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-600' }}">
                            {{ $counts['donasi'] }}
                        </span>
                    </a>

                    {{-- Tab: Kampanye --}}
                    <a href="{{ route('notifications.index', ['filter' => 'kampanye']) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ $filter === 'kampanye' ? 'bg-white text-blue-600 shadow-soft' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100' }}">
                        📢 Kampanye 
                        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold {{ $filter === 'kampanye' ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-600' }}">
                            {{ $counts['kampanye'] }}
                        </span>
                    </a>

                    {{-- Tab: Relawan --}}
                    <a href="{{ route('notifications.index', ['filter' => 'relawan']) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ $filter === 'relawan' ? 'bg-white text-blue-600 shadow-soft' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100' }}">
                        🤝 Relawan 
                        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold {{ $filter === 'relawan' ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-600' }}">
                            {{ $counts['relawan'] }}
                        </span>
                    </a>

                    {{-- Tab: Rekomendasi --}}
                    <a href="{{ route('notifications.index', ['filter' => 'rekomendasi']) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ $filter === 'rekomendasi' ? 'bg-white text-indigo-600 shadow-soft' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100' }}">
                        ✨ Rekomendasi 
                        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold {{ $filter === 'rekomendasi' ? 'bg-indigo-100 text-indigo-600 font-black' : 'bg-slate-200 text-slate-600' }}">
                            {{ $counts['rekomendasi'] }}
                        </span>
                    </a>

                    {{-- Tab: Sistem --}}
                    <a href="{{ route('notifications.index', ['filter' => 'sistem']) }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 {{ $filter === 'sistem' ? 'bg-white text-blue-600 shadow-soft' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-100' }}">
                        ⚙️ Sistem 
                        <span class="px-2 py-0.5 rounded-full text-xs font-extrabold {{ $filter === 'sistem' ? 'bg-blue-100 text-blue-600' : 'bg-slate-200 text-slate-600' }}">
                            {{ $counts['sistem'] }}
                        </span>
                    </a>
                </div>
            </div>

            {{-- NOTIFICATION LIST --}}
            <div class="space-y-4">
                @if($filter === 'rekomendasi')
                    @forelse($recommendations as $rec)
                        <div class="relative bg-gradient-to-br from-indigo-50/40 via-white to-blue-50/20 rounded-2xl shadow-soft border-2 border-indigo-100/60 transition-all duration-300 hover:shadow-md hover:border-indigo-200 flex flex-col md:flex-row gap-5 p-5 md:p-6 overflow-hidden">
                            {{-- Sparkle Decorative Element --}}
                            <div class="absolute -right-8 -top-8 w-24 h-24 bg-indigo-500/5 rounded-full blur-xl pointer-events-none"></div>
                            
                            {{-- Icon Kategori --}}
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-magic text-indigo-600 text-lg animate-pulse"></i>
                                </div>
                            </div>

                            {{-- Isi Konten --}}
                            <div class="flex-grow min-w-0 space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-base font-black text-slate-800 leading-tight pr-4">
                                        Rekomendasi Kampanye Baru: {{ $rec->campaign->judul ?? 'Kampanye Menarik' }}
                                    </h3>
                                    
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-100 text-indigo-600 tracking-wider uppercase">
                                        <i class="fas fa-magic text-[8px]"></i> Smart Suggestion
                                    </span>

                                    @if(!$rec->is_read)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-600 tracking-wider uppercase animate-pulse">
                                            Baru
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-slate-600 leading-relaxed max-w-3xl">
                                    {{ $rec->reason ?? 'Kampanye ini cocok untuk Anda karena Anda menunjukkan minat pada kategori tersebut.' }}
                                </p>
                                
                                <div class="p-3 bg-white border border-slate-100 rounded-xl flex items-center justify-between gap-4 max-w-2xl shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                                            <img src="{{ $rec->campaign && $rec->campaign->image ? $rec->campaign->image : 'https://placehold.co/40x40?text=No+Image' }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $rec->campaign->judul ?? '' }}</h4>
                                            <p class="text-[10px] text-slate-400 font-semibold"><i class="fas fa-tag mr-0.5"></i> Kategori: <span class="text-indigo-600 font-extrabold">{{ $rec->matching_category }}</span></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 pt-1">
                                    <span class="text-xs text-slate-400 font-medium flex items-center gap-1">
                                        <i class="far fa-clock"></i> {{ $rec->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex md:flex-col items-center justify-end gap-2 border-t md:border-t-0 border-slate-100 pt-4 md:pt-0 flex-shrink-0">
                                {{-- Lihat Detail (which marks read and redirects) --}}
                                <form action="{{ route('notifications.recommendations.read', $rec->id) }}" method="POST" class="w-full md:w-auto">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-extrabold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-sm">
                                        <i class="fas fa-external-link-alt text-[10px]"></i> Ikut Berpartisipasi
                                    </button>
                                </form>

                                {{-- Dismiss/Abaikan Button --}}
                                <form action="{{ route('notifications.recommendations.dismiss', $rec->id) }}" method="POST" class="w-full md:w-auto">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors border border-slate-200/40">
                                        <i class="fas fa-times text-[10px]"></i> Abaikan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        {{-- EMPTY RECOMMENDATIONS STATE --}}
                        <div class="bg-white rounded-3xl border border-slate-200/60 p-12 md:p-16 text-center max-w-2xl mx-auto shadow-soft mt-8 transform hover:scale-[1.01] transition-transform duration-300">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-indigo-500/10 to-blue-500/10 flex items-center justify-center mx-auto mb-6 shadow-inner">
                                <i class="fas fa-magic text-4xl bg-gradient-to-r from-indigo-600 to-blue-600 bg-clip-text text-transparent animate-pulse"></i>
                            </div>
                            
                            <h3 class="text-2xl font-extrabold text-slate-800 leading-tight">Belum Ada Rekomendasi</h3>
                            
                            <p class="text-slate-500 mt-3 text-sm md:text-base leading-relaxed max-w-md mx-auto">
                                Aktifkan atau perbarui <a href="{{ route('profiles.index') }}#volunteer-preferences" class="text-indigo-600 font-extrabold hover:underline">Minat Relawan</a> Anda untuk memicu saran kampanye personal otomatis kami!
                            </p>
                        </div>
                    @endforelse
                @else
                    @forelse($notifications as $notification)
                        <div class="relative bg-white rounded-2xl shadow-soft border border-slate-100 transition-all duration-300 hover:shadow-md flex flex-col md:flex-row gap-5 p-5 md:p-6 overflow-hidden {{ $notification->is_high ? 'border-l-4 border-l-blue-600 bg-blue-50/10' : '' }} {{ $notification->read_at ? 'opacity-80' : '' }}">
                        
                        {{-- Icon Kategori --}}
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center shadow-sm">
                                <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }} {{ $notification->data['color'] ?? 'text-blue-500' }} text-lg"></i>
                            </div>
                        </div>

                        {{-- Isi Konten --}}
                        <div class="flex-grow min-w-0 space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-bold text-slate-800 leading-tight pr-4">
                                    {{ $notification->data['title'] }}
                                </h3>
                                
                                {{-- Priority Badge --}}
                                @if($notification->is_high)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-600 tracking-wider uppercase">
                                        <i class="fas fa-star text-[8px]"></i> Utama
                                    </span>
                                @endif

                                {{-- Read Status Badge --}}
                                @if(!$notification->read_at)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-600 tracking-wider uppercase animate-pulse">
                                        ● Belum Dibaca
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400 tracking-wider uppercase">
                                        Dibaca
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-slate-600 leading-relaxed max-w-3xl">
                                {{ $notification->data['message'] }}
                            </p>

                            <div class="flex items-center gap-3 pt-1">
                                <span class="text-xs text-slate-400 font-medium flex items-center gap-1">
                                    <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </span>
                                @if($notification->data['url'] && $notification->data['url'] !== '#')
                                    <span class="text-xs text-slate-300">|</span>
                                    <a href="{{ $notification->data['url'] }}" class="text-xs font-bold text-primary hover:text-blue-700 transition-colors flex items-center gap-1">
                                        Lihat Detail <i class="fas fa-chevron-right text-[9px]"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex md:flex-col items-center justify-end gap-2 border-t md:border-t-0 border-slate-100 pt-4 md:pt-0 flex-shrink-0">
                            {{-- Tandai Dibaca Button --}}
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST" class="w-full md:w-auto">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/50 rounded-xl transition-colors">
                                        <i class="fas fa-check text-[10px]"></i> Tandai Dibaca
                                    </button>
                                </form>
                            @endif

                            {{-- Hapus Button --}}
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="w-full md:w-auto delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200/50 rounded-xl transition-colors">
                                    <i class="far fa-trash-alt text-[10px]"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    {{-- EMPTY STATE --}}
                    <div class="bg-white rounded-3xl border border-slate-200/60 p-12 md:p-16 text-center max-w-2xl mx-auto shadow-soft mt-8 transform hover:scale-[1.01] transition-transform duration-300">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-blue-500/10 to-indigo-500/10 flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="fas fa-bell-slash text-4xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"></i>
                        </div>
                        
                        <h3 class="text-2xl font-extrabold text-slate-800 leading-tight">Belum Ada Notifikasi</h3>
                        
                        <p class="text-slate-500 mt-3 text-sm md:text-base leading-relaxed max-w-md mx-auto">
                            Seluruh aktivitas donasi, kampanye, dan relawan Anda akan langsung muncul di sini saat berjalan.
                        </p>
                        
                        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                            <a href="{{ route('volunteer.landing') }}" 
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-full shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                                Jelajahi Kampanye <i class="fas fa-compass"></i>
                            </a>
                            
                            <a href="{{ route('notifications.seedDemo') }}" 
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-full transition-all">
                                <i class="fas fa-magic"></i> Generate Data Demo
                            </a>
                        </div>
                    </div>
                @endforelse
                @endif
            </div>

        </div>
    </div>

    {{-- SWEETALERT CONFIRMATION SCRIPT FOR DELETE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Notifikasi yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-3xl shadow-xl',
                            confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                            cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app>
