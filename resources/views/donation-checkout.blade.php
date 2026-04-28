<x-app title="Donasi | DonGiv">
    
    <style>
        :root {
            --primary: #1D4ED8;
            --primary-hover: #1E40AF;
            --dark: #0F172A;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-400: #94A3B8;
            --slate-600: #475569;
            --slate-900: #0F172A;
        }

        .main-container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 48px 24px;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 48px;
        }

        .left-side {
            flex: 1 1 600px;
            max-width: 100%;
        }

        .right-side {
            flex: 0 0 400px;
            max-width: 100%;
        }

        @media (max-width: 1024px) {
            .main-container {
                flex-direction: column;
            }
            .right-side {
                flex: 1 1 auto;
                order: -1; /* Show summary on top for mobile */
            }
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: var(--dark);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            margin-top: 4px;
        }

        .section-header {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
        }

        .section-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
        }

        .section-header p {
            margin: 4px 0 0;
            font-size: 14px;
            color: var(--slate-400);
            font-weight: 500;
        }

        .card {
            background: white;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            border: 1px solid var(--slate-100);
            margin-bottom: 40px;
        }

        .amount-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .amount-btn {
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            font-weight: 700;
            color: var(--slate-600);
            transition: all 0.2s;
            cursor: pointer;
            background: white;
        }

        .amount-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .amount-btn.active {
            background: #EFF6FF;
            border-color: var(--primary);
            color: var(--primary);
        }

        .input-group {
            position: relative;
            margin-bottom: 24px;
        }

        .input-group span {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 700;
            color: var(--slate-400);
        }

        .input-custom {
            background: var(--slate-50);
            border: 1.5px solid var(--slate-100);
            border-radius: 12px;
            padding: 14px 18px;
            width: 100%;
            outline: none;
            transition: all 0.2s;
            font-weight: 600;
            box-sizing: border-box;
        }

        .input-custom:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.1);
        }

        .payment-method-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .payment-method-card {
            border: 1.5px solid var(--slate-100);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--slate-50);
            text-align: center;
        }

        .payment-method-card i { font-size: 20px; }
        .payment-method-card span { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }

        .payment-method-card.active {
            background: var(--dark);
            color: white;
            border-color: var(--dark);
        }

        .payment-option-item {
            background: var(--slate-50);
            border: 1.5px solid var(--slate-100);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .payment-option-item:hover {
            border-color: var(--primary);
        }

        .payment-option-item.active {
            border-color: var(--primary);
            background: #EFF6FF;
        }

        .payment-option-item .check-circle {
            width: 20px;
            height: 20px;
            border: 2px solid var(--slate-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payment-option-item.active .check-circle {
            border-color: var(--primary);
        }

        .payment-option-item .check-dot {
            width: 10px;
            height: 10px;
            background: var(--primary);
            border-radius: 50%;
            display: none;
        }

        .payment-option-item.active .check-dot {
            display: block;
        }

        .method-options {
            display: none;
        }

        .method-options.active {
            display: block;
        }

        .summary-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            position: sticky;
            top: 100px; /* Adjust for navbar */
        }

        .summary-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .summary-content {
            padding: 32px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 18px;
            font-weight: 800;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
            box-shadow: 0 8px 20px rgba(29, 78, 216, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(29, 78, 216, 0.4);
        }

        .progress-container {
            margin: 20px 0;
        }

        .progress-bar {
            height: 6px;
            background: var(--slate-100);
            border-radius: 3px;
            width: 100%;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 3px;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: var(--primary);
            color: white;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }
    </style>

    <div class="main-container">
        
        <!-- LEFT SIDE -->
        <div class="left-side">
            <form id="donationForm" method="POST" action="{{ route('donation.process') }}">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                <!-- STEP 1 -->
                <div class="section-header">
                    <div class="step-number">1</div>
                    <div>
                        <h2>Tentukan Nominal</h2>
                        <p>Berapapun yang Anda berikan akan sangat membantu</p>
                    </div>
                </div>

                <div class="amount-grid">
                    <div class="amount-btn" data-amount="25000">25rb</div>
                    <div class="amount-btn active" data-amount="50000">50rb</div>
                    <div class="amount-btn" data-amount="100000">100rb</div>
                    <div class="amount-btn" data-amount="250000">250rb</div>
                    <div class="amount-btn" data-amount="500000">500rb</div>
                    <div class="amount-btn" data-amount="1000000">1000rb</div>
                </div>

                <div class="input-group">
                    <span>Rp</span>
                    <input type="number" name="amount" id="custom_amount" value="50000" class="input-custom" style="padding-left: 45px;" placeholder="Nominal Donasi Lainnya...">
                </div>

                <!-- STEP 2 -->
                <div class="section-header" style="margin-top: 48px;">
                    <div class="step-number">2</div>
                    <div>
                        <h2>Informasi Donatur</h2>
                        <p>Lengkapi data diri Anda (Opsional)</p>
                    </div>
                </div>

                <div class="card">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                        <div class="input-group" style="margin: 0;">
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--slate-400); text-transform: uppercase; margin-bottom: 8px;">Nama Lengkap</label>
                            <input type="text" name="donor_name" value="{{ auth()->user()->name ?? '' }}" class="input-custom" placeholder="Nama Anda">
                        </div>
                        <div class="input-group" style="margin: 0;">
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--slate-400); text-transform: uppercase; margin-bottom: 8px;">Email / WhatsApp</label>
                            <input type="text" name="donor_email" value="{{ auth()->user()->email ?? '' }}" class="input-custom" placeholder="Untuk update berkala">
                        </div>
                    </div>

                    <div class="input-group">
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--slate-400); text-transform: uppercase; margin-bottom: 8px;">Doa atau Dukungan (Opsional)</label>
                        <textarea name="prayer" rows="3" class="input-custom" style="resize: none;" placeholder="Semoga lekas sembuh, Tuhan memberkati..."></textarea>
                    </div>

                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="anonymous" value="1" style="width: 18px; height: 18px; accent-color: var(--primary);">
                        <span style="font-size: 14px; font-weight: 700; color: var(--slate-600);">Sembunyikan nama saya (Anonim)</span>
                    </label>
                </div>

                <!-- STEP 3 -->
                <div class="section-header">
                    <div class="step-number">3</div>
                    <div>
                        <h2>Metode Pembayaran</h2>
                        <p>Pilih kanal pembayaran yang paling nyaman bagi Anda</p>
                    </div>
                </div>

                <div class="payment-method-grid">
                    <div class="payment-method-card active" data-target="ewallet">
                        <i class="fas fa-mobile-alt"></i>
                        <span>E-Wallet & QRIS</span>
                    </div>
                    <div class="payment-method-card" data-target="bank">
                        <i class="fas fa-university"></i>
                        <span>Transfer Bank</span>
                    </div>
                </div>

                <div class="card">
                    <!-- E-WALLET OPTIONS -->
                    <div id="ewallet-options" class="method-options active">
                        <div class="payment-option-item active" data-method="GoPay">
                            <span style="font-weight: 700; font-size: 14px;">GoPay / QRIS</span>
                            <div class="check-circle"><div class="check-dot"></div></div>
                        </div>
                        <div class="payment-option-item" data-method="OVO">
                            <span style="font-weight: 700; font-size: 14px;">OVO</span>
                            <div class="check-circle"><div class="check-dot"></div></div>
                        </div>
                        <div class="payment-option-item" data-method="DANA">
                            <span style="font-weight: 700; font-size: 14px;">DANA</span>
                            <div class="check-circle"><div class="check-dot"></div></div>
                        </div>
                        <div class="payment-option-item" data-method="LinkAja">
                            <span style="font-weight: 700; font-size: 14px;">LinkAja</span>
                            <div class="check-circle"><div class="check-dot"></div></div>
                        </div>
                    </div>

                    <!-- BANK OPTIONS -->
                    <div id="bank-options" class="method-options">
                        <div class="payment-option-item" data-method="BCA">
                            <span style="font-weight: 700; font-size: 14px;">Transfer BCA</span>
                            <div class="check-circle"><div class="check-dot"></div></div>
                        </div>
                        <div class="payment-option-item" data-method="Mandiri">
                            <span style="font-weight: 700; font-size: 14px;">Transfer Mandiri</span>
                            <div class="check-circle"><div class="check-dot"></div></div>
                        </div>
                        <div class="payment-option-item" data-method="BNI">
                            <span style="font-weight: 700; font-size: 14px;">Transfer BNI</span>
                            <div class="check-circle"><div class="check-dot"></div></div>
                        </div>
                        <div class="payment-option-item" data-method="BRI">
                            <span style="font-weight: 700; font-size: 14px;">Transfer BRI</span>
                            <div class="check-circle"><div class="check-dot"></div></div>
                        </div>
                    </div>

                    <input type="hidden" name="payment_method" id="selected_method" value="GoPay">
                    
                    <button type="submit" id="submitBtn" class="btn-primary">
                        Donasi Rp <span id="btn_amount">50.000</span> Sekarang
                    </button>
                    <p style="text-align: center; font-size: 10px; font-weight: 800; color: var(--slate-200); text-transform: uppercase; margin-top: 20px; letter-spacing: 0.1em;">
                        <i class="fas fa-lock" style="margin-right: 4px;"></i> 100% Aman & Terenkripsi
                    </p>
                </div>
            </form>
        </div>

        <!-- RIGHT SIDE -->
        <div class="right-side">
            <div class="summary-card">
                @if ($campaign->image && !filter_var($campaign->image, FILTER_VALIDATE_URL))
                    <img src="{{ asset('storage/' . ltrim($campaign->image, '/')) }}" class="summary-img" alt="{{ $campaign->title }}">
                @else
                    <img src="{{ $campaign->image ?? 'https://placehold.co/600x400?text=Campaign+Image' }}" class="summary-img" alt="{{ $campaign->title }}">
                @endif
                <div class="summary-content">
                    <span class="badge">{{ $campaign->kategori ?? 'Kemanusiaan' }}</span>
                    <h3 style="margin: 0; font-size: 18px; font-weight: 800; line-height: 1.4;">{{ $campaign->title }}</h3>

                    <div class="progress-container">
                        <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
                            <span style="font-weight: 900; color: var(--primary);">RP {{ number_format($campaign->current_amount, 0, ',', '.') }} TERKUMPUL</span>
                            <span style="font-weight: 700; color: var(--slate-400);">TARGET: RP {{ number_format($campaign->target_amount / 1000000, 0) }}JT</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $campaign->target_amount > 0 ? min(100, ($campaign->current_amount / $campaign->target_amount) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px; padding: 16px 0; border-top: 1px solid var(--slate-50); border-bottom: 1px solid var(--slate-50); margin-bottom: 24px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--slate-100); display: flex; align-items: center; justify-content: center; color: var(--slate-400);">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 10px; font-weight: 700; color: var(--slate-400); text-transform: uppercase;">Penggalang Dana</p>
                            <p style="margin: 0; font-size: 14px; font-weight: 800;">{{ $campaign->yayasan ?? 'Yayasan Berbagi' }}</p>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--slate-400); font-weight: 700; margin-bottom: 12px;">
                        <span>NOMINAL DONASI</span>
                        <span style="color: var(--slate-900);">Rp <span id="summary_amount">50.000</span></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--slate-400); font-weight: 700; margin-bottom: 20px;">
                        <span>BIAYA PLATFORM</span>
                        <span style="color: var(--slate-900);">Rp 0</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid var(--slate-100);">
                        <span style="font-size: 14px; font-weight: 800; text-transform: uppercase;">Total</span>
                        <span style="font-size: 24px; font-weight: 900; color: var(--primary);">Rp <span id="total_amount">50.000</span></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const amountBtns = document.querySelectorAll('.amount-btn');
            const customInput = document.getElementById('custom_amount');
            const btnAmountDisp = document.getElementById('btn_amount');
            const summaryAmountDisp = document.getElementById('summary_amount');
            const totalAmountDisp = document.getElementById('total_amount');
            const form = document.getElementById('donationForm');
            const submitBtn = document.getElementById('submitBtn');

            const formatIDR = (num) => {
                return new Intl.NumberFormat('id-ID').format(num);
            };

            const updateDisplay = (val) => {
                const formatted = formatIDR(val || 0);
                btnAmountDisp.innerText = formatted;
                summaryAmountDisp.innerText = formatted;
                totalAmountDisp.innerText = formatted;
            };

            amountBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    amountBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const val = btn.dataset.amount;
                    customInput.value = val;
                    updateDisplay(val);
                });
            });

            customInput.addEventListener('input', (e) => {
                amountBtns.forEach(b => b.classList.remove('active'));
                updateDisplay(e.target.value);
            });

            // Payment Method Selection
            const methodCards = document.querySelectorAll('.payment-method-card');
            const methodOptions = document.querySelectorAll('.method-options');
            const optionItems = document.querySelectorAll('.payment-option-item');
            const selectedMethodInput = document.getElementById('selected_method');

            methodCards.forEach(card => {
                card.addEventListener('click', () => {
                    methodCards.forEach(c => c.classList.remove('active'));
                    card.classList.add('active');
                    
                    const target = card.dataset.target;
                    methodOptions.forEach(opt => opt.classList.remove('active'));
                    document.getElementById(`${target}-options`).classList.add('active');

                    // Reset sub-option selection when category changes
                    const firstOption = document.querySelector(`#${target}-options .payment-option-item`);
                    if (firstOption) firstOption.click();
                });
            });

            optionItems.forEach(item => {
                item.addEventListener('click', () => {
                    // Only remove active from items in the same container
                    const parent = item.parentElement;
                    parent.querySelectorAll('.payment-option-item').forEach(i => i.classList.remove('active'));
                    
                    item.classList.add('active');
                    selectedMethodInput.value = item.dataset.method;
                });
            });

            form.addEventListener('submit', () => {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.7';
                submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin" style="margin-right: 8px;"></i> Memproses...';
            });
        });
    </script>
</x-app>