<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\Category;
use App\Models\Donation;
use App\Models\FundDistribution;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Campaign::exists()) {
            $this->command?->warn('Sudah ada campaign. DemoSeeder dilewati.');
            return;
        }

        mt_srand(2026);

        $password = Hash::make('password123');

        /* ---------------- Pengguna ---------------- */
        $penggalang = [];
        foreach ([
            ['Yayasan Harapan Bunda',     'yayasan@demo.test', 'Panti Asuhan Harapan Bunda'],
            ['Panitia Masjid Al-Ikhlas',  'masjid@demo.test',  'Masjid Al-Ikhlas Medan Timur'],
            ['Relawan Peduli Medan',      'relawan@demo.test', 'Komunitas Relawan Peduli Medan'],
        ] as [$nama, $email, $org]) {
            $penggalang[] = User::create([
                'name' => $nama, 'email' => $email, 'password' => $password,
                'role' => 'penggalang', 'organization_name' => $org, 'is_active' => true,
            ]);
        }

        $donatur = [];
        foreach ([
            ['Budi Santoso', 'budi'], ['Siti Aminah', 'siti'], ['Andi Pratama', 'andi'],
            ['Dewi Lestari', 'dewi'], ['Rizky Ramadhan', 'rizky'], ['Nur Aisyah', 'aisyah'],
            ['Fajar Nugroho', 'fajar'], ['Maya Sari', 'maya'],
        ] as [$nama, $slug]) {
            $donatur[] = User::create([
                'name' => $nama, 'email' => "{$slug}@demo.test", 'password' => $password,
                'role' => 'donatur', 'is_active' => true,
            ]);
        }

        /* ---------------- Campaign ---------------- */
        $kategoriId = function (string $kata) {
            return Category::where('name', 'like', "%{$kata}%")->value('id_category')
                ?? Category::orderBy('id_category')->value('id_category');
        };


        $daftar = [
            [1, 'Ibadah', 'Bantu Renovasi Masjid Al-Ikhlas Medan Timur',
             "Atap dan lantai masjid yang melayani sekitar 400 jamaah setiap pekan sudah banyak yang bocor dan lapuk.\n\nDana akan dipakai untuk mengganti atap, memperbaiki lantai, dan menambah tempat wudhu. Seluruh pengeluaran dilaporkan pada tab Penyaluran Dana.",
             150000000, 25, 'aktif', 68],
            [0, 'Panti', 'Beasiswa untuk Anak Panti Asuhan Harapan Bunda',
             "Sebanyak 24 anak di panti kami membutuhkan biaya sekolah, seragam, dan perlengkapan belajar untuk satu tahun ajaran.\n\nSetiap donasi membantu mereka tetap bersekolah dan meraih cita-cita.",
             75000000, 40, 'aktif', 45],
            [2, 'Bencana', 'Bantu Korban Banjir di Kabupaten Langkat',
             "Banjir merendam ratusan rumah warga dan memaksa keluarga mengungsi. Mereka membutuhkan makanan siap saji, selimut, dan obat-obatan.\n\nTim relawan menyalurkan bantuan langsung ke posko pengungsian.",
             200000000, 12, 'aktif', 82],
            [2, 'Kesehatan', 'Operasi Jantung untuk Adik Rafa',
             "Rafa (7 tahun) didiagnosis kelainan jantung bawaan dan perlu operasi segera. Biaya yang tidak ditanggung jaminan kesehatan harus ditanggung keluarga.\n\nDonasi digunakan sepenuhnya untuk biaya operasi dan perawatan.",
             90000000, 9, 'aktif', 91],
            [0, 'Pendidikan', 'Perpustakaan Desa untuk Anak Pesisir',
             "Anak-anak di desa pesisir belum memiliki tempat membaca. Kami ingin membangun perpustakaan kecil lengkap dengan rak, buku, dan komputer.",
             40000000, 30, 'aktif', 30],
            [1, 'Bencana', 'Sembako untuk Lansia Dhuafa',
             "Paket sembako bulanan untuk lansia yang hidup sendirian dan tidak memiliki penghasilan tetap di sekitar Medan Timur.",
             30000000, 18, 'aktif', 55],
            [1, 'Ibadah', 'Pembangunan Mushala Kampus',
             "Pembangunan mushala untuk mahasiswa dan civitas kampus. Campaign ini sedang menunggu verifikasi admin.",
             60000000, 45, 'menunggu', 0],
        ];

        $kampanye = [];
        foreach ($daftar as $i => [$pemilik, $kata, $judul, $isi, $target, $hari, $status, $pct]) {
            $kampanye[$i] = Campaign::create([
                'id_user'       => $penggalang[$pemilik]->id_user,
                'id_category'   => $kategoriId($kata),
                'title'         => $judul,
                'description'   => $isi,
                'target_amount' => $target,
                'deadline'      => now()->addDays($hari)->toDateString(),
                'status'        => $status,
            ]);
            
            $kampanye[$i]->forceFill(['created_at' => now()->subDays(30 - $i * 4)])->save();
        }

        $nominal = [10000, 25000, 50000, 100000, 250000, 500000, 1000000, 2500000];
        $pesan = [
            'Semoga bermanfaat.', 'Semoga lekas membaik.', 'Barakallah untuk semua yang terlibat.',
            'Semangat terus!', 'Titipan dari kami sekeluarga.', null, null, null,
        ];
        $metode = ['QRIS', 'Virtual Account', 'E-Wallet'];

        foreach ($kampanye as $i => $campaign) {
            if ($campaign->status !== 'aktif') {
                continue; // campaign yang belum aktif tidak menerima donasi
            }

 
            $baris = [];
            for ($n = 0, $jumlah = mt_rand(8, 14); $n < $jumlah; $n++) {
                $dibuat = now()->subDays(mt_rand(0, 20))->subMinutes(mt_rand(0, 1400));
                $acak = mt_rand(1, 100);
                $baris[] = [
                    'dibuat' => $dibuat,
                    'status' => $acak <= 82 ? 'paid' : ($acak <= 92 ? 'pending' : 'failed'),
                    'donatur' => $donatur[mt_rand(0, count($donatur) - 1)]->id_user,
                    'dasar'  => $nominal[mt_rand(0, count($nominal) - 1)],
                    'pesan'  => $pesan[mt_rand(0, count($pesan) - 1)],
                    'anonim' => mt_rand(1, 100) <= 15,
                    'metode' => $metode[mt_rand(0, 2)],
                ];
            }

            
            $targetPct = $daftar[$i][7];
            $dasarPaid = array_sum(array_map(fn ($b) => $b['status'] === 'paid' ? $b['dasar'] : 0, $baris));
            $rasio = $dasarPaid > 0 ? ($campaign->target_amount * $targetPct / 100) / $dasarPaid : 1;

            foreach ($baris as $b) {
                $amount = max(10000, (int) (round($b['dasar'] * $rasio / 5000) * 5000));

                $donation = Donation::forceCreate([
                    'id_campaign'  => $campaign->id_campaign,
                    'id_user'      => $b['donatur'],
                    'amount'       => $amount,
                    'message'      => $b['pesan'],
                    'is_anonymous' => $b['anonim'],
                    'status'       => $b['status'],
                    'paid_at'      => $b['status'] === 'paid' ? (clone $b['dibuat'])->addMinutes(2) : null,
                    'created_at'   => $b['dibuat'],
                    'updated_at'   => $b['dibuat'],
                ]);

                Payment::forceCreate([
                    'id_donation'    => $donation->id_donation,
                    'order_id'       => 'DON-' . $b['dibuat']->format('Ymd') . '-' . $donation->id_donation . '-1',
                    'method'         => $b['metode'],
                    'gateway_status' => $b['status'] === 'paid' ? 'settlement' : ($b['status'] === 'pending' ? 'pending' : 'deny'),
                    'created_at'     => $b['dibuat'],
                    'updated_at'     => $b['dibuat'],
                ]);
            }
        }

        $berita = [
            [0, 'Pengumpulan dana dimulai', 'Terima kasih atas dukungan awal dari para donatur. Kami mulai menyusun rencana anggaran renovasi.', 20],
            [0, 'Survei kerusakan atap selesai', 'Tim teknis telah mengukur kerusakan. Atap yang perlu diganti seluas sekitar 180 meter persegi.', 8],
            [1, 'Pendataan penerima beasiswa', 'Sebanyak 24 anak telah terdata. Kami menyiapkan daftar kebutuhan sekolah masing-masing.', 12],
            [2, 'Posko pengungsian dibuka', 'Relawan membuka dua posko di Kecamatan Stabat. Kebutuhan mendesak: selimut dan obat-obatan.', 6],
            [3, 'Jadwal operasi sudah keluar', 'Rumah sakit menjadwalkan operasi dalam beberapa pekan ke depan. Doa dan dukungan sangat berarti.', 3],
        ];
        foreach ($berita as [$idx, $judul, $isi, $hariLalu]) {
            CampaignUpdate::forceCreate([
                'id_campaign' => $kampanye[$idx]->id_campaign,
                'title'       => $judul,
                'content'     => $isi,
                'is_active'   => true,
                'created_at'  => now()->subDays($hariLalu),
                'updated_at'  => now()->subDays($hariLalu),
            ]);
        }

        $penyaluran = [
            [0, 40000000, 'Toko Bangunan Jaya Abadi', 'Pembelian material atap seng dan rangka baja ringan tahap pertama.', 6],
            [2, 25000000, 'Posko Pengungsian Stabat', 'Pembelian makanan siap saji, selimut, dan obat-obatan untuk 60 keluarga.', 4],
        ];
        foreach ($penyaluran as [$idx, $jumlah, $penerima, $ket, $hariLalu]) {
            FundDistribution::forceCreate([
                'id_campaign'    => $kampanye[$idx]->id_campaign,
                'amount'         => $jumlah,
                'recipient'      => $penerima,
                'description'    => $ket,
                'distributed_at' => now()->subDays($hariLalu)->toDateString(),
                'is_active'      => true,
                'created_at'     => now()->subDays($hariLalu),
                'updated_at'     => now()->subDays($hariLalu),
            ]);
        }

        $this->command?->info('Data contoh dibuat: 3 penggalang, 8 donatur, 7 campaign beserta donasinya.');
        $this->command?->info('Password semua akun contoh: password123');
    }
}
