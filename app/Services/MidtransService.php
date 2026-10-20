<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public static function init(): void
    {
        Config::$serverKey = config('edonation.midtrans.server_key');
        Config::$isProduction = config('edonation.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Membuat Snap Token Midtrans untuk donasi.
     * Mengembalikan token string atau null bila terjadi kesalahan/offline.
     */
    public static function createSnapToken(Donation $donation, Payment $payment): ?string
    {
        self::init();

        $params = [
            'transaction_details' => [
                'order_id'     => $payment->order_id,
                'gross_amount' => (int) round($donation->amount),
            ],
            'customer_details' => [
                'first_name' => $donation->donor->name,
                'email'      => $donation->donor->email,
                'phone'      => $donation->donor->phone ?? '',
            ],
            'item_details' => [
                [
                    'id'       => (string) $donation->id_campaign,
                    'price'    => (int) round($donation->amount),
                    'quantity' => 1,
                    'name'     => mb_strimwidth($donation->campaign->title, 0, 45, '...'),
                ]
            ],
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (Exception $e) {
            Log::warning('Midtrans Snap Error: ' . $e->getMessage());
            return null;
        }
    }
}
