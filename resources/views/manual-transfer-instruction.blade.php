<x-app title="Instruksi Pembayaran | DonGiv">
    <style>
        :root {
            --primary: #1D4ED8;
            --dark: #0F172A;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-400: #94A3B8;
            --slate-600: #475569;
            --slate-900: #0F172A;
        }

        .container-custom {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .instruction-card {
            background: white;
            border-radius: 32px;
            padding: 48px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            border: 1px solid var(--slate-100);
        }

        .bank-info-box {
            background: var(--slate-50);
            border: 1.5px solid var(--slate-100);
            border-radius: 24px;
            padding: 32px;
            margin: 32px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .bank-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 900;
            color: var(--primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .copy-btn {
            background: white;
            border: 1px solid var(--slate-200);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 800;
            color: var(--primary);
            cursor: pointer;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .upload-area {
            border: 2px dashed var(--slate-200);
            border-radius: 24px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--slate-50);
        }

        .upload-area:hover {
            border-color: var(--primary);
            background: #EFF6FF;
        }

        .upload-icon {
            font-size: 40px;
            color: var(--slate-400);
            margin-bottom: 16px;
        }

        .btn-submit-proof {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 18px;
            font-weight: 800;
            width: 100%;
            cursor: pointer;
            margin-top: 24px;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(29, 78, 216, 0.2);
        }

        .btn-submit-proof:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(29, 78, 216, 0.3);
        }

        .step-item {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .step-dot {
            width: 24px;
            height: 24px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
            flex-shrink: 0;
        }
    </style>

    <div class="container-custom">
        <div class="instruction-card">
            <div style="text-align: center; margin-bottom: 40px;">
                <span style="background: #FFF7ED; color: #C2410C; padding: 6px 16px; border-radius: 100px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Menunggu Pembayaran</span>
                <h1 style="font-size: 32px; font-weight: 900; color: var(--dark); margin: 16px 0 8px;">Selesaikan Donasi Anda</h1>
                <p style="color: var(--slate-400); font-weight: 500;">Silakan transfer sesuai nominal untuk memverifikasi donasi</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                <!-- LEFT: INFO -->
                <div>
                    <div style="background: var(--slate-50); border-radius: 20px; padding: 24px; margin-bottom: 32px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="font-size: 12px; font-weight: 700; color: var(--slate-400);">ID TRANSAKSI</span>
                            <span style="font-size: 12px; font-weight: 800; color: var(--dark);">{{ $transaction->order_id }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 12px; font-weight: 700; color: var(--slate-400);">TOTAL DONASI</span>
                            <span style="font-size: 20px; font-weight: 900; color: var(--primary);">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 20px;">Langkah Pembayaran:</h3>
                    <div class="step-item">
                        <div class="step-dot">1</div>
                        <p style="font-size: 14px; font-weight: 600; color: var(--slate-600);">Transfer ke rekening yang tertera di samping.</p>
                    </div>
                    <div class="step-item">
                        <div class="step-dot">2</div>
                        <p style="font-size: 14px; font-weight: 600; color: var(--slate-600);">Pastikan nominal transfer sesuai (hingga digit terakhir).</p>
                    </div>
                    <div class="step-item">
                        <div class="step-dot">3</div>
                        <p style="font-size: 14px; font-weight: 600; color: var(--slate-600);">Upload foto bukti transfer pada kolom di samping.</p>
                    </div>
                    <div class="step-item">
                        <div class="step-dot">4</div>
                        <p style="font-size: 14px; font-weight: 600; color: var(--slate-600);">Tunggu tim admin melakukan verifikasi (maks 1x24 jam).</p>
                    </div>
                </div>

                <!-- RIGHT: BANK & UPLOAD -->
                <div>
                    <div class="bank-info-box">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div class="bank-logo">{{ substr($transaction->payment_method, 0, 3) }}</div>
                            <div>
                                <p style="margin: 0; font-size: 11px; font-weight: 700; color: var(--slate-400);">NO. REKENING</p>
                                <p style="margin: 0; font-size: 18px; font-weight: 900; color: var(--dark);">1234567890</p>
                                <p style="margin: 0; font-size: 13px; font-weight: 600; color: var(--slate-600);">a/n DonGiv Indonesia</p>
                            </div>
                        </div>
                        <button class="copy-btn">Salin</button>
                    </div>

                    <form action="{{ route('donation.upload.proof', $transaction->order_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-area" onclick="document.getElementById('proofInput').click()">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <h4 style="font-size: 16px; font-weight: 800; color: var(--dark); margin-bottom: 4px;">Klik untuk Upload Bukti</h4>
                            <p style="font-size: 12px; color: var(--slate-400); font-weight: 500;">Format JPG, PNG (Maks 2MB)</p>
                            <input type="file" name="proof" id="proofInput" style="display: none;" onchange="updateFileName(this)">
                            <p id="fileName" style="margin-top: 12px; font-size: 13px; font-weight: 700; color: var(--primary); display: none;"></p>
                        </div>

                        <button type="submit" class="btn-submit-proof">
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileNameDisp = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileNameDisp.innerText = 'File terpilih: ' + input.files[0].name;
                fileNameDisp.style.display = 'block';
            }
        }
    </script>
</x-app>