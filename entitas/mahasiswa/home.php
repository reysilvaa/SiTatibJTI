<?php
include 'Mahasiswa.php';
require "../../koneksi/koneksi.php";
require "../../fungsi/session.php";
require '../../fungsi/pesan_kilat.php';
require_once '../../template/TemplateMahasiswa.php';
$header = $templateMahasiswa->header($username, $koneksi);

if (isset($_SESSION['username']) && $role === 'mahasiswa') {
     $username = $_SESSION['username'];

     $infoMhs = $mahasiswa->getInfoMahasiswa($username);
     $jumlahPelaporanDiterima = $mahasiswa->getJumlahPelaporanDiterima($username);
     $jumlahPelaporanStatusDilaporkan = $mahasiswa->getJumlahPelaporanDilaporkan($username);
     $jumlahPelaporanStatusVerifikasi = $mahasiswa->getJumlahPelaporanMenungguVerifikasi($username);
     $dataPelaporan = $mahasiswa->getDataPelaporan($username);
     $namaMhs = $mahasiswa->getNamaMahasiswa($username);

} else {
     header("Location: ../../login.php");
     exit();
}

?>
<main id="main" class="main">
     <div class="pagetitle">


          <h1>Halo <?= $namaMhs ?? "User Unknown"; ?> !</h1>

          <?php
          require "../../koneksi/koneksi.php";
          require "../../fungsi/session.php";
          $id_mahasiswa = $_SESSION['username'];
          $sql_count = "SELECT *, COUNT(*) AS jumlah_pelanggaran 
                                   FROM mahasiswa m
                                   JOIN tingkat t ON m.id_tingkat = t.id_tingkat
                                   WHERE m.id_mahasiswa = '$id_mahasiswa'";
          $result = $koneksi->query($sql_count);
          if ($result->num_rows > 0) {
               while ($row = $result->fetch_assoc()) {
                    $tingkat_pelanggaran = $row['tingkat'];
                    $jumlah_pelanggaran = $row['jumlah_pelanggaran'];
                    if ($tingkat_pelanggaran ===  5 && $jumlah_pelanggaran >= 3) {
                         $sql_update = "UPDATE mahasiswa 
                                 SET id_tingkat = 4 
                                 WHERE id_mahasiswa = '$id_mahasiswa'";
                         echo '<span class="badge badge-pill badge-warning text-primary">Klasifikasi : ' . $tingkat_pelanggaran . '</span>';
                    } elseif ($tingkat_pelanggaran === 4 && $jumlah_pelanggaran >= 3) {
                         $sql_update = "UPDATE mahasiswa 
                                 SET id_tingkat = 3 
                                 WHERE id_mahasiswa = '$id_mahasiswa'";
                         echo '<span class="badge badge-pill badge-warning text-secondary">Klasifikasi : ' . $tingkat_pelanggaran . '</span>';
                    } elseif ($tingkat_pelanggaran === 3 && $jumlah_pelanggaran >= 3) {
                         $sql_update = "UPDATE mahasiswa 
                                 SET id_tingkat = 2 
                                 WHERE id_mahasiswa = '$id_mahasiswa'";
                         echo '<span class="badge badge-pill badge-warning text-warning">Klasifikasi : ' . $tingkat_pelanggaran . '</span>';
                    } elseif ($tingkat_pelanggaran === 2 && $jumlah_pelanggaran >= 3) {
                         $sql_update = "UPDATE mahasiswa 
                                 SET id_tingkat = 1 
                                 WHERE id_mahasiswa = '$id_mahasiswa'";
                         echo '<span class="badge badge-pill badge-warning text-danger">Klasifikasi : ' . $tingkat_pelanggaran . '</span>';
                    }
               }
          }

          ?>
          </h1>

          <nav>
               <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Pelanggaranku</a></li>
                    <li class="breadcrumb-item"><a href="../../fitur/sanksi/upload_sanksi.php">Upload Sanksi</a></li>
                    <li class="breadcrumb-item"><a href="../../fitur/profile/view_profile.php">Profilku</a></li>
               </ol>
          </nav>
     </div><!-- End Page Title -->

     <section class="section dashboard">
          <div class="row">

               <!-- Left side columns -->
               <div class="col-lg-15">
                    <div class="row">
                         <div class="col-xxl-4 col-md-6">
                              <div class="card info-card sales-card">
                                   <div class="card-body">
                                        <h5 class="card-title">Sanksi <span>| Tanggungan Sanksi</span></h5>

                                        <div class="d-flex align-items-center">
                                             <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                  <i class="bi bi-bell-fill"></i>
                                             </div>
                                             <div class="ps-3">
                                                  <h6><?php echo $jumlahPelaporanDiterima ?></h6>
                                                  <span class="text-success small pt-1 fw-bold"><?php echo $jumlahPelaporanDiterima ?></span> <span class="text-muted small pt-2 ps-1">Sanksi Belum di upload</span>
                                             </div>
                                        </div>
                                   </div>

                              </div>
                         </div><!-- End Sales Card -->

                         <!-- Revenue Card -->
                         <div class="col-xxl-4 col-md-6">
                              <div class="card info-card revenue-card">
                                   <div class="card-body">
                                        <h5 class="card-title">Pelanggaran <span>| Melakukan Pelanggaran</span></h5>

                                        <div class="d-flex align-items-center">
                                             <div class="card-icon rounded-circle d-flex align-items-center justify-content-center ">
                                                  <i class="bi bi-envelope-exclamation-fill"></i>
                                             </div>
                                             <div class="ps-3">
                                                  <h6><?php echo $jumlahPelaporanStatusDilaporkan ?></h6>
                                                  <span class="text-danger small pt-1 fw-bold"><?php echo $jumlahPelaporanStatusDilaporkan ?></span> <span class="text-muted small pt-2 ps-1">Laporan dari Dosen </span>

                                             </div>
                                        </div>
                                   </div>

                              </div>
                         </div><!-- End Revenue Card -->

                         <!-- Customers Card -->
                         <div class="col-xxl-4 col-xl-12">
                              <div class="card info-card customers-card">
                                   <div class="card-body">
                                        <h5 class="card-title">Sanksi <span>| Menunggu Verifikasi</span></h5>

                                        <div class="d-flex align-items-center">
                                             <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                  <i class="bi bi-exclamation-triangle-fill"></i>
                                             </div>
                                             <div class="ps-3">
                                                  <h6><?php echo $jumlahPelaporanStatusVerifikasi ?></h6>
                                                  <span class="text-danger small pt-1 fw-bold"><?php echo $jumlahPelaporanStatusVerifikasi ?></span> <span class="text-muted small pt-2 ps-1">Berkas Menunggu Verifikasi Dosen</span>

                                             </div>
                                        </div>

                                   </div>
                              </div>

                         </div><!-- End Customers Card -->

                         <!-- Recent Sales -->
                         <div class="col-12">
                              <div class="card recent-sales overflow-auto">
                                   <div class="card-body">
                                        <h5 class="card-title">Pelanggaranku <span>| Saat ini</span></h5>
                                        <?php
                                        $id_mahasiswa = $_SESSION['username'];
                                        $sql = "SELECT * FROM pelaporan pl
                            INNER JOIN pelanggaran pg ON pl.id_pelanggaran = pg.id_pelanggaran
                            WHERE pl.id_mahasiswa = '$id_mahasiswa'";
                                        $result = $koneksi->query($sql);
                                        ?>
                                        <?php if ($result->num_rows > 0) { ?>
                                             <table class="table table-borderless datatable">
                                                  <thead>
                                                       <tr>
                                                            <th scope="col">No</th>
                                                            <th scope="col">Tingkat</th>
                                                            <th scope="col">Pelanggaran</th>
                                                            <th scope="col">Sanksi</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       <?php $no = 0;
                                                       while ($row = $result->fetch_assoc()) {
                                                            $no++ ?>
                                                            <?php
                                                            // Memperoleh nilai status_pelanggaran dari baris saat ini
                                                            $status_pelanggaran = $row['status_pelanggaran'];

                                                            // Logika untuk menentukan badge_class dan status_teks
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
                                                            ?> <tr>
                                                                 <th scope="row"><a href="#"><?php echo $no; ?></a></th>
                                                                 <td>
                                                                      <span class="badge bg-black"><?php echo $mahasiswa->konversiKeRomawi($row['id_tingkat']); ?></span>
                                                                 </td>
                                                                 <td>
                                                                      <?php
                                                                      $nama_pelanggaran = isset($row['nama_pelanggaran']) ? $row['nama_pelanggaran'] : 'Nama pelanggaran tidak tersedia';
                                                                      $panjang_max = 115; // Tentukan panjang maksimum teks yang ingin ditampilkan
                                                                      echo strlen($nama_pelanggaran) > $panjang_max ? substr($nama_pelanggaran, 0, $panjang_max) . "..." : $nama_pelanggaran;
                                                                      ?>
                                                                 </td>
                                                                 <td>
                                                                      <span class="badge <?php echo $badge_class; ?> badge-sm"><?php echo $status_teks; ?></span>
                                                                 </td>
                                                            </tr>
                                                       <?php } ?>
                                                  </tbody>
                                             </table>
                                        <?php } else { ?>
                                             <p>Tidak ada pelanggaran yang dilaporkan.</p>
                                        <?php } ?>
                                   </div>

                                   <!-- Top Selling -->
                                   <div class="col-12">
                                        <div class="card top-selling overflow-auto">
                                             <div class="card-body pb-0">
                                                  <h5 class="card-title">Tata Tertib Kehidupan Kampus <span>| .pdf</span></h5>
                                                  <?php
                                                  $sql = "SELECT * FROM tingkat t RIGHT JOIN pelanggaran p
                    ON t.id_tingkat = p.id_tingkat";
                                                  $result = mysqli_query($koneksi, $sql);
                                                  if ($result && mysqli_num_rows($result) > 0) {

                                                  ?>
                                                       <table class="table table-borderless">
                                                            <thead>
                                                                 <tr>
                                                                      <th scope="col">No</th>
                                                                      <th scope="col">
                                                                           <center>Nama Pelanggaran
                                                                      </th>
                                                                      <th scope="col">Tingkat</th>
                                                                 </tr>
                                                            </thead>
                                                            <tbody>
                                                                 <tr>
                                                                      <?php
                                                                      // Tampilkan hasil query ke dalam tabel
                                                                      while ($row = mysqli_fetch_assoc($result)) {
                                                                      ?>
                                                                 <tr>
                                                                      <td><?php echo $row['id_pelanggaran']; ?></td>
                                                                      <td><?php echo $row['nama_pelanggaran']; ?></td>
                                                                      <td>
                                                                           <center><?php echo $row['tingkat']; ?>
                                                                      </td>
                                                                 </tr>
                                                            <?php
                                                                      }
                                                            ?>
                                                            </tbody>
                                                       </table>
                                                  <?php
                                                  } else {
                                                       echo "Tidak ada data yang ditemukan";
                                                  }
                                                  ?>

                                             </div>

                                        </div>
                                   </div><!-- End Top Selling -->

                              </div>
                         </div><!-- End Left side columns -->
                    </div>
     </section>

</main><!-- End #main -->
<?php
$footer = $templateMahasiswa->footer();
?>