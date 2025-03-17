@extends('layouts.app',[
    'page' => 'Manage Documents',
    'title' => 'Manage Documents'
])

@section('content')
<section class="content">
<div class="row">

<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-success">
      <span class="info-box-icon elevation-1" style="background-color: white; color: green">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Registered Documents</span>
        <span class="info-box-number register"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-danger">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #dc3545">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">For Review</span>
        <span class="info-box-number review"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-warning">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #ffc107">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">For Approval</span>
        <span class="info-box-number approval"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-secondary">
      <span class="info-box-icon elevation-1" style="background-color: white; color: gray">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Archived Documents</span>
        <span class="info-box-number archive"></span>
      </div>
    </div>
  </div>
</div>


  <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h4 class="h2" style="padding-left: 5px">List of Documents</h4>
                <div class="btn-toolbar mb-2 mb-md-0">
                  <div class="btn-group mr-2">
                      <div class="container-login100-form-btn">
                          <div class="wrap-login100-form-btn">
                              <div class="login100-form-bgbtn"></div>
                              <button class="login100-form-btn" data-toggle="modal" data-target="#request-modal"><i
                              class="fa fa-plus pr-2"></i>New Request</button>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
        </div>
        <!-- TABS -->
        <div class="card card-primary card-tabs" style="margin: 10px">
            <div class="card-header p-0 pt-1 bg-dblue">
                <ul class="nav nav-tabs" id="settings-tab" role="tablist">
                  @if(Auth::user()->role->id!=4)
                  <li class="nav-item">
                    <a class="nav-link active" id="request" data-toggle="pill" href="#requested-document" role="tab" aria-controls="requested-document" aria-selected="true">Requested</a>
                  </li>
                  @endif
                  <li class="nav-item">
                          <a class="nav-link {{Auth::user()->role->id == 4 ? 'active' : 'null'}}" id="review" data-toggle="pill" href="#review-document" role="tab" aria-controls="review-document" aria-selected="false">For Review</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="approval" data-toggle="pill" href="#approval-document" role="tab" aria-controls="approval-document" aria-selected="false">For Approval</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="registration" data-toggle="pill" href="#registration-document" role="tab" aria-controls="registration-document" aria-selected="false">For Registration</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="set-content">
                  @if(Auth::user()->role->id!=4)
                  <div class="tab-pane fade show active " id="requested-document" role="tabpanel" aria-labelledby="request-tab">
                    @include('pages.documents.requested-document')
                  </div>
                 @endif
                 <div class="tab-pane fade {{Auth::user()->role->id == 4 ? 'show active' : 'null'}}" id="review-document" role="tabpanel" aria-labelledby="review-tab">
                  @include('pages.documents.review-document')
                </div>
                  <div class="tab-pane fade" id="approval-document" role="tabpanel" aria-labelledby="approval-tab">
                    @include('pages.documents.approval-document')
                  </div>
                  <div class="tab-pane fade" id="registration-document" role="tabpanel" aria-labelledby="registration-tab">
                    @include('pages.documents.registration-document')
                  </div>
                </div>
              </div>
      </div>
      <!-- TABS -->
    </div>
    
</section>

