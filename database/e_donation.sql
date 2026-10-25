-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Okt 2026 pada 02.38
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e_donation`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `campaigns`
--

CREATE TABLE `campaigns` (
  `id_campaign` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_category` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `target_amount` decimal(15,2) NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('draft','menunggu','aktif','ditolak','selesai','nonaktif') NOT NULL DEFAULT 'draft',
  `rejection_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data untuk tabel `campaigns`
--

INSERT INTO `campaigns` (`id_campaign`, `id_user`, `id_category`, `title`, `description`, `image`, `target_amount`, `deadline`, `status`, `rejection_note`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 2, 'Bantu Renovasi Masjid Al-Ikhlas Medan Timur', 'Atap dan lantai masjid yang melayani sekitar 400 jamaah setiap pekan sudah banyak yang bocor dan lapuk.\r\n\r\nDana akan dipakai untuk mengganti atap, memperbaiki lantai, dan menambah tempat wudhu. Seluruh pengeluaran dilaporkan pada tab Penyaluran Dana.', 'campaigns/4ntBSSarSPowhOuu0s5NNoVkBVCmhFFLz8P0HyTN.jpg', 150000000.00, '2026-11-13', 'aktif', NULL, '2026-09-19 04:41:51', '2026-10-19 09:35:37', NULL),
(2, 2, 3, 'Beasiswa untuk Anak Panti Asuhan Harapan Bunda', 'Sebanyak 24 anak di panti kami membutuhkan biaya sekolah, seragam, dan perlengkapan belajar untuk satu tahun ajaran.\n\nSetiap donasi membantu mereka tetap bersekolah dan meraih cita-cita.', NULL, 75000000.00, '2026-11-28', 'nonaktif', NULL, '2026-09-23 04:41:51', '2026-10-23 12:58:10', NULL),
(3, 4, 1, 'Bantu Korban Banjir di Wilayah Sumatera', 'Banjir merendam ratusan rumah warga dan memaksa keluarga mengungsi. Mereka membutuhkan makanan siap saji, selimut, dan obat-obatan.\r\n\r\nTim relawan menyalurkan bantuan langsung ke posko pengungsian.', 'campaigns/dkerssVF4GGx50C5LEnvY5kQFf2CTY9E0tJgGxAT.jpg', 200000000.00, '2026-10-31', 'aktif', NULL, '2026-09-27 04:41:51', '2026-10-21 05:05:27', NULL),
(4, 4, 5, 'Operasi Jantung untuk Adik Rafa', 'Rafa (7 tahun) didiagnosis kelainan jantung bawaan dan perlu operasi segera. Biaya yang tidak ditanggung jaminan kesehatan harus ditanggung keluarga.\r\n\r\nDonasi digunakan sepenuhnya untuk biaya operasi dan perawatan.', 'campaigns/YKibS9OMvDWmK30IwXwEl12fbzxwo6gaQZYprb0d.jpg', 90000000.00, '2026-10-28', 'aktif', NULL, '2026-10-01 04:41:51', '2026-10-19 09:37:50', NULL),
(5, 2, 4, 'Perpustakaan Desa untuk Anak Pesisir', 'Anak-anak di desa pesisir belum memiliki tempat membaca. Kami ingin membangun perpustakaan kecil lengkap dengan rak, buku, dan komputer.', NULL, 40000000.00, '2026-10-14', 'nonaktif', NULL, '2026-10-05 04:41:51', '2026-10-23 12:59:12', NULL),
(6, 3, 1, 'Sembako untuk Lansia Dhuafa', 'Paket sembako bulanan untuk lansia yang hidup sendirian dan tidak memiliki penghasilan tetap di sekitar Medan Timur.', NULL, 30000000.00, '2026-11-06', 'nonaktif', NULL, '2026-10-09 04:41:51', '2026-10-23 12:57:47', NULL),
(7, 3, 2, 'Pembangunan Mushala Kampus', 'Pembangunan mushala untuk mahasiswa dan civitas kampus. Campaign ini sedang menunggu verifikasi admin.', NULL, 60000000.00, '2026-12-03', 'aktif', NULL, '2026-10-13 04:41:51', '2026-10-23 12:55:23', '2026-10-23 12:55:23'),
(8, 2, 2, 'Bersama Mewujudkan Gereja yang Lebih Layak.', 'Gereja merupakan tempat bagi jemaat untuk beribadah, berdoa, dan mempererat kebersamaan dalam iman. Namun, kondisi bangunan yang mulai membutuhkan perbaikan membuat renovasi diperlukan agar kegiatan ibadah dapat berlangsung dengan lebih aman dan nyaman.\r\n\r\nMelalui kampanye ini, kami mengajak para jemaat dan masyarakat untuk berpartisipasi dalam mendukung proses renovasi gereja. Dana yang terkumpul akan digunakan untuk memperbaiki bagian bangunan yang membutuhkan perhatian, meningkatkan kenyamanan ruang ibadah, serta mendukung tersedianya fasilitas yang lebih layak bagi jemaat.\r\n\r\nSetiap kontribusi, berapa pun nominalnya, merupakan bentuk dukungan yang berarti bagi keberlangsungan kegiatan ibadah dan kebersamaan jemaat. Mari ambil bagian dalam mewujudkan tempat ibadah yang lebih aman, nyaman, dan layak bagi semua.\r\n\r\nTerima kasih atas perhatian, doa, dan dukungan yang diberikan.', 'campaigns/bGiaBoIZf9JnIMRA2GhCKoQq2MSU780SD8W7d4bL.jpg', 99930000.00, '2026-12-31', 'aktif', NULL, '2026-10-19 08:31:44', '2026-10-19 09:28:01', NULL),
(9, 4, 3, 'Pembangunan Panti Jompo', 'Setiap lansia berhak mendapatkan tempat tinggal yang aman, nyaman, dan layak, serta lingkungan yang mendukung kebutuhan mereka sehari-hari. Namun, keterbatasan fasilitas dan tempat tinggal yang memadai masih menjadi tantangan dalam menyediakan perawatan yang layak bagi para lansia.\r\n\r\nMelalui campaign ini, kami menggalang dana untuk mendukung pembangunan panti jompo yang dapat menjadi tempat tinggal sekaligus ruang perawatan bagi lansia. Dana yang terkumpul akan digunakan untuk mendukung kebutuhan pembangunan, penyediaan fasilitas dasar, serta menciptakan lingkungan yang aman dan nyaman bagi para penghuni.\r\n\r\nDukungan dari setiap donatur, berapa pun nominalnya, akan membantu mewujudkan fasilitas yang lebih layak bagi para lansia. Mari bersama-sama mengambil bagian dalam menciptakan tempat yang memberikan kenyamanan, perhatian, dan kehidupan yang lebih baik bagi mereka.\r\n\r\nTerima kasih atas kepedulian dan dukungan yang diberikan.', 'campaigns/kJi29tHkMD1F6DcaEIgO71l2Fw1zbj0q83BNfkmd.jpg', 100000000.00, '2027-01-21', 'nonaktif', 'contoh', '2026-10-20 03:48:44', '2026-10-21 05:04:57', '2026-10-21 05:04:57'),
(10, 2, 5, 'Penyediaan Air Bersih untuk Desa', 'Akses terhadap air bersih sangat penting untuk menunjang kesehatan dan aktivitas sehari-hari masyarakat. Namun, sebagian wilayah masih menghadapi keterbatasan sarana air bersih sehingga warga harus menempuh jarak tertentu atau mengandalkan sumber air yang belum memadai.\r\n\r\nCampaign ini bertujuan mendukung penyediaan sarana air bersih bagi masyarakat desa yang membutuhkan. Dana yang terkumpul akan digunakan untuk kebutuhan pembangunan atau perbaikan fasilitas air, pengadaan perlengkapan pendukung, serta instalasi sarana distribusi sesuai dengan kondisi lokasi.\r\n\r\nMelalui dukungan bersama, diharapkan masyarakat dapat memperoleh akses air bersih yang lebih mudah dan memadai. Setiap kontribusi membantu mendukung upaya peningkatan kualitas hidup dan kesehatan masyarakat.', 'campaigns/MkTSscxN4alMNTfWIK3NmrpJ18jwBocDDlCROCKF.jpg', 100000000.00, '2026-12-31', 'nonaktif', NULL, '2026-10-20 06:37:22', '2026-10-23 12:58:47', '2026-10-23 12:58:47'),
(11, 4, 4, 'Pustaka Keliling untuk Anak-Anak Desa Terpencil', 'Di beberapa desa terpencil, anak-anak harus menempuh perjalanan lebih dari satu jam untuk sampai ke perpustakaan terdekat. Akibatnya, banyak dari mereka tidak punya buku bacaan selain buku pelajaran dari sekolah, dan minat baca pun sulit tumbuh.\r\n\r\nKami, sekelompok relawan pendidikan, ingin menghadirkan buku langsung ke desa mereka lewat program Pustaka Keliling. Setiap akhir pekan, relawan membawa buku dengan motor dan gerobak buku ke tiga desa, lalu membuka lapak baca gratis, mendongeng, dan belajar bersama.\r\n\r\nDana yang terkumpul akan digunakan untuk:\r\n1. Membeli 500 buku bacaan anak (cerita bergambar, sains populer, dan buku bahasa).\r\n2. Membuat satu gerobak buku dan rak yang mudah dibawa.\r\n3. Biaya bensin dan perawatan motor selama enam bulan.\r\n4. Perlengkapan lapak baca: tikar, terpal, dan alat tulis.\r\n\r\nKami akan melaporkan pembelian buku dan kegiatan setiap bulan di halaman ini, lengkap dengan foto dan rincian biaya. Terima kasih sudah membantu anak-anak desa mengenal dunia lewat buku.', 'campaigns/MycF4KeXChWBQFjdSoN1xLA0StFR5gJr0IZywbiy.jpg', 25000000.00, '2027-02-10', 'aktif', NULL, '2026-10-23 06:56:36', '2026-10-23 07:22:22', NULL),
(12, 4, 1, 'contoh campaign dengan verifikasi penolakan', 'abc', NULL, 999920000.00, '2026-10-29', 'ditolak', 'tidak relevan', '2026-10-23 06:57:24', '2026-10-23 07:22:34', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `campaign_updates`
--

CREATE TABLE `campaign_updates` (
  `id_campaign_update` int(10) UNSIGNED NOT NULL,
  `id_campaign` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `campaign_updates`
--

INSERT INTO `campaign_updates` (`id_campaign_update`, `id_campaign`, `title`, `content`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pengumpulan dana dimulai', 'Terima kasih atas dukungan awal dari para donatur. Kami mulai menyusun rencana anggaran renovasi.', NULL, 1, '2026-09-29 04:41:51', '2026-09-29 04:41:51'),
(2, 1, 'Survei kerusakan atap selesai', 'Tim teknis telah mengukur kerusakan. Atap yang perlu diganti seluas sekitar 180 meter persegi.', NULL, 1, '2026-10-11 04:41:51', '2026-10-11 04:41:51'),
(3, 2, 'Pendataan penerima beasiswa', 'Sebanyak 24 anak telah terdata. Kami menyiapkan daftar kebutuhan sekolah masing-masing.', NULL, 1, '2026-10-07 04:41:51', '2026-10-07 04:41:51'),
(4, 3, 'Posko pengungsian dibuka', 'Relawan membuka dua posko di Kecamatan Stabat. Kebutuhan mendesak: selimut dan obat-obatan.', NULL, 1, '2026-10-13 04:41:51', '2026-10-13 04:41:51'),
(5, 4, 'Jadwal operasi sudah keluar', 'Rumah sakit menjadwalkan operasi dalam beberapa pekan ke depan. Doa dan dukungan sangat berarti.', NULL, 1, '2026-10-16 04:41:51', '2026-10-16 04:41:51'),
(6, 8, 'Renovasi Gereja Memasuki Tahap Persiapan', 'Penggalangan dana untuk renovasi gereja terus berjalan dengan dukungan dari para donatur. Saat ini, persiapan renovasi difokuskan pada pemeriksaan kondisi bangunan, identifikasi bagian yang memerlukan perbaikan, serta penyusunan kebutuhan material.\r\n\r\nTahap persiapan ini dilakukan untuk memastikan proses renovasi berjalan sesuai kebutuhan dan dana yang terkumpul dapat dimanfaatkan secara tepat.\r\n\r\nRencana penggunaan dana\r\n- Perbaikan bagian bangunan yang mengalami kerusakan.\r\n- Pengadaan material untuk kebutuhan renovasi.\r\n- Perbaikan fasilitas pendukung kegiatan ibadah.\r\nKami akan memberikan pembaruan berikutnya setelah proses renovasi mulai dilaksanakan. Terima kasih kepada seluruh donatur yang telah mendukung campaign ini.', 'updates/p47ASoOaUIMd66pe1I49uERnZ0mPxLieiUHf88Eh.jpg', 1, '2026-10-19 09:31:26', '2026-10-19 09:31:26'),
(7, 3, 'Paket Sembako Tahap Pertama Sudah Sampai ke 250 Keluarga', 'Tahap pertama bantuan dari donasi Bapak dan Ibu sudah tersalurkan. Pada Sabtu, 18 Oktober 2026, tim relawan membagikan 250 paket sembako ke empat desa yang terdampak banjir di Kecamatan Sungai Rejo. Setiap paket berisi beras, minyak goreng, telur, mi instan, dan gula untuk kebutuhan sekitar satu minggu.\r\n\r\nDistribusi dibantu perangkat desa agar bantuan tepat sasaran. Warga yang masih berada di tenda pengungsian menjadi prioritas. Beberapa warga mengaku sangat terbantu karena stok makanan mereka habis terendam air.\r\n\r\nTahap berikutnya adalah penyaluran selimut, perlengkapan bayi, dan obat-obatan yang direncanakan pekan ini. Rincian penggunaan dana dapat dilihat di tab Penyaluran Dana. Terima kasih kepada seluruh donatur yang sudah ikut membantu.', 'updates/3ixn1y0vQhfJm6AhaoniCrWfvrWx4oSkO8CdZCbq.jpg', 1, '2026-10-23 07:00:26', '2026-10-23 07:00:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id_category` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id_category`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Bencana', 'Bantuan korban bencana alam dan kemanusiaan', '2026-10-19 01:03:26', '2026-10-19 01:03:26'),
(2, 'Tempat Ibadah', 'Pembangunan dan renovasi tempat ibadah', '2026-10-19 01:03:26', '2026-10-19 01:03:26'),
(3, 'Panti Asuhan', 'Dukungan untuk panti asuhan dan anak yatim', '2026-10-19 01:03:26', '2026-10-19 01:03:26'),
(4, 'Pendidikan', 'Beasiswa dan fasilitas pendidikan', '2026-10-19 01:03:26', '2026-10-19 01:03:26'),
(5, 'Kesehatan', 'Biaya pengobatan dan layanan kesehatan', '2026-10-19 01:03:26', '2026-10-19 01:03:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `certificates`
--

CREATE TABLE `certificates` (
  `id_certificate` int(10) UNSIGNED NOT NULL,
  `id_donation` int(10) UNSIGNED NOT NULL,
  `certificate_number` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `certificates`
--

INSERT INTO `certificates` (`id_certificate`, `id_donation`, `certificate_number`, `file_path`, `issued_at`) VALUES
(1, 1, 'CERT-202610-00001', 'certificates/CERT-202610-00001.pdf', '2026-10-19 07:17:40'),
(2, 2, 'CERT-202610-00002', 'certificates/CERT-202610-00002.pdf', '2026-10-19 07:17:40'),
(3, 3, 'CERT-202610-00003', 'certificates/CERT-202610-00003.pdf', '2026-10-19 07:17:40'),
(4, 4, 'CERT-202610-00004', 'certificates/CERT-202610-00004.pdf', '2026-10-19 07:17:40'),
(5, 5, 'CERT-202610-00005', 'certificates/CERT-202610-00005.pdf', '2026-10-19 07:17:40'),
(6, 7, 'CERT-202610-00007', 'certificates/CERT-202610-00007.pdf', '2026-10-19 07:17:40'),
(7, 8, 'CERT-202610-00008', 'certificates/CERT-202610-00008.pdf', '2026-10-19 07:17:40'),
(8, 10, 'CERT-202610-00010', 'certificates/CERT-202610-00010.pdf', '2026-10-19 07:17:40'),
(9, 11, 'CERT-202610-00011', 'certificates/CERT-202610-00011.pdf', '2026-10-19 07:17:40'),
(10, 12, 'CERT-202610-00012', 'certificates/CERT-202610-00012.pdf', '2026-10-19 07:17:41'),
(11, 13, 'CERT-202610-00013', 'certificates/CERT-202610-00013.pdf', '2026-10-19 07:17:41'),
(12, 14, 'CERT-202610-00014', 'certificates/CERT-202610-00014.pdf', '2026-10-19 07:17:41'),
(13, 15, 'CERT-202610-00015', 'certificates/CERT-202610-00015.pdf', '2026-10-19 07:17:41'),
(14, 16, 'CERT-202610-00016', 'certificates/CERT-202610-00016.pdf', '2026-10-19 07:17:41'),
(15, 17, 'CERT-202610-00017', 'certificates/CERT-202610-00017.pdf', '2026-10-19 07:17:41'),
(16, 19, 'CERT-202610-00019', 'certificates/CERT-202610-00019.pdf', '2026-10-19 07:17:41'),
(17, 20, 'CERT-202610-00020', 'certificates/CERT-202610-00020.pdf', '2026-10-19 07:17:41'),
(18, 21, 'CERT-202610-00021', 'certificates/CERT-202610-00021.pdf', '2026-10-19 07:17:41'),
(19, 24, 'CERT-202610-00024', 'certificates/CERT-202610-00024.pdf', '2026-10-19 07:17:41'),
(20, 25, 'CERT-202610-00025', 'certificates/CERT-202610-00025.pdf', '2026-10-19 07:17:41'),
(21, 27, 'CERT-202610-00027', 'certificates/CERT-202610-00027.pdf', '2026-10-19 07:17:41'),
(22, 29, 'CERT-202610-00029', 'certificates/CERT-202610-00029.pdf', '2026-10-19 07:17:41'),
(23, 30, 'CERT-202610-00030', 'certificates/CERT-202610-00030.pdf', '2026-10-19 07:17:41'),
(24, 31, 'CERT-202610-00031', 'certificates/CERT-202610-00031.pdf', '2026-10-19 07:17:41'),
(25, 32, 'CERT-202610-00032', 'certificates/CERT-202610-00032.pdf', '2026-10-19 07:17:41'),
(26, 33, 'CERT-202610-00033', 'certificates/CERT-202610-00033.pdf', '2026-10-19 07:17:41'),
(27, 34, 'CERT-202610-00034', 'certificates/CERT-202610-00034.pdf', '2026-10-19 07:17:41'),
(28, 35, 'CERT-202610-00035', 'certificates/CERT-202610-00035.pdf', '2026-10-19 07:17:41'),
(29, 37, 'CERT-202610-00037', 'certificates/CERT-202610-00037.pdf', '2026-10-19 07:17:42'),
(30, 38, 'CERT-202610-00038', 'certificates/CERT-202610-00038.pdf', '2026-10-19 07:17:42'),
(31, 40, 'CERT-202610-00040', 'certificates/CERT-202610-00040.pdf', '2026-10-19 07:17:42'),
(32, 42, 'CERT-202610-00042', 'certificates/CERT-202610-00042.pdf', '2026-10-19 07:17:42'),
(33, 43, 'CERT-202610-00043', 'certificates/CERT-202610-00043.pdf', '2026-10-19 07:17:42'),
(34, 44, 'CERT-202610-00044', 'certificates/CERT-202610-00044.pdf', '2026-10-19 07:17:42'),
(35, 45, 'CERT-202610-00045', 'certificates/CERT-202610-00045.pdf', '2026-10-19 07:17:42'),
(36, 47, 'CERT-202610-00047', 'certificates/CERT-202610-00047.pdf', '2026-10-19 07:17:42'),
(37, 48, 'CERT-202610-00048', 'certificates/CERT-202610-00048.pdf', '2026-10-19 07:17:42'),
(38, 49, 'CERT-202610-00049', 'certificates/CERT-202610-00049.pdf', '2026-10-19 07:17:42'),
(39, 51, 'CERT-202610-00051', 'certificates/CERT-202610-00051.pdf', '2026-10-19 07:17:42'),
(40, 52, 'CERT-202610-00052', 'certificates/CERT-202610-00052.pdf', '2026-10-19 07:17:42'),
(41, 53, 'CERT-202610-00053', 'certificates/CERT-202610-00053.pdf', '2026-10-19 07:17:42'),
(42, 55, 'CERT-202610-00055', 'certificates/CERT-202610-00055.pdf', '2026-10-19 07:17:42'),
(43, 56, 'CERT-202610-00056', 'certificates/CERT-202610-00056.pdf', '2026-10-19 07:17:42'),
(44, 57, 'CERT-202610-00057', 'certificates/CERT-202610-00057.pdf', '2026-10-19 07:17:42'),
(45, 59, 'CERT-202610-00059', 'certificates/CERT-202610-00059.pdf', '2026-10-19 07:17:42'),
(46, 60, 'CERT-202610-00060', 'certificates/CERT-202610-00060.pdf', '2026-10-19 07:17:42'),
(47, 61, 'CERT-202610-00061', 'certificates/CERT-202610-00061.pdf', '2026-10-19 07:17:43'),
(48, 62, 'CERT-202610-00062', 'certificates/CERT-202610-00062.pdf', '2026-10-19 07:17:43'),
(49, 64, 'CERT-202610-00064', 'certificates/CERT-202610-00064.pdf', '2026-10-19 07:17:43'),
(50, 65, 'CERT-202610-00065', 'certificates/CERT-202610-00065.pdf', '2026-10-19 07:17:43'),
(51, 66, 'CERT-202610-00066', 'certificates/CERT-202610-00066.pdf', '2026-10-19 07:17:43'),
(52, 67, 'CERT-202610-00067', 'certificates/CERT-202610-00067.pdf', '2026-10-19 07:17:43'),
(53, 68, 'CERT-202610-00068', 'certificates/CERT-202610-00068.pdf', '2026-10-19 07:27:26'),
(54, 69, 'CERT-202610-00069', 'certificates/CERT-202610-00069.pdf', '2026-10-19 08:22:04'),
(55, 70, 'CERT-202610-00070', 'certificates/CERT-202610-00070.pdf', '2026-10-19 08:28:37'),
(56, 71, 'CERT-202610-00071', 'certificates/CERT-202610-00071.pdf', '2026-10-19 09:03:45'),
(57, 72, 'CERT-202610-00072', 'certificates/CERT-202610-00072.pdf', '2026-10-19 09:20:54'),
(58, 73, 'CERT-202610-00073', 'certificates/CERT-202610-00073.pdf', '2026-10-19 19:26:52'),
(59, 74, 'CERT-202610-00074', 'certificates/CERT-202610-00074.pdf', '2026-10-19 19:30:01'),
(60, 76, 'CERT-202610-00076', 'certificates/CERT-202610-00076.pdf', '2026-10-20 03:56:38'),
(61, 77, 'CERT-202610-00077', 'certificates/CERT-202610-00077.pdf', '2026-10-20 03:59:22'),
(62, 79, 'CERT-202610-00079', 'certificates/CERT-202610-00079.pdf', '2026-10-23 13:07:45'),
(63, 81, 'CERT-202610-00081', 'certificates/CERT-202610-00081.pdf', '2026-10-25 01:34:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `donations`
--

CREATE TABLE `donations` (
  `id_donation` int(10) UNSIGNED NOT NULL,
  `id_campaign` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `message` text DEFAULT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','paid','failed','expired') NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data untuk tabel `donations`
--

INSERT INTO `donations` (`id_donation`, `id_campaign`, `id_user`, `amount`, `message`, `is_anonymous`, `status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 11875000.00, NULL, 0, 'paid', '2026-10-12 13:11:51', '2026-10-12 06:09:51', '2026-10-12 06:09:51'),
(2, 1, 9, 5935000.00, 'Barakallah untuk semua yang terlibat.', 0, 'paid', '2026-10-03 04:54:51', '2026-10-02 21:52:51', '2026-10-02 21:52:51'),
(3, 1, 12, 235000.00, NULL, 0, 'paid', '2026-10-01 18:37:51', '2026-10-01 11:35:51', '2026-10-01 11:35:51'),
(4, 1, 6, 5935000.00, 'Semangat terus!', 0, 'paid', '2026-10-03 13:31:51', '2026-10-03 06:29:51', '2026-10-03 06:29:51'),
(5, 1, 9, 59370000.00, NULL, 0, 'paid', '2026-10-13 21:44:51', '2026-10-13 14:42:51', '2026-10-13 14:42:51'),
(6, 1, 11, 23750000.00, 'Semoga lekas membaik.', 0, 'pending', NULL, '2026-10-01 08:11:51', '2026-10-01 08:11:51'),
(7, 1, 9, 595000.00, NULL, 0, 'paid', '2026-10-09 05:22:51', '2026-10-08 22:20:51', '2026-10-08 22:20:51'),
(8, 1, 8, 235000.00, NULL, 0, 'paid', '2026-10-09 22:07:51', '2026-10-09 15:05:51', '2026-10-09 15:05:51'),
(9, 1, 10, 11875000.00, 'Barakallah untuk semua yang terlibat.', 0, 'pending', NULL, '2026-10-18 09:47:51', '2026-10-18 09:47:51'),
(10, 1, 10, 5935000.00, 'Semangat terus!', 0, 'paid', '2026-10-06 22:52:51', '2026-10-06 15:50:51', '2026-10-06 15:50:51'),
(11, 1, 9, 11875000.00, 'Titipan dari kami sekeluarga.', 0, 'paid', '2026-10-12 13:49:51', '2026-10-12 06:47:51', '2026-10-12 06:47:51'),
(12, 2, 11, 565000.00, 'Semoga lekas membaik.', 0, 'paid', '2026-10-14 01:34:51', '2026-10-13 18:32:51', '2026-10-13 18:32:51'),
(13, 2, 8, 55000.00, 'Titipan dari kami sekeluarga.', 0, 'paid', '2026-10-09 08:48:51', '2026-10-09 01:46:51', '2026-10-09 01:46:51'),
(14, 2, 10, 14075000.00, NULL, 0, 'paid', '2026-10-17 12:38:51', '2026-10-17 05:36:51', '2026-10-17 05:36:51'),
(15, 2, 5, 565000.00, NULL, 1, 'paid', '2026-10-18 04:02:51', '2026-10-17 21:00:51', '2026-10-17 21:00:51'),
(16, 2, 7, 2815000.00, NULL, 0, 'paid', '2026-10-18 06:54:51', '2026-10-17 23:52:51', '2026-10-17 23:52:51'),
(17, 2, 8, 140000.00, NULL, 1, 'paid', '2026-10-13 04:04:51', '2026-10-12 21:02:51', '2026-10-12 21:02:51'),
(18, 2, 9, 280000.00, 'Semoga bermanfaat.', 0, 'failed', NULL, '2026-10-09 20:17:51', '2026-10-09 20:17:51'),
(19, 2, 12, 55000.00, 'Semoga bermanfaat.', 1, 'paid', '2026-10-09 22:43:51', '2026-10-09 15:41:51', '2026-10-09 15:41:51'),
(20, 2, 9, 14075000.00, 'Barakallah untuk semua yang terlibat.', 0, 'paid', '2026-10-18 05:53:51', '2026-10-17 22:51:51', '2026-10-17 22:51:51'),
(21, 2, 11, 1405000.00, NULL, 0, 'paid', '2026-10-04 02:17:51', '2026-10-03 19:15:51', '2026-10-03 19:15:51'),
(22, 3, 5, 132900000.00, 'Semoga bermanfaat.', 0, 'pending', NULL, '2026-10-01 03:03:51', '2026-10-01 03:03:51'),
(23, 3, 10, 5315000.00, 'Semoga bermanfaat.', 0, 'pending', NULL, '2026-10-12 02:53:51', '2026-10-12 02:53:51'),
(24, 3, 5, 1330000.00, 'Titipan dari kami sekeluarga.', 0, 'paid', '2026-10-07 11:02:51', '2026-10-07 04:00:51', '2026-10-07 04:00:51'),
(25, 3, 12, 1330000.00, 'Semoga lekas membaik.', 0, 'paid', '2026-10-19 02:33:51', '2026-10-18 19:31:51', '2026-10-18 19:31:51'),
(26, 3, 8, 26580000.00, NULL, 0, 'failed', NULL, '2026-10-08 21:32:51', '2026-10-08 21:32:51'),
(27, 3, 6, 53160000.00, 'Semoga lekas membaik.', 0, 'paid', '2026-10-09 18:39:51', '2026-10-09 11:37:51', '2026-10-09 11:37:51'),
(28, 3, 9, 13290000.00, 'Titipan dari kami sekeluarga.', 0, 'failed', NULL, '2026-10-05 19:54:51', '2026-10-05 19:54:51'),
(29, 3, 6, 530000.00, NULL, 0, 'paid', '2026-10-03 18:30:51', '2026-10-03 11:28:51', '2026-10-03 11:28:51'),
(30, 3, 11, 13290000.00, NULL, 0, 'paid', '2026-10-12 03:21:51', '2026-10-11 20:19:51', '2026-10-11 20:19:51'),
(31, 3, 9, 13290000.00, NULL, 0, 'paid', '2026-10-05 03:31:51', '2026-10-04 20:29:51', '2026-10-04 20:29:51'),
(32, 3, 6, 1330000.00, NULL, 0, 'paid', '2026-10-03 23:26:51', '2026-10-03 16:24:51', '2026-10-03 16:24:51'),
(33, 3, 5, 53160000.00, NULL, 0, 'paid', '2026-10-17 15:21:51', '2026-10-17 08:19:51', '2026-10-17 08:19:51'),
(34, 3, 11, 26580000.00, 'Semoga bermanfaat.', 1, 'paid', '2026-10-04 22:18:51', '2026-10-04 15:16:51', '2026-10-04 15:16:51'),
(35, 4, 12, 21645000.00, 'Semoga bermanfaat.', 0, 'paid', '2026-10-17 00:19:51', '2026-10-16 17:17:51', '2026-10-16 17:17:51'),
(36, 4, 5, 4330000.00, 'Semoga lekas membaik.', 0, 'pending', NULL, '2026-10-08 23:53:51', '2026-10-08 23:53:51'),
(37, 4, 7, 8660000.00, 'Semoga lekas membaik.', 0, 'paid', '2026-10-02 20:51:51', '2026-10-02 13:49:51', '2026-10-02 13:49:51'),
(38, 4, 5, 21645000.00, NULL, 1, 'paid', '2026-10-03 15:48:51', '2026-10-03 08:46:51', '2026-10-03 08:46:51'),
(39, 4, 10, 865000.00, NULL, 0, 'pending', NULL, '2026-10-17 03:33:51', '2026-10-17 03:33:51'),
(40, 4, 6, 435000.00, NULL, 0, 'paid', '2026-10-01 17:59:51', '2026-10-01 10:57:51', '2026-10-01 10:57:51'),
(41, 4, 6, 215000.00, NULL, 0, 'pending', NULL, '2026-10-01 19:47:51', '2026-10-01 19:47:51'),
(42, 4, 9, 435000.00, 'Titipan dari kami sekeluarga.', 0, 'paid', '2026-10-06 08:44:51', '2026-10-06 01:42:51', '2026-10-06 01:42:51'),
(43, 4, 5, 4330000.00, 'Titipan dari kami sekeluarga.', 0, 'paid', '2026-10-13 05:57:51', '2026-10-12 22:55:51', '2026-10-12 22:55:51'),
(44, 4, 6, 21645000.00, NULL, 0, 'paid', '2026-10-12 16:03:51', '2026-10-12 09:01:51', '2026-10-12 09:01:51'),
(45, 4, 9, 865000.00, 'Barakallah untuk semua yang terlibat.', 0, 'paid', '2026-10-10 17:07:51', '2026-10-10 10:05:51', '2026-10-10 10:05:51'),
(46, 4, 5, 865000.00, NULL, 0, 'pending', NULL, '2026-10-18 20:17:51', '2026-10-18 20:17:51'),
(47, 4, 7, 2165000.00, 'Semangat terus!', 0, 'paid', '2026-10-05 13:00:51', '2026-10-05 05:58:51', '2026-10-05 05:58:51'),
(48, 4, 11, 85000.00, NULL, 0, 'paid', '2026-10-10 21:05:51', '2026-10-10 14:03:51', '2026-10-10 14:03:51'),
(49, 5, 10, 1435000.00, 'Barakallah untuk semua yang terlibat.', 0, 'paid', '2026-10-12 06:35:51', '2026-10-11 23:33:51', '2026-10-11 23:33:51'),
(50, 5, 9, 2865000.00, 'Semoga bermanfaat.', 0, 'pending', NULL, '2026-10-02 20:55:51', '2026-10-02 20:55:51'),
(51, 5, 7, 2865000.00, 'Semoga lekas membaik.', 0, 'paid', '2026-10-13 13:58:51', '2026-10-13 06:56:51', '2026-10-13 06:56:51'),
(52, 5, 10, 285000.00, 'Semoga bermanfaat.', 0, 'paid', '2026-10-08 19:43:51', '2026-10-08 12:41:51', '2026-10-08 12:41:51'),
(53, 5, 10, 145000.00, 'Semoga lekas membaik.', 1, 'paid', '2026-10-05 02:11:51', '2026-10-04 19:09:51', '2026-10-04 19:09:51'),
(54, 5, 12, 30000.00, 'Titipan dari kami sekeluarga.', 0, 'paid', '2026-10-11 17:23:51', '2026-10-11 10:21:51', '2026-10-11 10:21:51'),
(55, 5, 11, 7170000.00, NULL, 0, 'paid', '2026-10-09 01:22:51', '2026-10-08 18:20:51', '2026-10-08 18:20:51'),
(56, 5, 10, 70000.00, 'Barakallah untuk semua yang terlibat.', 0, 'paid', '2026-10-07 11:13:51', '2026-10-07 04:11:51', '2026-10-07 04:11:51'),
(57, 6, 5, 1230000.00, 'Barakallah untuk semua yang terlibat.', 0, 'paid', '2026-10-03 06:56:51', '2026-10-02 23:54:51', '2026-10-02 23:54:51'),
(58, 6, 11, 50000.00, NULL, 1, 'pending', NULL, '2026-10-14 10:59:51', '2026-10-14 10:59:51'),
(59, 6, 12, 50000.00, NULL, 0, 'paid', '2026-10-11 01:30:51', '2026-10-10 18:28:51', '2026-10-10 18:28:51'),
(60, 6, 11, 1230000.00, 'Titipan dari kami sekeluarga.', 0, 'paid', '2026-10-09 04:54:51', '2026-10-08 21:52:51', '2026-10-08 21:52:51'),
(61, 6, 6, 50000.00, NULL, 0, 'paid', '2026-10-16 21:04:51', '2026-10-16 14:02:51', '2026-10-16 14:02:51'),
(62, 6, 7, 125000.00, NULL, 0, 'paid', '2026-10-14 02:28:51', '2026-10-13 19:26:51', '2026-10-13 19:26:51'),
(63, 6, 7, 245000.00, 'Titipan dari kami sekeluarga.', 0, 'failed', NULL, '2026-10-06 13:33:51', '2026-10-06 13:33:51'),
(64, 6, 12, 1230000.00, 'Semoga bermanfaat.', 1, 'paid', '2026-10-02 05:04:51', '2026-10-01 22:02:51', '2026-10-01 22:02:51'),
(65, 6, 9, 245000.00, NULL, 0, 'paid', '2026-10-06 03:11:51', '2026-10-05 20:09:51', '2026-10-05 20:09:51'),
(66, 6, 11, 50000.00, 'Semangat terus!', 0, 'paid', '2026-10-12 12:47:51', '2026-10-12 05:45:51', '2026-10-12 05:45:51'),
(67, 6, 7, 12295000.00, NULL, 0, 'paid', '2026-09-29 13:31:51', '2026-09-29 06:29:51', '2026-09-29 06:29:51'),
(68, 6, 5, 100000.00, 'semangat yaa', 0, 'paid', '2026-10-19 14:27:26', '2026-10-19 07:27:12', '2026-10-19 07:27:26'),
(69, 6, 5, 500000.00, NULL, 0, 'paid', '2026-10-19 15:24:32', '2026-10-19 08:12:55', '2026-10-19 08:24:32'),
(70, 2, 5, 1000000.00, 'semangaatt', 0, 'paid', '2026-10-19 15:28:37', '2026-10-19 08:28:34', '2026-10-19 08:28:37'),
(71, 4, 5, 250000.00, 'semangat', 1, 'paid', '2026-10-19 16:03:45', '2026-10-19 09:03:19', '2026-10-19 09:03:45'),
(72, 8, 5, 1000000.00, 'gereja Tuhan membawa berkat', 0, 'paid', '2026-10-19 16:20:54', '2026-10-19 09:20:48', '2026-10-19 09:20:54'),
(73, 4, 5, 250000.00, 'lekas sembuh yaa', 0, 'paid', '2026-10-20 02:26:52', '2026-10-19 19:26:43', '2026-10-19 19:26:52'),
(74, 8, 5, 500000.00, NULL, 0, 'paid', '2026-10-20 02:30:01', '2026-10-19 19:29:56', '2026-10-19 19:30:01'),
(75, 8, 5, 50000.00, 'Tuhan memberkati', 0, 'pending', NULL, '2026-10-20 03:53:25', '2026-10-20 03:53:25'),
(76, 8, 5, 50000.00, 'Tuhan memberkati', 0, 'paid', '2026-10-20 10:56:38', '2026-10-20 03:55:42', '2026-10-20 03:56:38'),
(77, 8, 5, 500000.00, NULL, 0, 'paid', '2026-10-20 10:59:22', '2026-10-20 03:59:18', '2026-10-20 03:59:22'),
(78, 11, 12, 2000000.00, 'semangat', 0, 'failed', NULL, '2026-10-23 13:05:14', '2026-10-23 13:11:18'),
(79, 11, 12, 2000000.00, 'semangat', 0, 'paid', '2026-10-23 20:07:45', '2026-10-23 13:07:21', '2026-10-23 13:07:45'),
(80, 11, 12, 1000.00, NULL, 1, 'paid', '2026-10-23 20:09:42', '2026-10-23 13:09:31', '2026-10-23 13:09:42'),
(81, 8, 5, 50000.00, NULL, 0, 'paid', '2026-10-25 08:34:17', '2026-10-25 01:34:12', '2026-10-25 01:34:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fund_distributions`
--

CREATE TABLE `fund_distributions` (
  `id_distribution` int(10) UNSIGNED NOT NULL,
  `id_campaign` int(10) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `recipient` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `distributed_at` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data untuk tabel `fund_distributions`
--

INSERT INTO `fund_distributions` (`id_distribution`, `id_campaign`, `amount`, `recipient`, `description`, `proof_file`, `distributed_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 40000000.00, 'Toko Bangunan Jaya Abadi', 'Pembelian material atap seng dan rangka baja ringan tahap pertama.', NULL, '2026-10-13', 1, '2026-10-13 04:41:51', '2026-10-13 04:41:51'),
(2, 3, 25000000.00, 'Posko Pengungsian Stabat', 'Pembelian makanan siap saji, selimut, dan obat-obatan untuk 60 keluarga.', NULL, '2026-10-15', 1, '2026-10-15 04:41:51', '2026-10-15 04:41:51'),
(3, 3, 60000000.00, 'kepala desa', 'Pembelian dan pembagian 250 paket sembako. Rincian: 250 paket x Rp 50.000 = Rp 12.500.000. Setiap paket berisi beras 5 kg, minyak goreng 1 liter, telur 1 kg, mi instan 10 bungkus, dan gula 1 kg. Dibagikan oleh relawan bersama perangkat desa.', NULL, '2026-10-23', 1, '2026-10-23 07:02:12', '2026-10-23 07:02:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_19_000001_create_categories_table', 1),
(5, '2026_10_19_000002_create_campaigns_table', 1),
(6, '2026_10_19_000003_create_donations_table', 1),
(7, '2026_10_19_000004_create_payments_table', 1),
(8, '2026_10_19_000005_create_certificates_table', 1),
(9, '2026_10_19_000006_create_campaign_updates_table', 1),
(10, '2026_10_19_000007_create_fund_distributions_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id_payment` int(10) UNSIGNED NOT NULL,
  `id_donation` int(10) UNSIGNED NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `gateway_status` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id_payment`, `id_donation`, `order_id`, `transaction_id`, `method`, `gateway_status`, `created_at`, `updated_at`) VALUES
(1, 1, 'DON-20261012-1-1', NULL, 'QRIS', 'settlement', '2026-10-12 06:09:51', '2026-10-12 06:09:51'),
(2, 2, 'DON-20261003-2-1', NULL, 'QRIS', 'settlement', '2026-10-02 21:52:51', '2026-10-02 21:52:51'),
(3, 3, 'DON-20261001-3-1', NULL, 'Virtual Account', 'settlement', '2026-10-01 11:35:51', '2026-10-01 11:35:51'),
(4, 4, 'DON-20261003-4-1', NULL, 'Virtual Account', 'settlement', '2026-10-03 06:29:51', '2026-10-03 06:29:51'),
(5, 5, 'DON-20261013-5-1', NULL, 'QRIS', 'settlement', '2026-10-13 14:42:51', '2026-10-13 14:42:51'),
(6, 6, 'DON-20261001-6-1', NULL, 'E-Wallet', 'pending', '2026-10-01 08:11:51', '2026-10-01 08:11:51'),
(7, 7, 'DON-20261009-7-1', NULL, 'QRIS', 'settlement', '2026-10-08 22:20:51', '2026-10-08 22:20:51'),
(8, 8, 'DON-20261009-8-1', NULL, 'E-Wallet', 'settlement', '2026-10-09 15:05:51', '2026-10-09 15:05:51'),
(9, 9, 'DON-20261018-9-1', NULL, 'E-Wallet', 'pending', '2026-10-18 09:47:51', '2026-10-18 09:47:51'),
(10, 10, 'DON-20261006-10-1', NULL, 'QRIS', 'settlement', '2026-10-06 15:50:51', '2026-10-06 15:50:51'),
(11, 11, 'DON-20261012-11-1', NULL, 'Virtual Account', 'settlement', '2026-10-12 06:47:51', '2026-10-12 06:47:51'),
(12, 12, 'DON-20261014-12-1', NULL, 'E-Wallet', 'settlement', '2026-10-13 18:32:51', '2026-10-13 18:32:51'),
(13, 13, 'DON-20261009-13-1', NULL, 'Virtual Account', 'settlement', '2026-10-09 01:46:51', '2026-10-09 01:46:51'),
(14, 14, 'DON-20261017-14-1', NULL, 'QRIS', 'settlement', '2026-10-17 05:36:51', '2026-10-17 05:36:51'),
(15, 15, 'DON-20261018-15-1', NULL, 'Virtual Account', 'settlement', '2026-10-17 21:00:51', '2026-10-17 21:00:51'),
(16, 16, 'DON-20261018-16-1', NULL, 'Virtual Account', 'settlement', '2026-10-17 23:52:51', '2026-10-17 23:52:51'),
(17, 17, 'DON-20261013-17-1', NULL, 'QRIS', 'settlement', '2026-10-12 21:02:51', '2026-10-12 21:02:51'),
(18, 18, 'DON-20261010-18-1', NULL, 'E-Wallet', 'deny', '2026-10-09 20:17:51', '2026-10-09 20:17:51'),
(19, 19, 'DON-20261009-19-1', NULL, 'QRIS', 'settlement', '2026-10-09 15:41:51', '2026-10-09 15:41:51'),
(20, 20, 'DON-20261018-20-1', NULL, 'E-Wallet', 'settlement', '2026-10-17 22:51:51', '2026-10-17 22:51:51'),
(21, 21, 'DON-20261004-21-1', NULL, 'QRIS', 'settlement', '2026-10-03 19:15:51', '2026-10-03 19:15:51'),
(22, 22, 'DON-20261001-22-1', NULL, 'Virtual Account', 'pending', '2026-10-01 03:03:51', '2026-10-01 03:03:51'),
(23, 23, 'DON-20261012-23-1', NULL, 'QRIS', 'pending', '2026-10-12 02:53:51', '2026-10-12 02:53:51'),
(24, 24, 'DON-20261007-24-1', NULL, 'Virtual Account', 'settlement', '2026-10-07 04:00:51', '2026-10-07 04:00:51'),
(25, 25, 'DON-20261019-25-1', NULL, 'E-Wallet', 'settlement', '2026-10-18 19:31:51', '2026-10-18 19:31:51'),
(26, 26, 'DON-20261009-26-1', NULL, 'Virtual Account', 'deny', '2026-10-08 21:32:51', '2026-10-08 21:32:51'),
(27, 27, 'DON-20261009-27-1', NULL, 'Virtual Account', 'settlement', '2026-10-09 11:37:51', '2026-10-09 11:37:51'),
(28, 28, 'DON-20261006-28-1', NULL, 'E-Wallet', 'deny', '2026-10-05 19:54:51', '2026-10-05 19:54:51'),
(29, 29, 'DON-20261003-29-1', NULL, 'QRIS', 'settlement', '2026-10-03 11:28:51', '2026-10-03 11:28:51'),
(30, 30, 'DON-20261012-30-1', NULL, 'Virtual Account', 'settlement', '2026-10-11 20:19:51', '2026-10-11 20:19:51'),
(31, 31, 'DON-20261005-31-1', NULL, 'E-Wallet', 'settlement', '2026-10-04 20:29:51', '2026-10-04 20:29:51'),
(32, 32, 'DON-20261003-32-1', NULL, 'E-Wallet', 'settlement', '2026-10-03 16:24:51', '2026-10-03 16:24:51'),
(33, 33, 'DON-20261017-33-1', NULL, 'Virtual Account', 'settlement', '2026-10-17 08:19:51', '2026-10-17 08:19:51'),
(34, 34, 'DON-20261004-34-1', NULL, 'QRIS', 'settlement', '2026-10-04 15:16:51', '2026-10-04 15:16:51'),
(35, 35, 'DON-20261017-35-1', NULL, 'Virtual Account', 'settlement', '2026-10-16 17:17:51', '2026-10-16 17:17:51'),
(36, 36, 'DON-20261009-36-1', NULL, 'QRIS', 'pending', '2026-10-08 23:53:51', '2026-10-08 23:53:51'),
(37, 37, 'DON-20261002-37-1', NULL, 'QRIS', 'settlement', '2026-10-02 13:49:51', '2026-10-02 13:49:51'),
(38, 38, 'DON-20261003-38-1', NULL, 'Virtual Account', 'settlement', '2026-10-03 08:46:51', '2026-10-03 08:46:51'),
(39, 39, 'DON-20261017-39-1', NULL, 'E-Wallet', 'pending', '2026-10-17 03:33:51', '2026-10-17 03:33:51'),
(40, 40, 'DON-20261001-40-1', NULL, 'E-Wallet', 'settlement', '2026-10-01 10:57:51', '2026-10-01 10:57:51'),
(41, 41, 'DON-20261002-41-1', NULL, 'E-Wallet', 'pending', '2026-10-01 19:47:51', '2026-10-01 19:47:51'),
(42, 42, 'DON-20261006-42-1', NULL, 'QRIS', 'settlement', '2026-10-06 01:42:51', '2026-10-06 01:42:51'),
(43, 43, 'DON-20261013-43-1', NULL, 'QRIS', 'settlement', '2026-10-12 22:55:51', '2026-10-12 22:55:51'),
(44, 44, 'DON-20261012-44-1', NULL, 'E-Wallet', 'settlement', '2026-10-12 09:01:51', '2026-10-12 09:01:51'),
(45, 45, 'DON-20261010-45-1', NULL, 'QRIS', 'settlement', '2026-10-10 10:05:51', '2026-10-10 10:05:51'),
(46, 46, 'DON-20261019-46-1', NULL, 'QRIS', 'pending', '2026-10-18 20:17:51', '2026-10-18 20:17:51'),
(47, 47, 'DON-20261005-47-1', NULL, 'E-Wallet', 'settlement', '2026-10-05 05:58:51', '2026-10-05 05:58:51'),
(48, 48, 'DON-20261010-48-1', NULL, 'QRIS', 'settlement', '2026-10-10 14:03:51', '2026-10-10 14:03:51'),
(49, 49, 'DON-20261012-49-1', NULL, 'QRIS', 'settlement', '2026-10-11 23:33:51', '2026-10-11 23:33:51'),
(50, 50, 'DON-20261003-50-1', NULL, 'QRIS', 'pending', '2026-10-02 20:55:51', '2026-10-02 20:55:51'),
(51, 51, 'DON-20261013-51-1', NULL, 'E-Wallet', 'settlement', '2026-10-13 06:56:51', '2026-10-13 06:56:51'),
(52, 52, 'DON-20261008-52-1', NULL, 'E-Wallet', 'settlement', '2026-10-08 12:41:51', '2026-10-08 12:41:51'),
(53, 53, 'DON-20261005-53-1', NULL, 'Virtual Account', 'settlement', '2026-10-04 19:09:51', '2026-10-04 19:09:51'),
(54, 54, 'DON-20261011-54-1', NULL, 'E-Wallet', 'settlement', '2026-10-11 10:21:51', '2026-10-11 10:21:51'),
(55, 55, 'DON-20261009-55-1', NULL, 'E-Wallet', 'settlement', '2026-10-08 18:20:51', '2026-10-08 18:20:51'),
(56, 56, 'DON-20261007-56-1', NULL, 'Virtual Account', 'settlement', '2026-10-07 04:11:51', '2026-10-07 04:11:51'),
(57, 57, 'DON-20261003-57-1', NULL, 'QRIS', 'settlement', '2026-10-02 23:54:51', '2026-10-02 23:54:51'),
(58, 58, 'DON-20261014-58-1', NULL, 'QRIS', 'pending', '2026-10-14 10:59:51', '2026-10-14 10:59:51'),
(59, 59, 'DON-20261011-59-1', NULL, 'QRIS', 'settlement', '2026-10-10 18:28:51', '2026-10-10 18:28:51'),
(60, 60, 'DON-20261009-60-1', NULL, 'QRIS', 'settlement', '2026-10-08 21:52:51', '2026-10-08 21:52:51'),
(61, 61, 'DON-20261016-61-1', NULL, 'Virtual Account', 'settlement', '2026-10-16 14:02:51', '2026-10-16 14:02:51'),
(62, 62, 'DON-20261014-62-1', NULL, 'E-Wallet', 'settlement', '2026-10-13 19:26:51', '2026-10-13 19:26:51'),
(63, 63, 'DON-20261006-63-1', NULL, 'Virtual Account', 'deny', '2026-10-06 13:33:51', '2026-10-06 13:33:51'),
(64, 64, 'DON-20261002-64-1', NULL, 'Virtual Account', 'settlement', '2026-10-01 22:02:51', '2026-10-01 22:02:51'),
(65, 65, 'DON-20261006-65-1', NULL, 'Virtual Account', 'settlement', '2026-10-05 20:09:51', '2026-10-05 20:09:51'),
(66, 66, 'DON-20261012-66-1', NULL, 'QRIS', 'settlement', '2026-10-12 05:45:51', '2026-10-12 05:45:51'),
(67, 67, 'DON-20260929-67-1', NULL, 'QRIS', 'settlement', '2026-09-29 06:29:51', '2026-09-29 06:29:51'),
(68, 68, 'EDON-1792420032-2340', 'SIM-1792420046-139', 'GoPay / QRIS', 'settlement', '2026-10-19 07:27:12', '2026-10-19 07:27:26'),
(69, 69, 'EDON-1792422775-5606', 'SIM-1792423472-366', 'Kartu Kredit Simulator', 'settlement', '2026-10-19 08:12:55', '2026-10-19 08:24:32'),
(70, 70, 'EDON-1792423714-6531', 'SIM-1792423717-953', 'GoPay / QRIS', 'settlement', '2026-10-19 08:28:34', '2026-10-19 08:28:37'),
(71, 71, 'EDON-1792425799-4184', 'SIM-1792425825-134', 'QRIS', 'settlement', '2026-10-19 09:03:19', '2026-10-19 09:03:45'),
(72, 72, 'EDON-1792426848-6790', 'SIM-1792426854-956', 'QRIS', 'settlement', '2026-10-19 09:20:48', '2026-10-19 09:20:54'),
(73, 73, 'EDON-1792463203-8877', 'SIM-1792463212-557', 'GoPay', 'settlement', '2026-10-19 19:26:43', '2026-10-19 19:26:52'),
(74, 74, 'EDON-1792463396-2949', 'SIM-1792463401-657', 'QRIS', 'settlement', '2026-10-19 19:29:56', '2026-10-19 19:30:01'),
(75, 75, 'EDON-1792468405-5757', NULL, NULL, NULL, '2026-10-20 03:53:25', '2026-10-20 03:53:25'),
(76, 76, 'EDON-1792468542-5073', 'SIM-1792468598-635', 'QRIS', 'settlement', '2026-10-20 03:55:42', '2026-10-20 03:56:38'),
(77, 77, 'EDON-1792468758-6543', 'SIM-1792468762-847', 'QRIS', 'settlement', '2026-10-20 03:59:18', '2026-10-20 03:59:22'),
(78, 78, 'EDON-1792760714-8295', 'SIM-1792761078-342', 'QRIS', 'deny', '2026-10-23 13:05:14', '2026-10-23 13:11:18'),
(79, 79, 'EDON-1792760841-5314', 'SIM-1792760865-994', 'QRIS', 'settlement', '2026-10-23 13:07:21', '2026-10-23 13:07:45'),
(80, 80, 'EDON-1792760971-4661', 'SIM-1792760982-670', 'GoPay', 'settlement', '2026-10-23 13:09:31', '2026-10-23 13:09:42'),
(81, 81, 'EDON-1792892052-2423', 'SIM-1792892057-490', 'QRIS', 'settlement', '2026-10-25 01:34:12', '2026-10-25 01:34:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('donatur','penggalang','admin') NOT NULL DEFAULT 'donatur',
  `phone` varchar(20) DEFAULT NULL,
  `organization_name` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `name`, `email`, `password`, `role`, `phone`, `organization_name`, `is_active`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrator', 'admin@edonation.test', '$2y$12$meHgcIkebBgZuhQxtA8aT.wY3eVUww69WkwxnpdW8JZypv88qfZKe', 'admin', NULL, NULL, 1, NULL, '2026-10-19 01:03:26', '2026-10-19 01:03:26', NULL),
(2, 'Yayasan Pelayanan', 'yayasan@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'penggalang', NULL, 'Lembaya Peduli Lingkungan', 1, NULL, '2026-10-19 04:41:51', '2026-10-20 06:35:29', NULL),
(3, 'Panitia Masjid Al-Ikhlas', 'masjid@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'penggalang', NULL, 'Masjid Al-Ikhlas Medan Timur', 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(4, 'Relawan Peduli Medan', 'relawan@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'penggalang', NULL, 'Komunitas Relawan Peduli Medan', 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(5, 'Budi Santoso', 'budi@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'donatur', NULL, NULL, 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(6, 'Siti Aminah', 'siti@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'donatur', NULL, NULL, 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(7, 'Andi Pratama', 'andi@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'donatur', NULL, NULL, 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(8, 'Dewi Lestari', 'dewi@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'donatur', NULL, NULL, 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(9, 'Rizky Ramadhan', 'rizky@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'donatur', NULL, NULL, 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(10, 'Nur Aisyah', 'aisyah@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'donatur', NULL, NULL, 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(11, 'Fajar Nugroho', 'fajar@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'donatur', NULL, NULL, 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(12, 'Maya Sari', 'maya@demo.test', '$2y$12$MMtyMfXCIqUCp.IdXqW/weilbY0CXSJCXMTwli1b1p.vWwQ4FxHpW', 'donatur', NULL, NULL, 1, NULL, '2026-10-19 04:41:51', '2026-10-19 04:41:51', NULL),
(13, 'Jelita', 'jelita123@gmail.com', '$2y$12$mCSsLFmZYuZYhctyNcw6buXstJAD2Hp3sPJJg1vFuvINBgzjgqIkO', 'donatur', '0812345678', NULL, 1, NULL, '2026-10-19 04:45:40', '2026-10-19 04:45:40', NULL),
(14, 'jelita', 'jelita@gmail.com', '$2y$12$EBvwL3LK2XeWHzADUx5NN.lBnODFG5LGB9N0zI9zznbaTtr8yfjMa', 'donatur', '081234567890', NULL, 1, NULL, '2026-10-20 06:27:21', '2026-10-20 06:27:21', NULL),
(15, 'jelitaa', 'jel123@gmail.com', '$2y$12$SvNgyP26QIwLf3.33LL9jejnhjYKkIY0NDEUFUkv8Iq91RD3F0v1.', 'donatur', '0812345678', NULL, 1, NULL, '2026-10-23 06:43:40', '2026-10-23 06:43:40', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id_campaign`),
  ADD KEY `campaigns_status_deadline_index` (`status`,`deadline`),
  ADD KEY `campaigns_id_user_foreign` (`id_user`),
  ADD KEY `campaigns_id_category_foreign` (`id_category`);

--
-- Indeks untuk tabel `campaign_updates`
--
ALTER TABLE `campaign_updates`
  ADD PRIMARY KEY (`id_campaign_update`),
  ADD KEY `campaign_updates_id_campaign_index` (`id_campaign`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indeks untuk tabel `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id_certificate`),
  ADD UNIQUE KEY `certificates_id_donation_unique` (`id_donation`),
  ADD UNIQUE KEY `certificates_certificate_number_unique` (`certificate_number`);

--
-- Indeks untuk tabel `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id_donation`),
  ADD KEY `donations_id_campaign_status_index` (`id_campaign`,`status`),
  ADD KEY `donations_id_user_status_index` (`id_user`,`status`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `fund_distributions`
--
ALTER TABLE `fund_distributions`
  ADD PRIMARY KEY (`id_distribution`),
  ADD KEY `fund_distributions_id_campaign_index` (`id_campaign`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment`),
  ADD UNIQUE KEY `payments_order_id_unique` (`order_id`),
  ADD KEY `payments_id_donation_foreign` (`id_donation`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_index` (`role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id_campaign` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `campaign_updates`
--
ALTER TABLE `campaign_updates`
  MODIFY `id_campaign_update` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id_certificate` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT untuk tabel `donations`
--
ALTER TABLE `donations`
  MODIFY `id_donation` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `fund_distributions`
--
ALTER TABLE `fund_distributions`
  MODIFY `id_distribution` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_id_category_foreign` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON UPDATE CASCADE,
  ADD CONSTRAINT `campaigns_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `campaign_updates`
--
ALTER TABLE `campaign_updates`
  ADD CONSTRAINT `campaign_updates_id_campaign_foreign` FOREIGN KEY (`id_campaign`) REFERENCES `campaigns` (`id_campaign`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_id_donation_foreign` FOREIGN KEY (`id_donation`) REFERENCES `donations` (`id_donation`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_id_campaign_foreign` FOREIGN KEY (`id_campaign`) REFERENCES `campaigns` (`id_campaign`) ON UPDATE CASCADE,
  ADD CONSTRAINT `donations_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `fund_distributions`
--
ALTER TABLE `fund_distributions`
  ADD CONSTRAINT `fund_distributions_id_campaign_foreign` FOREIGN KEY (`id_campaign`) REFERENCES `campaigns` (`id_campaign`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_id_donation_foreign` FOREIGN KEY (`id_donation`) REFERENCES `donations` (`id_donation`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
