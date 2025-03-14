
<!-- DATATABLE -->
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body table-responsive">
        <table class="table table-striped w-100" id="requested-document" style="font-size: 14px">
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
                    
                    @foreach($requestDocuments as $requestDocument)
                    <tr>
                        <!-- <td>$create->ticket_no</td>
                        <td>$create->caller ? $create->caller ->fullname : ""</td>
                        <td>$create->callerType ? $create->callerType->name : ""</td>
                        <td>$create->call_status</td>
                        <td>$create->status</td>
                        <td>$create->created_at</td> -->
                        <td>{{$requestDocument->requestID ? $requestDocument->requestID : ""}}</td>
                        <td>{{$requestDocument->docTitle ? $requestDocument->docTitle : ""}}</td>
                        <td>{{$requestDocument->DocumentType ? $requestDocument->DocumentType->docTypeDesc : ""}}</td>
                        <td>{{$requestDocument->createdBy ? $requestDocument->createdBy ->fullname : ""}}</td>
                        <td>{{$requestDocument->createdBy ? $requestDocument->createdBy ->unit->unitName : ""}}</td>
                        <td>{{$requestDocument->requestStatus ? $requestDocument->requestStatus : ""}}</td>
                        <td>
                            <button class="btn btn-info btn-xs" onclick="location.href='/documents/view/{{ $requestDocument->requestID }}'" ><i class="fas fa-eye"></i></button>                          
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
         $('#requested-document').DataTable();
    });
 
</script>