<!-- MODAL -->
<div class="modal fade" id="request-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 50%;">
        <div class="modal-content">
            <form action="javascript:void(0)" id="request-form" name="request-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="request-modal-title">Request Document</h4>
                </div>
                <div class="modal-body">

                    <!-- id -->
                    <input type="hidden" name="requestID" id="requestID">

                    <!-- Request/Document Type Dropdown -->
                    <div class="form-group row">
                      <div class="col-md-6">
                          <label for="requestType" class="form-label"><strong>Request For:</strong><span
                          class="require">*</span></label>
                          <select name="requestTypeID" id="requestTypeID" class="form-control">
                              <option value="">Select Request</option>
                          </select>
                      </div>
                      <div class="col-md-6">
                          <label for="docType" class="form-label"><strong>Document Type:</strong><span
                          class="require">*</span></label>
                          <select name="docTypeID" id="docTypeID" class="form-control">
                              <option value="">Select Document Type</option>
                          </select>
                      </div>
                    </div>

                    <!-- DocRefCode and CurrentRevNum -->
                    <div class="form-group row">
                      <div class="col-md-6">
                        <label for="docRefCode" class="form-label"><strong>Document Reference Code:</strong></label>
                            <!-- <select name="docRefCode" id="docRefCode" class="form-control">
                                <option value="">Doc Ref Code</option>
                            </select> -->
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="docRefCode" name="docRefCode">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="currentRevNo" class="col-sm-4 control-label">Current Revision Number:</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="currentRevNo" name="currentRevNo">
                        </div>
                      </div>
                    </div>

                    <!-- Document Title -->
                    <div class="form-group">
                        <label for="Document Title" class="col-sm-4 control-label">Document Title:<span
                                class="require">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="docTitle" name="docTitle"
                                placeholder="Enter Document Title">
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="form-group">
                        <label for="Reason" class="col-sm-4 control-label">Reason/s for the Request:<span
                                class="require">*</span></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="requestReason" name="requestReason"
                                placeholder="Enter Reason for Request">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="requestFile" class="col-sm-4 control-label">Upload Document (PDF Only):<span
                                class="require">*</span></label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control" id="documentFile" name="documentFile" accept=".pdf">
                        </div>
                    </div>

                    <!-- Status -->
                    <!-- <div class="form-group">
                        <label for="inputcontent" class="form-label"><strong>Status</strong></label>
                        <select name="status" id="status">
                            <option value=1>Active</option>
                            <option value=0>Inactive</option>
                        </select>
                    </div> -->

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info" id="request-btn-save">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

  <!-- AJAX -->
  <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
    $(document).ready(function(){
      //  COUNT TOTAL
        $.ajax({
          type: 'GET',
          url: "{{ url('/documents/computeTotal')}}",
          dataType: 'json',
          success:function(res)
          {
            $(".register").html(res['register']);
            $(".review").html(res['review']);
            $(".approval").html(res['approval']);
            $(".archive").html(res['archive']);
            $(".total").html(res['total_ticket']);
          }
        });
      });

      // Submit button
      $('#request-form').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: "{{ url('/documents/store') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: res.success
                    });

                    $("#request-modal").modal('hide');

                    // Refresh DataTable immediately after saving
                    $('#request-dt').DataTable().ajax.reload(null, false);
                    $('#review-dt').DataTable().ajax.reload(null, false);
                    $('#approval-dt').DataTable().ajax.reload(null, false);
                    $('#registration-dt').DataTable().ajax.reload(null, false);

                    $("#request-btn-save").html('Save Changes');
                    $("#request-btn-save").attr("disabled", false);
                    $("#request-form")[0].reset();
                } else {
                    swal.fire({
                        icon: 'error',
                        html: res.errors
                    });
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

      // Define loadRequestType before using it
      function loadRequestType(selectedrequestTypeID = null, callback = null) {
          $.ajax({
              url: "get-requestType",
              type: "GET",
              dataType: "json",
              success: function (response) {
                  console.log("Response received:", response);

                  if (response.data && response.data.length > 0) {
                      let options = '';

                      response.data.forEach(function (requestType) {
                          options += `<option value="${requestType.requestTypeID}">${requestType.requestTypeDesc}</option>`;
                      });

                      $("#requestTypeID").html(options);

                      // Execute callback after setting dropdown options
                      if (callback) {
                          callback();
                      }
                  } else {
                      $("#requestTypeID").html('<option value="">Please Check Libraries</option>');
                  }
              },
              error: function (xhr, status, error) {
                  console.error("AJAX Error:", xhr.responseText);
              }
          });
      }

      // Define loadDocType before using it
      function loadDocType(selecteddocTypeID = null, callback = null) {
          $.ajax({
              url: "get-docType",
              type: "GET",
              dataType: "json",
              success: function (response) {
                  console.log("Response received:", response);

                  if (response.data && response.data.length > 0) {
                      let options = '';

                      response.data.forEach(function (docType) {
                          options += `<option value="${docType.docTypeID}">${docType.docTypeDesc}</option>`;
                      });

                      $("#docTypeID").html(options);

                      // Execute callback after setting dropdown options
                      if (callback) {
                          callback();
                      }
                  } else {
                      $("#docTypeID").html('<option value="">Please Check Libraries</option>');
                  }
              },
              error: function (xhr, status, error) {
                  console.error("AJAX Error:", xhr.responseText);
              }
          });
      }

      // Define loadDocType before using it
      function loadDocRefCode(selectedrequestID = null, callback = null) {
          $.ajax({
              url: "get-docRefCode",
              type: "GET",
              dataType: "json",
              success: function (response) {
                  console.log("Response received:", response);

                  if (response.data && response.data.length > 0) {
                      let options = '';

                      response.data.forEach(function (requestDocument) {
                          options += `<option value="${requestDocument.requestDocumentID}">${requestDocument.docRefCode}</option>`;
                      });

                      $("#requestDocumentID").html(options);

                      // Execute callback after setting dropdown options
                      if (callback) {
                          callback();
                      }
                  } else {
                      $("#requestDocumentID").html('<option value="">Please Check Libraries</option>');
                  }
              },
              error: function (xhr, status, error) {
                  console.error("AJAX Error:", xhr.responseText);
              }
          });
      }

      // editRequest function
      function editRequest(requestID) {
          $.ajax({
              type: "POST",
              url: "{{ url('/documents/update') }}",
              data: { requestID: requestID },
              "token": "{{ csrf_token() }}",
              dataType: 'json',
              success: function(res) {
                  $('#request-modal-title').html("Request");
                  $('#request-modal').modal('show');
                  $('#requestID').val(res.requestID);
                  $('#docRefCode').val(res.docRefCode);
                  $('#currentRevNo').val(res.currentRevNo);
                  $('#docTitle').val(res.docTitle);
                  $('#requestReason').val(res.requestReason).change();
                  $('#requestStatus').val('For Review').change();

                  // Load data first, then set the selected value in a callback
                  loadRequestType(res.requestTypeID, function () {
                      $("#requestTypeID").val(res.requestTypeID).change(); // Ensure the correct selection
                  });
                  
                  loadDocType(res.docTypeID, function () {
                      $("#docTypeID").val(res.docTypeID).change(); // Ensure the correct selection
                  });
                  

                  $("#request-modal").modal('hide');

                  // Refresh DataTable immediately after saving
                  $('#request-dt').DataTable().ajax.reload(null, false);
                  $('#review-dt').DataTable().ajax.reload(null, false);
                  $('#approval-dt').DataTable().ajax.reload(null, false);
                  $('#registration-dt').DataTable().ajax.reload(null, false);
              },
              error: function(data) {
                  console.log(data);
              }
          });
      }

      function cancelRequest(requestID) {
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
                      if (result.isConfirmed) {

                          swal.fire({
                              html: '<h6>Loading... Please wait</h6>',
                              onRender: function() {
                                  $('.swal2-content').prepend(sweet_loader);
                              },
                              showConfirmButton: false
                          });

                          $.ajax({
                            type: "POST",
                            url: "{{ url('/documents/cancel') }}",
                            data: {
                                requestID: requestID,
                                _token: "{{ csrf_token() }}"
                              },
                              success: function(res) {

                                  setTimeout(function() {
                                      swal.fire({
                                          icon: 'success',
                                          html: '<h5>Successfully Cancelled!</h5>'
                                      });

                                  }, 700);
                                  $('#request-dt').DataTable().ajax.reload(null, false);
                                  $('#review-dt').DataTable().ajax.reload(null, false);
                                  $('#approval-dt').DataTable().ajax.reload(null, false);
                                  $('#registration-dt').DataTable().ajax.reload(null, false);
                              }
                          });
                      }
                  } else if (
                      result.dismiss === Swal.DismissReason.cancel
                  ) {
                      toastr.info(
                          'Action is cancelled',
                          'CANCELLED'
                      );
                  }
              });
          }

      // Call loadDivisions when modal opens
      $("#request-modal").on("show.bs.modal", function () {
        console.log("Modal Opened, Fetching Request Types...");
        loadRequestType();
        loadDocType();
      });
  </script>
@endsection