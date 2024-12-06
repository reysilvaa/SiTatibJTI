<?php
include "ProfileDosen.php";
class ProfileMahasiswa extends ProfileDosen
{
     // get profile mahasiswa
     public function getProfileUser($id_mahasiswa)
     {
          global $koneksi;
          $nama_mahasiswa = '';
          $nama_kelas = '';
          $prodi = '';
          $nama_dpa = '';

          $id_mahasiswa = $_SESSION['username'];
          $sql = "SELECT m.id_mahasiswa, m.nama, k.nama_kelas, k.prodi AS nama_prodi, d.nama AS nama_dpa
                    FROM mahasiswa m 
                    LEFT JOIN kelasdpa k ON m.id_kelas = k.id_kelas
                    LEFT JOIN dosen d ON k.id_dosen = d.id_dosen
                    WHERE m.id_mahasiswa = '$id_mahasiswa'";
          // var_dump($_SESSION['id_mahasiswa']);


          $result = $koneksi->query($sql);

          if ($result) {
               while ($row = $result->fetch_assoc()) {
                    // Menggunakan data dari query
                    $id = $row['id_mahasiswa'];
                    $nama_mahasiswa = $row['nama'];
                    $nama_kelas = $row['nama_kelas'];
                    $prodi = $row['nama_prodi'];
                    $nama_dpa = $row['nama_dpa'];
               }
          } else {
               echo "data kosong";
          }

          $sql = "SELECT foto FROM user WHERE username = '$id_mahasiswa'";
          $result = $koneksi->query($sql);

          if ($result && $result->num_rows > 0) {
               $row = $result->fetch_assoc();
               $path_gambar = $row['foto'];
          }

          return [
               'nama' => $nama_mahasiswa,
               'nama_kelas' => $nama_kelas,
               'prodi' => $prodi,
               'nama_dpa' => $nama_dpa,
               'path_gambar' => $path_gambar
          ];
     }

     // get mahasiswa by id
     function getUserById($username)
     {
          global $koneksi;
          $sql = "SELECT * FROM mahasiswa WHERE id_mahasiswa = '$username'";
          $result = $koneksi->query($sql);
          $row = $result->fetch_assoc();
          return $row;
     }

     public function getProfileImagePath($username)
     {
          return parent::getProfileImagePath($username);
     }

     function getUserData($username)
     {
          return parent::getUserData($username);
     }



