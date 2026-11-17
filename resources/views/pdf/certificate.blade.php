<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; size: A4 landscape; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Serif', serif; background: #fff; width: 297mm; height: 210mm; overflow: hidden; }

        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 50%, #f0f7ff 100%);
            display: flex;
            flex-direction: column;
        }

        /* Outer border */
        .border-outer {
            position: absolute;
            inset: 8mm;
            border: 3px solid #1d4ed8;
            border-radius: 4mm;
        }
        .border-inner {
            position: absolute;
            inset: 10mm;
            border: 1px solid rgba(29,78,216,0.3);
            border-radius: 3mm;
        }

        /* Gold accent corners */
        .corner { position: absolute; width: 20mm; height: 20mm; }
        .corner-tl { top: 8mm; left: 8mm; border-top: 5px solid #d4a851; border-left: 5px solid #d4a851; }
        .corner-tr { top: 8mm; right: 8mm; border-top: 5px solid #d4a851; border-right: 5px solid #d4a851; }
        .corner-bl { bottom: 8mm; left: 8mm; border-bottom: 5px solid #d4a851; border-left: 5px solid #d4a851; }
        .corner-br { bottom: 8mm; right: 8mm; border-bottom: 5px solid #d4a851; border-right: 5px solid #d4a851; }

        /* Top gradient stripe */
        .top-stripe {
            height: 12mm;
            background: linear-gradient(90deg, #1d4ed8 0%, #3b82f6 50%, #06b6d4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .top-stripe span { color: #fff; font-size: 11pt; letter-spacing: 5px; text-transform: uppercase; font-weight: bold; }

        /* Content area */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 20mm;
            position: relative;
            z-index: 2;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 120pt;
            color: rgba(29,78,216,0.04);
            font-weight: 900;
            z-index: 1;
            user-select: none;
            pointer-events: none;
        }

        .cert-title { font-size: 26pt; color: #1e293b; letter-spacing: 4px; text-transform: uppercase; margin-bottom: 3mm; text-align: center; }
        .cert-subtitle { font-size: 9pt; color: #64748b; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 6mm; text-align: center; }
        .divider { width: 80mm; height: 1px; background: linear-gradient(to right, transparent, #d4a851, transparent); margin: 0 auto 4mm; }
        .presented { font-size: 9pt; color: #64748b; font-style: italic; margin-bottom: 2mm; text-align: center; }
        .volunteer-name { font-size: 30pt; color: #1d4ed8; text-align: center; margin-bottom: 4mm; font-weight: bold; }
        .cert-desc { font-size: 10pt; color: #475569; text-align: center; line-height: 1.6; margin-bottom: 5mm; }
        .campaign-name { font-size: 14pt; color: #1e293b; font-weight: bold; }

        /* Info row */
        .info-row { display: flex; justify-content: center; gap: 12mm; margin-bottom: 6mm; }
        .info-item { text-align: center; }
        .info-label { font-size: 7pt; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 1mm; }
        .info-val { font-size: 9pt; font-weight: bold; color: #1e293b; }

        /* Bottom strip */
        .bottom-strip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4mm 20mm;
            border-top: 1px solid rgba(29,78,216,0.15);
        }

        .cert-no-wrap { text-align: left; }
        .cert-no-label { font-size: 7pt; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
        .cert-no-val { font-size: 9pt; font-weight: bold; color: #1d4ed8; font-family: 'DejaVu Sans Mono', monospace; }

        .qr-wrap { text-align: center; }
        .qr-wrap img { width: 20mm; height: 20mm; }
        .qr-label { font-size: 6pt; color: #94a3b8; margin-top: 1mm; }

        .signature-wrap { text-align: right; }
        .sig-line { width: 40mm; height: 1px; background: #1e293b; margin-bottom: 2mm; margin-left: auto; }
        .sig-name { font-size: 9pt; font-weight: bold; color: #1e293b; }
        .sig-title { font-size: 7pt; color: #64748b; }
    </style>
</head>
<body>
<div class="page">
    <!-- Borders -->
    <div class="border-outer"></div>
    <div class="border-inner"></div>
    <!-- Corners -->
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>
    <!-- Watermark -->
    <div class="watermark">DGV</div>

    <!-- Top stripe -->
    <div class="top-stripe">
        <span>🤝 Dongiv — Platform Donasi &amp; Relawan</span>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="cert-title">Sertifikat Penghargaan</div>
        <div class="cert-subtitle">Certificate of Appreciation</div>
        <div class="divider"></div>

        <div class="presented">Diberikan kepada</div>
        <div class="volunteer-name">{{ $certificate->volunteer_name }}</div>
        <div class="cert-desc">
            Sebagai bentuk apresiasi atas partisipasi aktif sebagai relawan dalam kegiatan:<br>
            <span class="campaign-name">"{{ $certificate->campaign_name }}"</span>
        </div>

        <div class="info-row">
            <div class="info-item">
                <div class="info-label">Kategori</div>
                <div class="info-val">{{ $certificate->category ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Lokasi</div>
                <div class="info-val">{{ $certificate->location ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Periode</div>
                <div class="info-val">
                    {{ optional($certificate->start_date)->format('d M Y') }} – {{ optional($certificate->end_date)->format('d M Y') }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Level Relawan</div>
                <div class="info-val">{{ $certificate->volunteer_level }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Jam</div>
                <div class="info-val">{{ $certificate->total_volunteer_hours }} Jam</div>
            </div>
        </div>
    </div>

    <!-- Bottom strip -->
    <div class="bottom-strip">
        <div class="cert-no-wrap">
            <div class="cert-no-label">Nomor Sertifikat</div>
            <div class="cert-no-val">{{ $certificate->certificate_number }}</div>
            <div class="cert-no-label" style="margin-top:1mm;">Diterbitkan: {{ optional($certificate->issue_date)->format('d M Y') }}</div>
        </div>

        <div class="qr-wrap">
            <img src="{{ $certificate->qr_data_uri }}" alt="QR">
            <div class="qr-label">Pindai untuk verifikasi</div>
        </div>

        <div class="signature-wrap">
            <div class="sig-line"></div>
            <div class="sig-name">Tim Dongiv</div>
            <div class="sig-title">Platform Donasi &amp; Relawan</div>
        </div>
    </div>
</div>
</body>
</html>
