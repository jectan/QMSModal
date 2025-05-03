
<!-- DATATABLE -->
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body table-responsive">
        <table class="table table-striped w-100" id="ad-dt" style="font-size: 14px">
            <thead>
                <tr>
                    <th style="width: 15%">Document Ref Code</th>
                    <th style="width: 10%">Request Type</th>
                    <th style="width: 20%">Doc Title</th>
                    <th style="width: 10%">Document Type</th>
                    <th style="width: 10%">Unit</th>
                    <th style="width: 10%">Revision No</th>
                    <th style="width: 5%">Action</th>
                </tr>
            </thead>
        </table>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
 
    $(document).ready(function () {
        $('#ad-dt').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/masterlist/data-request/7') }}", // Fetch data from this route
            columns: [
                { data: "docRefCode", name: "docRefCode" },
                { data: "requestType", name: "requestType" },
                { data: "docTitle", name: "docTitle" },
                { data: "docTypeDesc", name: "docTypeDesc" },
                { data: "unitName", name: "unitName" },
                { data: "currentRevNo", name: "currentRevNo" },
                { data: "action", name: "action" },
            ],
            language: {
                emptyTable: 'There are no Archived Documents'
            }
        });
    });
</script>