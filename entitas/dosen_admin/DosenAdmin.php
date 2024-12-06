<?php
require "../../fitur/laporan/Pelaporan.php";

class DosenAdmin extends Pelaporan
{
     public function getNamaMhs()
     {
          return parent::getNamaMhs();
     }

     public function getTingkat()
     {
          return parent::getTingkat();
     }

     public function pelaporan() {
          return parent::pelaporan();
     }

     // pilih status
     function getStatusCondition($selectedStatus)
     {
          $statusCondition = '';

          if ($selectedStatus !== 'semua') {
               $statusCondition = "WHERE p.status_pelanggaran = '$selectedStatus'";
          }

          return $statusCondition;
     }

     // search data
     function getSearchCondition() {
          $searchCondition = "";
      
          if (isset($_GET['search_id'])) {
              $searchId = $_GET['search_id'];
              if (!empty($searchId)) {
                  $searchCondition = "WHERE m.id_mahasiswa LIKE '%$searchId%'";
              }
          }
      
          return $searchCondition;
     }
}
$dosenAdmin = new DosenAdmin();
