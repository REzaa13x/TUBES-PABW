<x-app title="Terima Kasih | DonGiv">
    @php
        $order_id = request()->query('order_id');
        $transaction = \App\Models\DonationTransaction::where('order_id', $order_id)->first();
        $status = $transaction ? $transaction->status : 'VERIFIED';
        $isPending = ($status === 'PENDING_VERIFICATION' || $status === 'AWAITING_TRANSFER');
    @endphp

    <style>
        .success-wrapper {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background-color: #F8FAFC;
        }

        .success-modal {
            background: white;
            border-radius: 40px;
            padding: 48px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.05);
        }

        .heart-circle {
            width: 100px;
            height: 100px;
            background: #EFF6FF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
        }

        .heart-circle i {
            color: #1D4ED8;
            font-size: 40px;
        }

        .success-title {
            font-size: 32px;
            font-weight: 900;
            color: #0F172A;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .success-desc {
            color: #64748B;
            line-height: 1.6;
            margin-bottom: 40px;
            font-size: 16px;
            padding: 0 20px;
        }

        .success-desc strong {
            color: #0F172A;
            font-weight: 800;
        }

        .info-box {
            background: #F8FAFC;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 32px;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .info-row:last-child { margin-bottom: 0; }

        .info-label {
            font-size: 11px;
            font-weight: 800;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .info-value {
            font-size: 14px;
            font-weight: 700;
            color: #334155;
        }

        .badge-verified {
            background: #D1FAE5;
            color: #065F46;
            padding: 4px 12px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-pending {
            background: #FEF3C7;
            color: #92400E;
            padding: 4px 12px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-impact {
            background: #1D4ED8;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 20px;
            font-weight: 800;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
            display: block;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(29, 78, 216, 0.2);
        }

        .btn-impact:hover {
            background: #1E40AF;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(29, 78, 216, 0.3);
        }
    </style>

    <div class="success-wrapper">
        <div class="success-modal">
            <div class="heart-circle">
                <i class="fas fa-heart"></i>
            </div>
            
            <h1 class="success-title">Terima Kasih!</h1>
            <p class="success-desc">
                Donasi Anda sebesar <strong>Rp {{ number_format(request()->query('amount') ?? 50000, 0, ',', '.') }}</strong> telah kami terima. 
                @if($isPending)
                    Bukti transfer Anda sedang dalam proses verifikasi oleh admin.
                @else
                    Kebaikan Anda sangat berarti bagi mereka.
                @endif
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">ID DONASI</span>
                    <span class="info-value">{{ $order_id ?? 'HF-77291-TX' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">STATUS</span>
                    @if($isPending)
                        <span class="badge-pending">Sedang Diverifikasi</span>
                    @else
                        <span class="badge-verified">Terverifikasi</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('home') }}" class="btn-impact">
                Lihat Dampak Donasi
            </a>
        </div>
    </div>
</x-app>