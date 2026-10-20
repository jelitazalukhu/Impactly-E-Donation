<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FundDistribution;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** /dashboard: mengarahkan pengguna ke halaman sesuai role. */
    public function redirect(): RedirectResponse
    {
        return match (Auth::user()->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'penggalang' => redirect()->route('penggalang.dashboard'),
            default      => redirect()->route('home'),
        };
    }

    /** Dashboard untuk Penggalang Dana. */
    public function penggalang(): View
    {
        $userId = Auth::user()->id_user;
        $campaignIds = Campaign::where('id_user', $userId)->pluck('id_campaign');

        // Statistik bulanan untuk grafik Chart.js
        $monthly = Donation::paid()
            ->whereIn('id_campaign', $campaignIds)
            ->selectRaw("DATE_FORMAT(paid_at, '%b %Y') as label, DATE_FORMAT(paid_at, '%Y-%m') as ym, SUM(amount) as total")
            ->groupBy('label', 'ym')
            ->orderBy('ym')
            ->pluck('total', 'label');

        $totalTerkumpul = (float) Donation::paid()->whereIn('id_campaign', $campaignIds)->sum('amount');
        $totalDisalurkan = (float) FundDistribution::whereHas('campaign', fn ($q) => $q->where('id_user', $userId))->sum('amount');

        return view('dashboard.penggalang', [
            'stats' => [
                'campaign'   => $campaignIds->count(),
                'aktif'      => Campaign::where('id_user', $userId)->where('status', 'aktif')->count(),
                'menunggu'   => Campaign::where('id_user', $userId)->where('status', 'menunggu')->count(),
                'terkumpul'  => $totalTerkumpul,
                'disalurkan' => $totalDisalurkan,
                'sisa'       => max(0, $totalTerkumpul - $totalDisalurkan),
                'donasi'     => Donation::paid()->whereIn('id_campaign', $campaignIds)->count(),
            ],
            'campaigns' => Campaign::where('id_user', $userId)
                ->with('category')
                ->withProgress()
                ->latest('created_at')
                ->take(6)
                ->get(),
            'chartLabels' => $monthly->keys()->toArray(),
            'chartValues' => $monthly->values()->toArray(),
        ]);
    }

    /** Dashboard untuk Admin (Platform Manager). */
    public function admin(): View
    {
        // Donasi platform bulanan untuk grafik
        $monthly = Donation::paid()
            ->selectRaw("DATE_FORMAT(paid_at, '%b %Y') as label, DATE_FORMAT(paid_at, '%Y-%m') as ym, SUM(amount) as total")
            ->groupBy('label', 'ym')
            ->orderBy('ym')
            ->pluck('total', 'label');

        $totalDana = (float) Donation::paid()->sum('amount');
        $totalPenyaluran = (float) FundDistribution::sum('amount');

        return view('dashboard.admin', [
            'stats' => [
                'donatur'    => User::where('role', 'donatur')->count(),
                'penggalang' => User::where('role', 'penggalang')->count(),
                'campaign'   => Campaign::count(),
                'menunggu'   => Campaign::where('status', 'menunggu')->count(),
                'dana'       => $totalDana,
                'penyaluran' => $totalPenyaluran,
                'sisa'       => max(0, $totalDana - $totalPenyaluran),
            ],
            'statusCounts'    => Campaign::selectRaw('status, COUNT(*) AS jumlah')->groupBy('status')->pluck('jumlah', 'status'),
            'recentDonations' => Donation::with(['campaign', 'donor'])->latest('created_at')->take(7)->get(),
            'pendingCampaigns' => Campaign::where('status', 'menunggu')->with(['owner', 'category'])->latest('updated_at')->take(5)->get(),
            'chartLabels'     => $monthly->keys()->toArray(),
            'chartValues'     => $monthly->values()->toArray(),
        ]);
    }
}
