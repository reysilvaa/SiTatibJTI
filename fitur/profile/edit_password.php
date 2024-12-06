<?php
include '../../koneksi/koneksi.php';
require "../../fungsi/session.php";

function updatePassword($koneksi, $username, $password)
{
     require '../../fungsi/pesan_kilat.php';
     $hashed_password = password_hash($password, PASSWORD_DEFAULT);

     $sql = "UPDATE user SET password = '$hashed_password' WHERE username = '$username'";

     if ($koneksi->query($sql) === TRUE) {
          pesan('success', "Password berhasil diperbarui");
          header("Location: view_profile.php");
          exit();
     } else {
          pesan('danger', "Error: " . $sql . "<br>" . $koneksi->error);
          header("Location: view_profile.php");
          exit();
     }
}
// Perbarui password
if (isset($_POST['password'])) {
     $password = $_POST['password'];
     updatePassword($koneksi, $username, $password);
}
