<?php
include '../../entitas/mahasiswa/Mahasiswa.php';
include 'Sanksi.php';
require '../../koneksi/koneksi.php';
require "../../fungsi/session.php";
require_once '../../template/TemplateMahasiswa.php';

$header = $templateMahasiswa->header($username, $koneksi);
?>
<main id="main" class="main">
     <div class="pagetitle">
          <h1>Upload Sanksi</h1>
          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../../entitas/mahasiswa/home.php">Pelanggaranku</a></li>
                    <li class="breadcrumb-item"><a href="../../fitur/sanksi/upload_sanksi.php">Upload Sanksi</a></li>
                    <li class="breadcrumb-item"><a href="../../fitur/profile/view_profile.php">Profilku</a></li>
               </ol>
          </nav>
     </div><!-- End Page Title -->

     <section class="section">
          <div class="row">
               <div class="col-lg-12">
                    <div class="card">
                         <div class="card-body">
                              <h5 class="card-title">Data Pelanggaranku</h5>
                              <p>Pelanggaranku, Kesalahanku, Keteledoranku sudah terdata disini :(</p>
                              <!-- Table with stripped rows -->
                              <?php
                              $dataPelaporan = $sanksi->getDataPelaporan($username);

                              if (mysqli_num_rows($dataPelaporan) > 0) {
                                   // Memulai pembuatan tabel HTML
                                   
                                   echo "<table class='table table-bordered' style='width: 100%;'>
                                           <thead class='thead-dark'>
                                               <tr class='table-light '>
                                                   <th scope='col' class = 'text-center' style='width: 40%;'>Sanksi</th>
                                                   <th scope='col' class = 'text-center' style='width: 40%;'>Kirim Sanksi</th>
                                                   <th scope='col' class = 'text-center' style='width: 10%;'>Aksi</th>
                                                   <th scope='col' class = 'text-center' style='width: 10%;'>Status</th>
                                               </tr>
                                           </thead>
                                           <tbody>";
                                   // Loop untuk menampilkan data dalam baris tabel HTML

                                   while ($row = mysqli_fetch_assoc($dataPelaporan)) {
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
                                                  $status_teks = 'Selesai';
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
                                        // Mengambil daftar nama file yang diunggah dari database
                                        $nama_files = explode(',', $row['upload_sanksi']);
                                        echo "<tr>
                                             <td title='" . $row['jenis_sanksi'] . "'>" . substr($row['jenis_sanksi'], 0, 60);
                                        if (strlen($row['jenis_sanksi']) > 60) {
                                             echo "...";
                                        }
                                        echo "</td>
                                        <td class='text-center'>
                                             &nbsp;
                                             <form action='proses_upload.php' method='post' enctype='multipart/form-data' style='display: inline;'>
                                             <input id='file-upload' type='file' name='berkas_sanksi[]' multiple>
                                             <input type='hidden' name='id_pelanggaran' value='" . $row['id_pelanggaran'] . "'>
                                             <button type='submit' class='btn btn-primary'>
                                                  <i class='bi bi-send-fill'></i>
                                             </button>
                                             </form>
                                        </td>
                                        <td class='text-center'>
                                             <button type='button' class='btn btn-secondary' onclick='showDetail(\"" .
                                   $row['id_pelaporan'] . "\", \"" .
                                   $row['pelapor'] . "\", \"" .
                                   $row['nama_pelanggaran'] . "\", \"" .
                                   $row['bukti_pelanggaran'] . "\", \"" .
                                   $row['upload_sanksi'] . "\", \"" .
                                   $row['tgl_lapor'] . "\", \"" .
                                   $row['tgl_konfirmasi'] . "\")' data-bs-toggle='modal' data-bs-target='#detailModal'> 
                                             <i class='bi bi-three-dots'></i>
                                             </button>
                                        </td>";

                              echo "<td class='text-center'><span class='badge $badge_class badge-sm'>" . $status_teks . "</span></td>                        
                                   </tr>";
                         }
                              // Menutup tabel HTML
                              echo "</tbody>
                                   </table>";
                              } else {
                              echo "Tidak ada data yang ditemukan";
                         }
                              ?>

                              </table>
                              <!-- MODAL -->
                              <div class="modal" id="detailModal" tabindex="-1">
                                   <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                             <div class="modal-header">
                                                  <h5 class="modal-title">Detail Pelaporan</h5>
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
                                                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <script>
                                   function showDetail(id_pelaporan, pelapor, nama_pelanggaran, bukti_pelanggaran, upload_sanksi, tgl_lapor, tgl_konfirmasi) {
                                        document.getElementById('detailIdPelaporan').innerHTML = id_pelaporan;
                                        document.getElementById('detailPelapor').innerHTML = pelapor;
                                        document.getElementById('namaPelanggaran').innerHTML = nama_pelanggaran;
                                        document.getElementById('buktiPelanggaran').src = '../laporan/bukti/' + bukti_pelanggaran;
                                        document.getElementById('uploadSanksi').src = 'upload/' + upload_sanksi;
                                        document.getElementById('detailTglLapor').innerHTML = tgl_lapor;
                                        document.getElementById('detailTglKonfirmasi').innerHTML = tgl_konfirmasi;
                                        var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
                                        myModal.show();
                                   }
                              </script>
                              <div class="modal fade" id="firstTimeModal" tabindex="-1" aria-labelledby="firstTimeModalLabel" aria-hidden="true">
                                   <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                             <div class="modal-header">
                                                  <h5 class="modal-title" id="firstTimeModalLabel"> Ingat!</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                             </div>
                                             <div class="modal-body">
                                                  <p>Selalu Ingat, Sanksi yang diupload adalah sesuai dengan Kesepakatan Dosen</p>
                                             </div>
                                             <div class="modal-footer">
                                                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <script>
                                   document.addEventListener('DOMContentLoaded', function() {
                                        var firstTimeModal = new bootstrap.Modal(document.getElementById('firstTimeModal'));
                                        firstTimeModal.show();
                                   });
                              </script>
                         </div>
                    </div>
               </div>
          </div>

     </section>
</main>
<?php
$footer = $templateMahasiswa->footer();
?>