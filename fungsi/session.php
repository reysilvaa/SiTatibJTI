<?php
if (session_status() === PHP_SESSION_NONE)
session_start();

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
    // $id_dosen = $_SESSION['username'];
} else{
    header("Location: ../login.php");
}
?>
