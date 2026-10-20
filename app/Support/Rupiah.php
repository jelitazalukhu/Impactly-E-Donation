<?php

namespace App\Support;

class Rupiah
{
    /** Contoh: 1500000 menjadi "Rp 1.500.000" */
    public static function format($amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }

    /** Versi singkat untuk angka besar. Contoh: 2500000 menjadi "Rp 2,5 jt" */
    public static function short($amount): string
    {
        $n = (float) $amount;

        if ($n >= 1_000_000_000) {
            return 'Rp ' . rtrim(rtrim(number_format($n / 1_000_000_000, 1, ',', '.'), '0'), ',') . ' M';
        }
        if ($n >= 1_000_000) {
            return 'Rp ' . rtrim(rtrim(number_format($n / 1_000_000, 1, ',', '.'), '0'), ',') . ' jt';
        }

        return 'Rp ' . number_format($n, 0, ',', '.');
    }
}
