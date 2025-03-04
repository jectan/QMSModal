@extends('layouts.app',[
'page' => 'Caller Update',
'title' => ''
])
@section('content')
<div class="pb-2 mb-3 border-bottom">
    <div class="row" style="margin-bottom: 4px;">
        <div class="col-md-6">
            <h4 class="h4" style="padding-left: 5px">Caller Registration</h4>
        </div>
        <div class="col-md-6">
            <a class="btn btn-success btn-sm float-right mr-1"  onclick="location.href='/caller/{{ $caller->id }}'"><i class="fas fa-pen"></i>&nbsp;Edit</a>
            <a class="btn btn-secondary btn-sm float-right mr-1" onclick="location.href='{{ URL('/caller') }}'"><i
                class="fas fa-chevron-left"></i>&nbsp;Back</a>
        </div>
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
                    <label for="control_no" class="m-0">Firstname</label>
                    <h3 type="text">{{$caller->firstname}}</h3>
                   
                </div>
                <div class="form-group col-md-4">
                    <label for="control_no" class="m-0">Middlename</label>
                    <h3 type="text">{{$caller->middlename ? $caller->middlename :"N/A"}}</h3>
                </div>
                <div class="form-group  col-md-4">
                    <label for="control_no" class="m-0">Lastname</label>
                    <h3 type="text">{{$caller->lastname}}</h3>
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
                        <label for="control_no" class="m-0">Address</label>
                    <h3 type="text">{{$caller->address ? $caller->address : "N/A"}}</h3>
                    </div>
                </div>
                <div class="form-group  col-md-6">
                    <div class="form-group">
                        <label for="control_no" class="m-0">Address</label>
                    <h3 type="text">{{$caller->barangay ? $caller->barangay->name : "N/A"}}</h3>
                    </div>
                </div>
                <div class="form-group  col-md-6">
                    <label for="control_no" class="m-0">Contact No.</label>
                    <h3 type="text">{{$caller->contact_no ? $caller->contact_no : "N/A"}}</h3>
                </div>
                <div class="form-group  col-md-6">
                    <label for="control_no" class="m-0">Email</label>
                    <h3 type="text">{{$caller->email ? $caller->email : "N/A"}}</h3>
                </div>
           
            </div>

            </div>
           
        </div>

        <div class="card card-default">
            <div class="card-body">
                <div class="form-group  col-md-6">
                    <div class="form-group">
                        @if ($caller->updated_by_id == '')
                        <p>Created By : {{$caller->created_by_id ? $caller->createdBy->staff->fullname: " "}} on {{$caller->created_at}}</p>
                        @else 
                        <p>Created By : {{$caller->created_by_id ? $caller->createdBy->staff->fullname: " "}} on {{$caller->created_at}}</p>
                        <p>Updated By : {{$caller->updated_by_id ? $caller->updatedBy->staff->fullname: " "}} on {{$caller->updated_at}}</p>
                        @endif

                       
                    </div>
                </div>
        </div>
    </form>
</div>
</section>
@endsection