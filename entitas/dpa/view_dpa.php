<?php
require_once '../../fungsi/session.php';

if ($_SESSION['role'] == 'dosen_dpa') {
     require_once 'Dpa.php';
     require_once '../../template/TemplateDPA.php';

     $header = $template->header($username, $koneksi);
     echo $dpa->homeDpa();
     $foter = $template->footer();

} else if ($_SESSION['role'] == 'dosen_dpa_admin') {
     require_once '../dpa_admin/DpaAdmin.php';
     require_once '../../template/TemplateDpaAdmin.php';
     
     $header = $template->header($username, $koneksi);
     echo $dpaAdmin->homeDpa();
     $foter = $template->footer();
}
