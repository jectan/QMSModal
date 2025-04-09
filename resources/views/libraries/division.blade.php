@extends('layouts.app',[
'page' => 'Division',
'title' => ''
])

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4 class="h2" style="padding-left: 5px">List of Divisions</h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" data-bs-toggle="modal" data-bs-target="#division-modal"><i
                                class="fa fa-plus pr-2"></i>Division</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped w-100" id="division-dt" style="font-size: 14px">
                        <thead>
                            <tr>
                                {{-- <th style="width: 5%">divID</th> --}}
                                <th style="width: 10%">No</th>
                                <th style="width: 55%">Division</th>
                                <th style="width: 10%">Status</th>

                                {{-- <th style="width: 10%">divName</th> --}}
                                {{-- <th style="width: 5%">Status</th> --}}
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL -->
    <div class="modal fade" id="division-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form action="javascript:void(0)" id="division-form" name="division-form" class="form-horizontal" method="POST">
                    @csrf
                    <div class="modal-header" style="background-color:#17366f; color: white;">
                        <h4 class="modal-title" id="division-modal-title">Division</h4>
                    </div>
                    <div class="modal-body">

                        <!-- id -->
                        <input type="hidden" name="divID" id="divID">

                        <!-- Division Name -->
                        <div class="form-group">
                            <label for="divName" class="col-sm-4 control-label"><strong>Division Name</strong><span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="divName" name="divName"
                                    placeholder="Enter Name">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status" class="col-sm-4 control-label"><strong>Status</strong></label>
                            <div class="col-sm-12">
                                <select name="status" id="status">
                                    <option value=1>Active</option>
                                    <option value=0>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info" id="division-btn-save">Save</button>
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- AJAX -->
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#division-dt').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/divisions') }}",
                columns: [{
                        data: 'divID',
                        name: 'divID'
                    },
                    {
                        data: 'divName',
                        name: 'divName'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return data == 1 ? '<span class="badge bg-success" style="font-size: 10px; padding: 8px 12px;">Active</span>' : '<span class="badge bg-danger" style=" font-size: 10px; padding: 8px 12px;">Inactive</span>';
                        }
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                order: [
                    [0, 'asc']
                ]
            });
            
            $('#division-modal').on('hidden.bs.modal', function (e) {
                $("#division-form")[0].reset();
                $('#divID').val(''); // Clear the hidden ID field
                $('#division-modal-title').text('Division');
            });
        });

        // Submit button
        $('#division-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: "{{ url('/divisions/store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {

                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: res.success
                        });

                        $("#division-modal").modal('hide');
                        var oTable = $('#division-dt').dataTable();
                        oTable.fnDraw(false);

                        $("#division-btn-save").html('Save Changes');
                        $("#division-btn-save").attr("disabled", false);
                        $("#division-form")[0].reset();
                    } else {
                        swal.fire({
                            icon: 'error',
                            html: res.errors
                        });
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        //SHOW DATA TO UPDATE
        function editDivision(divID) {
            // self.reset('Office'); //calling function reset() at index.blade

            $.ajax({
                type: "POST",
                url: "{{ url('/divisions/update') }}",
                data: {
                    divID: divID
                },
                "token": "{{ csrf_token() }}",
                dataType: 'json',
                success: function(res) {
                    $('#division-modal-title').html("Edit Division");
                    $('#division-modal').modal('show');
                    $('#divID').val(res.divID);
                    $('#divName').val(res.divName);
                    $('#status').val(res.status).change();

                },

                error: function(data) {
                    console.log(data);
                }
            });
        }

        //DELETE DATA
        function deleteDivision(e) {
            let id = e.getAttribute('data-id');

            checkHasUnit(id, function(canDelete) {
                if (!canDelete) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot Delete',
                        html: 'There are Units under this Division.<br><strong>Please reassign or update them first.</strong>'
                    });
                    return;
                }

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-danger mx-2',
                        cancelButton: 'btn btn-default mx-2'
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
                    if (result.isConfirmed) {
                        swal.fire({
                            html: '<h6>Loading... Please wait</h6>',
                            didOpen: function () {
                                Swal.showLoading();
                            },
                            showConfirmButton: false
                        });

                        $.ajax({
                            type: 'DELETE',
                            url: '{{ url('/divisions/delete') }}/' + id,
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(res) {
                                setTimeout(function() {
                                    swal.fire({
                                        icon: 'success',
                                        html: '<h5>Successfully deleted!</h5>'
                                    });

                                    var oTable = $('#division-dt').dataTable();
                                    oTable.fnDraw(false);
                                }, 700);
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        toastr.info('Your data is safe :)', 'CANCELLED');
                    }
                });
            });
        }

        function checkHasUnit(id, callback) {
            $.ajax({
                url: "{{ url('/check-hasUnit') }}",
                type: "GET",
                data: { divID: id },
                success: function (response) {
                    callback(!response.exists); // true = deletable
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to check division status.'
                    });
                    callback(false);
                }
            });
        }
    </script>
@endsection
