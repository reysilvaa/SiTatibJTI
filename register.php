<?php
session_start();
require 'fungsi/pesan_kilat.php';
require 'template/TemplateLoginRegis.php';
$header = $template->header();
?>
<main>
     <div class="container">
          <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
               <div class="container">
                    <div class="row justify-content-center">
                         <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                              <div class="d-flex justify-content-center py-4">
                                   <a href="index.html" class="logo d-flex align-items-center w-auto">
                                        <img src="../assets/img/logo.png" alt="">
                                        <span class="d-none d-lg-block">Tata Tertib JTI</span>
                                   </a>
                              </div><!-- End Logo -->

                              <div class="card mb-3">

                                   <div class="card-body">

                                        <div class="pt-4 pb-2">
                                             <h5 class="card-title text-center pb-0 fs-4">Register</h5>
                                             <p class="text-center small">Buat Username dan Password anda</p>
                                        </div>

                                        <form class="row g-3 needs-validation" novalidate action="proses/proses_register.php" method="post">
                                             <?php
                                             if (isset($_SESSION['_flashdata'])) {
                                                  echo "<br>";
                                                  foreach ($_SESSION['_flashdata'] as $key => $val) {
                                                       echo get_flashdata($key);
                                                  }
                                             }
                                             ?>
                                             <div class="col-12">
                                                  <label for="yourUsername" class="form-label">NIM atau NIP</label>
                                                  <div class="input-group has-validation">
                                                       <input type="text" name="username" class="form-control" id="yourUsername" required>
                                                       <div class="invalid-feedback">Masukkan NIM atau NIP anda!</div>
                                                  </div>
                                             </div>
                                             <div class="col-12">
                                                  <label for="yourPassword" class="form-label">Password</label>
                                                  <input type="password" name="password" class="form-control" id="yourPassword" required>
                                                  <div class="invalid-feedback">Masukkan Password!</div>
                                             </div>
                                             <div class="col-12">
                                                  <label for="yourPassword" class="form-label">Siapa Anda?</label>
                                                  <select name="role" class="form-select" required>
                                                       <option value="" disabled selected>Pilih </option>
                                                       <option value="mahasiswa">Mahasiswa</option>
                                                       <option value="dosen">Dosen</option>
                                                       <option value="dosen_dpa">Dosen (DPA)</option>
                                                       <option value="dosen_dpa_admin">Dosen (Admin+DPA)</option>
                                                       <option value="dosen_admin">Dosen (Admin)</option>
                                                  </select>
                                             </div>
                                             <div class="col-12">
                                                  <button class="btn btn-primary w-100" type="submit">Create Account</button>
                                             </div>
                                             <div class="col-12">
                                                  <p class="small mb-0">Already have an account? <a href="login.php?page=login">Log in</a></p>
                                             </div>
                                        </form>

                                   </div>
                              </div>

                              <div class="credits">
                                   Designed by Kelompok 5</a>
                              </div>
                         </div>
                    </div>
               </div>

          </section>

     </div>
</main><!-- End #main -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<?php
$footer = $template->footer();
?>