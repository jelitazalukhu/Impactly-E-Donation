<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    /**
     * Menghasilkan sertifikat digital jika donasi berstatus paid
     * dan nominalnya memenuhi batas minimal.
     */
    public static function generateFor(Donation $donation): ?Certificate
    {
        if ($donation->status !== 'paid') {
            return null;
        }

        $minAmount = config('edonation.certificate_min_amount', 50000);
        if ($donation->amount < $minAmount) {
            return null;
        }

        // Jika sudah ada, jangan buat ganda
        if ($donation->certificate) {
            return $donation->certificate;
        }

        $certNumber = 'CERT-' . now()->format('Ym') . '-' . str_pad((string) $donation->id_donation, 5, '0', STR_PAD_LEFT);
        $fileName = 'certificates/' . $certNumber . '.pdf';

        // Pastikan relasi terload
        $donation->loadMissing(['donor', 'campaign.owner']);

        $certificate = new Certificate([
            'id_donation'        => $donation->id_donation,
            'certificate_number' => $certNumber,
            'file_path'          => $fileName,
            'issued_at'          => now(),
        ]);

        // Simpan file PDF ke public disk
        Storage::disk('public')->makeDirectory('certificates');
        $pdf = Pdf::loadView('certificates.template', [
            'certificate' => $certificate,
        ])->setPaper('a4', 'landscape');

        Storage::disk('public')->put($fileName, $pdf->output());

        $certificate->save();

        return $certificate;
    }
}
