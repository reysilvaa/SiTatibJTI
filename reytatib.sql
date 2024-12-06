-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Des 2023 pada 11.32
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reytatib`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetMahasiswaInfo` (IN `username` VARCHAR(255))   BEGIN
    -- Ambil informasi mahasiswa berdasarkan username
    SELECT u.*, m.nama
    FROM user u
    JOIN mahasiswa m ON u.id_user = m.id_user
    WHERE u.id_user = username;

    -- Ambil jumlah pelaporan yang telah diterima oleh mahasiswa
    SELECT COUNT(*) AS jumlahPelaporanDiterima
    FROM pelaporan
    WHERE (status_pelanggaran = 'ditolak' OR status_pelanggaran = 'dilaporkan')
    AND id_mahasiswa = username;

    -- Ambil jumlah pelaporan yang masih menunggu verifikasi
    SELECT COUNT(*) AS jumlahPelaporanMenungguVerifikasi
    FROM pelaporan
    WHERE status_pelanggaran = 'menunggu_verifikasi'
    AND id_mahasiswa = username;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetPelanggaranByTingkat` (IN `tingkat_id` INT)   BEGIN
    SELECT p.id_pelanggaran, p.nama_pelanggaran 
    FROM pelanggaran p 
    INNER JOIN tingkat t ON p.id_tingkat = t.id_tingkat 
    WHERE t.id_tingkat = tingkat_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetPelaporanData` (IN `username` VARCHAR(255))   BEGIN
    SELECT *, p.tgl_lapor, p.tgl_konfirmasi, d.nama AS pelapor, pg.nama_pelanggaran AS nama_pelanggaran
    FROM pelaporan p
    INNER JOIN pelanggaran pg ON p.id_pelanggaran = pg.id_pelanggaran
    INNER JOIN tingkat t ON pg.id_tingkat = t.id_tingkat
    INNER JOIN sanksi s ON p.id_sanksi = s.id_sanksi
    INNER JOIN dosen d ON p.id_dosen = d.id_dosen
    WHERE p.id_mahasiswa = username;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetSanksiByTingkat` (IN `tingkat_id` INT)   BEGIN
    SELECT id_sanksi, jenis_sanksi
    FROM sanksi
    WHERE id_tingkat = tingkat_id;
END$$

--
-- Fungsi
--
CREATE DEFINER=`root`@`localhost` FUNCTION `RemoveDpaFromOldClass` (`p_oldClassId` INT) RETURNS INT(11)  BEGIN
    DECLARE rows_affected INT;

    UPDATE kelasdpa SET id_dosen = NULL WHERE id_kelas = p_oldClassId;
    SET rows_affected = ROW_COUNT();

    RETURN rows_affected;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `UpdateNamaUser` (`p_username` VARCHAR(20), `p_nama` VARCHAR(20), `p_role` VARCHAR(20)) RETURNS INT(11)  BEGIN
    DECLARE rows_affected INT;

    IF p_role = 'dosen' THEN
        UPDATE dosen SET nama = p_nama WHERE id_dosen = p_username;
        SET rows_affected = ROW_COUNT();
    ELSEIF p_role = 'dosen_admin' THEN
        UPDATE dosen SET nama = p_nama WHERE id_dosen = p_username;
        -- ...
        SET rows_affected = -2; -- Nilai negatif lain untuk dosen_admin
    ELSEIF p_role = 'dosen_dpa' THEN
        UPDATE dosen SET nama = p_nama WHERE id_dosen = p_username;
        -- ...
        SET rows_affected = -3; -- Nilai negatif lain untuk dosen_dpa
    ELSEIF p_role = 'dosen_dpa_admin' THEN
        UPDATE dosen SET nama = p_nama WHERE id_dosen = p_username;
        -- ...
        SET rows_affected = -4; -- Nilai negatif lain untuk dosen_dpa_admin
    ELSEIF p_role = 'mahasiswa' THEN
        UPDATE mahasiswa SET nama = p_nama WHERE id_mahasiswa = p_username;
        -- ...
        SET rows_affected = -5; -- Nilai negatif lain untuk mahasiswa
    ELSE
        SET rows_affected = -1; -- Nilai negatif untuk peran tidak didukung
    END IF;

    RETURN rows_affected;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id_dosen` varchar(20) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id_dosen`, `nama`, `jabatan`, `id_user`) VALUES
('000123', NULL, NULL, 52),
('0987', 'atha3', NULL, 42),
('12345', NULL, NULL, 58),
('2241720223', 'Bagus Wahasdwika', NULL, 56),
('224411321', NULL, NULL, 55),
('admin', NULL, NULL, 32),
('admin123', 'rusdi', NULL, 62),
('c', NULL, NULL, 78),
('coo', NULL, NULL, 74),
('dosen', 'sri', 'dosen', 29),
('dosenadmin', 'Abiyyu', NULL, 65),
('dpa', NULL, NULL, 31),
('dpa123', NULL, NULL, 60),
('dpa1234', 'Athacok', NULL, 73),
('dpa2', NULL, NULL, 49),
('dpa2A', 'Sugik', NULL, 66),
('dpa3A', 'Atha', NULL, 67),
('dpa5A', NULL, NULL, 75),
('dpaadmin', 'Halo', NULL, 69),
('dpanew', 'tes', NULL, 70),
('dpatess', 'Valencia Prenly', NULL, 71),
('dpatess2', 'Tes', NULL, 72),
('dsn', NULL, NULL, 77),
('ikiadmin', 'rusdi', NULL, 54),
('ikidosen', 'ikidosen2', NULL, 41),
('ikidosendpa', 'rendi', NULL, 64),
('ranu', 'khalid', NULL, 39),
('sa', NULL, NULL, 79),
('sdc', 'yayan', NULL, 43),
('tes', 'Arul', NULL, 38),
('tesdosen', NULL, NULL, 51);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelasdpa`
--

