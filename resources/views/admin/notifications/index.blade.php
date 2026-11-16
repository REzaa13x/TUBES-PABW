@extends('admin.layouts.master')

@section('title', 'Notifikasi Admin')

@section('content')
<div class="min-h-screen pb-16">

    {{-- ===== HEADER SECTION ===== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200 flex-shrink-0">
                    <i class="fas fa-bell text-lg"></i>
                </div>
                <span>Notifikasi Admin</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1.5 ml-14">Pantau pemberitahuan sistem, donasi, dan relawan secara sederhana.</p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Tombol Buat Notifikasi Baru --}}
            <button type="button" onclick="openCreateModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-200 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                <i class="fas fa-plus"></i>
                <span>Buat Notifikasi</span>
            </button>

            {{-- Tandai Semua Dibaca --}}
            @if(($stats['total_unread'] ?? 0) > 0)
            <form method="POST" action="{{ route('admin.notifications.markAllAsRead') }}" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-sm transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fas fa-check-double text-blue-600"></i>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
            @endif

            {{-- Export CSV --}}
            <a href="{{ route('admin.notifications.export', ['category' => $category]) }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl border border-slate-200 shadow-sm transition-all"
               title="Export CSV Data">
                <i class="fas fa-download text-slate-500"></i>
                <span>Export</span>
            </a>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                <i class="fas fa-check text-xs"></i>
            </div>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 p-1">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>
    @endif

    {{-- ===== RINGKASAN STATISTIK SEDERHANA ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        {{-- Total --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Notifikasi</p>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $stats['all'] ?? 0 }}</h3>
            </div>
        </div>

        {{-- Belum Dibaca --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Belum Dibaca</p>
                <div class="flex items-center gap-2 mt-0.5">
                    <h3 class="text-2xl font-black text-slate-900">{{ $stats['total_unread'] ?? 0 }}</h3>
                    @if(($stats['total_unread'] ?? 0) > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-700 uppercase">
                            Perlu Dicek
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Hari Ini --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aktivitas Hari Ini</p>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $stats['today'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    {{-- ===== FILTER TABS & SEARCH BAR ===== --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        
        {{-- Tabs Kategori --}}
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 lg:pb-0">
            @php
                $tabItems = [
                    'semua'    => ['label' => 'Semua', 'count' => $stats['all'] ?? 0],
                    'unread'   => ['label' => 'Belum Dibaca', 'count' => $stats['total_unread'] ?? 0],
                    'kampanye' => ['label' => 'Kampanye', 'count' => $stats['kampanye'] ?? 0],
                    'donasi'   => ['label' => 'Donasi', 'count' => $stats['donasi'] ?? 0],
                    'relawan'  => ['label' => 'Relawan', 'count' => $stats['relawan'] ?? 0],
                    'sistem'   => ['label' => 'Sistem', 'count' => $stats['sistem'] ?? 0],
                ];
            @endphp

            @foreach($tabItems as $tabKey => $tabData)
                @php
                    $isActive = ($category === $tabKey);
                @endphp
                <a href="{{ route('admin.notifications.index', array_merge(request()->except('category', 'page'), ['category' => $tabKey])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ $isActive ? 'bg-blue-600 text-white shadow-sm shadow-blue-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>{{ $tabData['label'] }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                        {{ $tabData['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Search Input --}}
        <form method="GET" action="{{ route('admin.notifications.index') }}" class="relative w-full lg:w-72">
            @if($category && $category !== 'semua')
                <input type="hidden" name="category" value="{{ $category }}">
            @endif
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="search" value="{{ $search }}" 
                   placeholder="Cari notifikasi..." 
                   class="w-full pl-9 pr-8 py-2 text-xs font-medium rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:border-blue-500 transition-all">
            @if($search)
                <a href="{{ route('admin.notifications.index', ['category' => $category]) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xs"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- ===== DAFTAR NOTIFIKASI ===== --}}
    <div class="space-y-3">
        @forelse($notifications as $notif)
            @php
                $cat = strtolower($notif->category ?? 'sistem');
                
                // Icon & Color per Category
                if ($cat === 'donasi') {
                    $iconClass = 'fas fa-hand-holding-heart';
                    $iconBoxColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                    $badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
                    $catLabel = 'Donasi';
                } elseif ($cat === 'kampanye') {
                    $iconClass = 'fas fa-bullhorn';
                    $iconBoxColor = 'bg-blue-50 text-blue-600 border-blue-100';
                    $badgeColor = 'bg-blue-50 text-blue-700 border-blue-200/60';
                    $catLabel = 'Kampanye';
                } elseif ($cat === 'relawan') {
                    $iconClass = 'fas fa-hands-helping';
                    $iconBoxColor = 'bg-purple-50 text-purple-600 border-purple-100';
                    $badgeColor = 'bg-purple-50 text-purple-700 border-purple-200/60';
                    $catLabel = 'Relawan';
                } elseif ($cat === 'laporan') {
                    $iconClass = 'fas fa-flag';
                    $iconBoxColor = 'bg-rose-50 text-rose-600 border-rose-100';
                    $badgeColor = 'bg-rose-50 text-rose-700 border-rose-200/60';
                    $catLabel = 'Laporan';
                } else {
                    $iconClass = 'fas fa-bell';
                    $iconBoxColor = 'bg-slate-100 text-slate-600 border-slate-200';
                    $badgeColor = 'bg-slate-100 text-slate-700 border-slate-200/60';
                    $catLabel = 'Sistem';
                }
            @endphp

            <div id="notif-card-{{ $notif->id }}" 
                 class="bg-white rounded-2xl border p-4 sm:p-5 transition-all duration-200 hover:shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 {{ !$notif->is_read ? 'border-blue-200 bg-blue-50/20' : 'border-slate-100' }}">
                
                {{-- Konten Kiri (Icon + Info) --}}
                <div class="flex items-start gap-4 flex-1 min-w-0">
                    {{-- Icon Kategori --}}
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-base border {{ $iconBoxColor }} flex-shrink-0 mt-0.5 sm:mt-0">
                        <i class="{{ $iconClass }}"></i>
                    </div>

                    {{-- Teks & Metadata --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h3 class="text-sm font-bold text-slate-900 leading-snug">
                                {{ $notif->title }}
                            </h3>

                            {{-- Badge Kategori --}}
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $badgeColor }}">
                                {{ $catLabel }}
                            </span>

                            {{-- Badge Baru jika belum dibaca --}}
                            @if(!$notif->is_read)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-700 uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                    Baru
                                </span>
                            @endif
                        </div>

                        {{-- Pesan Notifikasi --}}
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed break-words">
                            {{ $notif->message }}
                        </p>

                        {{-- Waktu --}}
                        <div class="flex items-center gap-3 mt-2 text-[11px] font-medium text-slate-400">
                            <span class="flex items-center gap-1">
                                <i class="far fa-clock"></i> {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Aksi Kanan --}}
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0">
                    {{-- Link Detail (jika ada) --}}
                    @if($notif->resolved_action_url)
                        <a href="{{ $notif->resolved_action_url }}" 
                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 transition-colors">
                            <span>Detail</span>
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    @endif

                    {{-- Tandai Dibaca / Belum Dibaca --}}
                    @if($notif->is_read)
                        <button type="button" 
                                onclick="toggleRead({{ $notif->id }}, false)"
                                title="Tandai belum dibaca"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                            <i class="far fa-envelope-open text-xs"></i>
                        </button>
                    @else
                        <button type="button" 
                                onclick="toggleRead({{ $notif->id }}, true)"
                                title="Tandai sudah dibaca"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-blue-600 hover:bg-blue-50 transition-colors">
                            <i class="fas fa-check text-xs"></i>
                        </button>
                    @endif

                    {{-- Hapus Notifikasi --}}
                    <button type="button" 
                            onclick="confirmDelete({{ $notif->id }})"
                            title="Hapus notifikasi"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                        <i class="far fa-trash-alt text-xs"></i>
                    </button>
                </div>
            </div>
        @empty
            {{-- Empty State Bersih & Simpel --}}
            <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-800">Belum Ada Notifikasi</h3>
                <p class="text-slate-500 text-xs sm:text-sm mt-1 max-w-sm mx-auto">
                    Seluruh pemberitahuan baru terkait kampanye, donasi, dan relawan akan langsung ditampilkan di sini.
                </p>
                @if($search || $category !== 'semua')
                    <a href="{{ route('admin.notifications.index') }}" 
                       class="inline-flex items-center gap-1.5 mt-5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                        <i class="fas fa-sync-alt text-[10px]"></i>
                        <span>Reset Filter</span>
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- ===== PAGINATION ===== --}}
    @if($notifications->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $notifications->appends(request()->query())->links() }}
    </div>
    @endif

</div>

{{-- ===== MODAL BUAT NOTIFIKASI BARU (SIMPLE & CLEAN) ===== --}}
<div id="createNotificationModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-slate-100 overflow-hidden transform transition-all animate-fade-in">
        
        {{-- Modal Header --}}
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Buat Notifikasi Baru</h2>
                    <p class="text-xs text-slate-400">Kirim pemberitahuan atau pesan pengumuman.</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateModal()" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        {{-- Modal Form --}}
        <form action="{{ route('admin.notifications.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                
                {{-- Judul Notifikasi --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Notifikasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" required
                           placeholder="Contoh: Pemeliharaan Server atau Update Program Donasi"
                           class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all font-medium">
                </div>

                {{-- Kategori & Prioritas --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                        <select name="category" class="w-full px-3 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all font-medium">
                            <option value="sistem">Sistem</option>
                            <option value="kampanye">Kampanye</option>
                            <option value="donasi">Donasi</option>
                            <option value="relawan">Relawan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Prioritas</label>
                        <select name="priority" class="w-full px-3 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all font-medium">
                            <option value="medium">Normal</option>
                            <option value="high">Tinggi</option>
                            <option value="low">Rendah</option>
                        </select>
                    </div>
                </div>

                {{-- Isi Pesan --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Isi Pesan <span class="text-rose-500">*</span></label>
                    <textarea name="message" rows="3" required
                              placeholder="Tuliskan isi ringkas pemberitahuan notifikasi..."
                              class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all font-medium"></textarea>
                </div>

                {{-- Checkbox Broadcast User --}}
                <div class="flex items-center gap-2.5 pt-1">
                    <input type="checkbox" name="send_to_users" id="send_to_users" value="1"
                           class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                    <label for="send_to_users" class="text-xs font-semibold text-slate-600 cursor-pointer">
                        Kirimkan juga sebagai notifikasi ke seluruh pengguna
                    </label>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 hover:bg-slate-200/60 rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                    Kirim Notifikasi
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const BASE_NOTIF_URL = '{{ url('/admin/notifications') }}';

    // Modal Control
    function openCreateModal() {
        document.getElementById('createNotificationModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createNotificationModal').classList.add('hidden');
    }

    // Close on backdrop click
    document.getElementById('createNotificationModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });

    // Toggle Read Status via AJAX
    function toggleRead(id, markAsRead) {
        const url = BASE_NOTIF_URL + '/' + id + (markAsRead ? '/mark-as-read' : '/mark-as-unread');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => {
            console.error(err);
            location.reload();
        });
    }

    // Confirm Delete via SweetAlert2
    function confirmDelete(id) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Notifikasi?',
                text: 'Notifikasi yang dihapus tidak dapat dipulihkan kembali.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold text-xs',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold text-xs'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executeDelete(id);
                }
            });
        } else {
            if (confirm('Yakin ingin menghapus notifikasi ini?')) {
                executeDelete(id);
            }
        }
    }

    function executeDelete(id) {
        fetch(BASE_NOTIF_URL + '/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: '_method=DELETE'
        })
        .then(res => res.json())
        .then(data => {
            const card = document.getElementById('notif-card-' + id);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => card.remove(), 200);
            } else {
                location.reload();
            }
        })
        .catch(err => {
            console.error(err);
            location.reload();
        });
    }
</script>
@endpush
