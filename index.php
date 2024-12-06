<?php
require 'koneksi/koneksi.php';
require 'fungsi/pesan_kilat.php';

if (isset($_GET['page'])) {
  $page = $_GET['page'];
  switch ($page) {
    case 'dosen':
      header("Location: entitas/dosen/home.php");
      exit();
    case 'mahasiswa':
      header("Location: entitas/mahasiswa/home.php");
      exit();
    case 'admin':
      header("Location: entitas/admin/home.php?page=admin");
      exit();
    case 'dosen_dpa':
      header("Location: entitas/dpa/home.php?page=dpa");
      exit();
    case 'dosen_admin':
      header("Location: entitas/dosen_admin/home.php");
      exit();
    case 'dosen_admin':
      header("Location: fitur/admin/view_admin.php");
      exit();
    default:
      header("Location: login.php");
      exit();
  }
}
header("Location: login.php");
?>
