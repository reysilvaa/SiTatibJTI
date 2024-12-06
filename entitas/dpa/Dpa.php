<?php
require "../../fitur/laporan/Pelaporan.php";

class Dpa extends Pelaporan
{
  public function getNamaMhs()
  {
    return parent::getNamaMhs();
  }

  public function getTingkat()
  {
    return parent::getTingkat();
  }

  public function pelaporan()
  {
    return parent::pelaporan();
  }

  public function homeDpa()
  {
    require '../../koneksi/koneksi.php';
    require '../../fungsi/session.php';
    $id_dosen = $_SESSION['username'];

    $sql = "SELECT *, k.nama_kelas AS kelas, m.nama AS nama, d.nama AS pelapor FROM mahasiswa m
          INNER JOIN kelasdpa k ON m.id_kelas = k.id_kelas
          INNER JOIN pelaporan pl ON m.id_mahasiswa = pl.id_mahasiswa
          INNER JOIN pelanggaran pg ON pl.id_pelanggaran = pg.id_pelanggaran
          INNER JOIN tingkat t ON pg.id_tingkat = t.id_tingkat
          INNER JOIN dosen d ON k.id_dosen = d.id_dosen
          left join sanksi s on s.id_sanksi = s.id_sanksi
          WHERE k.id_dosen = '$id_dosen'";

    $result = $koneksi->query($sql);


    ob_start();
?>
    <main id="main" class="main">

      <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../../fitur/dpa/view_dpa.php">Data Mahasiswaku</a></li>
            <li class="breadcrumb-item"><a href="../../fitur/laporan/view_laporan.php">Pelaporan</a></li>
            <li class="breadcrumb-item active"><a href="../../fitur/profile/view_profile.php">Profilku</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section">
        <div class="row">
          <div class="col-lg-12">

            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Tabel Pelanggaran Mahasiswa Kelasku</h5>
                <p>Data - data Pelanggaran dari Mahasiswa yang saya DPA i</p>

                <!-- Table with stripped rows -->
                <table class="table datatable">
                  <thead>
                    <tr>
                    <tr>
                      <th>Nama</th>
                      <th>Prodi</th>
                      <th>Kelas</th>
                      <th>Pelanggaran</th>
                      <th>Tingkat</th>
                      <th>Status Pelanggaran</th>
                    </tr>
                  </thead>
                  <?php

                  ?>
                  <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {

                        echo '<tr>';
                        echo '<td>' . $row['nama'] . '</td>';
                        echo '<td>' . $row['prodi'] . '</td>';
                        echo '<td>' . $row['nama_kelas'] . '</td>';

                        $nama_pelanggaran = $row['nama_pelanggaran'];
                        $words = explode(' ', $nama_pelanggaran);
                        $limitedWords = implode(' ', array_slice($words, 0, 5));
                        $finalText = count($words) > 10 ? $limitedWords . '...' : $nama_pelanggaran;
                        echo '<td>' . $finalText . '</td>';

                        echo '<td>' . $row['tingkat'] . '</td>';
                        $status = $row['status_pelanggaran'];
                        $badgeClass = '';
                        if ($status === 'ditolak') {
                          $badgeClass = 'badge bg-danger';
                          $badgeText = 'Ditolak!';
                        } elseif ($status === 'dilaporkan') {
                          $badgeClass = 'badge bg-danger';
                          $badgeText = 'Dilaporkan!';
                        } elseif ($status === 'sukses') {
                          $badgeClass = 'badge bg-success';
                          $badgeText = 'Selesai!';
                        } elseif ($status === 'menunggu_verifikasi') {
                          $badgeClass = 'badge bg-warning text-dark';
                          $badgeText = 'Menunggu Verifikasi!';
                        } else {
                          $badgeClass = 'badge bg-secondary';
                        }

                        echo '<td>' . ' <span class="' . $badgeClass . '">' . $badgeText . '</span></td>';
                        echo '<td style="border-right: 1px solid #000;">';
                        echo '<button type="button" class="btn btn-secondary" onclick="showDetail(\'' .
                          $row['id_pelaporan'] . '\', \'' .
                          $row['pelapor'] . '\', \'' .
                          $row['nama_pelanggaran'] . '\', \'' .
                          $row['bukti_pelanggaran'] . '\', \'' .
                          $row['tgl_lapor'] . '\', \'' .
                          $row['upload_sanksi'] . '\', \'' .
                          $row['tgl_lapor'] . '\', \'' .
                          $row['tgl_konfirmasi'] . '\')" data-bs-toggle="modal" data-bs-target="#detailModal">';
                        echo '<i class="i bi-three-dots"></i>
                          </button>';
                        echo '</td>';
                        echo '</tr>';
                      }
                    }
                    ?>


                  </tbody>
                </table>
                <!-- MODAL -->
                <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Pelaporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p><b>ID Pelaporan : </b><span id="detailIdPelaporan"></span></p>
                        <p><b>Pelapor : </b><span id="detailPelapor"></span></p>
                        <p><b>Pelanggaran : </b><span id="namaPelanggaran"></span></p>
                        <p><b>Upload Sanksi : </b>
                          <br>
                          <img src="" alt="Upload Sanksi" id="uploadSanksi" style="max-width: 20%;">
                        <p><b>Bukti Pelanggaran : </b>
                          <br>
                          <img src="" alt="Bukti Pelanggaran" id="buktiPelanggaran" style="max-width: 20%;">
                        <p><b>Tanggal Lapor : </b><span id="detailTglLapor"></span></p>
                        <p><b>Tanggal Konfirmasi : </b><span id="detailTglKonfirmasi"></span></p>
                        <!-- Informasi lainnya sesuai kebutuhan -->
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>

                <script>
                  function showDetail(id_pelaporan, pelapor, nama_pelanggaran, bukti_pelanggaran, upload_sanksi, tgl_lapor, tgl_konfirmasi) {
                    document.getElementById('detailIdPelaporan').innerHTML = id_pelaporan;
                    document.getElementById('detailPelapor').innerHTML = pelapor;
                    document.getElementById('namaPelanggaran').innerHTML = nama_pelanggaran;
                    document.getElementById('detailTglKonfirmasi').innerHTML = tgl_konfirmasi;

                    document.getElementById('uploadSanksi').src = '../sanksi/upload/' + upload_sanksi;
                    document.getElementById('buktiPelanggaran').src = '../laporan/bukti/' + bukti_pelanggaran;
                    document.getElementById('detailTglLapor').innerHTML = tgl_lapor;
                    document.getElementById('detailTglKonfirmasi').innerHTML = tgl_konfirmasi;
                    var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
                    myModal.show();
                  }
                </script>
              </div>
            </div>

          </div>
        </div>
      </section>

    </main>
<?php
    $output = ob_get_clean(); // Ambil output yang ditangkap dan bersihkan penangkapan
    return $output;
  }
}
$dpa = new Dpa();
