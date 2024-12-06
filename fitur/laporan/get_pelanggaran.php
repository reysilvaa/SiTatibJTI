<?php
require '../../koneksi/koneksi.php'; // Sesuaikan dengan lokasi file koneksi.php Anda

if (isset($_POST['tingkat'])) {
    $tingkat = $_POST['tingkat'];

    // Lakukan kueri SQL untuk mengambil pelanggaran berdasarkan tingkat yang dipilih
    $sql_pelanggaran = "CALL GetPelanggaranByTingkat($tingkat)";

    $result_pelanggaran = $koneksi->query($sql_pelanggaran);

    // Buat dropdown pelanggaran berdasarkan hasil kueri
    if ($result_pelanggaran->num_rows > 0) {
        while ($row_pelanggaran = $result_pelanggaran->fetch_assoc()) {
            echo "<option value='" . $row_pelanggaran["id_pelanggaran"] . "'>" . $row_pelanggaran["nama_pelanggaran"] . "</option>";
        }
    } else {
        echo "<option value=''>Tidak ada data Pelanggaran untuk tingkat ini</option>";
    }
}
?>
