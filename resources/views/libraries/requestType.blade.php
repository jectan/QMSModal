@extends('layouts.app',[
'page' => 'RequestType',
'title' => ''
])

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4 class="h2" style="padding-left: 5px">List of Request Types</h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" data-toggle="modal" data-target="#requestType-modal"><i
                                class="fa fa-plus pr-2"></i>RequestType</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- DATATABLE -->
    <!-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped w-100" id="office-dt" style="font-size: 14px">
                        <thead>
                            <tr>
                                {{-- <th style="width: 5%">id</th> --}}
                                <th style="width: 10%">Code</th>
                                <th style="width: 35%">Office Name</th>
                                <th style="width: 35%">Head Name</th>

                                {{-- <th style="width: 10%">Province</th> --}}
                                {{-- <th style="width: 5%">Region</th> --}}
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div> -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped w-100" id="requestType-dt" style="font-size: 14px">
                        <thead>
                            <tr>
                                {{-- <th style="width: 5%">requestTypeID</th> --}}
                                <th style="width: 10%">No</th>
                                <th style="width: 55%">Request Type</th>
                                <th style="width: 10%">Status</th>

                                {{-- <th style="width: 10%">requestTypeDesc</th> --}}
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
    <div class="modal fade" id="requestType-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form action="javascript:void(0)" id="requestType-form" name="requestType-form" class="form-horizontal" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="requestType-modal-title"></h4>
                    </div>
                    <div class="modal-body">

                        <!-- id -->
                        <input type="hidden" name="requestTypeID" id="requestTypeID">

                        <!-- RequestType Name -->
                        <div class="form-group">
                            <label for="RequestType name" class="col-sm-4 control-label">Request Type<span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="requestTypeDesc" name="requestTypeDesc"
                                    placeholder="Enter Name">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="inputcontent" class="form-label"><strong>Status</strong></label>
                            <select name="status" id="status">
                                <option value=1>Active</option>
                                <option value=0>Inactive</option>
                            </select>
                        </div>



                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info" id="requestType-btn-save">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
            $('#requestType-dt').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/requestTypes') }}",
                columns: [{
                        data: 'requestTypeID',
                        name: 'requestTypeID'
                    },
                    {
                        data: 'requestTypeDesc',
                        name: 'requestTypeDesc'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return data == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
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


        });

        // Submit button
        $('#requestType-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: "{{ url('/requestTypes/store') }}",
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

                        $("#requestType-modal").modal('hide');
                        var oTable = $('#requestType-dt').dataTable();
                        oTable.fnDraw(false);

                        $("#requestType-btn-save").html('Save Changes');
                        $("#requestType-btn-save").attr("disabled", false);
                        $("#requestType-form")[0].reset();
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
        function editRequestType(requestTypeID) {
            // self.reset('Office'); //calling function reset() at index.blade

            $.ajax({
                type: "POST",
                url: "{{ url('/requestTypes/update') }}",
                data: {
                    requestTypeID: requestTypeID
                },
                "token": "{{ csrf_token() }}",
                dataType: 'json',
                success: function(res) {
                    $('#requestType-modal-title').html("Edit RequestType");
                    $('#requestType-modal').modal('show');
                    $('#requestTypeID').val(res.requestTypeID);
                    $('#requestTypeDesc').val(res.requestTypeDesc);
                    $('#status').val(res.status).change();

                },

                error: function(data) {
                    console.log(data);
                }
            });
        }

        //DELETE DATA
        function deleteRequestType(e) {
            let id = e.getAttribute('data-id');
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
                    if (result.isConfirmed) {

                        swal.fire({
                            html: '<h6>Loading... Please wait</h6>',
                            onRender: function() {
                                $('.swal2-content').prepend(sweet_loader);
                            },
                            showConfirmButton: false
                        });

                        $.ajax({
                            type: 'DELETE',
                            url: '{{ url('/requestTypes/delete') }}/' + id,
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

                                var oTable = $('#requestType-dt').dataTable();
                                oTable.fnDraw(false);
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
    </script>
@endsection
