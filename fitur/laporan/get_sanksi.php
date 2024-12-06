<?php
// Sambungkan ke database
require '../../koneksi/koneksi.php';

// Periksa jika data tingkat telah diterima
if (isset($_POST['tingkat'])) {
    $tingkat = $_POST['tingkat'];

    // Lakukan query untuk mengambil sanksi berdasarkan tingkat
    $sql_sanksi = "CALL GetSanksiByTingkat($tingkat)"; // Sesuaikan dengan struktur tabel Anda

    $result_sanksi = $koneksi->query($sql_sanksi);

    // Buat opsi sanksi dalam format HTML
    if ($result_sanksi->num_rows > 0) {
        $html_options = '';
        while ($row_sanksi = $result_sanksi->fetch_assoc()) {
            $html_options .= "<option value='" . $row_sanksi['id_sanksi'] . "'>" . $row_sanksi['jenis_sanksi'] . "</option>";
        }
        echo $html_options;
    } else {
        echo "<option value=''>Tidak ada sanksi untuk tingkat ini</option>";
    }
}
?>
