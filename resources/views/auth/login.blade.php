@extends('auth.index')
@section('content')

	<div class="contentbox">
		<div class="formbox">
			<a href="/" class="nav-link text-center" data-toggle="dropdown" style="padding: 0;">
				<img src="/img/login2.png" class="user-image float-center" alt="User Image" style="width:100%; margin-top: -4%">
			</a>
			<div style="text-align: center">
				<h2 class="header">Login</h2>
			</div>
			<form method="POST" action="{{ url('/login') }}">
                @csrf
				<div class="inputbox">
					<span class="spn">Username</span>
					<input type="text" name="username" >
				</div>
				<div class="inputbox">
					<span class="spn">Password</span>
					<input type="password" name="password">
				</div>
				<div>
					<label class="remember"><input type="checkbox">Remember me</label>
				</div>
				<div class="inputbox">
					<input type="submit" value="Sign in">
				</div>
			</form>
		</div>
	</div>
		
@endsection
