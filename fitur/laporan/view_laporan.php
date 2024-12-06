<?php

require '../../koneksi/koneksi.php';
include '../../fungsi/pesan_kilat.php';
require '../../fungsi/session.php';

if ($_SESSION['role'] == 'dosen') {
     require '../../entitas/dosen/Dosen.php';
     require '../../template/TemplateDosen.php';

     $header = $template->header($username, $koneksi);
     echo $dosen->pelaporan();
     $footer = $template->footer();

} else if ($_SESSION['role'] == 'dosen_admin') {
     require '../../entitas/dosen_admin/DosenAdmin.php';
     require '../../template/TemplateDosenAdmin.php';
     

     $header = $template->header($username, $koneksi);
     echo $dosenAdmin->pelaporan();
     $footer = $template->footer();

} else if($_SESSION['role'] == 'dosen_dpa') {
     require '../../entitas/dpa/Dpa.php';
     require '../../template/TemplateDPA.php';

     $header = $template->header($username, $koneksi);
     echo $dpa->pelaporan();
     $footer = $template->footer();

} else if($_SESSION['role'] == 'dosen_dpa_admin') {
     require '../../entitas/dpa_admin/DpaAdmin.php';
     require '../../template/TemplateDpaAdmin.php';

     $header = $template->header($username, $koneksi);
     echo $dpaAdmin->pelaporan();
     $footer = $template->footer();
}

?>