CREATE TABLE `kelasdpa` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(50) DEFAULT NULL,
  `prodi` enum('TI','SIB') DEFAULT NULL,
  `id_dosen` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelasdpa`
--

INSERT INTO `kelasdpa` (`id_kelas`, `nama_kelas`, `prodi`, `id_dosen`) VALUES
(12, '5A', 'TI', NULL),
(18, '3B', 'TI', 'dpanew'),
(20, '5B', 'TI', 'coo'),
(22, '7B', 'TI', 'dpa5A'),
(23, '8B', 'TI', 'dpaadmin'),
(24, '1C', 'TI', NULL),
(25, '2C', 'TI', NULL),
(28, '5C', 'TI', NULL),
(30, '7C', 'TI', NULL),
(31, '8C', 'TI', NULL),
(33, '2D', 'TI', NULL),
(34, '3D', 'TI', 'dpa2A'),
(35, '4D', 'TI', 'dpanew'),
(36, '5D', 'TI', 'dpanew'),
(37, '6D', 'TI', 'dpanew'),
(38, '7D', 'TI', 'dpa1234'),
(39, '8D', 'TI', NULL),
(40, '1E', 'TI', NULL),
(41, '2E', 'TI', NULL),
(42, '3E', 'TI', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` varchar(20) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `id_tingkat` int(11) DEFAULT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `nama`, `id_tingkat`, `id_kelas`, `id_user`) VALUES
('2241720215', 'agusty labdana', NULL, 18, 57),
('2241720249', 'Atha Abiyyu', NULL, 18, 63),
('m', NULL, NULL, NULL, 76);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggaran`
--

CREATE TABLE `pelanggaran` (
  `id_pelanggaran` int(11) NOT NULL,
  `nama_pelanggaran` longtext NOT NULL,
  `id_tingkat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggaran`
--

INSERT INTO `pelanggaran` (`id_pelanggaran`, `nama_pelanggaran`, `id_tingkat`) VALUES
(1, 'Berkomunikasi dengan tidak sopan, baik tertulis atau tidak tertulis kepada mahasiswa, dosen, karyawan, atau orang lain', 5),
(2, 'Berbusana tidak sopan dan tidak rapi. Yaitu antara lain adalah: berpakaian ketat, transparan, memakai t-shirt (baju kaos tidak berkerah), tank top, hipster, you can see, rok mini, backless, celana pendek, celana tiga per empat, legging, model celana atau baju koyak, sandal, sepatu sandal di lingkungan kampus', 4),
(3, 'Mahasiswa laki-laki berambut tidak rapi, gondrong yaitu panjang rambutnya melewati batas alis mata di bagian depan, telinga di bagian samping atau menyentuh kerah baju di bagian leher', 4),
(4, 'Mahasiswa berambut dengan model punk, dicat selain hitam dan/atau skinned.', 4),
(5, 'Makan, atau minum di dalam ruang kuliah/ laboratorium/ bengkel.', 4),
(6, 'Melanggar peraturan/ ketentuan yang berlaku di Polinema baik di Jurusan/ Program Studi', 3),
(7, 'Tidak menjaga kebersihan di seluruh area Polinema', 3),
(8, 'Membuat kegaduhan yang mengganggu pelaksanaan perkuliahan atau praktikum yang sedang berlangsung.', 3),
(9, 'Merokok di luar area kawasan merokok', 3),
(10, 'Bermain kartu, game online di area kampus', 3),
(11, 'Mengotori atau mencoret-coret meja, kursi, tembok, dan lain-lain di lingkungan Polinema', 3),
(12, 'Bertingkah laku kasar atau tidak sopan kepada mahasiswa, dosen, dan/atau karyawan', 3),
(13, 'Merusak sarana dan prasarana yang ada di area Polinema', 2),
(14, 'Tidak menjaga ketertiban dan keamanan di seluruh area Polinema (misalnya: parkir tidak pada tempatnya, konvoi selebrasi wisuda dll)', 2),
(15, 'Melakukan pengotoran/pengrusakan barang milik orang lain termasuk milik Politeknik Negeri Malang', 2),
(16, 'Mengakses materi pornografi di kelas atau area kampus', 2),
(17, 'Membawa dan/atau menggunakan senjata tajam dan/atau senjata api untuk hal kriminal', 2),
(18, 'Melakukan perkelahian, serta membentuk geng/kelompok yang bertujuan negatif', 2),
(19, 'Melakukan kegiatan politik praktis di dalam kampus', 2),
(20, 'Melakukan tindakan kekerasan atau perkelahian di dalam kampus', 2),
(21, 'Melakukan penyalahgunaan identitas untuk perbuatan negatif', 2),
(22, 'Mengancam, baik tertulis atau tidak tertulis kepada mahasiswa, dosen, dan/atau karyawan', 2),
(23, 'Mencuri dalam bentuk apapun', 2),
(24, 'Melakukan kecurangan dalam bidang akademik, administratif, dan keuangan', 2),
(25, 'Melakukan pemerasan dan/atau penipuan', 2),
(26, 'Melakukan pelecehan dan/atau tindakan asusila dalam segala bentuk di dalam dan di luar kampus', 2),
(27, 'Berjudi, mengkonsumsi minuman keras, dan/atau bermabuk-mabukan di lingkungan dan di luar lingkungan Kampus Polinema', 2),
(28, 'Mengikuti organisasi dan/atau menyebarkan faham-faham yang dilarang oleh Pemerintah', 2),
(29, 'Melakukan pemalsuan data/dokumen/tanda tangan', 2),
(30, 'Melakukan plagiasi (copy paste) dalam tugas-tugas atau karya ilmiah', 2),
(31, 'Tidak menjaga nama baik Polinema di masyarakat dan/atau mencemarkan nama baik Polinema melalui media apapun', 1),
(32, 'Melakukan kegiatan atau sejenisnya yang dapat menurunkan kehormatan atau martabat Negara, Bangsa dan Polinema', 1),
(33, 'Menggunakan barang-barang psikotropika dan/atau zat-zat Adiktif lainnya', 1),
(34, 'Mengedarkan serta menjual barang-barang psikotropika dan/atau zat-zat Adiktif lainnya', 1),
(35, 'Terlibat dalam tindakan kriminal dan dinyatakan bersalah oleh Pengadilan', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelaporan`
--

