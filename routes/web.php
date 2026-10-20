<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ModerationController as AdminModerationController;
use App\Http\Controllers\Admin\MonitoringController as AdminMonitoringController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Penggalang\CampaignController as PenggalangCampaignController;
use App\Http\Controllers\Penggalang\DistributionController as PenggalangDistributionController;
use App\Http\Controllers\Penggalang\DonorController as PenggalangDonorController;
use App\Http\Controllers\Penggalang\UpdateController as PenggalangUpdateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopDonorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/campaign', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaign/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::get('/top-donatur', [TopDonorController::class, 'index'])->name('top-donors');

Route::post('/midtrans/callback', [DonationController::class, 'midtransCallback'])->name('midtrans.callback');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/sertifikat/{certificate}/unduh', [DonationController::class, 'downloadCertificate'])->name('donations.certificate.download');
});

Route::middleware(['auth', 'role:donatur'])->group(function () {
    Route::get('/donasi/{campaign}', [DonationController::class, 'create'])->name('donasi.create');
    Route::post('/donasi/{campaign}', [DonationController::class, 'store'])->name('donasi.store');
    Route::get('/donasi/{donation}/pembayaran', [DonationController::class, 'payment'])->name('donasi.payment');
    Route::post('/donasi/{donation}/simulasi', [DonationController::class, 'simulate'])->name('donasi.simulate');
    Route::get('/donasi/{donation}/selesai', [DonationController::class, 'finish'])->name('donasi.finish');

  
    Route::get('/riwayat-donasi', [DonationController::class, 'history'])->name('donations.history');
});

Route::middleware(['auth', 'role:penggalang'])->prefix('penggalang')->as('penggalang.')->group(function () {
    Route::get('/', [DashboardController::class, 'penggalang'])->name('dashboard');

    Route::get('/campaigns', [PenggalangCampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [PenggalangCampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [PenggalangCampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}/edit', [PenggalangCampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [PenggalangCampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [PenggalangCampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::post('/campaigns/{campaign}/ajukan', [PenggalangCampaignController::class, 'submitVerification'])->name('campaigns.submit');

    Route::get('/berita', [PenggalangUpdateController::class, 'index'])->name('updates.index');
    Route::post('/berita', [PenggalangUpdateController::class, 'store'])->name('updates.store');
    Route::put('/berita/{update}', [PenggalangUpdateController::class, 'update'])->name('updates.update');
    Route::delete('/berita/{update}', [PenggalangUpdateController::class, 'destroy'])->name('updates.destroy');

    Route::get('/penyaluran-dana', [PenggalangDistributionController::class, 'index'])->name('distributions.index');
    Route::post('/penyaluran-dana', [PenggalangDistributionController::class, 'store'])->name('distributions.store');
    Route::put('/penyaluran-dana/{distribution}', [PenggalangDistributionController::class, 'update'])->name('distributions.update');
    Route::delete('/penyaluran-dana/{distribution}', [PenggalangDistributionController::class, 'destroy'])->name('distributions.destroy');

    Route::get('/donatur', [PenggalangDonorController::class, 'index'])->name('donors.index');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');

    Route::get('/verifikasi', [AdminVerificationController::class, 'index'])->name('verifications.index');
    Route::get('/verifikasi/{campaign}', [AdminVerificationController::class, 'show'])->name('verifications.show');
    Route::post('/verifikasi/{campaign}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifikasi/{campaign}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/kategori', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/kategori', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/kategori/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/kategori/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/donasi', [AdminMonitoringController::class, 'donations'])->name('donations.index');

    Route::get('/penyaluran-dana', [AdminMonitoringController::class, 'distributions'])->name('distributions.index');

    Route::get('/moderasi', [AdminModerationController::class, 'index'])->name('moderation.index');
    Route::post('/moderasi/campaign/{campaign}', [AdminModerationController::class, 'toggleCampaign'])->name('moderation.campaign');
    Route::post('/moderasi/update/{update}', [AdminModerationController::class, 'toggleUpdate'])->name('moderation.update');
    Route::post('/moderasi/distribution/{distribution}', [AdminModerationController::class, 'toggleDistribution'])->name('moderation.distribution');
});
