@extends('layouts.app',[
    'page' => 'Accounts',
    'title' => ''
])

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user"></i> Update Account <br>
                @if($user->isActive == false)
                    <span class="badge bg-danger ml-3">Deactivated</span>
                @endif
            </h3>
        </div>
        <form method="POST" action="/accounts/update" id="AccountForm" name="AccountForm">
            @csrf
            <input type="hidden" id="user_id" name="user_id" value="{{$user->id}}">
            <input type="hidden" id="staff_id" name="staff_id" value="{{$user->staff->id}}">
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-body">

                        <!-- id -->
                        <input type="hidden" name="id" id="id" value="{{$user->id}}">

                        <!-- Firstname -->
                        <div class="form-group row">
                            <label for="firstname" class="col-md-4">First Name<span class="require">*</span></label>
                            <div class="col-md-8">
                                <input id="firstname" name="firstname" type="text" class="form-control" value="{{$user->staff->firstname}}" required >
                            </div>
                        </div>

                        <!-- Middlename -->
                        <div class="form-group row">
                            <label for="middlename" class="col-md-4">Middle Name</label>
                            <div class="col-md-8">
                                <input id="middlename" name="middlename" type="text" class="form-control" value="{{$user->staff->middlename}}" >
                            </div>
                        </div>

                        <!-- Lastname -->
                        <div class="form-group row">
                            <label for="lastname" class="col-md-4">Last Name<span class="require">*</span></label>
                            <div class="col-md-8">
                                <input id="lastname" name="lastname" type="text" class="form-control" value="{{$user->staff->lastname}}" required >
                            </div>
                        </div>

                        <!-- Unit -->
                        <div class="form-group row">
                            <label for="unit" class="col-md-4">Unit<span class="require">*</span></label>
                            <div class="input-group col-md-8">
                                <select name="unitID" id="unitID" class="form-control @error('unitID') is-invalid @enderror">
                                    <option value="" disabled selected></option>
                                    @foreach ($unit as $unit)
                                        <option {{ $user->staff->unitID == $unit->unitID ? 'selected' : '' }} value="{{$unit->unitID}}">{{$unit->unitName}}</option> 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="col-md-6">
                        <div class="card-body">

                        <!-- Username -->
                        <div class="form-group row">
                            <label for="name" class="col-md-4">Username<span class="require">*</span></label>
                            <div class="col-md-8">
                                <input id="username" name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="{{$user->username}}" required >

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                         <!-- Role -->
                         <div class="form-group row" id="user_roles">
                            <label for="name" class="col-md-4">User Role<span class="require">*</span></label>
                            <div class="input-group col-md-8">
                                <select name="role_id" id="role_id" class="form-control">
                                    <option value="" disabled selected></option>
                                    @foreach ($roles as $role)
                                        <option {{ $user->role_id == $role->id ? 'selected' : '' }} value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                        

                    
            
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right" id="submitbtn">Save changes</button>
                <button type="button" class="btn btn-default" onclick="location.href='/accounts'"><i class="fas fa-arrow-left"></i> Back</button>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>




<script>


$(document).ready( function () {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Shows Datatable of UserRole Model
        $('#user-roles').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/accounts/user-roles') }}/" + $('#user_id').val(),
                columns: [
                    { data: 'role_name', name: 'role_name' },
                    { data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'asc']],
                paging: false,
                lengthChange: false,
                searching: false,
                autoWidth: false,
                responsive: true,
                info: false,
                sorting: false
        });
    });

    function addUserRole()
    {
        var user_id = $('#user_id').val();
        var role_id = $('#role_id').val();

        if(!role_id){
            swal.fire({
                icon: 'error',
                html: '<h5>No role selected!</h5>'
            });
            return;
        }

        $.ajax({
            type:'POST',
            url: '{{url("/accounts/user-roles/add")}}',
            data: { 
              user_id: user_id,
              role_id: role_id,
            },
            success: function(data) {
                if(data.success){
                    $('#role_id').val('');
                    var oTable = $('#user-roles').dataTable();
                    oTable.fnDraw(false);
                }else{
                    swal.fire({
                        icon: 'error',
                        html: '<h5>Role already designated!</h5>'
                    });
                }    
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function removeUserRole($user_role_id)
    {
        var id = $user_role_id;
        $.ajax ({
            type: 'DELETE',
            url: '{{url("/accounts/user-roles/remove")}}/'+ id,
            dataType: 'json',
            success:function(res) {
                var oTable = $('#user-roles').dataTable();
                oTable.fnDraw(false);
            }
        });
    }

</script>
@endsection
