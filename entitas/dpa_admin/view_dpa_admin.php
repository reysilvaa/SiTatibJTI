<?php
require_once 'DpaAdmin.php';
require_once '../../template/TemplateDpaAdmin.php';
$header = $template->header($username, $koneksi);
echo $dpaAdmin->homeDpa();
$foter = $template->footer();
?>