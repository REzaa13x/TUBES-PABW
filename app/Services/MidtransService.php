<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Membuat payment request ke Midtrans
     * 
     * @param array $params Parameter untuk transaksi
     * @return array Response dari Midtrans
     */
    public function createPaymentRequest(array $params)
    {
        try {
            // Tambahkan konfigurasi tambahan
            $params = array_merge($params, [
                'transaction_details' => array_merge($params['transaction_details'] ?? [], [
                    'currency' => config('midtrans.currency', 'IDR')
                ])
            ]);

            // Buat snap token
            $snapToken = Snap::createTransaction($params);

            return [
                'success' => true,
                'snap_token' => $snapToken->token,
                'redirect_url' => $snapToken->redirect_url,
                'transaction_id' => $params['transaction_details']['order_id']
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e
            ];
        }
    }

    /**
     * Mendapatkan detail transaksi dari Midtrans
     * 
     * @param string $orderId ID transaksi
     * @return array Detail transaksi
     */
    public function getTransactionStatus(string $orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return [
                'success' => true,
                'transaction' => $status
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Mendapatkan status pembayaran dari Midtrans
     * 
     * @param string $orderId ID transaksi
     * @return array Status pembayaran
     */
    public function getPaymentStatus(string $orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return [
                'success' => true,
                'status_code' => $status->status_code ?? null,
                'transaction_status' => $status->transaction_status ?? null,
                'fraud_status' => $status->fraud_status ?? null,
                'payment_type' => $status->payment_type ?? null,
                'transaction_id' => $status->transaction_id ?? null,
                'gross_amount' => $status->gross_amount ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Membuat parameter transaksi untuk donasi
     *
     * @param int $campaignId ID kampanye
     * @param float $amount Jumlah donasi
     * @param string $donorName Nama donatur
     * @param string $donorEmail Email donatur
     * @param string $orderId ID transaksi unik
     * @return array Parameter transaksi
     */
    public function createDonationParams(int $campaignId, float $amount, string $donorName, string $donorEmail, string $orderId)
    {
        return [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $donorName,
                'email' => $donorEmail,
            ],
            'item_details' => [
                [
                    'id' => 'donation_'.$campaignId,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Donasi untuk kampanye #' . $campaignId
                ]
            ],
            'custom_field1' => config('midtrans.custom_field_names.donation_id'),
            'custom_field2' => config('midtrans.custom_field_names.campaign_id'),
            'custom_field3' => config('midtrans.custom_field_names.donor_name'),
            'custom_field4' => config('midtrans.custom_field_names.donor_email'),
            'callbacks' => [
                'finish' => route('donation.success') . '?status=success', // Redirect to donation success page
            ]
        ];
    }

    /**
     * Verifikasi signature Midtrans
     * 
     * @param array $payload Data dari webhook
     * @return bool Validasi signature
     */
    public function verifySignature(array $payload)
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];

        $signature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        return hash_equals($signature, $payload['signature_key']);
    }
}