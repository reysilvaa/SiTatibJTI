<?php
if (session_status() === PHP_SESSION_NONE) {
     session_start();
}

include "../koneksi/koneksi.php";
include "../fungsi/pesan_kilat.php";
include "../fungsi/anti_injection.php";

function registrasiUser($koneksi, $username, $password, $role)
{
     $hashed_password = password_hash($password, PASSWORD_DEFAULT);

     $check_query = "SELECT * FROM user WHERE username = '$username'";
     $check_result = mysqli_query($koneksi, $check_query);

     if (mysqli_num_rows($check_result) > 0) {
          pesan('warning', "Username (NIM) sudah digunakan.");
          header("Location: ../register.php");
          mysqli_close($koneksi);
          die();
     }

     $q_tbl_user = "INSERT INTO user (username, password, role) 
                         VALUES ('$username', '$hashed_password', '$role')";

     $result = mysqli_query($koneksi, $q_tbl_user);

     if ($result) {
          $last_inserted_id = mysqli_insert_id($koneksi);

     //      switch ($role) {
     //           // case 'mahasiswa':
     //           //      $q_tbl_mahasiswa = "INSERT INTO mahasiswa (id_user, id_mahasiswa) 
     //           //                          VALUES ('$last_inserted_id', '$username')";
     //           //      mysqli_query($koneksi, $q_tbl_mahasiswa);
     //           //      break;
     //           case 'dosen':
     //                $q_tbl_dosen = "INSERT INTO dosen (id_user, id_dosen) 
     //                               VALUES ('$last_inserted_id' ,'$username')";
     //                mysqli_query($koneksi, $q_tbl_dosen);
     //                break;
     //           case 'dosen_dpa':
     //                $q_tbl_dosen_dpa = "INSERT INTO dosen (id_user, id_dosen) 
     //                                    VALUES ('$last_inserted_id' , '$username')";
     //                mysqli_query($koneksi, $q_tbl_dosen_dpa);
     //                break;
     //           case 'dosen_dpa_admin':
     //                $q_tbl_dosen_dpa_admin = "INSERT INTO dosen (id_user, id_dosen) 
     //                                         VALUES ('$last_inserted_id' ,'$username')";
     //                mysqli_query($koneksi, $q_tbl_dosen_dpa_admin);

     //                // $q_tbl_admin = "INSERT INTO user (id_user) 
     //                //                 VALUES ('$last_inserted_id')";
     //                // mysqli_query($koneksi, $q_tbl_admin);
     //                break;
     //           case 'dosen_admin':
     //                $q_tbl_dosen_admin = "INSERT INTO dosen (id_user, id_dosen) 
     //                                              VALUES ('$last_inserted_id' ,'$username')";
     //                mysqli_query($koneksi, $q_tbl_dosen_admin);

     //                // $q_tbl_admin = "INSERT INTO user (id_user) 
     //                //                 VALUES ('$last_inserted_id')";
     //                // mysqli_query($koneksi, $q_tbl_admin);
     //                break;
     //      }

          pesan('success', "Registrasi Sukses! Lanjut login.");
          header("Location: ../login.php");
          mysqli_close($koneksi);
          die();
     } else {
          pesan('danger', "Registrasi Gagal failed. Silahkan masukkan kembali.");
          header("Location: ../register.php");
          mysqli_close($koneksi);
          die();
     }
}

registrasiUser($koneksi, $_POST['username'], $_POST['password'], $_POST['role']);
