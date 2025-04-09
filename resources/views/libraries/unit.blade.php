@extends('layouts.app',[
'page' => 'Unit',
'title' => ''
])

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4 class="h2" style="padding-left: 5px">List of Units</h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" data-bs-toggle="modal" data-bs-target="#unit-modal"><i class="fa fa-plus pr-2"></i>Add Unit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped w-100" id="unit-dt" style="font-size: 14px">
                        <thead>
                            <tr>
                                {{-- <th style="width: 5%">unitID</th> --}}
                                <th style="width: 10%">No</th>
                                <th style="width: 20%">Unit</th>
                                <th style="width: 35%">Division</th>
                                <th style="width: 10%">Status</th>

                                {{-- <th style="width: 10%">unitName</th> --}}
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
    <div class="modal fade" id="unit-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form action="javascript:void(0)" id="unit-form" name="unit-form" class="form-horizontal" method="POST">
                    @csrf
                    <div class="modal-header" style="background-color:#17366f; color: white;">
                        <h4 class="modal-title" id="unit-modal-title">Unit</h4>
                    </div>
                    <div class="modal-body">

                        <!-- id -->
                        <input type="hidden" name="unitID" id="unitID">

                        <!-- Unit Name -->
                        <div class="form-group">
                            <label for="unitName" class="col-sm-4 control-label"><strong>Unit Name</strong><span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="unitName" name="unitName"
                                    placeholder="Enter Name">
                            </div>
                        </div>

                        <!-- Division Dropdown -->
                        <div class="form-group">
                            <label for="divID" class="col-sm-4 control-label"><strong>Division</strong></label>
                            <div class="col-sm-12">
                                <select name="divID" id="divID" class="form-control">
                                    <option value="">Select Division</option>
                                </select>
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
                            <button type="submit" class="btn btn-info" id="unit-btn-save">Save</button>
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- AJAX -->
    <script type="text/javascript">

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#unit-dt').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/units') }}",
                columns: [{
                        data: 'unitID',
                        name: 'unitID'
                    },
                    {
                        data: 'unitName',
                        name: 'unitName'
                    },
                    {
                        data: 'divName',
                        name: 'divName'
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
            
            $('#unit-modal').on('hidden.bs.modal', function (e) {
                $("#unit-form")[0].reset();
                $('#unitID').val(''); // Clear the hidden ID field
                $('#unit-modal-title').text('Unit');
            });
        });

        // Submit button
        $('#unit-form').submit(function(e) {
            e.preventDefault();
            let formData = new FormData($('#unit-form')[0]);

            $.ajax({
                type: 'POST',
                url: "{{ url('/units/store') }}",
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

                        $("#unit-modal").modal('hide');
                        
                        var oTable = $('#unit-dt').dataTable();
                        oTable.fnDraw(false);

                        $("#unit-form")[0].reset();

                    } else {
                        Swal.fire({
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

        // Define loadDivisions before using it
        function loadDivisions(selectedDivID = null, callback = null) {
            $.ajax({
                url: "/get-divisions",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    console.log("Response received:", response);

                    if (response.data && response.data.length > 0) {
                        let options = '';

                        response.data.forEach(function (division) {
                            options += `<option value="${division.divID}">${division.divName}</option>`;
                        });

                        $("#divID").html(options);

                        // Execute callback after setting dropdown options
                        if (callback) {
                            callback();
                        }
                    } else {
                        $("#divID").html('<option value="">No Divisions Available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        }

        // Call loadDivisions when modal opens
        $("#unit-modal").on("show.bs.modal", function () {
            loadDivisions();
        });

        // editUnit function
        function editUnit(unitID) {
            $.ajax({
                type: "POST",
                url: "{{ url('/units/update') }}",
                data: { unitID: unitID },
                "token": "{{ csrf_token() }}",
                dataType: 'json',
                success: function(res) {
                    $('#unit-modal-title').html("Edit Unit");
                    $('#unit-modal').modal('show');
                    $('#unitID').val(res.unitID);
                    $('#unitName').val(res.unitName);
                    $('#status').val(res.status).change();

                    // Load divisions first, then set the selected value in a callback
                    loadDivisions(res.divID, function () {
                        $("#divID").val(res.divID).change(); // Ensure the correct selection
                    });
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        //DELETE DATA
        function deleteUnit(e) {
            let id = e.getAttribute('data-id');

            checkHasStaff(id, function(canDelete) {
                if (!canDelete) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot Delete',
                        html: 'There are Staff under this Unit.<br><strong>Please reassign them first before deleting.</strong>'
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
                    if (result.value && result.isConfirmed) {
                        swal.fire({
                            html: '<h6>Loading... Please wait</h6>',
                            onRender: function () {
                                $('.swal2-content').prepend(sweet_loader);
                            },
                            showConfirmButton: false
                        });

                        $.ajax({
                            type: 'DELETE',
                            url: '{{ url('/units/delete') }}/' + id,
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

                                var oTable = $('#unit-dt').dataTable();
                                oTable.fnDraw(false);
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

        function checkHasStaff(id, callback) {
            $.ajax({
                url: "{{ url('/check-hasStaff') }}",
                type: "GET",
                data: { unitID: id },
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
