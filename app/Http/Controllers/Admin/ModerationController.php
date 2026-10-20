<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\FundDistribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerationController extends Controller
{
    /** Halaman moderasi konten: Campaign, Berita, dan Laporan Penyaluran. */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'campaign');

        $campaigns = Campaign::with('owner')->latest('updated_at')->paginate(10, ['*'], 'c_page');
        $updates = CampaignUpdate::with(['campaign.owner'])->latest('created_at')->paginate(10, ['*'], 'u_page');
        $distributions = FundDistribution::with(['campaign.owner'])->latest('distributed_at')->paginate(10, ['*'], 'd_page');

        return view('admin.moderation.index', [
            'tab'           => $tab,
            'campaigns'     => $campaigns,
            'updates'       => $updates,
            'distributions' => $distributions,
        ]);
    }

    /** Nonaktifkan atau aktifkan kembali campaign. */
    public function toggleCampaign(Campaign $campaign): RedirectResponse
    {
        $newStatus = ($campaign->status === 'nonaktif') ? 'aktif' : 'nonaktif';
        $campaign->update(['status' => $newStatus]);

        $text = ($newStatus === 'nonaktif') ? 'dinonaktifkan (disembunyikan dari publik)' : 'diaktifkan kembali';

        return back()->with('success', "Campaign \"{$campaign->title}\" berhasil {$text}.");
    }

    /** Nonaktifkan atau aktifkan berita perkembangan. */
    public function toggleUpdate(CampaignUpdate $update): RedirectResponse
    {
        $update->is_active = ! $update->is_active;
        $update->save();

        $text = $update->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Berita \"{$update->title}\" berhasil {$text}.");
    }

    /** Nonaktifkan atau aktifkan laporan penyaluran dana. */
    public function toggleDistribution(FundDistribution $distribution): RedirectResponse
    {
        $distribution->is_active = ! $distribution->is_active;
        $distribution->save();

        $text = $distribution->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Laporan penyaluran dana berhasil {$text}.");
    }
}
