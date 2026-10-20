<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FundDistribution;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    /** Pantau seluruh transaksi donasi platform. */
    public function donations(Request $request): View
    {
        $status = $request->query('status');
        $campaignId = $request->query('campaign');

        $campaigns = Campaign::orderBy('title')->get();

        $query = Donation::with(['donor', 'campaign', 'payments'])->latest('created_at');

        if ($status && in_array($status, ['paid', 'pending', 'failed'], true)) {
            $query->where('status', $status);
        }

        if ($campaignId) {
            $query->where('id_campaign', $campaignId);
        }

        $allPaidAmount = (float) Donation::paid()->sum('amount');
        $allPaidCount = Donation::paid()->count();

        return view('admin.donations.index', [
            'donations'     => $query->paginate(15)->withQueryString(),
            'campaigns'     => $campaigns,
            'selectedId'    => $campaignId,
            'currentStatus' => $status ?? 'all',
            'totalPaid'     => $allPaidAmount,
            'totalCount'    => $allPaidCount,
        ]);
    }

    /** Pantau penyaluran dana dan transparansi per campaign. */
    public function distributions(Request $request): View
    {
        $campaigns = Campaign::withProgress()->with(['owner', 'distributions' => function ($q) {
            $q->orderByDesc('distributed_at');
        }])->latest('created_at')->paginate(10);

        $totalPlatformDisbursed = (float) FundDistribution::sum('amount');
        $totalPlatformCollected = (float) Donation::paid()->sum('amount');

        return view('admin.distributions.index', [
            'campaigns'        => $campaigns,
            'totalCollected'   => $totalPlatformCollected,
            'totalDisbursed'   => $totalPlatformDisbursed,
            'totalUndisbursed' => max(0, $totalPlatformCollected - $totalPlatformDisbursed),
        ]);
    }
}
