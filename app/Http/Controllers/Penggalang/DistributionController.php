<?php

namespace App\Http\Controllers\Penggalang;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\FundDistribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DistributionController extends Controller
{
    /** Daftar laporan penyaluran dana milik penggalang login. */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $campaignId = $request->query('campaign');

        $campaigns = Campaign::where('id_user', $userId)->withProgress()->orderBy('title')->get();

        $query = FundDistribution::whereHas('campaign', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->with('campaign')->orderByDesc('distributed_at');

        $selectedCampaign = null;
        if ($campaignId) {
            $query->where('id_campaign', $campaignId);
            $selectedCampaign = $campaigns->firstWhere('id_campaign', (int) $campaignId);
        }

        $allDisbursed = (float) FundDistribution::whereHas('campaign', fn ($q) => $q->where('id_user', $userId))->sum('amount');
        $allCollected = (float) $campaigns->sum('terkumpul');

        return view('penggalang.distributions.index', [
            'distributions'    => $query->paginate(10)->withQueryString(),
            'campaigns'        => $campaigns,
            'selectedCampaign' => $selectedCampaign,
            'selectedId'       => $campaignId,
            'totalCollected'   => $allCollected,
            'totalDisbursed'   => $allDisbursed,
            'remaining'        => max(0, $allCollected - $allDisbursed),
        ]);
    }

    /** Simpan laporan penyaluran dana baru. */
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'id_campaign'    => ['required', 'exists:campaigns,id_campaign'],
            'amount'         => ['required', 'numeric', 'min:1000'],
            'recipient'      => ['required', 'string', 'max:150'],
            'description'    => ['required', 'string'],
            'distributed_at' => ['required', 'date'],
            'proof_file'     => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:3072'],
        ], [
            'id_campaign.required'    => 'Pilih campaign yang dananya disalurkan.',
            'amount.required'         => 'Nominal penyaluran wajib diisi.',
            'amount.min'              => 'Nominal minimal Rp 1.000.',
            'recipient.required'      => 'Nama penerima manfaat wajib diisi.',
            'description.required'    => 'Keterangan penyaluran wajib diisi.',
            'distributed_at.required' => 'Tanggal penyaluran dana wajib diisi.',
            'proof_file.mimes'        => 'Bukti penyaluran harus berupa gambar atau PDF.',
        ]);

        $campaign = Campaign::where('id_user', $userId)->where('id_campaign', $validated['id_campaign'])->firstOrFail();

        // Cek apakah nominal melebihi sisa dana yang terkumpul
        $totalCollected = (float) $campaign->paidDonations()->sum('amount');
        $alreadyDisbursed = (float) $campaign->distributions()->sum('amount');
        $sisaDana = max(0, $totalCollected - $alreadyDisbursed);

        if ($validated['amount'] > $sisaDana) {
            return back()->withInput()->withErrors([
                'amount' => 'Nominal penyaluran (' . \App\Support\Rupiah::format($validated['amount']) . ') melebihi sisa dana terkumpul yang tersedia (' . \App\Support\Rupiah::format($sisaDana) . ').',
            ]);
        }

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('proofs', 'public');
        }

        FundDistribution::create([
            'id_campaign'    => $campaign->id_campaign,
            'amount'         => $validated['amount'],
            'recipient'      => $validated['recipient'],
            'description'    => $validated['description'],
            'distributed_at' => $validated['distributed_at'],
            'proof_file'     => $proofPath,
            'is_active'      => true,
        ]);

        return redirect()->route('penggalang.distributions.index', ['campaign' => $campaign->id_campaign])
            ->with('success', 'Laporan penyaluran dana berhasil dicatat dan dipublikasikan.');
    }

    /** Update penyaluran dana. */
    public function update(Request $request, FundDistribution $distribution): RedirectResponse
    {
        if ((int) $distribution->campaign->id_user !== (int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount'         => ['required', 'numeric', 'min:1000'],
            'recipient'      => ['required', 'string', 'max:150'],
            'description'    => ['required', 'string'],
            'distributed_at' => ['required', 'date'],
            'proof_file'     => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:3072'],
        ]);

        if ($request->hasFile('proof_file')) {
            if ($distribution->proof_file) {
                Storage::disk('public')->delete($distribution->proof_file);
            }
            $distribution->proof_file = $request->file('proof_file')->store('proofs', 'public');
        }

        $distribution->amount = $validated['amount'];
        $distribution->recipient = $validated['recipient'];
        $distribution->description = $validated['description'];
        $distribution->distributed_at = $validated['distributed_at'];
        $distribution->save();

        return redirect()->route('penggalang.distributions.index', ['campaign' => $distribution->id_campaign])
            ->with('success', 'Laporan penyaluran dana berhasil diperbarui.');
    }

    /** Hapus laporan penyaluran dana. */
    public function destroy(FundDistribution $distribution): RedirectResponse
    {
        if ((int) $distribution->campaign->id_user !== (int) Auth::id()) {
            abort(403);
        }

        if ($distribution->proof_file) {
            Storage::disk('public')->delete($distribution->proof_file);
        }

        $campaignId = $distribution->id_campaign;
        $distribution->delete();

        return redirect()->route('penggalang.distributions.index', ['campaign' => $campaignId])
            ->with('success', 'Laporan penyaluran dana berhasil dihapus.');
    }
}
