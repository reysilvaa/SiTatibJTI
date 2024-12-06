<?php
include '../../fungsi/pesan_kilat.php';
require '../../fungsi/session.php';

class Admin
{

     public function dataPelanggaran()
     {
          require "../../entitas/dosen_admin/DosenAdmin.php";
          ob_start();
          $dosenAdmin = new DosenAdmin();
          $getStatus = $_GET['status'] ?? 'semua';
          $statusCondition = $dosenAdmin->getStatusCondition($getStatus);
          $searchCondition = $dosenAdmin->getSearchCondition();
?>
          <main id="main" class="main">

               <div class="pagetitle">
                    <h1>Data Tables</h1>
                    <nav>
                         <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="view_admin.php">Data Pelanggaran</a></li>
                              <li class="breadcrumb-item"><a href="../laporan/view_laporan.php">Pelaporan</a></li>
                              <li class="breadcrumb-item"><a href="../profile/view_profile.php">Profilku</a></li>
                         </ol>
                    </nav>
               </div><!-- End Page Title -->
               <section class="section">
                    <div class="row">
                         <div class="col-lg-12">

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title">Tabel Verifikasi</h5>
                                        <div class="mb-3">
                                             <label for="filterStatus" class="form-label">Filter Status:</label>
                                             <select id="filterStatus" class="form-select" onchange="filterStatus()">
                                                  <option value="semua" <?php echo ($getStatus === 'semua') ? 'selected' : ''; ?>>Semua</option>
                                                  <option value="menunggu_verifikasi" <?php echo ($getStatus === 'menunggu_verifikasi') ? 'selected' : ''; ?>>Menunggu verifikasi</option>
                                                  <option value="ditolak" <?php echo ($getStatus === 'ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                                  <option value="sukses" <?php echo ($getStatus === 'sukses') ? 'selected' : ''; ?>>Sukses</option>
                                                  <option value="dilaporkan" <?php echo ($getStatus === 'dilaporkan') ? 'selected' : ''; ?>>Dilaporkan</option>
                                             </select>
                                        </div>

                                        <div class="mb-3">
                                             <form action="view_admin.php" method="GET" class="d-flex">
                                                  <!-- <label for="searchIdMahasiswa" class="me-2">Cari NIM :</label> -->
                                                  <input type="text" id="searchIdMahasiswa" name="search_id" class="form-control me-2" placeholder="Cari NIM Mahasiswa">
                                                  <button type="submit" class="btn btn-primary">Cari</button>
                                             </form>
                                        </div>

                                        <!-- Table with stripped rows -->
                                        <table class="table class='table table-bordered' style='width: 100%;'">
                                             <thead class='thead-dark'>
                                                  <tr class='table-light '>
                                                       <th><b>ID</b></th>
                                                       <th>NIM</th>
                                                       <th>Sanksi</th>
                                                       <th>Status</th>
                                                       <th>Ubah Status</th>
                                                       <th>Detail</th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <?php

                                                  function executeQuery($koneksi, $statusCondition, $searchCondition)
                                                  {
                                                       $sql = "SELECT p.*, m.nama AS mhs, m.id_mahasiswa, pg.nama_pelanggaran AS pelanggaran, s.jenis_sanksi, 
                                        d.nama AS nama_dosen, k.nama_kelas
                                        FROM pelaporan p
                                        INNER JOIN mahasiswa m ON p.id_mahasiswa = m.id_mahasiswa
                                        INNER JOIN pelanggaran pg ON p.id_pelanggaran = pg.id_pelanggaran
                                        INNER JOIN tingkat t ON pg.id_tingkat = t.id_tingkat
                                        INNER JOIN sanksi s ON p.id_sanksi = s.id_sanksi
                                        LEFT JOIN kelasdpa k ON m.id_kelas = k.id_kelas
                                        LEFT JOIN dosen d ON k.id_dosen = d.id_dosen $statusCondition $searchCondition";

                                                       $result = $koneksi->query($sql);

                                                       // Handle errors or return the result as needed
                                                       if ($result === false) {
                                                            die("Query execution failed: " . $koneksi->error);
                                                       }

                                                       return $result;
                                                  }
                                                  $result = executeQuery($koneksi, $statusCondition, $searchCondition);

                                                  // Gunakan $result sesuai kebutuhan Anda


                                                  if ($result->num_rows > 0) {
                                                       while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo "<td>" . $row['id_pelaporan'] . "</td>";
                                                            echo "<td>" . $row['id_mahasiswa'] . "</td>";
                                                            echo "<td>" . $row['jenis_sanksi'] . "</td>";

                                                            // echo "
                                                            //  <td>" . substr($row['jenis_sanksi'], 0, 60);
                                                            // if (strlen($row['jenis_sanksi']) > 60) {
                                                            //      echo "...";
                                                            // }

                                                            // Menyesuaikan dengan logika status pelanggaran
                                                            $status_pelanggaran = $row['status_pelanggaran'];
                                                            $badge_class = '';
                                                            $status_teks = '';

                                                            switch ($status_pelanggaran) {
                                                                 case 'ditolak':
                                                                      $badge_class = 'bg-danger';
                                                                      $status_teks = 'Ditolak';
                                                                      break;
                                                                 case 'menunggu_verifikasi':
                                                                      $badge_class = 'bg-warning';
                                                                      $status_teks = 'Menunggu';
                                                                      break;
                                                                 case 'sukses':
                                                                      $badge_class = 'bg-success';
                                                                      $status_teks = 'Sukses';
                                                                      break;
                                                                 case 'dilaporkan':
                                                                      $badge_class = 'bg-danger';
                                                                      $status_teks = 'Dilaporkan';
                                                                      break;
                                                                 default:
                                                                      $badge_class = 'bg-secondary';
                                                                      $status_teks = 'Belum Diketahui';
                                                                      break;
                                                            }

                                                            // Menampilkan status pelanggaran dengan badge sesuai dengan logika di atas
                                                            echo "<td><span class='badge $badge_class'>$status_teks</span></td>";

                                                            echo "<td>";
                                                            echo "<form action='proses_ubah_status.php' method='post'>";
                                                            echo "<input type='hidden' name='id_pelaporan' value='" . $row['id_pelaporan'] . "'>";
                                                            echo "<select name='status'>";
                                                            // Tambahkan opsi status yang sesuai
                                                            echo "<option value='menunggu_verifikasi'>Menunggu verifikasi</option>";
                                                            echo "<option value='ditolak'>Ditolak</option>";
                                                            echo "<option value='sukses'>Sukses</option>";
                                                            echo "<option value='dilaporkan'>Dilaporkan</option>";
                                                            echo "</select>";
                                                            echo "<input type='submit' value='Ubah'>";
                                                            echo "</form>";
                                                            echo "</td>";

                                                            echo "<td><button type='button' class='btn btn-secondary' onclick='showDetail(\"" .
                                                                 $row['id_pelaporan'] . "\", \"" .
                                                                 $row['mhs'] . "\", \"" .
                                                                 $row['pelanggaran'] . "\", \"" .
                                                                 $row['bukti_pelanggaran'] . "\", \"" .
                                                                 $row['upload_sanksi'] . "\", \"" .
                                                                 $row['tgl_lapor'] . "\", \"" .
                                                                 $row['tgl_konfirmasi'] . "\", \"" .
                                                                 $row['nama_kelas'] . "\", \"" .
                                                                 $row['nama_dosen'] . "\", \"" .
                                                                 $row['pelanggaran'] . "\")' data-bs-toggle='modal' data-bs-target='#detailModal'><i class='bi bi-three-dots'></i></button></td>";
                                                            echo "</tr>";
                                                       }
                                                  } else {
                                                       echo "<tr><td colspan='7'>Tidak ada data untuk ditampilkan.</td></tr>";
                                                  }
                                                  ?>
                                                  <div class="modal" id="detailModal" tabindex="-1">
                                                       <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                 <div class="modal-header">
                                                                      <h5 class="modal-title">Detail Pelanggaran</h5>
                                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                 </div>
                                                                 <div class="modal-body">
                                                                      <p><b>ID : </b><span id="detailIdPelaporan"></span></p>
                                                                      <p><b>Nama : </b><span id="detailNamaMahasiswa"></span></p>
                                                                      <p><b>Pelanggaran : </b><span id="namaPelanggaran"></span></p>
                                                                      <p><b>Upload Sanksi : </b></p>
                                                                      <img id="uploadSanksi" src="" alt="Upload Sanksi" style="max-width: 20%; height: auto;">
                                                                      <p><b>Bukti Pelanggaran : </b></p>
                                                                      <img id="buktiPelanggaran" src="" alt="Bukti Pelanggaran" style="max-width: 20%; height: auto;">
                                                                      <p><b>Tgl Lapor : </b><span id="detailTglLapor"></span></p>
                                                                      <p><b>Tgl DIkonfirmasi : </b><span id="detailTglKonfirmasi"></span></p>
                                                                      <p><b>Kelas : </b><span id="detailKelas"></span></p>
                                                                      <p><b>Nama DPA: </b><span id="detailDPA"></span></p>
                                                                 </div>
                                                                 <div class="modal-footer">
                                                                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </div>


                                             </tbody>
                                        </table>
                                        <script>
                                             function showDetail(id_pelaporan, mhs, pelanggaran, bukti_pelanggaran,
                                                  upload_sanksi, tgl_lapor, tgl_konfirmasi, nama_kelas, nama_dosen) {
                                                  // Isi detail pada modal
                                                  document.getElementById('detailIdPelaporan').innerHTML = id_pelaporan;
                                                  document.getElementById('detailNamaMahasiswa').innerHTML = mhs;
                                                  document.getElementById('namaPelanggaran').innerHTML = pelanggaran;
                                                  document.getElementById('buktiPelanggaran').src = '../laporan/bukti/' + bukti_pelanggaran;
                                                  document.getElementById('uploadSanksi').src = '../sanksi/upload/' + upload_sanksi;
                                                  document.getElementById('detailTglLapor').innerHTML = tgl_lapor;
                                                  document.getElementById('detailTglKonfirmasi').innerHTML = tgl_konfirmasi;
                                                  document.getElementById('detailKelas').innerHTML = nama_kelas;
                                                  document.getElementById('detailDPA').innerHTML = nama_dosen;

                                             }

                                             function filterStatus() {
                                                  var selectedStatus = document.getElementById("filterStatus").value;
                                                  var url = "view_admin.php";

                                                  if (selectedStatus !== "semua") {
                                                       url += "?status=" + selectedStatus;
                                                  }
                                                  window.location.href = url;
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
$admin = new Admin();
