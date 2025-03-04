@extends('pages.feedback.index')
@section('content')
    <div class="body1">
        <div class="body2">
            <form action="/home/save-form" id="e-sumbong-form" method="POST"class="form-style-9" style="width: 100%;">
				@csrf
				<div class="row justify-content-md-center" style="margin-bottom: 2%">
					<div class="col-md-6" style="width: 100%">
						<img class="img-fluid" src="/img/complaint-form.png" alt="">
					</div>
				</div>
			
				{{-- <div class="card card-default">
					<label for="chkAnonymous" style="margin: 20px;">
								<input type="checkbox" id="anonymous" name="anonymous" value="true"/>
								Do you want your caller to be Anonymous?
							</label>
				</div> --}}
				<div class="card" style="padding: 5%">
					<div class="row">
						<span class="number" style="margin-bottom: 20px">1</span><span>Basic Information</span>
					</div>
					<div class="row" id="basicInfo">
						<div class="col-md-4">
							<input  id="firstname" name="firstname" type="text" value="{{ old('firstname') }}" class="field-style form-control row-margin" required placeholder="First Name" />
						</div>
						<div class="col-md-4">
							<input id="middlename" name="middlename" type="text" value="{{ old('middlename') }}" class="field-style form-control row-margin" placeholder="Middle Name" />
						</div>
						<div class="col-md-4">
							<input id="lastname" name="lastname" type="text" value="{{ old('lastname') }}" class="field-style form-control row-margin" required placeholder="Last Name" />
						</div>
					</div>
	
					<div class="row">
						<div class="col-md-6">
							<input id="contact-no" name="contact_no" class="field-style form-control row-margin" required placeholder="Contact number" />
							@error('contact_no')
							<span class="invalid-feedback" role="alert" style="display: block">
								<strong>{{ $message }}</strong>
							</span>
							@enderror

						
						</div>
						<div class="col-md-6">
							<input type="text" id="email" name="email"  class="field-style form-control row-margin" placeholder="Email Address" />
						</div>
					</div>
	
					<div class="row">
						<div class="col-md-8">
							<input type="text" name="field1" class="field-style form-control row-margin" placeholder="Address" />
						</div>
						<div class="col-md-4">
							<select name="barangay" required class="field-style form-control row-margin">
								<option value="" selected disabled>Select Barangay</option>
									@foreach ($barangays as $brgy)
										<option value="{{ $brgy->id }}">{{ $brgy->name }}</option>
									@endforeach
							</select>
						</div>
					</div>
					</div>
	
					<div class="card" style="padding: 5%">
					<div class="row">
						<span class="number" style="margin-bottom: 20px">2</span><span>e-Sumbong Details</span>
					</div>
	
					<div class="row">
						<div class="col-md-12">
							<select name="call_type_id" required class="field-style form-control row-margin">
								<option value="" selected disabled>Select Type of e-Sumbong</option>
								@foreach ($caller_types as $ct)
                                <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                            	@endforeach
							</select>
						</div>
						<div class="form-group  col-md-12">
                            <div class="col-md-12">
								<textarea name="call_details"  class="field-style form-control row-margin" required placeholder="Details"></textarea>
							</div>
                        </div>

					</div>
	
					<div class="row float-right" style="margin: 10px">
						<input type="submit"  value="Submit" class="btn btn-primary float-right m-1" name="process"/>
					</div>
					
				</form>
			</div>
			
	</div>

    @endsection
