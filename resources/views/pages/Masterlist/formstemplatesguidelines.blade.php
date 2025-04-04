
<!-- DATATABLE -->
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body table-responsive">
        <table class="table table-striped w-100" id="ftg-dt" style="font-size: 14px">
            <thead>
                <tr>
                    <th style="width: 15%">Document Ref Code</th>
                    <th style="width: 20%">Doc Title</th>
                    <th style="width: 10%">Document Type</th>
                    <th style="width: 10%">Unit</th>
                    <th style="width: 10%">Effectivity Date</th>
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
        $('#ftg-dt').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/masterlist/data-request/2') }}", // Fetch data from this route
            columns: [
                { data: "docRefCode", name: "docRefCode" },
                { data: "docTitle", name: "docTitle" },
                { data: "docTypeDesc", name: "docTypeDesc" },
                { data: "unitName", name: "unitName" },
                { data: "effectivityDate", name: "effectivityDate" },
                { data: "action", name: "action" },
            ]
        });
    });
</script>
