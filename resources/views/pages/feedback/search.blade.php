@extends('pages.feedback.index')
@section('content')
<section class="sec">
<div class="imagebox">
	<img src="img/login.jpg" class="image">
</div>
	<div class="contentbox">
		<div class="formbox">
			<div>
				<a href="/" class="nav-link text-center" data-toggle="dropdown" style="padding: 0;">
					<img src="/img/login2.png" class="user-image float-center" alt="User Image" style="width:100%; margin-top: -4%">
				</a>
			</div>
			<br>
			<div style="text-align: center">
				<h2 class="header">Search Ticket</h2>
			</div>
			<form action="/feedbacktracks/search" method='post'>
                @csrf
				<div class="inputbox" style="margin-bottom: 50px;">
					<span class="spn" style="">Ticket no.</span> 
					<input type="text" name="username" required="">
				</div>
				<div class="inputbox">
					<input type="submit" value="Submit">
				</div>
			</form>
		</div>
	</div>
</section>
@endsection
