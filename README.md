# Impactly: Platform Donasi Online yang Transparan

Proyek UTS E-Application, Program Studi D3 Teknik Informatika.

Anggota kelompok:
- Jelita Crisna Zalukhu (241712022)
- Kabul Manik (241712023)

## Deskripsi
Impactly adalah aplikasi donasi online berbasis web dengan tiga peran: donatur, penggalang dana, dan administrator. Campaign diverifikasi admin sebelum tayang, dilengkapi berita dan laporan penyaluran dana, dan donatur mendapat sertifikat digital.

## Fitur utama
- Login multi-role (donatur, penggalang dana, admin)
- CRUD campaign dengan verifikasi admin
- Donasi nominal bebas dengan opsi anonim
- Pembayaran Midtrans Dummy dan progress bar otomatis
- Berita campaign dan laporan penyaluran dana
- Sertifikat donatur digital (PDF)
- Papan top donatur

## Teknologi
PHP 8.2+, Laravel 12, MySQL, Bootstrap 5.3, Midtrans Dummy (Snap SDK), barryvdh/laravel-dompdf.

## Cara menjalankan
1. `git clone https://github.com/USERNAME/impactly-e-donation.git`
2. `cd impactly-e-donation`
3. `composer install`
4. `copy .env.example .env`
5. Buat database kosong bernama `e_donation` di phpMyAdmin
6. `php artisan key:generate`
7. `php artisan migrate --seed`
8. `php artisan storage:link`
9. `php artisan serve`
10. Buka `http://127.0.0.1:8000`

Sebagai alternatif langkah 7, impor berkas `database/e_donation.sql` lewat phpMyAdmin ke database `e_donation`.

## Akun demo
| Peran | Email | Password |
| --- | --- | --- |
| Admin | admin@edonation.test | Admin#12345 |
| Penggalang |yayasan@demo.test | password123 |
| Donatur | budi@demo.test | password123 |

Batas nominal sertifikat: Rp 50.000.