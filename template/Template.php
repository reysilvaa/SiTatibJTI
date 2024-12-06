<?php 
abstract class Template {
     abstract public function header($username, $koneksi);

     public function footer()
     {
          echo '
          <!--=======Footer=======-->
          <footer id="footer" class="footer">
               <div class="copyright">
                    &copy; Copyright <strong><span>Si Tatib</span></strong>.
               </div>
               <div class="credits">
                    Designed by <a href="#">Kelompok 5</a>
               </div>
          </footer><!-- End Footer -->

          <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

          <script src="../../assets/vendor/apexcharts/apexcharts.min.js"></script>
          <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
          <script src="../../assets/vendor/chart.js/chart.umd.js"></script>
          <script src="../../assets/vendor/echarts/echarts.min.js"></script>
          <script src="../../assets/vendor/quill/quill.min.js"></script>
          <script src="../../assets/vendor/simple-datatables/simple-datatables.js"></script>
          <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
          <script src="../../assets/vendor/php-email-form/validate.js"></script>

          <!-- Template Main JS File -->
          <script src="../../assets/js/main.js"></script>';
     }
}
?>