<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Certificate;
use App\Models\Donation;
use App\Models\Payment;
use App\Services\CertificateService;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DonationController extends Controller
{
    /** Form donasi: nominal bebas, pesan/doa, opsi anonim. */
    public function create(Campaign $campaign): View|RedirectResponse
    {
        if ($campaign->berakhir) {
            return redirect()->route('campaigns.show', $campaign)
                ->with('error', 'Campaign ini sudah tidak menerima donasi.');
        }

        return view('donations.create', [
            'campaign'    => $campaign,
            'certMin'     => config('edonation.certificate_min_amount', 50000),
            'quickValues' => [25000, 50000, 100000, 250000, 500000],
        ]);
    }

    /** Simpan donasi (status awal pending) dan siapkan pembayaran. */
    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        if ($campaign->berakhir) {
            return redirect()->route('campaigns.show', $campaign)
                ->with('error', 'Campaign ini sudah berakhir.');
        }

        $validated = $request->validate([
            'amount'       => ['required', 'numeric', 'min:1000'],
            'message'      => ['nullable', 'string', 'max:500'],
            'is_anonymous' => ['nullable', 'boolean'],
        ], [
            'amount.required' => 'Nominal donasi wajib diisi.',
            'amount.numeric'  => 'Nominal donasi harus berupa angka.',
            'amount.min'      => 'Nominal donasi minimal Rp 1.000.',
            'message.max'     => 'Pesan atau doa maksimal 500 karakter.',
        ]);

        $donation = Donation::create([
            'id_campaign'  => $campaign->id_campaign,
            'id_user'      => Auth::id(),
            'amount'       => $validated['amount'],
            'message'      => $validated['message'] ?? null,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'status'       => 'pending',
        ]);

        Payment::create([
            'id_donation' => $donation->id_donation,
            'order_id'    => 'EDON-' . time() . '-' . rand(1000, 9999),
        ]);

        return redirect()->route('donasi.payment', $donation);
    }

    /** Halaman pembayaran: Midtrans Snap simulator & Simulator internal. */
    public function payment(Donation $donation): View|RedirectResponse
    {
        if ((int) $donation->id_user !== (int) Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($donation->status === 'paid') {
            return redirect()->route('donasi.finish', $donation);
        }

        $payment = $donation->payments()->latest()->first();
        if (! $payment) {
            $payment = Payment::create([
                'id_donation' => $donation->id_donation,
                'order_id'    => 'EDON-' . time() . '-' . rand(1000, 9999),
            ]);
        }

        $snapToken = MidtransService::createSnapToken($donation, $payment);

        return view('donations.payment', [
            'donation'  => $donation->load('campaign'),
            'payment'   => $payment,
            'snapToken' => $snapToken,
            'certMin'   => config('edonation.certificate_min_amount', 50000),
        ]);
    }

    /**
     * Handler simulasi pembayaran internal (fitur cadangan jika Midtrans sandbox offline/dummy)
     * Mengubah status menjadi paid, failed, atau expired.
     */
    public function simulate(Request $request, Donation $donation): RedirectResponse
    {
        if ((int) $donation->id_user !== (int) Auth::id()) {
            abort(403);
        }

        $status = $request->input('status', 'paid');
        $method = $request->input('method', 'Simulasi Pembayaran');

        $payment = $donation->payments()->latest()->first();
        if ($payment) {
            $payment->update([
                'method'         => $method,
                'gateway_status' => $status === 'paid' ? 'settlement' : ($status === 'failed' ? 'deny' : 'pending'),
                'transaction_id' => 'SIM-' . time() . '-' . rand(100, 999),
            ]);
        }

        if ($status === 'paid') {
            $donation->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);

            // Terbitkan sertifikat digital jika mencapai threshold
            CertificateService::generateFor($donation);

            return redirect()->route('donasi.finish', $donation)
                ->with('success', 'Pembayaran berhasil dikonfirmasi! Terima kasih atas donasi Anda.');
        }

        if ($status === 'failed') {
            $donation->update(['status' => 'failed']);

            return redirect()->route('donasi.finish', $donation)
                ->with('error', 'Pembayaran tidak berhasil atau dibatalkan.');
        }

        // Pending / Bayar nanti
        return redirect()->route('donations.history')
            ->with('info', 'Donasi Anda berstatus pending. Anda dapat melanjutkannya kapan saja.');
    }

    /** Webhook notifikasi Midtrans. */
    public function midtransCallback(Request $request): \Illuminate\Http\JsonResponse
    {
        $orderId = $request->input('order_id');
        $status = $request->input('transaction_status');
        $fraud = $request->input('fraud_status');

        $payment = Payment::where('order_id', $orderId)->first();
        if (! $payment) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $payment->update([
            'transaction_id' => $request->input('transaction_id'),
            'method'         => $request->input('payment_type'),
            'gateway_status' => $status,
        ]);

        $donation = $payment->donation;

        if ($status === 'capture') {
            if ($fraud === 'accept') {
                $donation->update(['status' => 'paid', 'paid_at' => now()]);
                CertificateService::generateFor($donation);
            }
        } elseif ($status === 'settlement') {
            $donation->update(['status' => 'paid', 'paid_at' => now()]);
            CertificateService::generateFor($donation);
        } elseif (in_array($status, ['cancel', 'deny', 'expire'], true)) {
            $donation->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'Notification handled']);
    }

    /** Halaman hasil akhir pembayaran donasi (Sukses / Gagal). */
    public function finish(Donation $donation): View
    {
        if ((int) $donation->id_user !== (int) Auth::id()) {
            abort(403);
        }

        $donation->load(['campaign', 'certificate']);

        return view('donations.finish', [
            'donation' => $donation,
            'certMin'  => config('edonation.certificate_min_amount', 50000),
        ]);
    }

    /** Riwayat donasi donatur login. */
    public function history(Request $request): View
    {
        $status = $request->query('status');

        $query = Donation::where('id_user', Auth::id())
            ->with(['campaign.category', 'certificate'])
            ->latest('created_at');

        if ($status && in_array($status, ['paid', 'pending', 'failed'], true)) {
            $query->where('status', $status);
        }

        return view('donations.history', [
            'donations' => $query->paginate(10)->withQueryString(),
            'current'   => $status ?? 'all',
            'counts'    => [
                'all'     => Donation::where('id_user', Auth::id())->count(),
                'paid'    => Donation::where('id_user', Auth::id())->where('status', 'paid')->count(),
                'pending' => Donation::where('id_user', Auth::id())->where('status', 'pending')->count(),
                'failed'  => Donation::where('id_user', Auth::id())->where('status', 'failed')->count(),
            ],
            'totalPaid' => (float) Donation::where('id_user', Auth::id())->paid()->sum('amount'),
        ]);
    }

    /** Mengunduh sertifikat digital dalam format PDF. */
    public function downloadCertificate(Certificate $certificate): BinaryFileResponse
    {
        $donation = $certificate->donation;

        // Hanya donatur pemilik atau admin yang boleh mengunduh
        if (Auth::user()->role !== 'admin' && (int) $donation->id_user !== (int) Auth::id()) {
            abort(403, 'Akses sertifikat tidak diizinkan.');
        }

        $path = storage_path('app/public/' . $certificate->file_path);

        if (! file_exists($path)) {
            // Buat ulang jika file belum ada di storage
            CertificateService::generateFor($donation);
        }

        $filename = 'Sertifikat-Donasi-' . $certificate->certificate_number . '.pdf';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
