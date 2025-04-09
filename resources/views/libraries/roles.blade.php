@extends('layouts.app',[
    'page' => 'Roles',
    'title' => ''
])

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4 class="h2" style="padding-left: 5px">List of Roles</h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" data-bs-toggle="modal" data-bs-target="#role-modal"><i class="fa fa-plus pr-2"></i>Roles</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped w-100" id="role-dt" style="font-size: 14px">
                        <thead>
                            <tr>
                                {{-- <th style="width: 20%">Code</th> --}}
                                <th style="width: 70%">Role</th>

                                {{-- <th style="width: 10%">name</th> --}}
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="role-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="javascript:void(0)" id="role-form" name="role_form" class="form-horizontal" method="POST">
                    @csrf
                    <div class="modal-header" style="background-color:#17366f; color: white;">
                        <h4 class="modal-title" id="role-modal-title">Role</h4>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" name="role_id" id="role_id">

                        <div class="form-group">
                            <label for="code-label" class="col-sm-2 control-label">Name<span class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Role" maxlength="50" required="">
                                </div>
                            </div>  
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info" id="btn-save">Save</button>
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
  
    //DATATABLES
    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#role-dt').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/roles') }}",
            columns: [
                    // { data: 'code', name: 'code' },
                    { data: 'name', name: 'name' },
                    // { data: 'description', name: 'description' },
                    { data: 'action', name: 'action', orderable: false },
            ],
            order: [[0, 'desc']]
        });
            
        $('#role-modal').on('hidden.bs.modal', function (e) {
            $("#role-form")[0].reset();
            $('#role_id').val(''); // Clear the hidden ID field
            $('#role-modal-title').text('Role');
        });
    });

    // SAVE DATA
    $('#role-form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        swal.fire({
			html: '<h6>Loading... Please wait</h6>',
			onRender: function() {
				$('.swal2-content').prepend(sweet_loader);
			},
            showConfirmButton: false
		});

        $.ajax({
            type:'POST',
            url: "{{ url('/roles/store')}}",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: (res) => {
                if(res.success){
                    Swal.fire({
                        icon: 'success',
                        title: res.success
                    }).then(function() {
                        var oTable = $('#role-dt').dataTable();
                            oTable.fnDraw(false);
                    });

                    $("#role-modal").modal('hide');
                    $("#btn-save").attr("disabled", false);
                    $("#role-form").trigger('reset');
               
                    
                }else{
                    swal.fire({
                        icon: 'error',
                        html: res.errors
                    });
                    
                }
               
            },
           
            error: function(data){
            console.log(data);
            }
        }); 
    });

    //SHOW DATA TO UPDATE
    function editrole(id){
        $.ajax({
        type:"POST",
        url: "{{ url('/roles/edit') }}",
        data: { id: id },
        "token":"{{ csrf_token()}}",
        dataType: 'json',
        success: function(res){
            $('#role-modal-title').html("Edit Role");
            $('#role-modal').modal('show');
            $('#role_id').val(res.id);
            $('#name').val(res.name);
            },
            error:function(data){
                console.log(data);
            }
        });
    }  

    // DELETE DATA
    function deleteRole(e) {
        let id = e.getAttribute('data-id');

        // Check if the case can be deleted (you'll need to create a function like checkHasStaff)
        checkHasUser(id, function(canDelete) {
            if (!canDelete) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Delete',
                    html: 'There are existing Users with this Role.<br><strong>Please reassign them first before deleting.</strong>'
                });
                return;
            }

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger mx-2', // Added mx-2 for spacing
                    cancelButton: 'btn btn-default mx-2' // Added mx-2 for spacing
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
                if (result.value && result.isConfirmed) {
                    swal.fire({
                        html: '<h6>Loading... Please wait</h6>',
                        onRender: function() {
                            $('.swal2-content').prepend(sweet_loader);
                        },
                        showConfirmButton: false
                    });

                    $.ajax({
                        type: 'DELETE',
                        url: '{{ url("/roles/delete") }}/' + id,
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(res) {
                            setTimeout(function() {
                                swal.fire({
                                    icon: 'success',
                                    html: '<h5>Success deleted!</h5>'
                                });
                            }, 700);

                            var oTable = $('#role-dt').dataTable();
                            oTable.fnDraw(false);
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to delete case.'
                            });
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr.info(
                        'Your data is safe :)',
                        'CANCELLED'
                    );
                }
            });
        });
    }

    // Function to check if the case can be deleted (replace with your logic)
    function checkHasUser(id, callback) {
        $.ajax({
            url: "{{ url('/check-hasUser') }}", // Replace with your actual route
            type: "GET",
            data: { id: id }, // Adjust the data sent
            success: function(response) {
                callback(!response.exists); // true = deletable
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to check case deletion status.'
                });
                callback(false); // Default to false if there's an error
            }
        });
    }
</script>
@endsection
