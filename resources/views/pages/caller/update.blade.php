@extends('layouts.app',[
'page' => 'Caller Update',
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
    <form action="/caller/update" id="caller-form" method="POST">
        @csrf
        <input type="hidden" name="id" id="id" value="{{ $caller->id}}">
        <div class="card card-default">
            <div class="card-header card-head">
            <h3 class="card-title"><strong>Basic Information</strong></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <!-- Primary FullName -->
            <div class="row">
                <div class="form-group  col-md-4">
                    <p>Firstname</p>
                    <input id="firstname" name="firstname" type="text" class="form-control" value="{{$caller->firstname}}">
                </div>
                <div class="form-group col-md-4">
                    <p>Middlename</p>
                    <input id="middlename" name="middlename" type="text" class="form-control" value="{{$caller->middlename}}">
                </div>
                <div class="form-group  col-md-4">
                    <p>Lastname<p>
                    <input id="lastname" name="lastname" type="text" class="form-control"value="{{$caller->lastname}}">
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
                        <input id="address" name="address" type="text" class="form-control" value="{{$caller->address}}">
                    </div>
                </div>
                <div class="form-group  col-md-6">
                    <div class="form-group">
                        <p>Barangay<span class="require">*</span></p>
                        <select name="barangay_id" id="barangay-id" class="custom-select form-control-border @error('barangay') is-invalid @enderror">
                            <option selected value="{{$caller->barangay->id}}">{{$caller->barangay->name}}</option>
                            @foreach ($barangays as $barangay)
                            @unless ($caller->barangay_id == $barangay->id)
                                <option value="{{$barangay->id}}">{{$barangay->name}}</option>
                              @endunless
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group  col-md-6">
                    <p>Contact No.<span class="require">*</span></p>
                    <input id="contact_no" name="contact_no" type="text" class="form-control" maxlength="12" minlength="11" required="" value="{{$caller->contact_no}}"> 
                    @error('contact_no')
                    <span class="invalid-feedback" role="alert" style="display: block">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    </div>
                <div class="form-group  col-md-6">
                    <p>Email</p>
                    <input id="email" name="email" type="text" class="form-control" value="{{$caller->email}}">
                </div>
            </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success float-right">Update Caller</button>
            </div>
        </div>
    </form>
</div>
</section>
@endsection