CREATE TABLE `pelaporan` (
  `id_pelaporan` int(11) NOT NULL,
  `id_dosen` varchar(20) DEFAULT NULL,
  `id_mahasiswa` varchar(20) DEFAULT NULL,
  `id_pelanggaran` int(11) DEFAULT NULL,
  `id_sanksi` int(11) NOT NULL,
  `upload_sanksi` blob DEFAULT NULL,
  `bukti_pelanggaran` blob NOT NULL,
  `status_pelanggaran` enum('ditolak','menunggu_verifikasi','sukses','dilaporkan') DEFAULT NULL,
  `tgl_lapor` varchar(20) NOT NULL,
  `tgl_konfirmasi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelaporan`
--

INSERT INTO `pelaporan` (`id_pelaporan`, `id_dosen`, `id_mahasiswa`, `id_pelanggaran`, `id_sanksi`, `upload_sanksi`, `bukti_pelanggaran`, `status_pelanggaran`, `tgl_lapor`, `tgl_konfirmasi`) VALUES
(229, 'dosenadmin', 'm', 31, 5, 0x53637265656e73686f7420283434292e706e67, '', 'sukses', '2023-12-19 16:55:04', '19-12-2023 17:12:26'),
(230, 'dosenadmin', 'm', 31, 5, 0x53637265656e73686f7420283434292e706e67, '', 'sukses', '2023-12-19 17:05:50', '19-12-2023 17:12:17'),
(231, 'dosenadmin', '2241720215', 31, 10, 0x53637265656e73686f7420283434292e706e67, '', 'sukses', '2023-12-19 17:06:11', '19-12-2023 17:12:21'),
(232, 'dosenadmin', '2241720215', 13, 4, NULL, '', 'dilaporkan', '2023-12-19 17:06:28', ''),
(233, 'dosenadmin', '2241720215', 13, 7, NULL, '', 'dilaporkan', '2023-12-19 17:07:09', ''),
(234, 'dosenadmin', '2241720215', 13, 8, NULL, '', 'dilaporkan', '2023-12-19 17:07:36', ''),
(235, 'dpanew', '2241720215', 6, 9, NULL, '', 'dilaporkan', '2023-12-19 17:09:02', ''),
(236, 'dpanew', '2241720215', 31, 5, 0x53637265656e73686f7420283434292e706e67, '', 'sukses', '2023-12-19 17:09:27', '19-12-2023 17:12:30');

--
-- Trigger `pelaporan`
--
DELIMITER $$
CREATE TRIGGER `trg_UpdateTanggalLapor` BEFORE INSERT ON `pelaporan` FOR EACH ROW BEGIN
    SET NEW.tgl_lapor = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sanksi`
--

CREATE TABLE `sanksi` (
  `id_sanksi` int(11) NOT NULL,
  `jenis_sanksi` text NOT NULL,
  `id_tingkat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sanksi`
--

INSERT INTO `sanksi` (`id_sanksi`, `jenis_sanksi`, `id_tingkat`) VALUES
(1, 'Teguran lisan disertai dengan surat pernyataan tidak mengulangi perbuatan\r\ntersebut, dibubuhi materai, ditandatangani mahasiswa yang bersangkutan dan\r\nDPA', 5),
(2, 'Teguran tertulis disertai dengan surat pernyataan tidak mengulangi perbuatan\r\ntersebut, dibubuhi materai, ditandatangani mahasiswa yang bersangkutan dan\r\nDPA', 4),
(3, 'Membuat surat pernyataan tidak mengulangi perbuatan tersebut, dibubuhi\r\nmaterai, ditandatangani mahasiswa yang bersangkutan dan DPA;\r\n\r\n', 3),
(4, 'Dikenakan penggantian kerugian atau penggantian benda/barang\r\nsemacamnya', 2),
(5, 'Dinonaktifkan (Cuti Akademik/ Terminal) selama dua semester', 1),
(6, 'Pemberian sanksi dan mekanisme ditetapkan dalam peraturan tersendiri', 0),
(7, 'Melakukan tugas layanan sosial dalam jangka waktu tertentu', 2),
(8, 'Diberikan nilai D pada mata kuliah terkait saat melakukan pelanggaran', 2),
(9, 'Melakukan tugas khusus, misalnya bertanggungjawab untuk memperbaiki\r\natau membersihkan kembali, dan tugas-tugas lainnya.', 3),
(10, 'Diberhentikan sebagai mahasiswa', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tingkat`
--

CREATE TABLE `tingkat` (
  `id_tingkat` int(11) NOT NULL,
  `tingkat` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tingkat`
--

INSERT INTO `tingkat` (`id_tingkat`, `tingkat`) VALUES
(1, 'I'),
(2, 'II'),
(3, 'III'),
(4, 'IV'),
(5, 'V');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `role` enum('mahasiswa','dosen','dosen_dpa','dosen_dpa_admin','dosen_admin') NOT NULL,
  `foto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`, `foto`) VALUES
(56, '2241720223', '$2y$10$4RYs6QD8W3TAXWNQBZgGeuyV5MF6Oo5Ybzu7QUW/lpGrIIoXGFv2C', 'dosen', '../../img/uploads/foto mysapk.jpg'),
(57, '2241720215', '$2y$10$xOHWi0XN6WOUaDHrsr6Tv.cYnHXUAeoeznAnb9ZMjKbHW7/LxChEK', 'mahasiswa', '../../img/uploads/Screenshot (43).png'),
(58, '12345', '$2y$10$PplXFmdS/quUFBwDUHtWOOR2Q/IK4.3pAblcGeUBT2vyZTnutzVwe', 'dosen_dpa', ''),
(59, 'dpa', '$2y$10$cbzNaLYezH6MKKCFFhrkYeMt7jj1P9bzwt1i8pXbrLLHWMgBGEJAm', 'dosen_dpa', ''),
(60, 'dpa123', '$2y$10$S7FJmam3rwgQkBzq4iBPju6MvU2qDJOuWzf0YlT.7n7Wgr3GHk4Zi', 'dosen_dpa', ''),
(61, 'admin', '$2y$10$vKRxes4tAEfo0UQkUeim4OuswtVwDNlM8Zwoc4FhO.putQYfGITHK', 'dosen_admin', ''),
(62, 'admin123', '$2y$10$OCe0KRvdDllDScxjU.jeMO/TaTuBdtpXxLxe81/zwMdhzHi5tiW8y', 'dosen_admin', ''),
(63, '2241720249', '$2y$10$tKsoxs8cJqa4RYyg1V9Ig.8Kb/clZR5LGymTilbV6wLNSb2HektFS', 'mahasiswa', '../../img/uploads/kabinet.jpg'),
(64, 'ikidosendpa', '$2y$10$I/FrA89mgWyRUhMNlZuBoeeweb0B0MeZYNYyA0hNesNreDrPLP9yG', 'dosen_dpa', 'uploads/profilkabinet.jpg'),
(65, 'dosenadmin', '$2y$10$8Q6LcVCpnkgFwWTJ.k2pUOhjeroy7QqIc9dlkmMxYwxvrk7l/prBO', 'dosen_admin', '../../img/uploads/wp1.jpg'),
(66, 'dpa2A', '$2y$10$4S6zA2fUZdpkGMAgoCnxNu235TOSyQPJIvDcGzCBPAqI.ZT0KUfo2', 'dosen_dpa', 'uploads/profilkabinet.jpg'),
(67, 'dpa3A', '$2y$10$uJGQ7J99d1X3V1VFLl9R9OefgPm0P8.88bmaxCYukPqhSvX18Dcga', 'dosen_dpa', ''),
(69, 'dpaadmin', '$2y$10$yR/MiLiPYE43JG6gcDj4CeoojudqgMWFkH4i0fzFIC8HjfNtlBTfa', 'dosen_dpa_admin', '../../img/uploads/poline.png'),
(70, 'dpanew', '$2y$10$P6bSg4qHisAOspljP1eeKOQiN/Beh1C/NqMIGVqk7g85H9HQn264C', 'dosen_dpa', '../../img/uploads/kabinet.jpg'),
(71, 'dpatess', '$2y$10$IA2L9vta0YHSCGuYAxJl2.xFNC42P0DNh9rx4NDDuddicAjr6NAMK', 'dosen_dpa', ''),
(72, 'dpatess2', '$2y$10$p7JJV/TWhpbYmOs8yZ4cd.uQzzy9xD0.TyjkZEVUbgr0FVJnLqoFa', 'dosen_dpa', ''),
(73, 'dpa1234', '$2y$10$kEauaDSxB3CjN2sZWXOyqekBSY7r0.WpmHMbmXIm.nvGS4j0c5D06', 'dosen_dpa', ''),
(74, 'coo', '$2y$10$Mkyg5Q7Pdt.UbSeu04UKWO1/K8ucZYP5Jq4.1Koo2Q4mSN2jsjyv6', 'dosen_dpa', ''),
(75, 'dpa5A', '$2y$10$a8hJrn.8RtkUp2huLEE5N.PGtE/qAS1HIv/dcx6w2LYXClsHUyWRS', 'dosen_dpa', ''),
(76, 'm', '$2y$10$xfrtEDchYA6pltreiIwyg.S0Nrr1xyw6fOdG.nEzXaNKhazajCo2.', 'mahasiswa', ''),
(77, 'dsn', '$2y$10$sSezdP8SPiPIgEI5s/OByemo8CsjSSCub4PP01FQ6OkOxx.y5c0Ni', 'dosen', ''),
(78, 'c', '$2y$10$sS6kIdS7OAnA8OKHqmNWde/SxCrvjLppkJ4ZxKuQkI6cTvJaQ3g9O', 'dosen', ''),
(79, 'sa', '$2y$10$8zTjhwQ5w9n/rBl7R5.DIuhMRLDfwsrkOwIDzEJEJ.10ovhKoqUsy', 'dosen', '');

--
-- Trigger `user`
--
DELIMITER $$
CREATE TRIGGER `after_insert_user` AFTER INSERT ON `user` FOR EACH ROW BEGIN
    DECLARE last_inserted_id INT;

    -- Mendapatkan ID yang baru saja diinsert
    SET last_inserted_id = NEW.id_user;

    -- Cek apakah rolenya adalah 'mahasiswa'
    IF NEW.role = 'mahasiswa' THEN
        -- Insert ke tabel mahasiswa
        INSERT INTO mahasiswa (id_user, id_mahasiswa) VALUES (last_inserted_id, NEW.username);

    -- Cek apakah rolenya adalah 'dosen'
    ELSEIF NEW.role = 'dosen' THEN
        -- Insert ke tabel dosen
        INSERT INTO dosen (id_user, id_dosen) VALUES (last_inserted_id, NEW.username);

    -- Cek apakah rolenya adalah 'dosen_dpa' atau 'dosen_dpa_admin'
    ELSEIF NEW.role = 'dosen_dpa' OR NEW.role = 'dosen_dpa_admin' THEN
        -- Insert ke tabel dosen
        INSERT INTO dosen (id_user, id_dosen) VALUES (last_inserted_id, NEW.username);
        -- Jika role adalah 'dosen_dpa_admin', tambahkan logika bisnis lainnya
        IF NEW.role = 'dosen_dpa_admin' THEN
        INSERT INTO dosen (id_user, id_dosen) VALUES (last_inserted_id, NEW.username);
        END IF;

    -- Cek apakah rolenya adalah 'dosen_admin'
    ELSEIF NEW.role = 'dosen_admin' THEN
        -- Insert ke tabel dosen
        INSERT INTO dosen (id_user, id_dosen) VALUES (last_inserted_id, NEW.username);
        -- Logika bisnis untuk 'dosen_admin'
    END IF;
    -- Tambahkan logika bisnis untuk role lainnya jika diperlukan
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id_dosen`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `kelasdpa`
--
ALTER TABLE `kelasdpa`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `id_dosen` (`id_dosen`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD KEY `id_tingkat` (`id_tingkat`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `pelanggaran`
--
ALTER TABLE `pelanggaran`
  ADD PRIMARY KEY (`id_pelanggaran`),
  ADD KEY `id_tingkat` (`id_tingkat`);

--
-- Indeks untuk tabel `pelaporan`
--
ALTER TABLE `pelaporan`
  ADD PRIMARY KEY (`id_pelaporan`),
  ADD KEY `id_dosen` (`id_dosen`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_pelanggaran` (`id_pelanggaran`),
  ADD KEY `index_id_pelaporan` (`id_pelaporan`),
  ADD KEY `id_sanksi` (`id_sanksi`);

--
-- Indeks untuk tabel `sanksi`
--
ALTER TABLE `sanksi`
  ADD PRIMARY KEY (`id_sanksi`),
  ADD KEY `id_tingkat` (`id_tingkat`);

--
-- Indeks untuk tabel `tingkat`
--
ALTER TABLE `tingkat`
  ADD PRIMARY KEY (`id_tingkat`),
  ADD KEY `tingkat` (`tingkat`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kelasdpa`
--
ALTER TABLE `kelasdpa`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `pelaporan`
--
ALTER TABLE `pelaporan`
  MODIFY `id_pelaporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `kelasdpa`
--
ALTER TABLE `kelasdpa`
  ADD CONSTRAINT `kelasdpa_ibfk_1` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`);

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`id_tingkat`) REFERENCES `tingkat` (`id_tingkat`),
  ADD CONSTRAINT `mahasiswa_ibfk_3` FOREIGN KEY (`id_kelas`) REFERENCES `kelasdpa` (`id_kelas`),
  ADD CONSTRAINT `mahasiswa_ibfk_4` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `pelanggaran`
--
ALTER TABLE `pelanggaran`
  ADD CONSTRAINT `pelanggaran_ibfk_1` FOREIGN KEY (`id_tingkat`) REFERENCES `tingkat` (`id_tingkat`);

--
-- Ketidakleluasaan untuk tabel `pelaporan`
--
ALTER TABLE `pelaporan`
  ADD CONSTRAINT `pelaporan_ibfk_3` FOREIGN KEY (`id_pelanggaran`) REFERENCES `pelanggaran` (`id_pelanggaran`),
  ADD CONSTRAINT `pelaporan_ibfk_4` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`),
  ADD CONSTRAINT `pelaporan_ibfk_5` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`),
  ADD CONSTRAINT `pelaporan_ibfk_6` FOREIGN KEY (`id_sanksi`) REFERENCES `sanksi` (`id_sanksi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
