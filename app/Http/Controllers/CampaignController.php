<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /** Daftar campaign publik: cari, filter kategori, urutkan. */
    public function index(Request $request): View
    {
        $query = Campaign::aktif()->with('category', 'owner')->withProgress();

        $keyword = trim((string) $request->query('q', ''));
        if ($keyword !== '') {
            $query->where(function ($w) use ($keyword) {
                $w->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('id_category', (int) $request->query('kategori'));
        }

        $urut = $request->query('urut', 'terbaru');
        if ($urut === 'berakhir') {
            $query->orderBy('deadline');
        } elseif ($urut === 'terbanyak') {
            $query->orderByDesc('terkumpul');
        } else {
            $query->latest('created_at');
        }

        return view('campaigns.index', [
            'campaigns'  => $query->paginate(9)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters'    => ['q' => $keyword, 'kategori' => $request->query('kategori'), 'urut' => $urut],
        ]);
    }

    /** Detail campaign: progress, berita, penyaluran dana, donatur. */
    public function show(Campaign $campaign): View
    {
        $user = Auth::user();
        $bolehPratinjau = $user && ($user->isAdmin() || (int) $user->id_user === (int) $campaign->id_user);

        // Campaign yang belum aktif hanya boleh dilihat pemilik dan admin.
        if ($campaign->status !== 'aktif' && ! $bolehPratinjau) {
            abort(404);
        }

        $campaign->load('category', 'owner');

        $updates = $campaign->updates()->where('is_active', true)->latest()->get();
        $distributions = $campaign->distributions()->where('is_active', true)->orderByDesc('distributed_at')->get();
        $donations = $campaign->paidDonations()->with('donor')->latest('paid_at')->limit(30)->get();

        return view('campaigns.show', [
            'campaign'      => $campaign,
            'updates'       => $updates,
            'distributions' => $distributions,
            'donations'     => $donations,
            'donorCount'    => (int) $campaign->paidDonations()->distinct()->count('id_user'),
            'disbursed'     => (float) $distributions->sum('amount'),
            'pratinjau'     => $campaign->status !== 'aktif',
        ]);
    }
}
