@extends('layouts.app',[
    'page' => 'Accounts',
    'title' => ''
])

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h4 class="h2" style="padding-left: 5px">List of Accounts</h4>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <div class="container-login100-form-btn">
                <div class="wrap-login100-form-btn">
                    <div class="login100-form-bgbtn"></div>
                    <button class="login100-form-btn" onclick="location.href='{{ URL('/accounts/create') }}'"><i class="fa fa-plus pr-2"></i> New Account</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DATATABLE -->
<div class="row">
    <div class="col-md-12">
      <div class="card">
          <div class="card-body table-responsive">
            <table class="table table-hover" id="accountTable" style="font-size: 14px">
                <thead>
                <tr>
                    <th style="width: 10%">Id</th>
                    <th style="width: 25%">Name</th>
                    <th style="width: 20%">Office</th>
                    <th style="width: 20%">User Role</th>
                    <th style="width: 15%">Username</th>
                    <th style="width: 10%">Action</th>
                </tr>
                <thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->staff->firstname . ' ' . $user->staff->middlename . ' ' . $user->staff->lastname ?? ''}}</td>
                    <td>{{$user->staff->office->name}}</td>
                    <td>{{$user->staff->role->name}}</td>
                    <td>{{$user->username}} <br>
                        @if($user->isActive == false)
                        <span class="badge bg-danger">Deactivated</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group float-right">
                        <button type="button" class="btn btn-default dropdown-toggle dropdown-hover dropdown-icon" data-toggle="dropdown">
                            <i class="fas fa-gear pr-2"></i>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">                
                            @if($user->isActive == true)
                                <button class="dropdown-item" onclick="location.href='/accounts/{{ $user->id }}'"><i class="fas fa-edit mr-1"></i>{{ _('Edit') }}</button>
                                <button class="dropdown-item" onclick="activeAccount({{ $user->id }}, 0)"><i class="fas fa-lock mr-1"></i>{{ _('Deactivate') }}</button>
                                <button class="dropdown-item" onclick="resetPassword({{ $user->id }})"><i class="fas fa-key mr-1"></i>{{ _('Reset Password') }}</button>
                            @else
                                <button class="dropdown-item" onclick="activeAccount({{ $user->id }}, 1)"><i class="fas fa-unlock mr-1"></i>{{ _('Activate') }}</button>
                            @endif
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item text-red" onclick="deleteAccount({{ $user->id }})"><i class="fas fa-trash mr-1"></i>{{ _('Delete') }}</button>
                        </div>
                    </div>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>

  <script>

    $(document).ready( function () {
      var table = $('#accountTable').DataTable();

    });

    function editAccount(id)
    {
        $.ajax({
            type:'GET',
            url: '{{url("/accounts")}}/' +id,
            dataType: 'json'
        });
    }

    function deleteAccount(id)
    {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-default'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                if (result.isConfirmed){

                    swal.fire({
                        html: '<h6>Loading... Please wait</h6>',
                        onRender: function() {
                            $('.swal2-content').prepend(sweet_loader);
                        },
                        showConfirmButton: false
                    });

                    $.ajax({
                        type:'DELETE',
                        url:'{{url("/accounts/delete")}}/' +id,
                        data:{
                            "_token": "{{ csrf_token() }}",
                        },
                        success:function(res) {
                            
                            setTimeout(function() {
                                swal.fire({
                                    icon: 'success',
                                    html: '<h5>Success deleted!</h5>'
                                }).then(function() {
                                    window.location = '{{ url('accounts') }}'
                                });
                            }, 700);
                        }
                    });
                }
            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                toastr.info(
                    'Your data is safe :)',
                    'CANCELLED'
                );
            }
        });
    }

    function activeAccount(id, setActive)
    {
        $.ajax({
            type:'POST',
            url: '{{url("/accounts/users/deactivate")}}',
            data: { 
                id: id,
                setActive: setActive,
                "_token": "{{ csrf_token() }}",
            },
            success:function(data){
                swal.fire({
                    icon: 'success',
                    html: data.message
                }).then(function() {
                    window.location = '{{ url('accounts') }}'
                });
            },
            error: function(data){
            console.log(data);
            }
        });
    }

    function resetPassword(id)
    {
        $.ajax({
            type:'GET',
            url: '{{url("/accounts/users/reset-password")}}/' +id,
            dataType: 'json',
            success:function(res){
                swal.fire({
                    icon: 'success',
                    html: '<h4>Password successfully reset!</h4><br>Default Password is <strong>*1234#</strong>'
                }).then(function() {
                    window.location = '{{ url('accounts') }}'
                });
            }
        })
    }

  </script>
@endsection