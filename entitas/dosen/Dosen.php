<?php
require "../../fitur/laporan/Pelaporan.php";
class Dosen extends Pelaporan
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
}
$dosen = new Dosen();

