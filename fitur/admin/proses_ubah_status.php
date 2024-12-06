<?php
include '../../koneksi/koneksi.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pelaporan = $_POST['id_pelaporan'];
    $status_baru = $_POST['status'];

    if (isset($status_baru)) {
        $tgl_konfirmasi = date("d-m-Y H:i:s");
        $sql_update = "UPDATE pelaporan SET status_pelanggaran = '$status_baru', tgl_konfirmasi = '$tgl_konfirmasi' WHERE id_pelaporan = '$id_pelaporan'";
    } else {
        $sql_update = "UPDATE pelaporan SET status_pelanggaran = '$status_baru' WHERE id_pelaporan = '$id_pelaporan'";
    }

    if ($koneksi->query($sql_update) === TRUE) {
        echo "Status berhasil diperbarui.";
        header("Location: view_admin.php");
    } else {
        echo "Error: " . $sql_update . "<br>" . $koneksi->error;
    }
}

$koneksi->close();
