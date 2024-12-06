<?php
require_once 'Admin.php';

if ($_SESSION['role'] == 'dosen_admin') {
     require_once '../../template/TemplateDosenAdmin.php';
     $header = $template->header($username, $koneksi);
     echo $admin->dataPelanggaran();
     $foter = $template->footer();

} else if($_SESSION['role'] == 'dosen_dpa_admin') {
     require_once '../../template/TemplateDpaAdmin.php';
     $header = $template->header($username, $koneksi);
     echo $admin->dataPelanggaran();
     $foter = $template->footer();
}
