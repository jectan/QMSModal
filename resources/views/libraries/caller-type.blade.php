@extends('layouts.app',[
    'page' => 'CallerType',
    'title' => ''
])

@section('content')
<!-- HEADER -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h4 class="h2" style="padding-left: 5px">Caller Types</h4>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <div class="container-login100-form-btn">
                <div class="wrap-login100-form-btn">
                    <div class="login100-form-bgbtn"></div>
                    <button class="login100-form-btn" data-toggle="modal" data-target="#caller-modal"><i class="fa fa-plus pr-2"></i>Caller Type</button>
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
        <table class="table table-striped w-100" id="caller-table" style="font-size: 14px">
            <thead>
                <tr>
                    <th style="width: 20%">Name</th>
                    <th style="width: 70%">Description</th>
                    <th style="width: 10%">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        </div>
      </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="caller-modal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="caller-modal-title"> Add Caller Type</h4>
            </div>
            <form action="javascript:void(0)" id="caller-form" name="caller_form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="caller_id" id="caller_id">
                    <div class="form-group">
                        <label for="code-label" class="col-sm-2 control-label">Name<span class="require">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="call-type" name="name" placeholder="Enter Call Type" maxlength="50" required="">
                        </div>
                        <div class="form-group">
                            <label for="code-label" class="col-sm-2 control-label">Description<span class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="description" name="description" placeholder="Enter description" maxlength="50" required="">
                            </div>
                        {{-- <label for="name" class="col-sm-2 control-label">Case Classification<span class="require">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="case-name" name="case_name" placeholder="Enter New Classification" maxlength="50" required="">
                        </div>
                        <label for="name" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-12">
                            <textarea type="longtext" class="summernote" id="case-description" name="case_description" placeholder="Enter Description" maxlength="50"></textarea>
                        </div> --}}
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" onClick="clearModal()" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
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
        $('#caller-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/caller-type') }}",
            columns: [
                    // { data: 'code', name: 'code' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    // { data: 'description', name: 'description' },
                    { data: 'action', name: 'action', orderable: false },
            ],
            order: [[0, 'desc']]
        });
    });

    // SAVE DATA
    $('#caller-form').submit(function(e) {
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
            url: "{{ url('/caller-type/store')}}",
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
                        var oTable = $('#caller-table').dataTable();
                            oTable.fnDraw(false);
                    });

                    $("#caller-modal").modal('hide');
                    $("#btn-save").attr("disabled", false);
                    $("#caller-form").trigger('reset');
               
                    
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

    //  //clear Modal
    //  function clearModal(){
    //      $('#role-form').trigger("reset");
    //      $('#role-modal-title').html("Add Case Classification"
    // }


    // SHOW DATA TO UPDATE
    function editcaller(id){
        $.ajax({
        type:"POST",
        url: "{{ url('/caller-type/edit') }}",
        data: { id: id },
        "token":"{{ csrf_token()}}",
        dataType: 'json',
        success: function(res){
            $('#caller-modal-title').html("Edit Case Classification");
            $('#caller-modal').modal('show');
            $('#caller_id').val(res.id);
            $('#call-type').val(res.name);
            $('#description').val(res.description);
            },
            error:function(data){
                console.log(data);
            }
        });
    }  

    // //DELETE DATA
    function deletecaller(e)
    {
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
                        url:'{{url("/caller-type/delete")}}/' +id,
                        data:{
                            "_token": "{{ csrf_token() }}",
                        },
                        success:function(res) {
                            
                            setTimeout(function() {
                                swal.fire({
                                    icon: 'success',
                                    html: '<h5>Success deleted!</h5>'
                                });
                        
                            }, 700);

                            var oTable = $('#caller-table').dataTable();
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
</div>
@endsection
