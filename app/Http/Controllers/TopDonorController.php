<?php

namespace App\Http\Controllers;

use App\Services\DonationStats;
use Illuminate\View\View;

class TopDonorController extends Controller
{
    public function index(): View
    {
        return view('top-donors', [
            'donors' => DonationStats::topDonors(20),
        ]);
    }
}
