<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sertifikat {{ $certificate->certificate_number }} — Admin Dongiv</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; color: #1e293b; }

        .page { max-width: 1200px; margin: 0 auto; padding: 32px 24px; }
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: #64748b; font-size: 13px; font-weight: 600; text-decoration: none; margin-bottom: 20px; transition: color 0.15s; }
        .back-link:hover { color: #1d4ed8; }

        .header-bar { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
        .page-title { font-size: 24px; font-weight: 800; color: #0f172a; }
        .cert-code { font-family: monospace; font-size: 14px; font-weight: 700; color: #1d4ed8; background: #eff6ff; padding: 4px 10px; border-radius: 6px; display: inline-block; margin-top: 4px; }

        .action-btns { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .btn { padding: 9px 18px; border-radius: 8px; font-family: inherit; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: none; transition: all 0.15s; }
        .btn-primary { background: #1d4ed8; color: #fff; }
        .btn-primary:hover { background: #1e40af; }
        .btn-success { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }
        .btn-danger { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-outline { background: #fff; border: 1.5px solid #cbd5e1; color: #334155; }
        .btn-outline:hover { background: #f1f5f9; }

        .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-pending { background: #fef9c3; color: #854d0e; }
        .badge-revoked { background: #fee2e2; color: #991b1b; }

        .grid-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        @media(max-width: 900px) { .grid-layout { grid-template-columns: 1fr; } }

        .card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.04); overflow: hidden; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; font-weight: 700; font-size: 15px; color: #1e293b; display: flex; align-items: center; justify-content: space-between; }
        .card-body { padding: 20px; }

        /* Preview container */
        .cert-preview {
            border: 2px dashed #cbd5e1; border-radius: 16px; padding: 32px 24px; text-align: center;
            background: radial-gradient(circle at 50% 50%, #ffffff 0%, #f8fafc 100%);
            position: relative;
        }
        .cert-preview-inner {
            border: 2px solid #f59e0b; padding: 28px 20px; border-radius: 12px; background: #fff;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.08);
        }
        .preview-title { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }
        .preview-sub { font-size: 10px; color: #b45309; text-transform: uppercase; font-weight: 700; letter-spacing: 2px; margin-top: 4px; }
        .preview-name { font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700; color: #1d4ed8; margin: 18px 0 6px 0; }
        .preview-campaign { font-size: 15px; font-weight: 700; color: #334155; margin-top: 6px; }

        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 500; }
        .info-value { font-weight: 600; color: #1e293b; text-align: right; }

        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 18px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    </style>
</head>
<body>
    <div class="page">
        <a href="{{ route('admin.certificates.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Sertifikat
        </a>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="header-bar">
            <div>
                <h1 class="page-title">Detail Sertifikat</h1>
                <span class="cert-code">{{ $certificate->certificate_number }}</span>
                @php
                    $badgeClass = match($certificate->status) {
                        'active' => 'badge-active',
                        'pending' => 'badge-pending',
                        'revoked' => 'badge-revoked',
                        default => ''
                    };
                @endphp
                <span class="badge {{ $badgeClass }}" style="margin-left: 8px;">
                    <i class="fas fa-circle" style="font-size: 8px;"></i> {{ $certificate->status_label }}
                </span>
            </div>

            <div class="action-btns">
                <a href="{{ route('admin.certificates.download', $certificate->id) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-download"></i> Unduh PDF
                </a>
                <a href="{{ route('certificate.verify', $certificate->certificate_number) }}" class="btn btn-outline" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Verifikasi Publik
                </a>
                @if($certificate->status === 'pending')
                    <form action="{{ route('admin.certificates.activate', $certificate->id) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Aktifkan Sertifikat
                        </button>
                    </form>
                @endif
                @if($certificate->status !== 'revoked')
                    <form action="{{ route('admin.certificates.revoke', $certificate->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin mencabut sertifikat ini?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban"></i> Cabut
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid-layout">
            <!-- Left: Certificate Preview -->
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-certificate text-primary" style="margin-right: 6px; color: #1d4ed8;"></i> Pratinjau Sertifikat</span>
                    <span style="font-size: 12px; color: #64748b; font-weight: normal;">Format Lanskap A4</span>
                </div>
                <div class="card-body">
                    <div class="cert-preview">
                        <div class="cert-preview-inner">
                            <div style="font-size: 28px; color: #1d4ed8; margin-bottom: 8px;">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div style="font-size: 13px; font-weight: 800; color: #1e293b; margin-bottom: 12px; letter-spacing: 1px;">DONGIV INDONESIA</div>
                            
                            <h2 class="preview-title">Sertifikat Penghargaan</h2>
                            <div class="preview-sub">Certificate of Appreciation</div>

                            <p style="font-size: 12px; color: #64748b; font-style: italic; margin-top: 16px;">Diberikan dengan bangga kepada:</p>
                            <h3 class="preview-name">{{ $certificate->volunteer_name }}</h3>
                            
                            <p style="font-size: 12px; color: #475569; max-width: 500px; margin: 0 auto; line-height: 1.6;">
                                Atas partisipasi aktif dan kontribusi luar biasa sebagai relawan dalam program:
                            </p>
                            <div class="preview-campaign">"{{ $certificate->campaign_name }}"</div>

                            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-around; font-size: 11px; color: #64748b;">
                                <div>
                                    <span style="display: block; font-weight: 700; color: #1e293b;">{{ $certificate->total_volunteer_hours }} Jam</span>
                                    <span>Total Kontribusi</span>
                                </div>
                                <div>
                                    <span style="display: block; font-weight: 700; color: #1e293b;">{{ $certificate->volunteer_level }}</span>
                                    <span>Tingkat Relawan</span>
                                </div>
                                <div>
                                    <span style="display: block; font-weight: 700; color: #1e293b;">{{ optional($certificate->issue_date)->format('d M Y') }}</span>
                                    <span>Tanggal Terbit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Details Card -->
            <div class="card">
                <div class="card-header">
                    <span><i class="fas fa-info-circle" style="margin-right: 6px; color: #1d4ed8;"></i> Informasi Lengkap</span>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Nomor Sertifikat</span>
                        <span class="info-value" style="font-family: monospace;">{{ $certificate->certificate_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama Relawan</span>
                        <span class="info-value">{{ $certificate->volunteer_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email Relawan</span>
                        <span class="info-value">{{ $certificate->volunteer->email ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama Kampanye</span>
                        <span class="info-value">{{ $certificate->campaign_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kategori</span>
                        <span class="info-value">{{ $certificate->category ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Lokasi</span>
                        <span class="info-value">{{ $certificate->location ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Periode Kegiatan</span>
                        <span class="info-value">
                            {{ optional($certificate->start_date)->format('d M Y') }} - {{ optional($certificate->end_date)->format('d M Y') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Jam</span>
                        <span class="info-value">{{ $certificate->total_volunteer_hours }} Jam</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tingkat Relawan</span>
                        <span class="info-value">{{ $certificate->volunteer_level }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Diterbitkan</span>
                        <span class="info-value">{{ optional($certificate->issue_date)->format('d F Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">URL Verifikasi Publik</span>
                        <span class="info-value">
                            <a href="{{ route('certificate.verify', $certificate->certificate_number) }}" target="_blank" style="color: #1d4ed8; text-decoration: none; word-break: break-all;">
                                Buka Link <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
