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
              <a href="#" class="logo d-flex align-items-center w-auto">
                <img src="assets/img/logoJTI.png" alt="">
                <span class="d-none d-lg-block">Tata Tertib JTI</span>
              </a>
            </div>
            <div class="card mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  <h5 class="card-title text-center pb-0 fs-4">Login !</h5>
                  <p class="text-center small">Masukkan Akun Anda</p>
                </div>
                <?php
                if (isset($_SESSION['_flashdata'])) {
                  echo "<br>";
                  foreach ($_SESSION['_flashdata'] as $key => $val) {
                    echo get_flashdata($key);
                  }
                }
                ?>
                <form class="row g-3 needs-validation" action="proses/proses_login.php" method="POST">
                  <div class="col-12">
                    <label for="yourPassword" class="form-label">NIM atau NIP</label>
                    <input type="username" name="username" class="form-control" id="nim" required>
                    <div class="invalid-feedback">Masukkan NIM anda!</div>
                  </div>
                  <div class="col-12">
                    <label for="yourPassword" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    <div class="invalid-feedback">Masukkan Password!</div>
                  </div>
                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0">Don't have account? <a href="register.php">Register</a>
                      <span></span>
                    </p>
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
</main>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
