
<!-- DATATABLE -->
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body table-responsive">
        <table class="table table-striped w-100" id="ticket-closed" style="font-size: 14px">
            <thead>
                <tr>
                    <th style="width: 10%">Ticket No.</th>
                    <th style="width: 25%">Ticket Requestor</th>
                    <th style="width: 10%">Ticket Type</th>
                    <th style="width: 15%">Call Status</th>
                    <th style="width: 15%">Status</th>
                    <th style="width: 15%">Call Date & Time</th>
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
                            <button class="btn btn-info btn-xs" onclick="location.href='/ticket/view/{{ $close->id }}'" ><i class="fas fa-eye"></i></button>                          
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
