<?php
require "../../koneksi/koneksi.php";
require "../../fungsi/session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mendapatkan informasi dari file yang dikirim
    $id_pelanggaran = $_POST['id_pelanggaran'];
    $total_files = count($_FILES['berkas_sanksi']['name']);

    for ($i = 0; $i < $total_files; $i++) {
        $nama_file = $_FILES['berkas_sanksi']['name'][$i];
        $ukuran_file = $_FILES['berkas_sanksi']['size'][$i];
        $tmp_file = $_FILES['berkas_sanksi']['tmp_name'][$i];

        // Direktori tempat menyimpan file yang diterima
        $tujuan_upload = "upload/";

        // Pindahkan file yang diterima ke direktori penyimpanan yang ditentukan
        if (move_uploaded_file($tmp_file, $tujuan_upload . $nama_file)) {
            // Memperbarui database dengan informasi dari file yang diunggah
            $sql_update = "UPDATE pelaporan SET upload_sanksi = '$nama_file', status_pelanggaran = 'menunggu_verifikasi'
               WHERE id_pelanggaran = '$id_pelanggaran'";


            if ($koneksi->query($sql_update) === TRUE) {
                // Pesan sukses menggunakan modal
                echo '
                <script>
                    alert("File berhasil diunggah!");
                    window.location.href = "upload_sanksi.php";
                </script>
                ';
            } else {
                echo "Error: " . $sql_update . "<br>" . $koneksi->error;
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }
}
?>
