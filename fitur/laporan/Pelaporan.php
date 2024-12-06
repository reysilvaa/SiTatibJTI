<?php
require '../../koneksi/koneksi.php';

class Pelaporan
{

     public function getNamaMhs()
     {
          global $koneksi;

          $sql_mahasiswa = "SELECT id_mahasiswa, nama FROM mahasiswa";
          $result_mahasiswa = $koneksi->query($sql_mahasiswa);

          if ($result_mahasiswa->num_rows > 0) {
               while ($row_mahasiswa = $result_mahasiswa->fetch_assoc()) {
                    echo "<option value='" . $row_mahasiswa["id_mahasiswa"] . "'>" . $row_mahasiswa["id_mahasiswa"] . "</option>";
               }
          } else {
               echo "<option value=''>No student data available</option>";
          }
     }

     public function getTingkat()
     {
          global $koneksi;

          $sql_tingkat = "SELECT id_tingkat, tingkat FROM tingkat";
          $result_tingkat = $koneksi->query($sql_tingkat);

          if ($result_tingkat->num_rows > 0) {
               while ($row_tingkat = $result_tingkat->fetch_assoc()) {
                    echo "<option value='" . $row_tingkat["id_tingkat"] . "'>" . $row_tingkat["tingkat"] . "</option>";
               }
          } else {
               echo "<option value=''>No level data available</option>";
          }

          return $row_tingkat["id_angkat"];
     }

     public function pelaporan()
     {
          ob_start();
?>
          <main id="main" class="main">
               <div class="card">
                    <div class="card-body">
                         <h5 class="card-title">Pelaporan Mahasiswa</h5>
                         <!-- Floating Label Form -->
                         <form class="row g-3" action="../../fitur/laporan/proses_lapor.php" method="post" enctype="multipart/form-data">
                              <div class="col-md-12 mb-3">
                                   <div class="form-floating">
                                        NIM:
                                        <select class="form-select select2-class" id="floatingName" name="id_mahasiswa" style="width: 100%" data-dropdownAutoWidth="true">
                                             <?php
                                             if ($_SESSION['role'] == 'dosen') {
                                                  $this->getNamaMhs();
                                             } elseif ($_SESSION['role'] == 'dosen_admin') {
                                                  $this->getNamaMhs();
                                             } elseif($_SESSION['role'] == 'dosen_dpa') {
                                                  $this->getNamaMhs();
                                             } elseif($_SESSION['role'] == 'dosen_dpa_admin') {
                                                  $this->getNamaMhs();
                                             }
                                             ?>
                                        </select>
                                   </div>
                              </div>

                              <div class="col-md-12 mb-3">
                                   <div class="form-floating">
                                        Tingkat:
                                        <select class="form-select select2-class" id="tingkat" name="tingkat" data-dropdownAutoWidth="true" style="width: 100%" onchange="getPelanggaran()">
                                             <?php
                                             if ($_SESSION['role'] == 'dosen') {
                                                  $this->getTingkat();
                                             } elseif ($_SESSION['role'] == 'dosen_admin') {
                                                  $this->getTingkat();
                                             } elseif($_SESSION['role'] == 'dosen_dpa') {
                                                  $this->getTingkat();
                                             } elseif($_SESSION['role'] == 'dosen_dpa_admin') {
                                                  $this->getTingkat();
                                             }
                                             ?>
                                        </select>
                                   </div>
                              </div>

                              <div class="col-md-12 mb-3">
                                   <div class="form-floating">
                                        Pelanggaran:
                                        <select class="form-select select2-class" id="pelanggaran" name="id_pelanggaran" style="width: 100%" data-dropdownAutoWidth="true">
                                             <!-- Pelanggaran options will be updated using AJAX -->
                                        </select>
                                   </div>
                              </div>

                              <div class="col-md-12 mb-3">
                                   <div class="form-floating">
                                        Sanksi:
                                        <select class="form-select select2-class" id="sanksi" name="sanksi" style="width: 100%" data-dropdownAutoWidth="true">
                                             <!-- Sanksi options will be updated using AJAX -->
                                        </select>
                                   </div>
                              </div>

                              <div class="col-md-6 mb-3">
                                   <div class="form-floating">
                                        <input type="file" class="form-control" id="uploadBuktiPelanggaran" name="uploadBuktiPelanggaran" placeholder="Upload Bukti Pelanggaran" multiple>
                                        <label for="uploadBuktiPelanggaran">Upload Bukti Pelanggaran</label>
                                   </div>
                              </div>

                              <div>
                                   <button type="submit" class="btn btn-primary" name="updatePassword">Submit</button>
                              </div>
                         </form>
                    </div>
               </div>

               <!-- Add Bootstrap script -->
               <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
               <!-- Add Select2 CSS link -->
               <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

               <!-- Make sure jQuery is loaded -->
               <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

               <!-- Add Select2 script -->
               <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
               <script>
                    function getPelanggaran() {
                         var tingkat = $('#tingkat').val();

                         $.ajax({
                              url: 'get_pelanggaran.php',
                              type: 'POST',
                              data: {
                                   tingkat: tingkat
                              },
                              success: function(response) {
                                   $('#pelanggaran').html(response);

                                   if (tingkat >= '0' && tingkat <= '5') {
                                        $.ajax({
                                             url: 'get_sanksi.php',
                                             type: 'POST',
                                             data: {
                                                  tingkat: tingkat
                                             },
                                             success: function(sanksiResponse) {
                                                  $('#sanksi').html(sanksiResponse);
                                                  $('#sanksi').prop('disabled', false);
                                             }
                                        });
                                   } else {
                                        $('#sanksi').html('');
                                        $('#sanksi').prop('disabled', true);
                                   }
                              }
                         });
                    }

                    $(document).ready(function() {
                         getPelanggaran();

                         $('.select2-class').select2();

                         $('#tingkat').change(function() {
                              getPelanggaran();
                         });
                    });
               </script>
          </main>
<?php
          $output = ob_get_clean(); // Ambil output yang ditangkap dan bersihkan penangkapan
          return $output;
     }
}
?>