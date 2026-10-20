<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    /** Daftar campaign yang sedang menunggu verifikasi admin. */
    public function index(): View
    {
        $campaigns = Campaign::where('status', 'menunggu')
            ->with(['owner', 'category'])
            ->latest('updated_at')
            ->paginate(10);

        return view('admin.verifications.index', [
            'campaigns' => $campaigns,
        ]);
    }

    /** Detail peninjauan campaign untuk verifikasi. */
    public function show(Campaign $campaign): View
    {
        $campaign->load(['owner', 'category']);

        return view('admin.verifications.show', [
            'campaign' => $campaign,
        ]);
    }

    /** Menyetujui campaign agar aktif dan tampil publik. */
    public function approve(Campaign $campaign): RedirectResponse
    {
        $campaign->update([
            'status'         => 'aktif',
            'rejection_note' => null,
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', "Campaign \"{$campaign->title}\" berhasil disetujui dan kini aktif!");
    }

    /** Menolak campaign dengan alasan penolakan wajib. */
    public function reject(Request $request, Campaign $campaign): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_note' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'rejection_note.required' => 'Alasan penolakan wajib diisi untuk mengedukasi penggalang dana.',
            'rejection_note.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $campaign->update([
            'status'         => 'ditolak',
            'rejection_note' => $validated['rejection_note'],
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', "Campaign \"{$campaign->title}\" telah ditolak disertai alasan.");
    }
}
