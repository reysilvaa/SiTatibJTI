<?php
include '../../koneksi/koneksi.php';
require "../../fungsi/session.php";
$id_dosen = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Perbarui ID Dosen pada Kelas DPA
    if (isset($_POST['id_kelas']) && ($_SESSION['role'] == 'dosen_dpa' || $_SESSION['role'] == 'dosen_dpa_admin')) {
        $id_kelas = $_POST['id_kelas'];

        // Check if DPA already has a class
        $checkDpaClass = "SELECT COUNT(*) as class_count FROM kelasdpa WHERE id_dosen = '$id_dosen'";
        $result = $koneksi->query($checkDpaClass);
        $row = $result->fetch_assoc();
        $classCount = $row['class_count'];

        if ($classCount == 0) {
            // DPA doesn't have a class, update the class
            $sqlKelasDpa = "UPDATE kelasdpa SET id_dosen = '$id_dosen' WHERE id_kelas = $id_kelas";
            
            if ($koneksi->query($sqlKelasDpa) === TRUE) {
                echo '
                <script>
                    alert("Data kelas DPA berhasil diubah !");
                    window.location.href = "view_profile.php";
                </script>';
                exit();
            } else {
                echo '
                <script>
                    alert("Error updating kelas DPA");
                    window.location.href = "view_profile.php";
                </script>';
                exit();
            }
        } else {
            echo '
            <script>
                alert("Anda hanya dapat membimbing satu kelas");
                window.location.href = "view_profile.php";
            </script>';
            exit();
        }
    } else {
        echo "Unauthorized access";
        var_dump($id_dosen);
    }
}
?>
