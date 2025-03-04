<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zamboanga City e-Sumbong</title>
    <link rel="shortcut icon" type="image/jpg" href="/img/logo1.png"/>
    <link rel="stylesheet" type="text/css" href="/login-css/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/login-css/css/design.css">
    <link rel="stylesheet" href="/bower_components/admin-lte/plugins/toastr/toastr.min.css">
</head>
<body>
	<section class="sec">
		<div class="imagebox">
			<img src="img/login.jpg" class="image">
		</div>
	@yield('content')
</section>
</body>
<script src="/login-css/vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="/login-css/vendor/bootstrap/js/popper.js"></script>
<script src="/login-css/vendor/bootstrap/js/popper.min.js"></script>
<script src="/login-css/vendor/bootstrap/js/bootstrap.min.js"></script>
{{-- <script src="/login-css/js/login.js"></script> --}}

<script src="/bower_components/admin-lte/plugins/toastr/toastr.min.js"></script>
<script>
    //Toastr -->
    @if(Session::has('success'))
        toastr.options = { "closeButton" : true }
        toastr.success("{{ session('success') }}", "Success");
    @endif
  
    @if(Session::has('error'))
        toastr.options = { "closeButton" : true }
        toastr.error("{{ session('error') }}", "Error");
    @endif
</script>
</html>