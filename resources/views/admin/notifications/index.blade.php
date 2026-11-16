@extends('admin.layouts.master')

@section('title', 'Pusat Notifikasi Admin')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ============================================================
   ADMIN NOTIFICATION CENTER — Dongiv Design System
   ============================================================ */
.notif-page { font-family: 'Poppins', sans-serif; }

/* ---- Page Header ---- */
.notif-header {
    background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
    border-radius: 1.25rem;
    padding: 2rem 2.5rem;
    color: #fff;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 8px 32px rgba(29,78,216,.25);
}
.notif-header h1 { font-size: 1.75rem; font-weight: 700; margin: 0; }
.notif-header p  { font-size: 0.9rem; opacity: .85; margin: .25rem 0 0; }
.notif-header-actions { display: flex; gap: .75rem; }
.btn-ghost {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    color: #fff;
    padding: .5rem 1.1rem;
    border-radius: .6rem;
    cursor: pointer;
    font-size: .85rem;
    font-family: 'Poppins', sans-serif;
    transition: background .2s;
    text-decoration: none;
}
.btn-ghost:hover { background: rgba(255,255,255,.3); }

/* ---- Stats Bar ---- */
.stats-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.stat-card {
    background: #fff;
    border-radius: 1rem;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .75rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.stat-icon {
    width: 42px; height: 42px;
    border-radius: .75rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}
.stat-card .label { font-size: .75rem; color: #6b7280; font-weight: 500; }
.stat-card .value { font-size: 1.4rem; font-weight: 700; color: #111827; line-height: 1; }
.stat-blue   { background: #dbeafe; }
.stat-red    { background: #fee2e2; }
.stat-green  { background: #d1fae5; }
.stat-orange { background: #ffedd5; }

/* ---- Toolbar ---- */
.toolbar {
    background: #fff;
    border-radius: 1rem;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    margin-bottom: 1.25rem;
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    align-items: center;
}
.toolbar input[type=text], .toolbar input[type=date], .toolbar select {
    border: 1px solid #e5e7eb;
    border-radius: .6rem;
    padding: .5rem .85rem;
    font-family: 'Poppins', sans-serif;
    font-size: .85rem;
    color: #374151;
    outline: none;
    transition: border .2s;
}
.toolbar input[type=text]:focus,
.toolbar input[type=date]:focus,
.toolbar select:focus { border-color: #3b82f6; }

/* ---- Category Tabs ---- */
.cat-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
    background: #f3f6fc;
    padding: .5rem;
    border-radius: .75rem;
    margin-bottom: 1.25rem;
}
.cat-tab {
    padding: .45rem 1rem;
    border-radius: .5rem;
    font-size: .82rem;
    font-weight: 500;
    cursor: pointer;
    color: #6b7280;
    border: none;
    background: transparent;
    font-family: 'Poppins', sans-serif;
    transition: all .2s;
}
.cat-tab:hover { background: rgba(59,130,246,.1); color: #1d4ed8; }
.cat-tab.active {
    background: #1d4ed8;
    color: #fff;
    box-shadow: 0 2px 8px rgba(29,78,216,.25);
}
.cat-tab .badge {
    background: rgba(255,255,255,.25);
    border-radius: 999px;
    padding: .05rem .4rem;
    font-size: .72rem;
    margin-left: .3rem;
}
.cat-tab.active .badge { background: rgba(255,255,255,.3); }
.cat-tab:not(.active) .badge { background: #e5e7eb; color: #374151; }

/* ---- Notification Card ---- */
.notif-card {
    background: #fff;
    border-radius: 1rem;
    padding: 1.25rem 1.5rem;
    margin-bottom: .9rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border-left: 5px solid #d1d5db;
    transition: transform .2s, box-shadow .2s;
    position: relative;
}
.notif-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
}
.notif-card.unread { background: linear-gradient(90deg, #f0f9ff 0%, #fff 30%); }
.notif-card.priority-high   { border-left-color: #ef4444; }
.notif-card.priority-medium { border-left-color: #f59e0b; }
.notif-card.priority-low    { border-left-color: #10b981; }

.notif-icon {
    font-size: 2rem;
    min-width: 50px;
    text-align: center;
    line-height: 1;
}
.notif-body { flex: 1; min-width: 0; }
.notif-body .notif-title {
    font-size: 1rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 .3rem;
}
.notif-body .notif-message {
    font-size: .875rem;
    color: #4b5563;
    margin: 0 0 .5rem;
    line-height: 1.5;
}
.notif-meta {
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-wrap: wrap;
}
.notif-time { font-size: .75rem; color: #9ca3af; }
.badge-cat {
    font-size: .7rem;
    padding: .15rem .6rem;
    border-radius: 999px;
    font-weight: 600;
}
.badge-kampanye    { background:#dbeafe; color:#1d4ed8; }
.badge-donasi      { background:#d1fae5; color:#065f46; }
.badge-relawan     { background:#ede9fe; color:#5b21b6; }
.badge-laporan     { background:#fee2e2; color:#991b1b; }
.badge-smart_matching { background:#fef3c7; color:#92400e; }
.badge-sistem      { background:#f3f4f6; color:#374151; }
.badge-priority {
    font-size: .7rem;
    padding: .15rem .5rem;
    border-radius: 999px;
    font-weight: 600;
}
.badge-high   { background:#fee2e2; color:#991b1b; }
.badge-medium { background:#ffedd5; color:#92400e; }
.badge-low    { background:#d1fae5; color:#065f46; }
.unread-dot {
    width: 9px; height: 9px;
    background: #3b82f6;
    border-radius: 50%;
    position: absolute;
    top: 1rem; right: 1rem;
}

.notif-actions {
    display: flex;
    flex-direction: column;
    gap: .4rem;
    min-width: 130px;
}
.action-btn {
    display: block;
    padding: .4rem .8rem;
    border-radius: .5rem;
    font-size: .8rem;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    border: 1px solid #e5e7eb;
    background: #f9fafb;
    color: #374151;
    text-align: center;
    text-decoration: none;
    transition: background .15s, color .15s;
}
.action-btn:hover { background: #e5e7eb; }
.action-btn.primary { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe; }
.action-btn.primary:hover { background: #bfdbfe; }
.action-btn.danger  { background: #fee2e2; color: #dc2626; border-color: #fecaca; }
.action-btn.danger:hover  { background: #fecaca; }

/* ---- Empty State ---- */
.empty-state {
    text-align: center;
    padding: 4rem 1rem;
    background: #fff;
    border-radius: 1.25rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.empty-state .empty-icon { font-size: 5rem; opacity: .3; margin-bottom: 1rem; }
.empty-state h3 { font-size: 1.3rem; font-weight: 600; color: #374151; margin: 0 0 .5rem; }
.empty-state p  { color: #9ca3af; font-size: .9rem; margin: 0 0 1.5rem; }

/* ---- Alert ---- */
.alert-success {
    background: #d1fae5;
    border-left: 4px solid #10b981;
    color: #065f46;
    padding: .75rem 1.2rem;
    border-radius: .75rem;
    margin-bottom: 1rem;
    font-size: .875rem;
}

/* ---- Pagination ---- */
.pagination-wrap { display: flex; justify-content: center; margin-top: 1.5rem; }
.pagination-wrap .pagination { gap: .35rem; }
.pagination-wrap .page-link {
    border-radius: .5rem !important;
    border: 1px solid #e5e7eb !important;
    color: #374151 !important;
    font-family: 'Poppins', sans-serif;
    font-size: .85rem;
}
.pagination-wrap .page-item.active .page-link {
    background: #1d4ed8 !important;
    border-color: #1d4ed8 !important;
    color: #fff !important;
}

/* ---- Activity Feed sidebar ---- */
.activity-feed {
    background: #fff;
    border-radius: 1rem;
    padding: 1.25rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.activity-feed h3 { font-size: 1rem; font-weight: 600; margin: 0 0 1rem; color: #111827; }
.feed-item {
    display: flex;
    gap: .75rem;
    align-items: flex-start;
    padding: .6rem 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: .8rem;
}
.feed-item:last-child { border: none; }
.feed-time { color: #9ca3af; white-space: nowrap; font-size: .75rem; min-width: 38px; }
.feed-text { color: #374151; }

/* ---- Layout ---- */
.two-col { display: grid; grid-template-columns: 1fr 300px; gap: 1.25rem; align-items: start; }
@media(max-width:900px) { .two-col { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="notif-page">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="notif-header">
        <div>
            <h1>🔔 Pusat Notifikasi Admin</h1>
            <p>Pantau seluruh aktivitas penting platform Dongiv secara real‑time.</p>
        </div>
        <div class="notif-header-actions">
            <form method="POST" action="{{ route('admin.notifications.markAllAsRead') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn-ghost">✅ Tandai Semua Dibaca</button>
            </form>
            <a href="{{ route('admin.notifications.export') }}" class="btn-ghost">📥 Export CSV</a>
        </div>
    </div>

    {{-- ===== STATS BAR ===== --}}
    <div class="stats-bar">
        <div class="stat-card">
            <div class="stat-icon stat-blue">🔔</div>
            <div>
                <div class="label">Total Notifikasi</div>
                <div class="value">{{ $stats['all'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-red">📭</div>
            <div>
                <div class="label">Belum Dibaca</div>
                <div class="value">{{ $stats['total_unread'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-orange">🚨</div>
            <div>
                <div class="label">Prioritas Tinggi</div>
                <div class="value">{{ $stats['high_priority'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-green">📅</div>
            <div>
                <div class="label">Hari Ini</div>
                <div class="value">{{ $stats['today'] }}</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- ===== TOOLBAR (SEARCH + FILTER) ===== --}}
    <div class="toolbar">
        <input type="text" id="searchInput" placeholder="🔍  Cari notifikasi, nama kampanye, pengguna..." value="{{ $search }}" style="flex:1;min-width:220px">
        <select id="priorityFilter">
            <option value="">Semua Prioritas</option>
            <option value="high"   {{ $priority == 'high'   ? 'selected' : '' }}>🔴 Tinggi</option>
            <option value="medium" {{ $priority == 'medium' ? 'selected' : '' }}>🟡 Sedang</option>
            <option value="low"    {{ $priority == 'low'    ? 'selected' : '' }}>🟢 Rendah</option>
        </select>
        <input type="date" id="dateFrom" value="{{ $dateFrom }}" title="Dari Tanggal">
        <input type="date" id="dateTo"   value="{{ $dateTo }}"   title="Sampai Tanggal">
        <button id="applyFilters" onclick="applyFilters()"
            style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#fff;border:none;padding:.5rem 1.2rem;border-radius:.6rem;cursor:pointer;font-family:'Poppins',sans-serif;font-size:.85rem;">
            Terapkan
        </button>
    </div>

    {{-- ===== CATEGORY TABS ===== --}}
    <div class="cat-tabs">
        @php
            $cats = [
                'semua'         => ['label' => 'Semua',         'icon' => '📋', 'count' => $stats['all']],
                'kampanye'      => ['label' => 'Kampanye',      'icon' => '📢', 'count' => $stats['kampanye']],
                'donasi'        => ['label' => 'Donasi',        'icon' => '💰', 'count' => $stats['donasi']],
                'relawan'       => ['label' => 'Relawan',       'icon' => '🤝', 'count' => $stats['relawan']],
                'laporan'       => ['label' => 'Laporan',       'icon' => '🚩', 'count' => $stats['laporan']],
                'smart_matching'=> ['label' => 'Smart Matching','icon' => '⭐', 'count' => $stats['smart_matching']],
                'sistem'        => ['label' => 'Sistem',        'icon' => '⚙️', 'count' => $stats['sistem']],
            ];
        @endphp
        @foreach($cats as $key => $c)
            <button class="cat-tab {{ $category == $key ? 'active' : '' }}"
                    data-category="{{ $key }}"
                    onclick="setCategory('{{ $key }}')">
                {{ $c['icon'] }} {{ $c['label'] }}
                <span class="badge">{{ $c['count'] }}</span>
            </button>
        @endforeach
    </div>

    {{-- ===== TWO-COLUMN LAYOUT ===== --}}
    <div class="two-col">

        {{-- === LEFT: NOTIFICATIONS LIST === --}}
        <div>
            @if($notifications->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">🔕</div>
                    <h3>Belum Ada Aktivitas Baru</h3>
                    <p>Semua aktivitas platform Dongiv akan muncul di sini saat ada kampanye, donasi, atau pendaftaran relawan baru.</p>
                    <a href="{{ route('admin.dashboard') }}"
                       style="display:inline-block;background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:#fff;padding:.65rem 1.5rem;border-radius:.75rem;text-decoration:none;font-weight:600;font-size:.9rem;">
                        Kembali ke Dashboard
                    </a>
                </div>
            @else
                @foreach($notifications as $notif)
                    <div class="notif-card priority-{{ $notif->priority }} {{ !$notif->is_read ? 'unread' : '' }}"
                         id="notif-{{ $notif->id }}">

                        @if(!$notif->is_read)
                            <div class="unread-dot"></div>
                        @endif

                        {{-- Icon --}}
                        <div class="notif-icon">{{ $notif->icon ?? '🔔' }}</div>

                        {{-- Body --}}
                        <div class="notif-body">
                            <p class="notif-title">{{ $notif->title }}</p>
                            <p class="notif-message">{{ $notif->message }}</p>
                            <div class="notif-meta">
                                <span class="notif-time">🕐 {{ $notif->created_at->diffForHumans() }}</span>
                                <span class="badge-cat badge-{{ $notif->category }}">
                                    {{ ucfirst(str_replace('_', ' ', $notif->category)) }}
                                </span>
                                <span class="badge-priority badge-{{ $notif->priority }}">
                                    {{ $notif->priority == 'high' ? '🔴 Tinggi' : ($notif->priority == 'medium' ? '🟡 Sedang' : '🟢 Rendah') }}
                                </span>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="notif-actions">
                            @if($notif->resolved_action_url && !str_contains(strtolower($notif->action_label ?? ''), 'setujui'))
                                <a href="{{ $notif->resolved_action_url }}" class="action-btn primary" target="_blank">
                                    {{ $notif->action_label ?? 'Lihat Detail' }}
                                </a>
                            @endif
                            @if($notif->secondary_action_url && !str_contains(strtolower($notif->secondary_action_label ?? ''), 'setujui'))
                                <a href="{{ $notif->secondary_action_url }}" class="action-btn primary" target="_blank">
                                    {{ $notif->secondary_action_label }}
                                </a>
                            @endif
                            @if($notif->is_read)
                                <button class="action-btn"
                                        onclick="markUnread({{ $notif->id }})">
                                    📌 Tandai Belum Dibaca
                                </button>
                            @else
                                <button class="action-btn"
                                        onclick="markRead({{ $notif->id }})">
                                    ✅ Tandai Dibaca
                                </button>
                            @endif
                            <button class="action-btn danger"
                                    onclick="deleteNotif({{ $notif->id }})">
                                🗑 Hapus
                            </button>
                        </div>
                    </div>
                @endforeach

                {{-- Pagination --}}
                <div class="pagination-wrap">
                    {{ $notifications->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        {{-- === RIGHT: ACTIVITY FEED === --}}
        <div>
            <div class="activity-feed">
                <h3>⚡ Aktivitas Terbaru</h3>
                @forelse($activityFeed as $item)
                    <div class="feed-item">
                        <span class="feed-time">{{ $item->created_at->format('H:i') }}</span>
                        <span class="feed-text">{{ $item->icon ?? '🔔' }} {{ $item->title }}</span>
                    </div>
                @empty
                    <p style="color:#9ca3af;font-size:.8rem;text-align:center;padding:1rem 0;">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>

    </div>{{-- end two-col --}}

</div>
@endsection

@push('scripts')
<script>
    const BASE_URL = '{{ url('/admin/notifications') }}';
    const CSRF    = '{{ csrf_token() }}';

    // ----- Category filter -----
    let activeCategory = '{{ $category }}';
    function setCategory(cat) {
        activeCategory = cat;
        document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
        document.querySelector('[data-category="'+cat+'"]').classList.add('active');
    }

    // ----- Apply all filters (redirect) -----
    function applyFilters() {
        const params = new URLSearchParams();
        params.set('category',  activeCategory);
        const priority = document.getElementById('priorityFilter').value;
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo   = document.getElementById('dateTo').value;
        const search   = document.getElementById('searchInput').value;
        if (priority) params.set('priority',  priority);
        if (dateFrom) params.set('date_from', dateFrom);
        if (dateTo)   params.set('date_to',   dateTo);
        if (search)   params.set('search',    search);
        window.location.href = BASE_URL + '?' + params.toString();
    }

    // Live search on Enter
    document.getElementById('searchInput').addEventListener('keydown', function(e){
        if (e.key === 'Enter') applyFilters();
    });

    // ----- AJAX helpers -----
    async function ajaxPost(url, method = 'POST') {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json',
                       'Content-Type': 'application/x-www-form-urlencoded' },
            body: method !== 'POST' ? '_method=' + method : ''
        });
        return res.json();
    }

    function markRead(id) {
        ajaxPost(BASE_URL + '/' + id + '/mark-as-read').then(() => location.reload());
    }
    function markUnread(id) {
        ajaxPost(BASE_URL + '/' + id + '/mark-as-unread').then(() => location.reload());
    }
    function deleteNotif(id) {
        if (!confirm('Yakin ingin menghapus notifikasi ini?')) return;
        ajaxPost(BASE_URL + '/' + id, 'DELETE').then(() => {
            document.getElementById('notif-' + id)?.remove();
        });
    }
</script>
@endpush
