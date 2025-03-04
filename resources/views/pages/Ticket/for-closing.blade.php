
<!-- DATATABLE -->
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body table-responsive">
        <table class="table table-striped w-100" id="ticket-for-closing" style="font-size: 14px">
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
                    
                    @foreach($for_closings as $for_closing)
                    <tr>
                        <td>{{$for_closing->ticket_no}}</td>
                        <td>{{$for_closing->caller ? $for_closing->caller ->fullname : ""}}</td>
                        <td>{{$for_closing->callerType ? $for_closing->callerType->name : ""}}</td>
                        <td>{{$for_closing->call_status}}</td>
                        <td>{{$for_closing->status}}</td>
                        <td>{{$for_closing->created_at}}</td>
                        <td>
                            <button class="btn btn-info btn-xs" onclick="location.href='/ticket/view/{{ $for_closing->id }}'" ><i class="fas fa-eye"></i></button>                          
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
         $('#ticket-for-closing').DataTable();
    });
 
</script>
