<?php 
class Sanksi {
     function getDataPelaporan($username) {

          global $koneksi;
          $sql = "CALL GetPelaporanData($username)";
          $result = mysqli_query($koneksi, $sql);
          return $result;
     }
}
$sanksi = new Sanksi();
?>