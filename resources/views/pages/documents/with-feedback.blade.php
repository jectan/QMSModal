
<!-- DATATABLE -->
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body table-responsive">
        <table class="table table-striped w-100" id="ticket-feedback" style="font-size: 14px">
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
                    
                    @foreach($with_feedbacks as $with_feedback)
                    <tr>
                        <td>{{$with_feedback->ticket_no}}</td>
                        <td>{{$with_feedback->caller ? $with_feedback->caller ->fullname : ""}}</td>
                        <td>{{$with_feedback->callerType ? $with_feedback->callerType->name : ""}}</td>
                        <td>{{$with_feedback->call_status}}</td>
                        <td>{{$with_feedback->status}}</td>
                        <td>{{$with_feedback->created_at}}</td>
                        <td>
                            <button class="btn btn-info btn-xs" onclick="location.href='/documents/view/{{ $with_feedback->id }}'" ><i class="fas fa-eye"></i></button>                          
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
         $('#ticket-feedback').DataTable();
    } );
 
</script>
