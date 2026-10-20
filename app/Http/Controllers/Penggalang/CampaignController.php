<?php

namespace App\Http\Controllers\Penggalang;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /** Daftar campaign milik penggalang yang sedang login. */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $status = $request->query('status');

        $query = Campaign::where('id_user', $userId)->with('category')->withProgress()->latest('created_at');

        if ($status && in_array($status, ['draft', 'menunggu', 'aktif', 'ditolak', 'selesai', 'nonaktif'], true)) {
            $query->where('status', $status);
        }

        return view('penggalang.campaigns.index', [
            'campaigns' => $query->paginate(8)->withQueryString(),
            'current'   => $status ?? 'all',
            'counts'    => [
                'all'      => Campaign::where('id_user', $userId)->count(),
                'draft'    => Campaign::where('id_user', $userId)->where('status', 'draft')->count(),
                'menunggu' => Campaign::where('id_user', $userId)->where('status', 'menunggu')->count(),
                'aktif'    => Campaign::where('id_user', $userId)->where('status', 'aktif')->count(),
                'ditolak'  => Campaign::where('id_user', $userId)->where('status', 'ditolak')->count(),
                'selesai'  => Campaign::where('id_user', $userId)->where('status', 'selesai')->count(),
            ],
        ]);
    }

    /** Form buat campaign baru. */
    public function create(): View
    {
        return view('penggalang.campaigns.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /** Simpan campaign baru. */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:150'],
            'id_category'   => ['required', 'exists:categories,id_category'],
            'target_amount' => ['required', 'numeric', 'min:10000'],
            'deadline'      => ['required', 'date', 'after:today'],
            'description'   => ['required', 'string'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'action'        => ['nullable', 'in:draft,submit'],
        ], [
            'title.required'         => 'Judul campaign wajib diisi.',
            'id_category.required'   => 'Pilih kategori campaign.',
            'target_amount.required' => 'Target donasi wajib diisi.',
            'target_amount.min'      => 'Target donasi minimal Rp 10.000.',
            'deadline.required'      => 'Batas waktu (deadline) wajib diisi.',
            'deadline.after'         => 'Batas waktu harus tanggal setelah hari ini.',
            'description.required'   => 'Deskripsi campaign wajib diisi.',
            'image.image'            => 'File cover harus berupa gambar.',
            'image.max'              => 'Ukuran gambar maksimal 2MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
        }

        $status = ($request->input('action') === 'submit') ? 'menunggu' : 'draft';

        $campaign = Campaign::create([
            'id_user'        => Auth::id(),
            'id_category'    => $validated['id_category'],
            'title'          => $validated['title'],
            'description'    => $validated['description'],
            'image'          => $imagePath,
            'target_amount'  => $validated['target_amount'],
            'deadline'       => $validated['deadline'],
            'status'         => $status,
            'rejection_note' => null,
        ]);

        $msg = ($status === 'menunggu')
            ? 'Campaign berhasil dibuat dan diajukan ke admin untuk diverifikasi.'
            : 'Campaign berhasil disimpan sebagai draft.';

        return redirect()->route('penggalang.campaigns.index')->with('success', $msg);
    }

    /** Form edit campaign. */
    public function edit(Campaign $campaign): View|RedirectResponse
    {
        if ((int) $campaign->id_user !== (int) Auth::id()) {
            abort(403);
        }

        return view('penggalang.campaigns.edit', [
            'campaign'   => $campaign,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    /** Update data campaign. */
    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        if ((int) $campaign->id_user !== (int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:150'],
            'id_category'   => ['required', 'exists:categories,id_category'],
            'target_amount' => ['required', 'numeric', 'min:10000'],
            'deadline'      => ['required', 'date'],
            'description'   => ['required', 'string'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'action'        => ['nullable', 'in:save,submit'],
        ]);

        if ($request->hasFile('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $campaign->image = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->title = $validated['title'];
        $campaign->id_category = $validated['id_category'];
        $campaign->target_amount = $validated['target_amount'];
        $campaign->deadline = $validated['deadline'];
        $campaign->description = $validated['description'];

        // Jika diajukan ulang setelah ditolak atau dari draft
        if ($request->input('action') === 'submit') {
            $campaign->status = 'menunggu';
            $campaign->rejection_note = null; // reset alasan penolakan sebelumnya
        }

        $campaign->save();

        $msg = ($campaign->status === 'menunggu')
            ? 'Campaign berhasil diperbarui dan diajukan untuk verifikasi.'
            : 'Perubahan campaign berhasil disimpan.';

        return redirect()->route('penggalang.campaigns.index')->with('success', $msg);
    }

    /** Mengajukan verifikasi ke admin. */
    public function submitVerification(Campaign $campaign): RedirectResponse
    {
        if ((int) $campaign->id_user !== (int) Auth::id()) {
            abort(403);
        }

        if (! in_array($campaign->status, ['draft', 'ditolak'], true)) {
            return back()->with('error', 'Hanya campaign berstatus draft atau ditolak yang dapat diajukan verifikasi.');
        }

        $campaign->update([
            'status'         => 'menunggu',
            'rejection_note' => null,
        ]);

        return redirect()->route('penggalang.campaigns.index')
            ->with('success', 'Campaign berhasil diajukan untuk verifikasi admin.');
    }

    /** Hapus campaign (soft delete). */
    public function destroy(Campaign $campaign): RedirectResponse
    {
        if ((int) $campaign->id_user !== (int) Auth::id()) {
            abort(403);
        }

        // Jangan hapus jika sudah ada donasi berstatus paid
        if ($campaign->paidDonations()->exists()) {
            return back()->with('error', 'Campaign yang telah menerima donasi tidak dapat dihapus.');
        }

        $campaign->delete();

        return redirect()->route('penggalang.campaigns.index')
            ->with('success', 'Campaign berhasil dihapus.');
    }
}
