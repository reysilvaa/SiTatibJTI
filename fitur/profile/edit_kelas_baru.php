<?php
session_start();
include '../../koneksi/koneksi.php';
require "../../fungsi/session.php";
require "../../fungsi/pesan_kilat.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if (isset($_POST['id_kelas']) && ($_SESSION['role'] == 'dosen_dpa' || $_SESSION['role'] == 'dosen_dpa_admin')) {
          $id_kelas = $_POST['id_kelas'];

          // Mendapatkan ID Dosen dari session
          $id_dosen = $_SESSION['username'];

          // Check if DPA already has a class
          $checkDpaClass = "SELECT id_kelas FROM kelasdpa WHERE id_dosen = '$id_dosen'";
          $result = $koneksi->query($checkDpaClass);

          if ($result && $result->num_rows > 0) {
               // DPA already has a class, update the class
               $row = $result->fetch_assoc();
               $oldClassId = $row['id_kelas'];

               // Remove DPA from the old class
               $removeDpaFromOldClass = "SELECT RemoveDpaFromOldClass($oldClassId) as rows_affected";
               $koneksi->query($removeDpaFromOldClass);
          }

          // Update the new class
          $sqlKelasDpa = "UPDATE kelasdpa SET id_dosen = '$id_dosen' WHERE id_kelas = '$id_kelas'";

          if ($koneksi->query($sqlKelasDpa) === TRUE) {
               echo '
                <script>
                    alert("Data kelas DPA berhasil diubah !");
                    window.location.href = "view_profile.php";
                </script>';
               exit();
          } else {
               echo '
                <script>
                    alert("Error updating kelas DPA");
                    window.location.href = "view_profile.php";
                </script>';
               exit();
          }
     }
}
