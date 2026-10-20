<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\FundDistribution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EDonationFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $penggalang;
    private User $donatur;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Kemanusiaan',
            'description' => 'Bantuan untuk korban bencana dan kemanusiaan',
        ]);

        $this->admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@edonation.test',
            'password' => bcrypt('Admin#12345'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->penggalang = User::create([
            'name' => 'Yayasan Peduli Sesama',
            'email' => 'yayasan@edonation.test',
            'password' => bcrypt('password123'),
            'role' => 'penggalang',
            'organization_name' => 'Yayasan Peduli Sesama',
            'is_active' => true,
        ]);

        $this->donatur = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@edonation.test',
            'password' => bcrypt('password123'),
            'role' => 'donatur',
            'is_active' => true,
        ]);
    }

    public function test_public_pages_are_accessible_for_guests(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/campaign')->assertStatus(200);
        $this->get('/top-donatur')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
    }

    public function test_login_multi_role_redirects_properly(): void
    {
        // Donatur redirects to home
        $resDonatur = $this->post('/login', [
            'email' => $this->donatur->email,
            'password' => 'password123',
        ]);
        $resDonatur->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($this->donatur);

        $this->post('/logout');

        // Penggalang redirects to penggalang dashboard
        $resPenggalang = $this->post('/login', [
            'email' => $this->penggalang->email,
            'password' => 'password123',
        ]);
        $resPenggalang->assertRedirect(route('penggalang.dashboard'));
        $this->assertAuthenticatedAs($this->penggalang);

        $this->post('/logout');

        // Admin redirects to admin dashboard
        $resAdmin = $this->post('/login', [
            'email' => $this->admin->email,
            'password' => 'Admin#12345',
        ]);
        $resAdmin->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_penggalang_can_create_campaign_and_submit_verification(): void
    {
        $response = $this->actingAs($this->penggalang)->post('/penggalang/campaigns', [
            'title' => 'Bantuan Medis Anak Pesisir',
            'id_category' => $this->category->id_category,
            'target_amount' => 10000000,
            'deadline' => now()->addDays(30)->toDateString(),
            'description' => 'Program bantuan medis untuk anak-anak di daerah pesisir.',
            'action' => 'submit',
        ]);

        $response->assertRedirect(route('penggalang.campaigns.index'));
        $this->assertDatabaseHas('campaigns', [
            'title' => 'Bantuan Medis Anak Pesisir',
            'status' => 'menunggu',
        ]);
    }

    public function test_admin_can_approve_and_reject_campaign(): void
    {
        $campaign = Campaign::create([
            'id_user' => $this->penggalang->id_user,
            'id_category' => $this->category->id_category,
            'title' => 'Renovasi Rumah Singgah',
            'target_amount' => 50000000,
            'deadline' => now()->addDays(20)->toDateString(),
            'description' => 'Renovasi fasilitas rumah singgah.',
            'status' => 'menunggu',
        ]);

        // Admin menolak dengan alasan wajib
        $rejectResponse = $this->actingAs($this->admin)->post("/admin/verifikasi/{$campaign->id_campaign}/reject", [
            'rejection_note' => 'Lampiran foto bangunan belum memadai, mohon lengkapi.',
        ]);
        $rejectResponse->assertRedirect(route('admin.verifications.index'));
        $campaign->refresh();
        $this->assertEquals('ditolak', $campaign->status);
        $this->assertEquals('Lampiran foto bangunan belum memadai, mohon lengkapi.', $campaign->rejection_note);

        // Penggalang ajukan ulang
        $this->actingAs($this->penggalang)->post("/penggalang/campaigns/{$campaign->id_campaign}/ajukan");
        $campaign->refresh();
        $this->assertEquals('menunggu', $campaign->status);

        // Admin menyetujui
        $approveResponse = $this->actingAs($this->admin)->post("/admin/verifikasi/{$campaign->id_campaign}/approve");
        $approveResponse->assertRedirect(route('admin.verifications.index'));
        $campaign->refresh();
        $this->assertEquals('aktif', $campaign->status);
    }

    public function test_donation_payment_simulation_and_certificate_issuance(): void
    {
        $campaign = Campaign::create([
            'id_user' => $this->penggalang->id_user,
            'id_category' => $this->category->id_category,
            'title' => 'Bantuan Beasiswa Yatim',
            'target_amount' => 20000000,
            'deadline' => now()->addDays(25)->toDateString(),
            'description' => 'Beasiswa untuk siswa yatim.',
            'status' => 'aktif',
        ]);

        // Donatur membuat donasi nominal bebas Rp 100.000 (di atas batas 50.000)
        $storeResponse = $this->actingAs($this->donatur)->post("/donasi/{$campaign->id_campaign}", [
            'amount' => 100000,
            'message' => 'Semoga berkah untuk adik-adik.',
            'is_anonymous' => 0,
        ]);

        $donation = Donation::where('id_campaign', $campaign->id_campaign)->first();
        $this->assertNotNull($donation);
        $this->assertEquals('pending', $donation->status);
        $storeResponse->assertRedirect(route('donasi.payment', $donation));

        // Simulasikan pembayaran sukses (Paid)
        $simResponse = $this->actingAs($this->donatur)->post("/donasi/{$donation->id_donation}/simulasi", [
            'status' => 'paid',
            'method' => 'GoPay / QRIS',
        ]);

        $simResponse->assertRedirect(route('donasi.finish', $donation));
        $donation->refresh();
        $this->assertEquals('paid', $donation->status);
        $this->assertNotNull($donation->paid_at);

        // Sertifikat harus otomatis diterbitkan karena >= Rp 50.000
        $this->assertNotNull($donation->certificate);
        $this->assertDatabaseHas('certificates', [
            'id_donation' => $donation->id_donation,
        ]);

        // Cek bahwa progress campaign terhitung otomatis
        $this->assertEquals(100000, $campaign->fresh()->terkumpul);
    }

    public function test_fund_distribution_cannot_exceed_collected_amount(): void
    {
        $campaign = Campaign::create([
            'id_user' => $this->penggalang->id_user,
            'id_category' => $this->category->id_category,
            'title' => 'Pembangunan Sumur Air Bersih',
            'target_amount' => 10000000,
            'deadline' => now()->addDays(15)->toDateString(),
            'description' => 'Pembuatan sumur bor di desa terpencil.',
            'status' => 'aktif',
        ]);

        // Ada donasi paid Rp 500.000
        Donation::create([
            'id_campaign' => $campaign->id_campaign,
            'id_user' => $this->donatur->id_user,
            'amount' => 500000,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Mencoba menyalurkan Rp 1.000.000 (melebihi 500.000 yang terkumpul) -> Harap ditolak
        $failResponse = $this->actingAs($this->penggalang)->post('/penggalang/penyaluran-dana', [
            'id_campaign' => $campaign->id_campaign,
            'amount' => 1000000,
            'recipient' => 'Warga Dusun 3',
            'description' => 'Pembelian pipa dan semen',
            'distributed_at' => now()->toDateString(),
        ]);
        $failResponse->assertSessionHasErrors('amount');

        // Menyalurkan Rp 300.000 (valid <= 500.000) -> Sukses
        $successResponse = $this->actingAs($this->penggalang)->post('/penggalang/penyaluran-dana', [
            'id_campaign' => $campaign->id_campaign,
            'amount' => 300000,
            'recipient' => 'Warga Dusun 3',
            'description' => 'Pembelian pipa paralon tahap 1',
            'distributed_at' => now()->toDateString(),
        ]);
        $successResponse->assertSessionHasNoErrors();
        $this->assertDatabaseHas('fund_distributions', [
            'id_campaign' => $campaign->id_campaign,
            'amount' => 300000,
        ]);
    }
}
