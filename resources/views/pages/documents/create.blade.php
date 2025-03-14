@extends('layouts.app',[
'page' => 'Caller Registration',
'title' => ''
])
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h4 class="h4" style="padding-left: 5px">Caller Registration</h4>
    <div>
        <button type="button" class="btn btn-block btn-secondary" style="float: right" onclick="location.href='{{ URL('/caller') }}'"><i class="fas fa-chevron-left"></i>&nbsp;Back</button>
    </div>
</div>
<section class="content">
<div class="container-fluid">
    <form action="/caller/store" id="caller-form" method="POST">
        @csrf
        {{-- <div class="card card-default">
            <div class="card-header card-head">
                <div class="card-body">
                    <label for="chkAnonymous">
                        <input type="checkbox" id="chkAnonymous" name="anonymous" value="true" />
                        Do you want your caller to be Anonymous?
                    </label>
                </div>
            </div>
        </div> --}}
        <div class="card card-default" id="basicInfo">
            <div class="card-header card-head">
            <h3 class="card-title"><strong>Basic Information</strong></h3>
            </div>
            <!-- /.card-header -->
            
            <div class="card-body">
            <!-- Caller FullName -->
            <div class="row">
                <div class="form-group  col-md-4">
                    <p>Firstname<span class="require">*</span></p>
                    <input id="firstname" name="firstname" type="text" value="{{ old('firstname') }}" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <p>Middlename</p>
                    <input id="middlename" name="middlename" type="text" value="{{ old('middlename') }}" class="form-control">
                </div>
                <div class="form-group  col-md-4">
                    <p>Lastname<span class="require">*</span><p>
                    <input id="lastname" name="lastname" type="text" value="{{ old('lastname') }}" class="form-control" required>
                </div>
            </div>

            </div>
        </div>
        
        <div class="card card-default">
            <div class="card-header card-head">
               <h3 class="card-title"><strong>Contact Information</strong></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <!-- Caller Details -->
            <div class="row">
                <div class="form-group  col-md-6">
                    <div class="form-group">
                        <p>Address</p>
                        <input id="address" name="address" type="text" value="{{ old('address') }}" class="form-control">
                    </div>
                </div>
                <div class="form-group  col-md-6">
                    <div class="form-group">
                        <p>Barangay<span class="require">*</span></p>
                        <select class="form-control" name="barangay" required>
                            <option value=""> Select Barangay</option>
                            @foreach ($barangays as $brgy)
                                <option value="{{ $brgy->id }}">{{ $brgy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group  col-md-6">
                    <p>Contact No.<span class="require">*</span></p>
                    <input id="contact-no" name="contact_no"  class="form-control"> 
                    @error('contact_no')
                    <span class="invalid-feedback" role="alert" style="display: block">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group  col-md-6">
                    <p>Email</p>
                    <input id="email" name="email" type="text" class="form-control"  value="{{ old('email') }}" >
                </div>
            </div>
            </div>
            <div class="card-footer">
                <input type="submit"  value="Proceed to Ticket" class="btn btn-primary float-right m-1" name="process"/>
                <input type="submit"  value="Save Caller" class="btn btn-success float-right m-1" name="process"/>
            </div>
        </div>
    </form>

    <script>
    //     $(function () {
    //     $("#chkAnonymous").click(function () {
    //         if ($(this).is(":checked")) {
    //             $("#basicInfo").hide();
    //         } else {
    //             $("#basicInfo").show();
    //         }
    //     });
    // });

    $('#caller-form').validate({
        rules: {
            contact_no: { 
                required: false, 
                number: true,
                maxlength: 11 
            },
        },
        messages: {
            contact_no: {
                number: "Please enter a valid number",
                maxlength: "Must NOT EXCEED 11 digit long"
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
        });

   
    </script>


</section>
@endsection