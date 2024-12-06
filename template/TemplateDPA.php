<?php
require "Template.php";
require "../../fungsi/session.php";
require "../../koneksi/koneksi.php";

class TemplateDPA extends Template
{

     public function header($username, $koneksi)
     {
          echo '
          <!DOCTYPE html>
               <html lang="en">

               <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>DPA</title>

                    <!-- Favicons -->
                    <link href="../../assets/img/faviconJTI.png" rel="icon">

                    <!-- Google Fonts -->
                    <link href="https://fonts.gstatic.com" rel="preconnect">
                    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

                    <!-- Vendor CSS Files -->
                    <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
                    <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
                    <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
                    <link href="../../assets/vendor/quill/quill.snow.css" rel="stylesheet">
                    <link href="../../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
                    <link href="../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
                    <link href="../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
                    <script src="https://kit.fontawesome.com/1a38f31853.js" crossorigin="anonymous"></script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/fontawesome.min.css">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/fontawesome.min.css" integrity="sha384-BY+fdrpOd3gfeRvTSMT+VUZmA728cfF9Z2G42xpaRkUGu2i3DyzpTURDo5A6CaLK" crossorigin="anonymous">
                    <!-- Template Main CSS File -->
                    <link href="../../assets/css/style.css" rel="stylesheet">
                    <script src="../../assets/vendor/apexcharts/apexcharts.min.js"></script>
                    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                    <script src="../../assets/vendor/chart.js/chart.umd.js"></script>
                    <script src="../../assets/vendor/echarts/echarts.min.js"></script>
                    <script src="../../assets/vendor/quill/quill.min.js"></script>
                    <script src="../../assets/vendor/simple-datatables/simple-datatables.js"></script>
                    <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
                    <script src="../../assets/vendor/php-email-form/validate.js"></script>
                    <!-- Template Main JS File -->
                    <script src="../../assets/js/main.js"></script>
                    <!-- Bootstrap JS -->
                    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                    <!-- JavaScript Kustom Anda -->
                    <script src="../../assets/js/main.js"></script>';

          $query_join = "SELECT u.*, m.nama AS nama_mhs FROM user u JOIN mahasiswa m ON u.id_user = m.id_user";
          $result = mysqli_query($koneksi, $query_join);
          $row = mysqli_fetch_assoc($result);
          $username = $_SESSION["username"];
          echo '
                    </head>
                    <body>
                    <header id="header" class="header fixed-top d-flex align-items-center">
                       <div class="d-flex align-items-center justify-content-between">
                         <a href="../../entitas/dpa/view_dpa.php" class="logo d-flex align-items-center">
                              <img src="../../assets/img/logoJTI.png" alt="">
                              <span class="d-none d-lg-block">Si Tatib</span>
                         </a>
                         <i class="bi bi-list toggle-sidebar-btn"></i>
                         </div><!-- End Logo -->

                         <nav class="header-nav ms-auto">
                         <ul class="d-flex align-items-center">

                              <li class="nav-item dropdown pe-3">';

          $sql_foto = "SELECT foto FROM user WHERE username = '$username'";
          $result_foto = mysqli_query($koneksi, $sql_foto);
          $path_gambar = '../../fitur/profile/uploads/';

          if ($result_foto && mysqli_num_rows($result_foto) > 0) {
               $row_foto = mysqli_fetch_assoc($result_foto);
               $path_gambar = $row_foto['foto'];
          }

          $query_join = "SELECT u.*, d.nama AS nama_dpa
                                             FROM user u
                                             LEFT JOIN dosen d ON u.id_user = d.id_user
                                             WHERE d.id_dosen = '$username'";
          $result_join = mysqli_query($koneksi, $query_join);
          $row_join = mysqli_fetch_assoc($result_join);

          if ($row_join) {
               $nama = $row_join["nama_dpa"];
               $role = $row_join["role"];
          } else {
               $nama = "User Unknown";
          }

          echo '
                              <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                                   <img src="' . $path_gambar . '" alt="Profile" class="rounded-circle" style="object-fit: cover; width: 40px; height: 40px;";"">
                                   <span class="d-none d-md-block dropdown-toggle ps-2">' . $nama . '</span>
                              </a>

                              </a><!-- End Profile Iamge Icon -->

                              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                                   <li class="dropdown-header">
                                   <h6>' . $nama . '</h6>
                                   <span>' . $role . '</span>
                                   </li>
                                   <li>
                                   <hr class="dropdown-divider">
                                   </li>

                                   <li>
                                   <a class="dropdown-item d-flex align-items-center" href="../../fitur/profile/view_profile.php">
                                   <i class="bi bi-person"></i>
                                   <span>My Profile</span>
                                   </a>
                                   </li>
                                   <li>
                                   <hr class="dropdown-divider">
                                   </li>
                                   <li>
                                   <a class="dropdown-item d-flex align-items-center" href="../../logout.php">
                                   <i class="bi bi-box-arrow-right"></i>
                                   <span>Sign Out</span>
                                   </a>
                                   </li>

                              </ul><!-- End Profile Dropdown Items -->
                              </li><!-- End Profile Nav -->

                         </ul>
                         </nav><!-- End Icons Navigation -->
                    </header><!-- End Header -->
                    <aside id="sidebar" class="sidebar">

                         <ul class="sidebar-nav" id="sidebar-nav">

                         <li class="nav-item">
                              <a class="nav-link" href="../../entitas/dpa/view_dpa.php">
                              <i class="bi bi-grid"></i>
                              <span>Data Mahasiswaku</span>
                              </a>
                         </li><!-- End Dashboard Nav -->
                         <li class="nav-heading">Pelanggaran</li>
                         <li class="nav-item">
                              <a class="nav-link collapsed" href="../../fitur/laporan/view_laporan.php">
                              <i class="bi bi-person"></i>
                              <span>Pelaporan</span>
                              </a>
                         </li><!-- End Profile Page Nav -->
                         <li class="nav-item">
                              <a class="nav-link collapsed" href="../../fitur/profile/view_profile.php">
                              <i class="bi bi-question-circle"></i>
                              <span>Profilku</span>
                              </a>
                         </li><!-- End F.A.Q Page Nav -->
                         <li class="nav-item">
                              <a class="nav-link collapsed" href="../../logout.php">
                              <i class="bi bi-file-earmark"></i>
                              <span>Logout</span>
                              </a>
                         </li><!-- End Logout Page Nav -->
                         </ul>
                    </aside><!-- End Sidebar-->';
     }

     public function footer()
     {
          return parent::footer();
     }
}
$template = new TemplateDPA();
