<?php
require '../../fungsi/session.php';
if ($_SESSION['role'] == 'dosen') {
     include "../../template/TemplateDosen.php";
     require "ProfileDosen.php";

     $header = $template->header($username, $koneksi);
     echo $profileDosen->profile();
     $footer = $template->footer();

} else if ($_SESSION['role'] == 'mahasiswa') {
     require_once '../../template/TemplateMahasiswa.php';
     require "ProfileMahasiswa.php";

     $header = $templateMahasiswa->header($username, $koneksi);
     echo $profile->profile();
     $footer = $templateMahasiswa->footer();

} else if ($_SESSION['role'] == 'dosen_admin') {
     require_once "../../template/TemplateDosenAdmin.php";
     require "ProfileDosenAdmin.php";

     $header = $template->header($username, $koneksi);
     echo $profile->profile();
     $footer = $template->footer();

} else if ($_SESSION['role'] == 'dosen_dpa') {
     require_once "../../template/TemplateDPA.php";
     require "ProfileDPA.php";

     $header = $template->header($username, $koneksi);
     echo $profile->profile();
     $footer = $template->footer();

} else if ($_SESSION['role'] == 'dosen_dpa_admin') {
     require_once "../../template/TemplateDpaAdmin.php";
     require "ProfileDpaAdmin.php";

     $header = $template->header($username, $koneksi);
     echo $profile->profile();
     $footer = $template->footer();
}
