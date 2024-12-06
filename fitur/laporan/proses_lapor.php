<?php
include '../../koneksi/koneksi.php';
require "../../fungsi/session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $id_pelanggaran = $_POST['id_pelanggaran'];
    $id_sanksi = $_POST['sanksi'];
    $bukti_pelanggaran = $_FILES['uploadBuktiPelanggaran']['name'];
    // $tgl_lapor = date('d-m-Y');
    $username = $_SESSION['username'];

    // Upload file bukti pelanggaran ke server
    $target_dir = "bukti/";
    $target_file = $target_dir . basename($_FILES["uploadBuktiPelanggaran"]["name"]);
    move_uploaded_file($_FILES["uploadBuktiPelanggaran"]["tmp_name"], $target_file);

    // Simpan data ke dalam tabel Pelaporan
    $sql = "INSERT INTO pelaporan (id_mahasiswa, id_dosen, id_pelanggaran, id_sanksi, bukti_pelanggaran, status_pelanggaran)
            VALUES ('$id_mahasiswa', '$username', '$id_pelanggaran', '$id_sanksi',  '$bukti_pelanggaran', 'dilaporkan')";

    if ($koneksi->query($sql) === TRUE) {
        if ($_SESSION['role'] == 'dosen') {
            echo '
                <script>
                    alert("Laporan berhasil diunggah!");
                    window.location.href = "view_laporan.php";
                </script>
                ';
        } else if($_SESSION['role'] == 'dosen_admin') {
            echo '
                <script>
                    alert("Laporan berhasil diunggah!");
                    window.location.href = "view_laporan.php";
                </script>
                ';
        } else if($_SESSION['role'] == 'dosen_dpa') {
            echo '
                <script>
                    alert("Laporan berhasil diunggah!");
                    window.location.href = "view_laporan.php";
                </script>
                ';
        } 
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}
$koneksi->close();
