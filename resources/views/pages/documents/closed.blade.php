
<!-- DATATABLE -->
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body table-responsive">
        <table class="table table-striped w-100" id="ticket-created" style="font-size: 14px">
            <thead>
                <tr>
                    <th style="width: 10%">Document Ref No.</th>
                    <th style="width: 25%">Doc Title</th>
                    <th style="width: 15%">Document Type</th>
                    <th style="width: 15%">Requestor</th>
                    <th style="width: 15%">Unit</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 10%">Action</th>
                </tr>
                <tbody>
                    
                    @foreach($closed as $close)
                    <tr>
                        <td>{{$close->ticket_no}}</td>
                        <td>{{$close->caller ? $close->caller ->fullname : ""}}</td>
                        <td>{{$close->callerType ? $close->callerType->name : ""}}</td>
                        <td>{{$close->call_status}}</td>
                        <td>{{$close->status}}</td>
                        <td>{{$close->created_at}}</td>
                        <td>
                            <button class="btn btn-info btn-xs" onclick="location.href='/documents/view/{{ $close->id }}'" ><i class="fas fa-eye"></i></button>                          
                        </td>
                    </tr >
                    @endforeach
                 
                </tbody>
            </thead>
        </table>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
 
    $(document).ready( function () {
         $('#ticket-closed').DataTable();
    });
 
</script>
