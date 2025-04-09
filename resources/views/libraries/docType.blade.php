@extends('layouts.app',[
'page' => 'DocType',
'title' => ''
])

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4 class="h2" style="padding-left: 5px">List of Document Types</h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" data-bs-toggle="modal" data-bs-target="#docType-modal"><i
                                class="fa fa-plus pr-2"></i>DocType</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped w-100" id="docType-dt" style="font-size: 14px">
                        <thead>
                            <tr>
                                {{-- <th style="width: 5%">docTypeID</th> --}}
                                <th style="width: 10%">No</th>
                                <th style="width: 55%">Document Type</th>
                                <th style="width: 10%">Status</th>

                                {{-- <th style="width: 10%">docTypeDesc</th> --}}
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
    <div class="modal fade" id="docType-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form action="javascript:void(0)" id="docType-form" name="docType-form" class="form-horizontal" method="POST">
                    @csrf
                    <div class="modal-header" style="background-color:#17366f; color: white;">
                        <h4 class="modal-title" id="docType-modal-title">Document Type</h4>
                    </div>
                    <div class="modal-body">

                        <!-- id -->
                        <input type="hidden" name="docTypeID" id="docTypeID">

                        <!-- DocType Name -->
                        <div class="form-group">
                            <label for="DocType name" class="col-sm-4 control-label"><strong>Document Type</strong><span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="docTypeDesc" name="docTypeDesc"
                                    placeholder="Enter Name">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="inputcontent" class="col-sm-4 control-label"><strong>Status</strong></label>
                            <div class="col-sm-12">
                                <select name="status" id="status">
                                    <option value=1>Active</option>
                                    <option value=0>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info" id="docType-btn-save">Save</button>
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
            $('#docType-dt').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/docTypes') }}",
                columns: [{
                        data: 'docTypeID',
                        name: 'docTypeID'
                    },
                    {
                        data: 'docTypeDesc',
                        name: 'docTypeDesc'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return data == 1 ? '<span class="badge bg-success" style="font-size: 10px; padding: 8px 12px;">Active</span>' : '<span class="badge bg-danger" style="font-size: 10px; padding: 8px 12px;">Inactive</span>';
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
            
            $('#docType-modal').on('hidden.bs.modal', function (e) {
                $("#docType-form")[0].reset();
                $('#docTypeID').val(''); // Clear the hidden ID field
                $('#docType-modal-title').text('Document Type');
            });
        });

        // Submit button
        $('#docType-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: "{{ url('/docTypes/store') }}",
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

                        $("#docType-modal").modal('hide');
                        var oTable = $('#docType-dt').dataTable();
                        oTable.fnDraw(false);

                        $("#docType-btn-save").html('Save Changes');
                        $("#docType-btn-save").attr("disabled", false);
                        $("#docType-form")[0].reset();
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
        function editDocType(docTypeID) {
            // self.reset('Office'); //calling function reset() at index.blade

            $.ajax({
                type: "POST",
                url: "{{ url('/docTypes/update') }}",
                data: {
                    docTypeID: docTypeID
                },
                "token": "{{ csrf_token() }}",
                dataType: 'json',
                success: function(res) {
                    $('#docType-modal-title').html("Edit Document Type");
                    $('#docType-modal').modal('show');
                    $('#docTypeID').val(res.docTypeID);
                    $('#docTypeDesc').val(res.docTypeDesc);
                    $('#status').val(res.status).change();

                },

                error: function(data) {
                    console.log(data);
                }
            });
        }

        //DELETE DATA
        function deleteDocType(e) {
            let id = e.getAttribute('data-id');

            checkHasDoc(id, function(canDelete) {
                if (!canDelete) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot Delete',
                        html: 'There are Documents with this Type.'
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
                                url: '{{ url('/docTypes/delete') }}/' + id,
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

                                    var oTable = $('#docType-dt').dataTable();
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
            });
        }

        function checkHasDoc(id, callback) {
            $.ajax({
                url: "{{ url('/check-hasDoc') }}",
                type: "GET",
                data: { docTypeID: id },
                success: function (response) {
                    callback(!response.exists); // true = deletable
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to check document type status.'
                    });
                    callback(false);
                }
            });
        }
    </script>
@endsection
