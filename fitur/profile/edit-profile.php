<?php
session_start();
include '../../koneksi/koneksi.php';
require "../../fungsi/session.php";
require "../../fungsi/pesan_kilat.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $username = $_SESSION['username'];

     // Perbarui Nama User
     if (isset($_POST['nama'])) {
          $nama = $_POST['nama'];
          $sqlNama = "";

          if ($_SESSION['role']) {
               $sqlNama = "SELECT UpdateNamaUser('$username', '$nama', '$role') as result";

          }

          if ($koneksi->query($sqlNama)) {
               echo '
                    <script>
                         alert("Data nama berhasil diubah !");
                         window.location.href = "view_profile.php";
                    </script>
               ';
               exit();
          } else {
               pesan('danger', "Error: " . $sqlNama . "<br>" . $koneksi->error);
               header("Location: view_profile.php");
               exit();
          }
     }

     $username = $_SESSION['username'];
     // Perbarui Nama Kelas Mahasiswa
     if (isset($_POST['id_kelas']) && $_SESSION['role'] == 'mahasiswa') {
          $id_kelas = $_POST['id_kelas'];
          $sqlKelas = "UPDATE mahasiswa SET id_kelas = $id_kelas WHERE id_mahasiswa = '$username'";

          if ($koneksi->query($sqlKelas)) {
               echo '
         <script>
             alert("Data kelas berhasil diubah !");
             window.location.href = "view_profile.php";
         </script>
         ';
               exit();
          } else {
               echo "Error: " . $sqlKelas . "<br>" . $koneksi->error;
               exit();
          }
     }

     // Proses unggah gambar
     if (isset($_FILES['file_gambar'])) {
          $file_name = $_FILES['file_gambar']['name'];
          $file_temp = $_FILES['file_gambar']['tmp_name'];
          $file_type = $_FILES['file_gambar']['type'];

          $folder = "../../img/uploads/";
          $target_file = $folder . basename($file_name);

          if (move_uploaded_file($file_temp, $target_file)) {
               $sqlGambar = "UPDATE user SET foto = '$target_file' WHERE username = '$username'";

               if ($koneksi->query($sqlGambar) === TRUE) {
                    echo '
                    <script>
                         alert("Gambar berhasil diubah !");
                         window.location.href = "view_profile.php";
                    </script>
                    ';
                    exit();
               } else {
                    echo "Error: " . $sqlGambar . "<br>" . $koneksi->error;
               }
          } else {
               pesan("danger", "Password atau Username salah");
               header("Location: view_profile.php");
          }
     }

     // Hapus gambar
     if (isset($_POST['hapus_gambar'])) {
          $sqlHapusGambar = "UPDATE user SET foto = NULL WHERE username = '$username'";

          if ($koneksi->query($sqlHapusGambar) === TRUE) {
               echo '
                    <script>
                         alert("Gambar berhasil dihapus !");
                         window.location.href = "view_profile.php";
                    </script>
               ';
               exit();
          } else {
               echo "Error: " . $sqlHapusGambar . "<br>" . $koneksi->error;
          }
     }
}
