<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Sertifikat — Admin Dongiv</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; }

        .page { max-width: 1400px; margin: 0 auto; padding: 32px 24px; }
        .page-title { font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
        .page-sub { font-size: 14px; color: #64748b; margin-bottom: 28px; }

        /* Stats */
        .stats { display: grid; grid-template-columns: repeat(6, 1fr); gap: 14px; margin-bottom: 28px; }
        .stat { background: #fff; border-radius: 14px; padding: 18px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04); text-align: center; }
        .stat-num { font-size: 28px; font-weight: 800; color: #1d4ed8; }
        .stat-label { font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }

        /* Filter */
        .filter-bar { background: #fff; border-radius: 12px; padding: 14px 18px; margin-bottom: 18px; display: flex; gap: 12px; border: 1px solid #e2e8f0; align-items: center; flex-wrap: wrap; }
        .filter-bar input, .filter-bar select { border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-family: inherit; font-size: 13px; outline: none; }
        .filter-bar input:focus, .filter-bar select:focus { border-color: #3b82f6; }
        .btn-filter { padding: 9px 18px; border-radius: 8px; font-family: inherit; font-size: 13px; font-weight: 600; cursor: pointer; }
        .btn-filter-primary { background: #1d4ed8; color: #fff; border: none; }
        .btn-activate-all { background: #16a34a; color: #fff; border: none; margin-left: auto; padding: 9px 16px; border-radius: 8px; font-family: inherit; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; }
        .btn-activate-all:hover { background: #15803d; }

        /* Table */
        .table-wrap { background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap; }
        td { padding: 14px 16px; border-top: 1px solid #f1f5f9; vertical-align: middle; font-size: 13px; }
        tr:hover td { background: #fafbff; }

        .cert-no { font-family: monospace; font-size: 12px; font-weight: 700; color: #1d4ed8; background: #eff6ff; padding: 3px 8px; border-radius: 6px; }
        .badge { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge-active { background: #dcfce7; color: #16a34a; }
        .badge-pending { background: #fef9c3; color: #ca8a04; }
        .badge-revoked { background: #fee2e2; color: #dc2626; }

        .actions { display: flex; gap: 6px; align-items: center; }
        .btn-sm { padding: 5px 11px; border-radius: 7px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: all 0.15s; white-space: nowrap; }
        .btn-view { background: #eff6ff; color: #1d4ed8; }
        .btn-view:hover { background: #dbeafe; }
        .btn-dl { background: #f0fdf4; color: #16a34a; }
        .btn-dl:hover { background: #dcfce7; }
        .btn-activate { background: #ecfdf5; color: #059669; }
        .btn-activate:hover { background: #d1fae5; }
        .btn-revoke { background: #fff1f2; color: #be123c; }
        .btn-revoke:hover { background: #ffe4e6; }

        .pagination { padding: 16px 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b; }

        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        @media (max-width: 1024px) { .stats { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 600px) { .stats { grid-template-columns: repeat(2, 1fr); } }
    </style>
</head>
<body>
@extends('admin.layouts.master')
@section('content')

<div class="page">
    <h1 class="page-title">🎓 Manajemen Sertifikat Relawan</h1>
    <p class="page-sub">Kelola, aktivasi, dan pantau semua sertifikat digital relawan.</p>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats">
        <div class="stat"><div class="stat-num">{{ $totalIssued }}</div><div class="stat-label">Total Diterbitkan</div></div>
        <div class="stat"><div class="stat-num" style="color:#16a34a;">{{ $totalActive }}</div><div class="stat-label">Aktif</div></div>
        <div class="stat"><div class="stat-num" style="color:#ca8a04;">{{ $totalPending }}</div><div class="stat-label">Pending</div></div>
        <div class="stat"><div class="stat-num" style="color:#dc2626;">{{ $totalRevoked }}</div><div class="stat-label">Dicabut</div></div>
        <div class="stat"><div class="stat-num">{{ $thisMonth }}</div><div class="stat-label">Bulan Ini</div></div>
        <div class="stat"><div class="stat-num">{{ $uniqueVolunteers }}</div><div class="stat-label">Relawan Unik</div></div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.certificates.index') }}" class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, nomor, campaign...">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Dicabut</option>
        </select>
        <button type="submit" class="btn-filter btn-filter-primary"><i class="fas fa-search"></i> Cari</button>
        <a href="{{ route('admin.certificates.index') }}" class="btn-filter" style="background:#f1f5f9; color:#475569; border:none;">Reset</a>

        {{-- Activate All Expired --}}
        <form action="{{ route('admin.certificates.activate-expired') }}" method="POST" style="margin-left:auto;">
            @csrf
            <button type="submit" class="btn-activate-all" onclick="return confirm('Aktifkan semua sertifikat yang kampanye-nya sudah selesai?')">
                <i class="fas fa-magic"></i> Aktifkan Semua yang Sudah Selesai
            </button>
        </form>
    </form>

    {{-- Table --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nomor Sertifikat</th>
                    <th>Relawan</th>
                    <th>Campaign</th>
                    <th>Level</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Tanggal Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificates as $cert)
                <tr>
                    <td><span class="cert-no">{{ $cert->certificate_number }}</span></td>
                    <td>
                        <div style="font-weight:600; color:#1e293b;">{{ $cert->volunteer_name }}</div>
                        <div style="font-size:11px; color:#94a3b8;">ID: {{ $cert->volunteer_id }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600; max-width:180px; color:#1e293b;">{{ \Illuminate\Support\Str::limit($cert->campaign_name, 30) }}</div>
                        <div style="font-size:11px; color:#94a3b8;">{{ $cert->category }}</div>
                    </td>
                    <td style="white-space:nowrap;">{{ $cert->volunteer_level }}</td>
                    <td style="font-weight:700; color:#1d4ed8;">{{ $cert->total_volunteer_hours }}j</td>
                    <td>
                        @php $badgeClass = match($cert->status) { 'active'=>'badge-active', 'pending'=>'badge-pending', 'revoked'=>'badge-revoked', default=>'' }; @endphp
                        <span class="badge {{ $badgeClass }}">{{ $cert->status_label }}</span>
                    </td>
                    <td style="white-space:nowrap; font-size:12px;">{{ optional($cert->issue_date)->format('d M Y') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.certificates.show', $cert->id) }}" class="btn-sm btn-view" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.certificates.download', $cert->id) }}" class="btn-sm btn-dl" title="Download PDF" target="_blank">
                                <i class="fas fa-download"></i>
                            </a>
                            @if($cert->status === 'pending')
                                <form action="{{ route('admin.certificates.activate', $cert->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-activate" title="Aktifkan">
                                        <i class="fas fa-check"></i> Aktifkan
                                    </button>
                                </form>
                            @endif
                            @if($cert->status !== 'revoked')
                                <form action="{{ route('admin.certificates.revoke', $cert->id) }}" method="POST" style="display:inline"
                                      onsubmit="return confirm('Cabut sertifikat {{ $cert->certificate_number }}?')">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-revoke" title="Cabut">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:60px; color:#94a3b8;">
                        <i class="fas fa-certificate" style="font-size:40px; margin-bottom:12px; display:block; opacity:0.3"></i>
                        Belum ada sertifikat diterbitkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">
            <span>Menampilkan {{ $certificates->firstItem() ?? 0 }}–{{ $certificates->lastItem() ?? 0 }} dari {{ $certificates->total() }} sertifikat</span>
            {{ $certificates->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@endsection
