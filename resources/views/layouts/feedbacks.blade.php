@extends('layouts.app',[
    'page' => 'Feedbacks',
    'title' => ''
])

@section('content')
<!-- HEADER -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h4 class="h2" style="padding-left: 5px">   FIND ME</h4>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <div class="container-login100-form-btn">
                <div class="wrap-login100-form-btn">
                    <div class="login100-form-bgbtn"></div>
                    <button class="login100-form-btn" data-toggle="modal" data-target="#role-modal"><i class="fa fa-plus pr-2"></i>TIX</button>
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
        <table class="table table-striped w-100" id="role-table" style="font-size: 14px">
            <thead>
                <tr>
                    {{-- <th style="width: 20%">Code</th> --}}
                    <th style="width: 70%">Name</th>
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
<div class="modal fade" id="role-modal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="role-modal-title"> FIND TICKET</h4>
            </div>
            <form action="javascript:void(0)" id="trackfeedback-form" name="role_form" class="form-horizontal"  enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    {{-- <input type="hidden" name="role_id" id="role_id"> --}}
                    <div class="form-group">
                        <label for="code-label" class="col-sm-2 control-label">Name<span class="require">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="ticketfeedback_number" placeholder="Enter Role" maxlength="50" required="">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn-save">find</button>
                </div>
        </form>
        </div>
    </div>
</div>
<script type="text/javascript">
  

    //DATATABLES
    $(document).ready( function () {
    //     $.ajaxSetup({
    //         headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     $('#role-table').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: "{{ url('/roles') }}",
    //         columns: [
    //                 // { data: 'code', name: 'code' },
    //                 { data: 'name', name: 'name' },
    //                 // { data: 'description', name: 'description' },
    //                 { data: 'action', name: 'action', orderable: false },
    //         ],
    //         order: [[0, 'desc']]
    //     });
    // });
    

    // SAVE DATA
    $('#trackfeedback-form').submit(function(e) {
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
            type:'GET',
            url: "{{ url('/feedbacktrack/search')}}/"+$('#ticketfeedback_number').val(),
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
                        var oTable = $('#role-table').dataTable();
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
});
    

    //  //clear Modal
    //  function clearModal(){
    //      $('#role-form').trigger("reset");
    //      $('#role-modal-title').html("Add Case Classification"
    // }


    //SHOW DATA TO UPDATE
    // function editrole(id){
    //     $.ajax({
    //     type:"POST",
    //     url: "{{ url('/roles/edit') }}",
    //     data: { id: id },
    //     "token":"{{ csrf_token()}}",
    //     dataType: 'json',
    //     success: function(res){
    //         $('#role-modal-title').html("Edit Case Classification");
    //         $('#role-modal').modal('show');
    //         $('#role_id').val(res.id);
    //         $('#name').val(res.name);
    //         },
    //         error:function(data){
    //             console.log(data);
    //         }
    //     });
    // }  

    // //DELETE DATA
    // function deleteCase(e)
    // {
    //     let id = e.getAttribute('data-id');
    //     const swalWithBootstrapButtons = Swal.mixin({
    //         customClass: {
    //             confirmButton: 'btn btn-danger',
    //             cancelButton: 'btn btn-default'
    //         },
    //         buttonsStyling: false
    //     });

    //     swalWithBootstrapButtons.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes, delete it!',
    //         cancelButtonText: 'No, cancel!',
    //         reverseButtons: true
    //     }).then((result) => {
    //         if (result.value) {
    //             if (result.isConfirmed){

    //                 swal.fire({
    //                     html: '<h6>Loading... Please wait</h6>',
    //                     onRender: function() {
    //                         $('.swal2-content').prepend(sweet_loader);
    //                     },
    //                     showConfirmButton: false
    //                 });

    //                 $.ajax({
    //                     type:'DELETE',
    //                     url:'{{url("/roles/delete")}}/' +id,
    //                     data:{
    //                         "_token": "{{ csrf_token() }}",
    //                     },
    //                     success:function(res) {
                            
    //                         setTimeout(function() {
    //                             swal.fire({
    //                                 icon: 'success',
    //                                 html: '<h5>Success deleted!</h5>'
    //                             });
                        
    //                         }, 700);

    //                         var oTable = $('#role-table').dataTable();
    //                         oTable.fnDraw(false);
    //                     }
    //                 });
    //             }
    //         } else if (
    //             result.dismiss === Swal.DismissReason.cancel
    //         ) {
    //             toastr.info(
    //                 'Your data is safe :)',  
    //                 'CANCELLED'
    //             );
    //         }
    //     });
    // }
</script>
@endsection
