<?php
require '../../koneksi/koneksi.php';
require '../../fungsi/session.php';
class Mahasiswa
{
     function konversiKeRomawi($angka)
     {
          $romawi = '';
          $konversi = array(
               '1' => 'I',
               '2' => 'II',
               '3' => 'III',
               '4' => 'IV',
               '5' => 'V',
          );

          if (isset($konversi[$angka])) {
               $romawi = $konversi[$angka];
          }

          return $romawi;
     }

     public function getInfoMahasiswa($username)
     {
          global $koneksi;

          $query_join = "SELECT u.*, m.nama
                       FROM user u
                       JOIN mahasiswa m ON u.id_user = m.id_user
                       WHERE u.id_user = '$username'";

          $result = mysqli_query($koneksi, $query_join);

          if ($result) {
               $row = mysqli_fetch_assoc($result);
               return $row;
          } else {
               // Handle query error if needed
               return false;
          }
     }

     public function getNamaMahasiswa($username)
     {
          global $koneksi;

          $query_nama = "SELECT nama
                         FROM mahasiswa
                         WHERE id_mahasiswa = '$username'";

          $result = mysqli_query($koneksi, $query_nama);

          if ($result) {
               $row = mysqli_fetch_assoc($result);
               return $row['nama'];
          } else {
               // Handle query error if needed
               return false;
          }
     }

     public function getJumlahPelaporanDiterima($username)
     {
          global $koneksi;

          // Ambil jumlah pelanggaran dengan status 'ditolak' atau 'dilaporkan'
          $suksesQuery = "SELECT COUNT(*) AS jml FROM pelaporan WHERE 
        (status_pelanggaran = 'ditolak' OR status_pelanggaran = 'dilaporkan') 
        AND id_mahasiswa = '$username'";
          $suksesResult = mysqli_query($koneksi, $suksesQuery);

          if ($suksesResult->num_rows > 0) {
               $row = $suksesResult->fetch_assoc();
               $jml = $row['jml'];
               return $jml;
          } else {
               return 0; // Return 0 if there are no rows
          }
     }


     public function getJumlahPelaporanDilaporkan($username)
     {
          global $koneksi;

          // Query untuk menghitung jumlah pelaporan yang memiliki status 'dilaporkan' berdasarkan username
          $query = "SELECT COUNT(*) AS jml FROM pelaporan
          
          WHERE id_mahasiswa = '$username'";

          // Eksekusi query
          $result = mysqli_query($koneksi, $query);

          // Cek apakah query mengembalikan hasil
          if ($result->num_rows > 0) {
               $row = $result->fetch_assoc();
               // Ambil nilai jumlah pelaporan dari hasil query
               $jumlahPelaporanDiterima = $row['jml'];
               return $jumlahPelaporanDiterima;
          } else {
               // Handle query error if needed
               return false;
          }
     }

     public function getDataPelaporan($username)
     {
          global $koneksi;

          // Query untuk mengambil data pelaporan berdasarkan username
          $query = "SELECT * FROM pelaporan pl
                  INNER JOIN pelanggaran pg ON pl.id_pelanggaran = pg.id_pelanggaran
                  WHERE pl.id_mahasiswa = '$username'";

          // Eksekusi query
          $result = $koneksi->query($query);
          // Cek apakah query mengembalikan hasil
          if ($result->num_rows > 0) {
               return $result;
          } else {
               // Handle query error if needed
               return false;
          }
     }

     public function getJumlahPelaporanMenungguVerifikasi($username)
     {
          global $koneksi;
          // Query untuk menghitung jumlah pelaporan yang memiliki status 'menunggu_verifikasi' berdasarkan username
          $query = "SELECT COUNT(*) AS jml FROM pelaporan WHERE status_pelanggaran = 'menunggu_verifikasi' AND id_mahasiswa = '$username'";

          // Eksekusi query
          $result = mysqli_query($koneksi, $query);

          // Cek apakah query mengembalikan hasil
          if ($result->num_rows > 0) {
               $row = $result->fetch_assoc();
               // Ambil nilai jumlah pelaporan dari hasil query
               $jumlahPelaporanMenungguVerifikasi = $row['jml'];
               return $jumlahPelaporanMenungguVerifikasi;
          } else {
               // Handle query error if needed
               return false;
          }
     }
}
$mahasiswa = new Mahasiswa();
