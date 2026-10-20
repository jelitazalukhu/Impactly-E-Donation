<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FundDistribution;
use Illuminate\Support\Collection;

/**
 * Perhitungan yang dipakai di beberapa halaman.
 * Semua angka dihitung dari donasi berstatus "paid", tidak disimpan sebagai kolom.
 */
class DonationStats
{
    /** Ringkasan seluruh platform (untuk beranda). */
    public static function platform(): array
    {
        return [
            'total_dana'       => (float) Donation::paid()->sum('amount'),
            'jumlah_donatur'   => (int) Donation::paid()->distinct()->count('id_user'),
            'campaign_aktif'   => Campaign::aktif()->count(),
            'dana_tersalurkan' => (float) FundDistribution::where('is_active', true)->sum('amount'),
        ];
    }

    /**
     * Peringkat donatur berdasarkan total donasi paid.
     * Donatur yang pernah memilih anonim ditampilkan sebagai "Anonim".
     */
    public static function topDonors(int $limit = 10): Collection
    {
        return Donation::query()
            ->where('donations.status', 'paid')
            ->join('users', 'users.id_user', '=', 'donations.id_user')
            ->selectRaw('users.id_user, users.name, MAX(donations.is_anonymous) AS anon, SUM(donations.amount) AS total, COUNT(*) AS jumlah')
            ->groupBy('users.id_user', 'users.name')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(function ($row) {
                $row->display_name = $row->anon ? 'Anonim' : $row->name;
                return $row;
            });
    }
}
