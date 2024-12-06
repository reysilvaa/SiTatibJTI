<?php
include '../../koneksi/koneksi.php';
require "../../fungsi/session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

     
}
