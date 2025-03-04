<!-- MODAL -->
<div class="modal fade" id="office-modal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="z-index: 1100 !important;">
            <div class="modal-header">
                <h4 class="modal-title" id="OfficeModal"></h4>
            </div>
            <div class="modal-body">
            <form action="javascript:void(0)" id="OfficeForm" name="OfficeForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="office_id" name="office_id">
                <div class="form-group">
                    <label for="code" class="col-sm-2 control-label">Code<span class="require"> *</span></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="office_code" name="office_code" placeholder="Enter office code" maxlength="50">
                    </div>
                </div>  
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name<span class="require"> *</span></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="office_name" name="office_name" placeholder="Enter office name" maxlength="50" required>
                    </div>
                </div>  
                <div class="form-group">
                    <label for="head" class="col-sm-2 control-label">Head</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="office_head" name="office_head" placeholder="Enter office head" maxlength="50">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btn-save">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    // SHOW MODAL
    function addOffice(){
        $('#OfficeForm').trigger("reset");
        $('#OfficeModal').html("Add office");
        $('#office-modal').modal('show');
        $('#id').val('');
    }  

    // SAVE DATA
    $('#OfficeForm').submit(function(e) {
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
            url: "{{ url('/offices/store')}}",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: function(res){

                if(res.success){
                    swal.fire({
                        icon: 'success',
                        html: res.success
                    });
                    $("#office-modal").modal('hide');
                    
                    var id = res.office.id;
                    var name = res.office.name;
                    var option = "<option value='"+id+"' selected>"+name+"</option>"; 

                    $("#office_id").append(option); 
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
</script>