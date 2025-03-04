<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zamboanga City e-Sumbong</title>
    <link rel="shortcut icon" type="image/jpg" href="/img/logo1.png"/>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="/bower_components/admin-lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="/bower_components/admin-lte/plugins/toastr/toastr.min.css">

    <link rel="stylesheet" type="text/css" href="/login-css/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/login-css/css/design.css">
    <link rel="stylesheet" href="/bower_components/admin-lte/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/bower_components/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/css/feedback.css">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/about-us.css">
    <link rel="stylesheet" href="/bower_components/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="/bower_components/admin-lte/plugins/summernote/summernote-bs4.min.css">
</head>
<body style="background-color: #2caaa9">
   
		
	@yield('content')

</body>
 {{-- footer --}}
 {{-- <div style="text-align: center;">
    <img src="/img/footer.png" alt="" style="width: 30%;">
</div> --}}


<script src="/login-css/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="/login-css/vendor/bootstrap/js/popper.js"></script>
<script src="/login-css/vendor/bootstrap/js/popper.min.js"></script>
<script src="/login-css/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="/js/rate.js"></script>
<script src="/js/anonymous.js"></script>

{{-- <script src="/login-css/js/login.js"></script> --}}

<!-- BS-Stepper -->
<script src="/bower_components/admin-lte/plugins/bs-stepper/js/bs-stepper.min.js"></script>

<script src="/bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.js"></script>

<!-- Select2 -->
<script src="/bower_components/admin-lte/plugins/select2/js/select2.full.min.js"></script>

<!-- Bootstrap4 Duallistbox -->
<script src="/bower_components/admin-lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

<!-- Bootstrap Switch -->
<script src="/bower_components/admin-lte/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

<!-- date-range-picker -->
<script src="/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.js"></script>

<script src="/bower_components/admin-lte/plugins/toastr/toastr.min.js"></script>
{{-- <script> --}}

    
    <!-- Summernote -->
    <script src="/bower_components/admin-lte/plugins/summernote/summernote-bs4.min.js"></script>
 
    <script>
          
         $(document).ready(function() {
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            // console.log('hey');
            $('.callDetails').summernote('disable');
         });
          
         @if (Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: '{{ session("error") }}'
          
        }).then(function() {
                window.location.reload();
            });
            {{Session::flush()}}
        @endif
      


        @if (Session::has('success'))
            @php
                $message = session("success")."<br>".session("ticket_no") != '' && session("ticket_no")? "Ticket No.: ".session("ticket_no"): "";
            @endphp
            Swal.fire({
                icon: 'success',
                title: 'Success',
                html: '{{ $message  }}'
        })
            {{Session::flush()}}
         @endif


        // Submit button
        $('#rate-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: "{{ url('/feedback/save') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {

                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: res.success
                        });
                        $("#office-form")[0].reset();
                    } else {
                        swal.fire({
                            icon: 'error',
                            html: res.errors
                        });
                    }
                },
                error: function(data) {
                    console.log('data');
                }
            });
        });

</script>

  


{{-- </script> --}}
</html>