<?php
session_start();
include "../koneksi/koneksi.php";
include "../fungsi/pesan_kilat.php";
require '../fungsi/anti_injection.php';
require "../fungsi/session.php";

class Login
{
	private $koneksi;

	public function __construct($koneksi)
	{
		$this->koneksi = $koneksi;
	}

	public function loginUser($username, $password)
	{
		$username = antiinjection($this->koneksi, $username);
		$password = antiinjection($this->koneksi, $password);

		$username = mysqli_real_escape_string($this->koneksi, $username);

		$query = "SELECT * FROM user WHERE username = '$username'";
		$result = mysqli_query($this->koneksi, $query);
		$row = mysqli_fetch_assoc($result);
		$_SESSION['username'] = $row['username'];
		$_SESSION['role'] = $row['role'];
		$o = (password_verify($password, '$2y$10$4ybmJ2uaHjAYRFsPtjOga.Do09UP02AOIB6bbwWRMfycwR6UBzusC'));
		echo $o;

		if (password_verify($password, $row['password'])) {
			$username = $_SESSION['username'];
			// Cek peran pengguna
			if ($_SESSION['role'] == 'mahasiswa') {
				header("Location: ../entitas/mahasiswa/home.php");
				exit;
			} elseif ($_SESSION['role'] == 'dosen') {
				header("Location: ../fitur/laporan/view_laporan.php");
				exit;
			} elseif ($_SESSION['role'] == 'dosen_dpa') {
				header("Location: ../entitas/dpa/view_dpa.php");
				exit;
			} elseif ($_SESSION['role'] == 'dosen_admin') {
				header("Location: ../fitur/admin/view_admin.php");
				exit;
			} elseif ($_SESSION['role'] == 'dosen_dpa_admin') {
				header("Location: ../entitas/dpa_admin/view_dpa_admin.php");
				exit;
			} else {
				pesan("danger", "Password atau Username salah");
				header("Location: ../login.php");
			}
		} else {
			pesan("danger", "Password atau Username salah");
			header("Location: ../login.php");
		}
	}
}
$login = new Login($koneksi);
$login->loginUser($_POST['username'], $_POST['password']);
