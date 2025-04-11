
<!-- DATATABLE -->
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped w-100" id="review-dt" style="font-size: 14px">
                <thead>
                    <tr>
                        <th style="width: 15%">Document Ref Code</th>
                        <th style="width: 25%">Doc Title</th>
                        <th style="width: 15%">Document Type</th>
                        <th style="width: 15%">Requestor</th>
                        <th style="width: 15%">Unit</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 10%">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
 
    $(document).ready(function () {
        $('#review-dt').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('documents.data-request', ['status' => 'For Review']) }}", // Fetch data from this route
            columns: [
                { data: "docRefCode", name: "docRefCode" },
                { data: "docTitle", name: "docTitle" },
                { data: "docTypeDesc", name: "docTypeDesc" },
                { data: "requestor", name: "requestor" },
                { data: "unitName", name: "unitName" },
                { data: "requestStatus", name: "requestStatus" },
                { data: "action", name: "action", orderable: false, searchable: false }
            ]
        });
    });
</script>