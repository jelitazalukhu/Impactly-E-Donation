<?php

namespace App\Http\Controllers\Penggalang;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class UpdateController extends Controller
{
    /** Daftar berita perkembangan milik penggalang login. */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $campaignId = $request->query('campaign');

        $campaigns = Campaign::where('id_user', $userId)->orderBy('title')->get();

        $query = CampaignUpdate::whereHas('campaign', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->with('campaign')->latest('created_at');

        if ($campaignId) {
            $query->where('id_campaign', $campaignId);
        }

        return view('penggalang.updates.index', [
            'updates'    => $query->paginate(10)->withQueryString(),
            'campaigns'  => $campaigns,
            'selectedId' => $campaignId,
        ]);
    }

    /** Simpan berita baru. */
    public function store(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'id_campaign' => ['required', 'exists:campaigns,id_campaign'],
            'title'       => ['required', 'string', 'max:150'],
            'content'     => ['required', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'id_campaign.required' => 'Pilih campaign yang akan diupdate.',
            'title.required'       => 'Judul berita perkembangan wajib diisi.',
            'content.required'     => 'Isi berita perkembangan wajib diisi.',
            'image.image'          => 'File dokumentasi harus berupa gambar.',
        ]);

        // Verifikasi kepemilikan campaign
        $campaign = Campaign::where('id_user', $userId)->where('id_campaign', $validated['id_campaign'])->firstOrFail();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('updates', 'public');
        }

        CampaignUpdate::create([
            'id_campaign' => $campaign->id_campaign,
            'title'       => $validated['title'],
            'content'     => $validated['content'],
            'image'       => $imagePath,
            'is_active'   => true,
        ]);

        return redirect()->route('penggalang.updates.index', ['campaign' => $campaign->id_campaign])
            ->with('success', 'Berita perkembangan berhasil dipublikasikan.');
    }

    /** Update berita perkembangan. */
    public function update(Request $request, CampaignUpdate $update): RedirectResponse
    {
        // Pastikan update ini milik campaign penggalang login
        if ((int) $update->campaign->id_user !== (int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'   => ['required', 'string', 'max:150'],
            'content' => ['required', 'string'],
            'image'   => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($update->image) {
                Storage::disk('public')->delete($update->image);
            }
            $update->image = $request->file('image')->store('updates', 'public');
        }

        $update->title = $validated['title'];
        $update->content = $validated['content'];
        $update->save();

        return redirect()->route('penggalang.updates.index', ['campaign' => $update->id_campaign])
            ->with('success', 'Berita perkembangan berhasil diperbarui.');
    }

    /** Hapus berita. */
    public function destroy(CampaignUpdate $update): RedirectResponse
    {
        if ((int) $update->campaign->id_user !== (int) Auth::id()) {
            abort(403);
        }

        if ($update->image) {
            Storage::disk('public')->delete($update->image);
        }

        $campaignId = $update->id_campaign;
        $update->delete();

        return redirect()->route('penggalang.updates.index', ['campaign' => $campaignId])
            ->with('success', 'Berita perkembangan berhasil dihapus.');
    }
}