     public function profile()
     {
          require "../../koneksi/koneksi.php";
          require "../../fungsi/session.php";
          ob_start();
?>
          <main id="main" class="main">

               <div class="pagetitle">
                    <h1>Profile</h1>
                    <nav>
                         <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="../../entitas/mahasiswa/home.php">Pelanggaranku</a></li>
                              <li class="breadcrumb-item"><a href="../../fitur/sanksi/upload_sanksi.php">Upload Sanksi</a></li>
                              <li class="breadcrumb-item"><a href="../../fitur/profile/view_profile.php">Profilku</a></li>
                         </ol>
                    </nav>
               </div><!-- End Page Title -->

               <section class="section profile">
                    <div class="row">
                         <div class="col-xl-4">

                              <div class="card">
                                   <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                                        <?php
                                        $id_mahasiswa = $_SESSION["username"];
                                        $data = $this->getProfileUser($id_mahasiswa);
                                        $nama_mahasiswa = $data["nama"];
                                        $nama_kelas = $data['nama_kelas'];
                                        $prodi = $data['prodi'];
                                        $nama_dpa = $data['nama_dpa'];
                                        $path_gambar = $data["path_gambar"];
                                        ?>

                                        <img src="<?php echo $path_gambar; ?>" alt="Profile" class="rounded-circle img-thumbnail" style="width: 300px; height: 120px; object-fit: cover;">
                                        <h2 style="text-align: center;"><?php echo $nama_mahasiswa ?></h2>
                                        <h3><?php echo $prodi ?> - <?php echo $nama_kelas ?></h3>
                                        <div class="social-links mt-2">
                                             <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                                             <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                                             <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                                             <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                                        </div>
                                   </div>
                              </div>

                         </div>

                         <div class="col-xl-8">

                              <div class="card">
                                   <div class="card-body pt-3">
                                        <ul class="nav nav-tabs nav-tabs-bordered">

                                             <li class="nav-item">
                                                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                             </li>

                                             <li class="nav-item">
                                                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                                             </li>

                                             <li class="nav-item">
                                                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#edit_kelas">Edit Kelas</button>
                                             </li>

                                             <li class="nav-item">
                                                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Edit Password</button>
                                             </li>

                                        </ul>
                                        <div class="tab-content pt-2">

                                             <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                                  <h5 class="card-title">Profile Details</h5>

                                                  <div class="row">
                                                       <div class="col-lg-3 col-md-4 label ">Nama</div>
                                                       <div class="col-lg-9 col-md-8"><?php echo $nama_mahasiswa ?></div>
                                                  </div>

                                                  <div class="row">
                                                       <div class="col-lg-3 col-md-4 label">NIM</div>
                                                       <div class="col-lg-9 col-md-8"><?php echo $username ?></div>
                                                  </div>

                                                  <div class="row">
                                                       <div class="col-lg-3 col-md-4 label">Dosen DPA</div>
                                                       <div class="col-lg-9 col-md-8"><?php echo $nama_dpa ?></div>
                                                  </div>
                                             </div>

                                             <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                                  <!-- Profile Edit Form -->
                                                  <!-- Profile Image Edit Form -->
                                                  <form method="post" action="edit-profile.php" enctype="multipart/form-data">
                                                       <div class="row mb-3">
                                                            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                                            <div class="col-md-8 col-lg-9">

                                                                 <?php
                                                                 $this->getProfileImagePath($username);
                                                                 ?>

                                                                 <div class="pt-2">
                                                                      <!-- Tombol Hapus Gambar Profil -->
                                                                      <form action="edit-profile.php" method="post">
                                                                           <button type="submit" name="hapus_gambar" class="btn btn-danger btn-sm" title="Hapus foto profil"><i class="bi bi-trash"></i></button>
                                                                      </form>

                                                                      <!-- Form Unggah Gambar Profil -->
                                                                      <form action="edit-profile.php" method="post" enctype="multipart/form-data">
                                                                           <input type="file" name="file_gambar" id="file_gambar">
                                                                           <button type="submit" class="btn btn-primary btn-sm" title="Unggah foto baru"><i class="bi bi-upload"></i></button>
                                                                      </form>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </form><!-- End Profile Image Edit Form -->

                                                  <?php
                                                  $this->getUserById($username);
                                                  ?>

                                                  <!-- Profile Edit Form -->
                                                  <form method="post" action="edit-profile.php?edit">
                                                       <div class="row mb-3">
                                                            <label for="nama" class="col-md-4 col-lg-3 col-form-label">Nama</label>
                                                            <div class="col-md-8 col-lg-9">
                                                                 <input name="nama" type="text" class="form-control" id="nama" value="<?php echo $nama_mahasiswa; ?>">
                                                            </div>
                                                       </div>
                                                       <?php
                                                       $this->getUserData($username);
                                                       ?>

                                                       <div class="text-center">
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                       </div>
                                                  </form><!-- End Profile Edit Form -->
                                             </div>

                                             <div class="tab-pane fade pt-3" id="edit_kelas">
                                             <h5 class="card-title">Edit Kelas</h5>
                                             <form method="post" action="edit_kelas.php?edit">
                                                       <div class="row mb-3">
                                                            <label for="id_kelas" class="col-md-4 col-lg-3 col-form-label">Kelas & Prodi</label>
                                                            <div class="col-md-8 col-lg-9">
                                                                 <select name="id_kelas" class="form-select" id="id_kelas">
                                                                      <option value="kelas" selected>Pilih Kelas dan Prodi</option>
                                                                      <?php
                                                                      // Query untuk mengambil data kelasdpa
                                                                      $sql_kelas = "SELECT * FROM kelasdpa";
                                                                      $result_kelas = $koneksi->query($sql_kelas);
                                                                      if ($result_kelas && $result_kelas->num_rows > 0) {
                                                                           while ($row_kelas = $result_kelas->fetch_assoc()) {
                                                                                $nama_kelas = $row_kelas['nama_kelas'];
                                                                                $prodi = $row_kelas['prodi'];
                                                                                $id_kelas = $row_kelas["id_kelas"];
                                                                                echo "<option value='$id_kelas'>$nama_kelas ($prodi)</option>";
                                                                           }
                                                                      } else {
                                                                           echo "<option value=''>Tidak ada kelas</option>";
                                                                      }
                                                                      ?>
                                                                 </select>
                                                            </div>
                                                       </div>

                                                       <?php
                                                       $this->getUserData($username);
                                                       ?>

                                                       <div class="text-center">
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                       </div>
                                                  </form>
                                                  </div>

                                             <div class="tab-pane fade pt-3" id="profile-change-password">
                                                  <h5 class="card-title">Edit Password</h5>
                                                  <form method="post" action="edit_password.php" enctype="multipart/form-data">
                                                       <div class="row mb-3">
                                                            <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                                            <div class="col-md-8 col-lg-9">
                                                                 <input name="password" type="password" class="form-control" id="password">
                                                            </div>
                                                       </div>

                                                       <div class="text-center">
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                       </div>
                                                  </form>
                                             </div>

                                             <div class="tab-pane fade pt-3" id="profile-change-password">
                                             </div>

                                        </div><!-- End Bordered Tabs -->

                                   </div>
                              </div>

                         </div>
                    </div>
               </section>

          </main><!-- End #main -->
<?php
          $output = ob_get_clean(); // Ambil output yang ditangkap dan bersihkan penangkapan
          return $output;
     }
}
$profile = new ProfileMahasiswa();
?>