<?php

namespace App\Http\Controllers\Penggalang;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DonorController extends Controller
{
    /** Daftar donatur yang menyumbang ke campaign milik penggalang. */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $campaignId = $request->query('campaign');

        $campaigns = Campaign::where('id_user', $userId)->orderBy('title')->get();
        $myCampaignIds = $campaigns->pluck('id_campaign');

        $query = Donation::whereIn('id_campaign', $myCampaignIds)
            ->where('status', 'paid')
            ->with(['donor', 'campaign'])
            ->latest('paid_at');

        if ($campaignId) {
            $query->where('id_campaign', $campaignId);
        }

        return view('penggalang.donors.index', [
            'donations'  => $query->paginate(15)->withQueryString(),
            'campaigns'  => $campaigns,
            'selectedId' => $campaignId,
            'totalPaid'  => (float) Donation::whereIn('id_campaign', $myCampaignIds)->where('status', 'paid')->sum('amount'),
            'totalCount' => Donation::whereIn('id_campaign', $myCampaignIds)->where('status', 'paid')->count(),
        ]);
    }
}
