<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Zamboanga City e-Sumbong</title>
  <link rel="shortcut icon" type="image/jpg" href="/img/logo1.png"/>

  {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="/bower_components/admin-lte/dist/css/adminlte.min.css">
  <!-- icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- JQUERY -->
  <script language="JavaScript" type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/css/custom.css">
  <link rel="stylesheet" href="/css/custom-slider.css">
  
  <!-- Preloader -->
  <link rel="stylesheet" href="/css/preload.css">
  <!-- patient-->
  <link rel="stylesheet" href="/css/patient.css">

  <!-- button CSS -->
  <link rel="stylesheet" href="/css/button.css">
  <link rel="stylesheet" href="/css/home.css">
  <link rel="stylesheet" href="/css/sidebar.css">
  <link rel="stylesheet" href="/js/sidebar.js">
  <!-- Toastr -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/toastr/toastr.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

   <!-- ChartJS -->
   <script src="/bower_components/admin-lte/plugins/chart.js/Chart.min.js"></script>
   <script src="/js/chartjs-plugin-datalabels.min.js"></script>
 

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

  <!-- BS Stepper -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/bs-stepper/css/bs-stepper.min.css">

  <!-- Select2 -->
  {{-- <link rel="stylesheet" href="/bower_components/admin-lte/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> --}}

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="/bower_components/admin-lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">

  <!-- JS-Validate -->
  <script language="JavaScript" type="text/javascript" src="/js/jquery.validate.js"></script>

   <!-- daterange picker -->
   <link rel="stylesheet" href="/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.css">
   
    <!-- summernote -->
    <link rel="stylesheet" href="/bower_components/admin-lte/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="/css/feedback.css">
  </head>
<body class="hold-transition sidebar-mini">
  <div class="preload">
    <div class="position">
      <div class="loadingio-spinner-cube-5ctdgk2q8k"><div class="ldio-kkcjvi12bvg">
        <div></div><div></div><div></div><div></div>
        </div></div>
    </div>
  </div>
  <div class="wrapper">
  
      {{-- @guest
          @include('auth.login')
      @else --}}
      <!-- Navbar -->
          @include('layouts.navbar')
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
          @include('layouts.sidebar')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h4 class="pl-3">{{ $title ?? ''}}</h4>
              </div>
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">{{ $page ?? ''}}</li>
                  </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid" id="container">
              @yield('content')
          </div>
        </div>
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-light">
          <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->

      <!-- Main Footer -->
          @include('layouts.footer')
      {{-- @endguest --}}
  </div>

<!-- REQUIRED SCRIPTS -->
{{-- <script src="/bower_components/admin-lte/plugins/jquery/jquery.min.js"></script> --}}
<script src="/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/bower_components/admin-lte/dist/js/adminlte.js"></script>

{{-- <!-- CUSTOM JS -->
<script src="/js/ajax.js"></script> --}}

<!-- DataTables  & Plugins -->
<script src="/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/bower_components/admin-lte/plugins/jszip/jszip.min.js"></script>
<script src="/bower_components/admin-lte/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/bower_components/admin-lte/plugins/pdfmake/vfs_fonts.js"></script>

<!-- Datatables -->
<script src="/bower_components/admin-lte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- InputMask -->
<script src="/bower_components/admin-lte/plugins/moment/moment.min.js"></script>
<script src="/bower_components/admin-lte/plugins/inputmask/jquery.inputmask.min.js"></script>

<!-- BS-Stepper -->
<script src="/bower_components/admin-lte/plugins/bs-stepper/js/bs-stepper.min.js"></script>

<!-- Select2 -->
<script src="/bower_components/admin-lte/plugins/select2/js/select2.full.min.js"></script>

<!-- Bootstrap4 Duallistbox -->
<script src="/bower_components/admin-lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

<!-- Bootstrap Switch -->
<script src="/bower_components/admin-lte/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<!-- date-range-picker -->
<script src="/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.js"></script>
<!-- preloader -->
<script src="/js/preloader.js"></script>
<!-- drag and drop picture -->
<script src="/js/patient-picture.js"></script>

<script src="/js/sidebar.css"></script>
<script src="/css/sidebar.js"></script>

<!-- Datatables -->
<script src="/bower_components/admin-lte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/bower_components/admin-lte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- Select2 -->
<script src="/bower_components/admin-lte/plugins/select2/js/select2.full.min.js"></script>

<!-- InputMask -->
<script src="/bower_components/admin-lte/plugins/moment/moment.min.js"></script>
<script src="/bower_components/admin-lte/plugins/inputmask/jquery.inputmask.min.js"></script>


<!-- AdminLTE App -->
<script src="/bower_components/admin-lte/dist/js/adminlte.min.js"></script>
<script src="/bower_components/jquery/src/qrcode/dist/jquery-qrcode.js"></script>

 <!-- Summernote -->
 <script src="/bower_components/admin-lte/plugins/summernote/summernote-bs4.min.js"></script>
 
 <script>
    $(document).ready( function () {
         $('[data-widget="pushmenu"]').PushMenu('expand')
    });
</script>

@include('layouts.toastr')

</body>
</html>

