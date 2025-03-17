@extends('layouts.app',[
    'page' => 'Accounts',
    'title' => 'Add Staff Account'
])

@section('content')
<section class="content">
    @if(Session::has('error-user'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h6>Error! {{ session('error-user') }}</h6>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Error!</h5>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card card-default">
        <div class="card-body">
            <div class="bs-stepper">
                <div class="bs-stepper-header" role="tablist">
                    <!-- your steps here -->
                    <div class="step" data-target="#profile-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="profile-part" id="profile-part-trigger">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">Profile</span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#credential-part">
                        <button type="button" class="step-trigger" role="tab" aria-controls="credential-part" id="credential-part-trigger">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">Credential</span>
                        </button>
                    </div>
                </div>
                <div class="bs-stepper-content">
                    <!-- your steps content here -->
                    <form method="POST" action="/accounts">
                        @csrf
                        <!-- First TAB -->
                        <div id="profile-part" class="content" role="tabpanel" aria-labelledby="profile-part-trigger">

                            <fieldset id="profile" class="pl-5 mt-3" style="background-color: rgb(247, 255, 255)">
                                <div class="row">

                                    <div class="col-md-6">
                                        <!-- Firstname -->
                                        <div class="form-group row">
                                            <label for="firstname" class="col-md-3">First Name<span class="require">*</span></label>
                                            <div class="col-md-8">
                                                <input id="firstname" name="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" value="{{old('firstname')}}">
                                            </div>
                                        </div>
                                        <!-- Middlename -->
                                        <div class="form-group row">
                                            <label for="middlename" class="col-md-3">Middle Name</label>
                                            <div class="col-md-8">
                                                <input id="middlename" name="middlename" type="text" class="form-control" value="{{old('middlename')}}">
                                            </div>
                                        </div>
                                        <!-- Lastname -->
                                        <div class="form-group row">
                                            <label for="lastname" class="col-md-3">Last Name<span class="require">*</span></label>
                                            <div class="col-md-8">
                                                <input id="lastname" name="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" value="{{old('lastname')}}">
                                            </div>
                                        </div>
                                        <!-- Unit -->
                                        <div class="form-group row">
                                            <label for="unit" class="col-md-3">Unit<span class="require">*</span></label>
                                            <div class="input-group col-md-8">
                                                <select name="unitID" id="unitID" class="form-control @error('unitID') is-invalid @enderror">
                                                    <option value="" disabled selected>Select Unit</option>
                                                    @foreach ($unit as $unit)
                                                        <option {{ old("unitID") == $unit->unitID ? 'selected' : '' }} value="{{$unit->unitID}}">{{$unit->unitName}}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>

                            <div class="card-footer p-0" style="background-color: white">
                                <div class="btn-group float-right">
                                    <button type="button" class="btn btn-default" onclick="location.href='/accounts'"><i class="fas fa-arrow-left"></i> Back</button>
                                    <button type="button" class="btn btn-primary" onclick="stepper.next()">Next <i class="fas fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Second TAB -->
                        <div id="credential-part" class="content" role="tabpanel" aria-labelledby="credential-part-trigger">
                            <fieldset id="profile" class="pl-5 mt-3" style="background-color: rgb(247, 255, 255)">

                                <!-- User Role/s -->
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="unit" class="col-md-3">Role<span class="require">*</span></label>
                                        <div class="input-group">
                                            <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                                <option value="" disabled selected>Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option {{ old("role_id") == $role->id ? 'selected' : '' }} value="{{$role->id}}">{{$role->name}}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label>Select user role/s <span class="require">*</span></label>
                                    <select class="duallistbox @error('role_id') is-invalid @enderror role" multiple="multiple" name="role_id[]" id="role_id[]">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="form-group">
                                    <div class="row">
                                        <!-- Username -->
                                        <div class="col-md-6">
                                            <label>Username<span class="require">*</span></label>
                                            <input id="username" name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="{{old('username')}}">
                                            
                                                @error('username')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer pt-2  p-0 white" style="background-color: white">
                                    <!-- Default Password -->
                                    <div class="col-md-6 p-2 pl-3" style="background-color: #f5faff;border:1px solid black;">
                                            <h6>NOTE: For the first login, use default password: <strong>*1234#</strong></h6>
                                    </div>
                                    <div class="btn-group float-right">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save pr-2"></i>Submit</button>
                                        <button type="button" class="btn btn-default" onclick="stepper.previous()"><i class="fas fa-arrow-left"></i> Previous</button>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    //BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
      window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    });

    $(document).ready( function () {
        
    });
</script>
@endsection