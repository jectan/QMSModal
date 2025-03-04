@extends('layouts.app',[
'page' => 'Office',
'title' => ''
])

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4 class="h2" style="padding-left: 5px">List of Offices</h4>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" data-toggle="modal" data-target="#office-modal"><i
                                class="fa fa-plus pr-2"></i>Office</button>
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
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="office-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form action="javascript:void(0)" id="office-form" name="office-form" class="form-horizontal" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="office-modal-title"></h4>
                    </div>
                    <div class="modal-body">

                        <!-- id -->
                        <input type="hidden" name="office_id" id="office_id">

                        <!-- Code -->
                        <div class="form-group">
                            <label for="code" class="col-sm-4 control-label">Code<span
                                    class="require">*</span></label>
                            <div class="col-sm-12 ">
                                <input type="text" class="form-control" id="code" name="code" placeholder="Enter code">
                            </div>
                        </div>

                        <!-- Office Name -->
                        <div class="form-group">
                            <label for="Office name" class="col-sm-4 control-label">Office Name<span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Name">
                            </div>
                        </div>

                        <!-- Office Name -->
                        <div class="form-group">
                            <label for="Office name" class="col-sm-4 control-label">Head Name<span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="head" name="head"
                                    placeholder="Enter Name">
                            </div>
                        </div>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="office-btn-save">Save</button>
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
            $('#office-dt').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/offices') }}",
                columns: [{
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'head',
                        name: 'head'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });


        });

        // Submit button
        $('#office-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: "{{ url('/offices/store') }}",
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

                        $("#office-modal").modal('hide');
                        var oTable = $('#office-dt').dataTable();
                        oTable.fnDraw(false);

                        $("#office-btn-save").html('Save Changes');
                        $("#office-btn-save").attr("disabled", false);
                        $("#office-form")[0].reset();
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
        function editoffice(id) {
            // self.reset('Office'); //calling function reset() at index.blade

            $.ajax({
                type: "POST",
                url: "{{ url('/offices/update') }}",
                data: {
                    id: id
                },
                "token": "{{ csrf_token() }}",
                dataType: 'json',
                success: function(res) {
                    $('#office-modal-title').html("Edit Office");
                    $('#office-modal').modal('show');
                    $('#office_id').val(res.id);
                    $('#code').val(res.code);
                    $('#name').val(res.name);
                    $('#head').val(res.head);
                },

                error: function(data) {
                    console.log(data);
                }
            });
        }

        //DELETE DATA
        function deleteOffice(e) {
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
                            url: '{{ url('/offices/delete') }}/' + id,
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

                                var oTable = $('#office-dt').dataTable();
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
