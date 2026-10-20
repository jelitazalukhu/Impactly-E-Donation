<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Services\DonationStats;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Campaign sorotan di hero: yang dananya terbanyak.
        $featured = Campaign::aktif()->with('category', 'owner')->withProgress()
            ->orderByDesc('terkumpul')->first();

        $campaigns = Campaign::aktif()->with('category', 'owner')->withProgress()
            ->latest('created_at')->take(8)->get();

        $categories = Category::withCount(['campaigns as aktif_count' => fn ($q) => $q->aktif()])
            ->orderBy('name')->get();

        // Ambil campaign yang diikuti oleh donatur yang sedang login
        $myCampaigns = collect();
        $donorStats = null;
        if (Auth::check()) {
            $user = Auth::user();
            $myCampaigns = Campaign::whereHas('donations', function ($q) use ($user) {
                $q->where('id_user', $user->id_user);
            })->with('category', 'owner')->withProgress()->latest('created_at')->get();

            if ($user->role === 'donatur') {
                $donorStats = [
                    'total_donasi'   => (float) $user->donations()->where('status', 'paid')->sum('amount'),
                    'campaign_count' => $myCampaigns->count(),
                    'cert_count'     => $user->donations()->has('certificate')->count(),
                ];
            }
        }

        return view('home', [
            'stats'       => DonationStats::platform(),
            'featured'    => $featured,
            'campaigns'   => $campaigns,
            'categories'  => $categories,
            'topDonors'   => DonationStats::topDonors(5),
            'myCampaigns' => $myCampaigns,
            'donorStats'  => $donorStats,
        ]);
    }
}
