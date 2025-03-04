@extends('auth.index')
@section('content')

<div class="contentbox" style="background-color: white">
	<div class="formbox">
		<form method="POST" action="/setpassword">
			@csrf
			<h3 style="text-align: center">
				<img src="/img/user1.png" alt="" style="width: 12%">
				Hi! {{Auth::user()->staff->firstname}}
			</h3><br><br>
			<h6>Set your password first,</h6>
			<br><br>

			<!-- Account id -->
			<input type="hidden" name="id" id="id" value="{{Auth::user()->id}}">

			<!-- Password -->
			<div class="inputbox">
				<span class="spn">Enter Password</span>
				<input id="password" name="password" type="password" class="form-control input100 @error('password') is-invalid @enderror" required>
 
				@error('password')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
				@enderror

				<span class="focus-input100" data-placeholder="Password"></span>
			</div>

			<!-- Confirm Password -->
			<div class="inputbox">
				<span class="spn">Confirm Password</span>
				<input class="form-control input100" id="password-confirm" name="password_confirmation" type="password">
				<span class="focus-input100" data-placeholder="Confirm Password"></span>
			</div>

				<div class="inputbox">
				<input type="submit" value="Save">
			</div>
			
		</form>
	</div>
</div>
@endsection
			
