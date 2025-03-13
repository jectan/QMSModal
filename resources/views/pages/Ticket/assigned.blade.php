
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
                    
                    @foreach($assigned as $assign)
                    <tr>
                        <td>{{$assign->ticket_no}}</td>
                        <td>{{$assign->caller ? $working->caller ->fullname : ""}}</td>
                        <td>{{$assign->callerType ? $working->callerType->name : ""}}</td>
                        <td>{{$assign->call_status}}</td>
                        <td>{{$assign->status}}</td>
                        <td>{{$assign->created_at}}</td>
                        <td>
                            <button class="btn btn-info btn-xs" onclick="location.href='/ticket/view/{{ $assign->id }}'" ><i class="fas fa-eye"></i></button>                          
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
         $('#ticket-assigned').DataTable();
    });
 
</script>
