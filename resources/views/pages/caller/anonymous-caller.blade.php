  {{-- Anonymous Table --}}
  <div class="col-md-12">
    <div class="card">
      <div class="card-body table-responsive">
      <table class="table table-striped w-100" id="anon-list" name="anon_list" style="font-size: 14px">
          <thead>
              <tr>
                  <th style="width: 15%">Contact No.</th>
                  <th style="width: 30%">Address</th>
                  <th style="width: 15%">Email</th>
                  <th style="width: 30%">Time & Date</th>
                  <th style="width: 10%">Action</th>
              </tr>
          </thead>
          <tbody>
              @foreach($anoncallers as $anoncaller)
              <tr class="data-user-id-{{$anoncaller->id}}">
                <td>{{$anoncaller->contact_no}}</td>  
                <td>{{$anoncaller->address}}</td>
                <td>{{$anoncaller->email}}</td>
                <td>{{$anoncaller->created_at}}</td>
                <td>
                    <button class="btn btn-primary btn-xs" 
                    onclick="location.href='/documents/create'"><i class="fas fa-ticket-alt"></i></button>
                    <button class="btn btn-danger btn-xs" onclick="deleteCase(this)" data-id="{{$anoncaller->id}}" data-toggle="tooltip" data-original-title="Delete"><i class="fas fa-trash"></i></button>           
                </td>
              </tr >
              @endforeach
          </tbody>

      </table>
      </div>
    </div>
  </div>
  <script type="text/javascript">

   $(document).ready( function () {
        $('#anon-list').DataTable();
   });


   //DELETE DATA
   function deleteCase(e)
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
                       url:'{{url("/caller/delete/")}}/' +id,
                       data:{
                           "_token": "{{ csrf_token() }}",
                       },
                       success:function(res) {
                           
                           setTimeout(function() {
                               swal.fire({
                                   icon: 'success',
                                   html: '<h5>Success deleted!</h5>'
                               });
                           
                                location.reload();
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
</script>