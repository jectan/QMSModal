@extends('layouts.app', [
    'page' => 'View Request',
    'title' => ''
])

@section('content')
    <div class="container mt-4 custom-container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <h2 class="mb-3">Document Details</h2>
            <!-- Action Buttons -->
            <div class="d-flex justify-content-end mt-2 mb-3">
                <!-- Back Button -->
                <div class="expandable-button">
                    <a href="{{ url('/documents') }}" class="btn btn-sm btn-secondary mx-1 btn-icon" data-bs-toggle="tooltip">
                        <span class="material-icons" style="font-size: 20px;">chevron_left</span>
                        <span class="btn-label">Back</span>
                    </a>
                </div>
                <!-- Submit Button for Review -->
                @if(Auth::check() && in_array(Auth::user()->role_id, [1, 5]) && (in_array($document->requestStatus, ['Requested'])))
                    <div class="expandable-button">
                        <button type="button" class="btn btn-sm btn-success mx-1 btn-icon" href="javascript:void(0)"  onClick="forReview('{{ $document->requestID }}')">
                            <span class="material-icons" style="font-size: 20px;">check_circle</span>
                            <span class="btn-label">Submit</span>
                        </button>
                    </div>
                @endif

                <!-- Buttons for QMR for Users -->
                @if(Auth::check() && in_array(Auth::user()->role_id, [1, 4]) && (in_array($document->requestStatus, ['For Review'])))
                    <div class="expandable-button">
                        <button type="button" class="btn btn-sm btn-success mx-1 btn-icon" id="reviewedButton" href="javascript:void(0)"  onClick="reviewedRequest('{{ $document->requestID }}')">
                            <span class="material-icons" style="font-size: 20px;">check_circle</span>
                            <span class="btn-label">Reviewed</span>
                        </button>
                    </div>
                @endif
                @if(Auth::check() && in_array(Auth::user()->role_id, [1, 4]) && (in_array($document->requestStatus, ['For Review'])))
                    <div class="expandable-button">
                        <button type="button" id="rejectButton" class="btn btn-sm btn-warning mx-1 btn-icon" data-bs-toggle="collapse" data-bs-target="#reviewComment" title="For Revision" >
                            <span class="material-icons" style="font-size: 20px;">comment</span>
                            <span class="btn-label">For Revision</span>
                        </button>
                    </div>
                @endif

                <!-- Buttons for Approving Authority -->
                @if(Auth::check() && in_array(Auth::user()->role_id, [1, 3]) && (in_array($document->requestStatus, ['For Approval'])))
                    <div class="expandable-button">
                        <button type="button" class="btn btn-sm btn-success mx-1 btn-icon" id="approvedButton" href="javascript:void(0)"  onClick="approvedRequest('{{ $document->requestID }}')">
                            <span class="material-icons" style="font-size: 20px;">check_circle</span>
                            <span class="btn-label">Approved</span>
                        </button>
                    </div>
                @endif
                @if(Auth::check() && in_array(Auth::user()->role_id, [1, 3]) && (in_array($document->requestStatus, ['For Approval'])))
                    <div class="expandable-button">
                        <button type="button" id="rejectApproveButton" class="btn btn-sm btn-warning mx-1 btn-icon" data-bs-toggle="collapse" data-bs-target="#approvalComment" title="For Revision" >
                            <span class="material-icons" style="font-size: 20px;">comment</span>
                            <span class="btn-label">For Revision</span>
                        </button>
                    </div>
                @endif

                <!-- Buttons for Document Manager -->
                @if(Auth::check() && in_array(Auth::user()->role_id, [1, 2]) && (in_array($document->requestStatus, ['For Registration'])))
                    <div class="expandable-button">
                        <button type="button" class="btn btn-sm btn-success mx-1+ btn-icon" data-bs-toggle="modal" data-bs-target="#register-modal" data-id='{{ $document->requestID }}' data-requestTypeID='{{ $document->requestTypeID }}'>
                            <span class="material-icons" style="font-size: 20px;">check_circle</span>
                            <span class="btn-label">Register</span>
                        </button>
                    </div>
                @endif

                <!-- Edit Button for Users -->
                @if(Auth::user()->role_id == 1 || (Auth::user()->id == $document->userID && (!in_array($document->requestStatus, ['For Review', 'For Approval', 'For Registration']))))
                    <div class="expandable-button">
                        <button class="btn btn-sm btn-info mx-1 btn-icon" id="editButton" href="javascript:void(0)">
                            <span class="material-icons" style="font-size: 20px;">edit</span>
                            <span class="btn-label">Edit</span>
                        </button>
                    </div>
                @endif
                <!-- Delete Button for Users -->
                @if(Auth::user()->id == $document->userID || Auth::user()->role_id == 1)
                    <div class="expandable-button">
                        <button class="btn btn-sm btn-danger btn-icon" href="javascript:void(0)" onClick="cancelRequest('{{ $document->requestID }}')">
                            <span class="material-icons" style="font-size: 20px;">delete</span>
                            <span class="btn-label">Delete</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Content Section -->
        <div class="row">
            <!-- Left Side: Document Details -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        
                    @if(Auth::check() && in_array(Auth::user()->role_id, [1, 2]) && (in_array($document->requestStatus, ['For Registration'])))
                        <form action="{{ url('/documents/register') }}" method="POST" enctype="multipart/form-data">
                    @else
                        <form action="{{ url('/documents/storeEdit') }}" method="POST" enctype="multipart/form-data">
                    @endif
                            @csrf
                            <input type="hidden" name="requestID" id="requestID" value="{{ $document->requestID }}">
                            <input type="hidden" name="requestFileOld" id="requestFileOld" value="{{ $document->requestFile }}">

                            @if($document->requestStatus == "Requested")
                                <input type="hidden" name="requestStatus" id="requestStatus" value="Requested">
                            @elseif($document->requestStatus == "For Revision")
                                <input type="hidden" name="requestStatus" id="requestStatus" value="For Review">
                            @elseif($document->requestStatus == "For Revision (Approval)")
                                <input type="hidden" name="requestStatus" id="requestStatus" value="For Approval">
                            @else
                                <input type="hidden" name="requestStatus" id="requestStatus" value="{{ $document->requestStatus }}">
                            @endif

                            <!-- Request Type & Document Type -->
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="requestTypeID" class="form-label"><strong>Request For:</strong></label>
                                    <input type="text" class="form-control" id="requestType" name="requestType" value="{{$document->requestType->requestTypeDesc}}" readonly>
                                    <!-- <select name="requestTypeID" id="requestTypeID" class="form-control" readonly>
                                            @foreach($requestType as $row)
                                                @if($row->requestTypeID == $document->requestTypeID)
                                                    <option value="{{ $row->requestTypeID }}" selected>{{ $row->requestTypeDesc }}</option>
                                                @else
                                                    <option value="{{ $row->requestTypeID }}">{{ $row->requestTypeDesc }}</option>
                                                @endif
                                            @endforeach
                                    </select> -->
                                </div>
                                <div class="col-md-6">
                                    <label for="docTypeID" class="form-label"><strong>Document Type:</strong><span class="require">*</span></label>
                                    <input type="text" class="form-control" id="docType" name="docType" value="{{$document->DocumentType->docTypeDesc}}" readonly>
                                    <!-- <select name="docTypeID" id="docTypeID" class="form-control" readonly>
                                        @foreach($docType as $row)
                                            @if($row->docTypeID == $document->docTypeID)
                                            <option value="{{ $row->docTypeID }}" selected>{{ $row->docTypeDesc }}</option>
                                            @else
                                                <option value="{{ $row->docTypeID }}">{{ $row->docTypeDesc }}</option>
                                            @endif
                                        @endforeach
                                    </select> -->
                                </div>
                            </div>

                            <!-- Document Reference Code & Current Revision Number -->
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="docRefCode" class="form-label"><strong>Document Reference Code:</strong></label>
                                    <input type="text" class="form-control" id="docRefCode" name="docRefCode" value="{{$document->docRefCode}}" readonly>
                                    <!--<span id="docRefCodeError" class="text-danger"></span>-->
                                </div>
                                <div class="col-md-6">
                                    <label for="currentRevNo" class="form-label"><strong>Current Revision Number:</strong><span class="require">*</span></label>
                                    <input type="number" class="form-control" id="currentRevNo" name="currentRevNo" value="{{ $document->currentRevNo }}" min="0" required readonly>
                                </div>
                            </div>

                            <!-- Document Title -->
                            <div class="form-group">
                                <label for="docTitle" class="form-label"><strong>Document Title:</strong><span class="require">*</span></label>
                                <input type="text" class="form-control" id="docTitle" name="docTitle" value="{{ $document->docTitle }}" required readonly>
                            </div>

                            <!-- Reason for Request -->
                            <div class="form-group">
                                <label for="requestReason" class="form-label"><strong>Reason/s for the Request:</strong><span class="require">*</span></label>
                                <input type="text" class="form-control" id="requestReason" name="requestReason" value="{{ $document->requestReason }}" readonly>
                            </div>

                            <!-- File Upload
                            <div class="form-group">
                                @if(in_array($document->requestStatus, ['For Registration']))
                                    <label for="requestFile" class="col-sm-4 control-label">Upload Final Copy (PDF Only):<span class="require">*</span></label>
                                    <input type="file" class="form-control" id="documentFile" name="documentFile" accept=".pdf">
                                @else
                                    <label for="requestFile" class="col-sm-4 control-label">Upload Document (PDF Only):<span class="require">*</span></label>
                                    <div class="col-sm-12">
                                        Display existing file
                                        @if ($document->requestFile)
                                            <p id="documentLabel">Current File: <a href="{{ asset('storage/' . $document->requestFile) }}" target="_blank">{{ basename($document->requestFile) }}</a></p>
                                        @endif

                                        Allow uploading a new file
                                        <input type="file" class="form-control" id="documentFile" name="documentFile" accept=".pdf" value="{{ basename($document->requestFile) }}" hidden>
                                    </div>
                                @endif
                            </div> -->

                            <!-- Submit Button -->
                            <div class="form-group text-center mt-3">
                                @if(Auth::check() && !in_array(Auth::user()->role_id, [1, 2]) && (in_array($document->requestStatus, ['For Registration'])))
                                    <button type="submit" id="submitButton" class="btn btn-primary" hidden>Submit Request</button>
                                    <button type="button" id="cancelEditButton" class="btn btn-secondary" hidden>Cancel</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: PDF Preview -->
            <div class="col-md-8 mb-3">
                <div class="card">
                    <!-- Collapsible Comment Section -->
                    <!-- Review Comments -->
                    <div class="collapse" id="reviewComment">
                        <div class="card border-warning m-3">
                            <div class="card-header bg-warning text-white">
                                <strong>Comments for Revision</strong>
                            </div>
                            <div class="card-body">
                                <form action="javascript:void(0)" id="review-form" name="review-form" class="form-horizontal" method="POST">
                                    @csrf
                                    <input type="hidden" name="requestID" id="requestID" value="{{ $document->requestID }}">
                                    <textarea class="form-control" rows="3" id="reviewComments" name="reviewComments" placeholder="Add your comment..."></textarea>
                                    <button class="btn btn-sm btn-warning mt-2">Submit Comments</button>
                                    <button type="button" class="btn btn-sm btn-secondary mt-2" data-bs-toggle="collapse" data-bs-target="#reviewComment">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Comments -->
                    <div class="collapse" id="approvalComment">
                        <div class="card border-warning m-3">
                            <div class="card-header bg-warning text-white">
                                <strong>Comments for Revision (Approval)</strong>
                            </div>
                            <div class="card-body">
                                <form action="javascript:void(0)" id="approve-form" name="approve-form" class="form-horizontal" method="POST">
                                    @csrf
                                    <input type="hidden" name="requestID" id="requestID" value="{{ $document->requestID }}">
                                    <textarea class="form-control" rows="3" id="reviewComment2" name="reviewComment2" placeholder="Add your comment..."></textarea>
                                    <button class="btn btn-sm btn-warning mt-2">Submit Comments</button>
                                    <button type="button" class="btn btn-sm btn-secondary mt-2" data-bs-toggle="collapse" data-bs-target="#approvalComment">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- PDF Viewer -->
                    <div class="card-body text-center">
                        @if($document->requestFile)
                            <iframe id="documentPreview" src="{{ asset('storage/' . $document->requestFile) }}" 
                                width="100%" height="700px" style="border: none;">
                            </iframe>
                        @else
                            <p>No File Uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(in_array($document->requestStatus, ['For Review', 'For Revision', 'For Approval', 'For Revision (Approval)', 'For Registration']))
            <!-- Review History Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card border-warning">
                                <div class="card-header bg-dblue text-white">
                                    <strong>Document Review History</strong>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-body">
                                            <ul class="timeline" id="review-timeline"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="register-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form action="javascript:void(0)" id="register-form" name="register-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title" id="request-modal-title">Register Document</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="requestID2" name="requestID2" value="{{ $document->requestID }}">
                        <input type="hidden" id="requestTypeID2" name="requestTypeID2" value="{{ $document->requestTypeID }}">
                        <input type="hidden" id="regDocID" name="regDocID">

                        <!-- DocRefCode and CurrentRevNum -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="docRefCode2" class="form-label"><strong>Document Reference Code:</strong></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="docRefCode2" name="docRefCode2" value="{{ $document->docRefCode }}" readonly required>
                                    <span id="docRefCodeError2" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="currentRevNo" class="col-sm-4 control-label">Current Revision Number:<span
                                class="require">*</span></label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" id="currentRevNo2" name="currentRevNo2" min="0" value="{{ $document->currentRevNo }}" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="documentFile2" class="col-sm-4 control-label">Upload Signed Document (PDF Only):<span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" id="documentFile2" name="documentFile2" accept=".pdf" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="request-btn-save">Register</button>
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- AJAX -->
    <script type="text/javascript">
        $(document).ready(function () {
            // Ensure CSRF Token is included
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var isEdit = {{ $isEdit ?? 0 }};
            if(isEdit == 1){
                activateFields();
            }

            $(document).ready(function () {
                $.ajax({
                    url: "{{ url('/documents/view/review') }}/{{ $document->requestID }}",
                    type: "GET",
                    dataType: "json",
                    success: function (res) {
                        console.log("API Response:", res); // Debugging step

                        // Ensure res is an array
                        let reviews = Array.isArray(res) ? res : Object.values(res);
                        
                        reviews.sort((a, b) => new Date(b.reviewDate) - new Date(a.reviewDate));

                        let timelineHTML = "";

                        reviews.forEach(item => {
                            timelineHTML += `
                                <li class="timeline-item">
                                    <div class="date">${item.reviewDate}</div>
                                    <div class="status">${item.reviewComment}</div>
                                </li>
                            `;
                        });

                        $("#review-timeline").html(timelineHTML);
                    },
                    error: function () {
                        $("#review-timeline").html("<li class='text-danger'>Failed to load review history.</li>");
                    }
                });
            });

            $('#review-dt').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthChange: false,
                searching: false,
                ajax: "{{ url('/documents/view/review') }}/{{ $document->requestID }}",
                columns: [
                    { data: 'reviewComment', name: 'reviewComment' },
                    { data: 'reviewDate', name: 'reviewDate' }
                ],
                order: [[1, 'desc']]
            });

            //Hide/Unhide DocumentFile
            $("#editButton").click(function () {
                //activateFields();
            });

            $("#cancelEditButton").click(function () {
                // Reload the page after 1 second (optional delay for better UX)
                setTimeout(function () {
                    window.location.href = "{{ url('/documents/view/edit') }}/" + {{ $document->requestID }};
                }, 1000); // Adjust time as needed
            });
            
            //Enable/Disable DocRefCode
            $("#requestTypeID").change(function () {
                var requestType = $(this).val(); // Get selected value
                var docRefCode = $("#docRefCode");
                var currentRevNo = $("#currentRevNo");
                if (requestType == "1") {
                    alert('lock');
                    docRefCode.val("For Assigning").prop("readonly", true).removeAttr("required"); // Clear, disable, and remove required
                    currentRevNo.val("0");
                } else {
                    alert('unlock');
                    docRefCode.val("{{$document->docRefCode}}").prop("readonly", false).attr("required", "required"); // Enable and make required
                }
            });

            //Auto Current Revision Number
            $("#docRefCode").change(function () {
                var docRefCode = $(this).val(); // Get entered value
                var currentRevNo = $("#currentRevNo");
                var errorField = $("#docRefCodeError"); // Error message span

                if (docRefCode !== "") {
                    $.ajax({
                        url: "{{ url('/check-docRefCode') }}", // Change to your route
                        type: "GET",
                        data: { docRefCode: docRefCode },
                        success: function (response) {
                            if (response.exists) {
                                alert("Document Reference Code not found!");
                                currentRevNo.val(response.currentRevNo ?? ""); // ✅ Prevent undefined errors
                                errorField.text(""); // Clear error message
                                $("#docRefCode").removeClass("is-invalid"); // Remove error styling
                            } else {
                                currentRevNo.val("0"); 
                                $("#docRefCode").addClass("is-invalid"); // Add error styling
                            }
                        },
                        error: function () {
                            errorField.text("Error checking document reference code.");
                            $("#docRefCode").addClass("is-invalid"); // Add error styling
                        }
                    });
                }
            });

            // Clear reviewComment field on page refresh
            $('#reviewComments').val('');

            //Change Document Displayed
            $("#documentFile").change(function () {
                var documentFile = this.files[0]; // Get the File object
                var documentPreview = $("#documentPreview");
                documentPreview.attr("src", URL.createObjectURL(documentFile));
            });

            // Submit button event for Review
            $('#review-form').submit(function (e) {
                e.preventDefault();

                let isValid = true;
                let errorMessages = [];
                let missingFields = [];

                // Clear previous errors
                $(".is-invalid").removeClass("is-invalid");
                $(".error-message").remove();

                // Get input values
                let reviewComment = $('#reviewComments');

                // Validation Rules
                if (!reviewComment.val().trim()) {
                    isValid = false;
                    missingFields.push("Review Comment");
                    reviewComment.addClass("is-invalid");
                    reviewComment.after("<div class='error-message text-danger'>The review comments are required.</div>");
                } else if (reviewComment.val().length > 500) {
                    isValid = false;
                    errorMessages.push("The review comment must be at most 500 characters.");
                    reviewComment.addClass("is-invalid");
                }

                // Show error messages if validation fails
                if (!isValid) {
                    let errorText = "";

                    if (missingFields.length > 0) {
                        errorText += `<strong>Missing Fields:</strong><br>• ${missingFields.join("<br>• ")}<br><br>`;
                    }
                    if (errorMessages.length > 0) {
                        errorText += `<strong>Errors:</strong><br>• ${errorMessages.join("<br>• ")}`;
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Input',
                        html: errorText,
                    });

                    return;
                }

                // Proceed with form submission via AJAX
                let formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('/documents/storeReview') }}",
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

                            // Clear the input field after successful submission
                            $('#reviewComments').val('');
                            $('#rejectButton').hide();
                            $('#reviewedButton').hide();

                            // Optional: Refresh DataTable
                            if ($.fn.DataTable.isDataTable("#review-dt")) {
                                $('#review-dt').DataTable().ajax.reload();
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: res.errors
                            });
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = [];

                            $.each(errors, function (key, messages) {
                                errorMessages.push(messages.join("<br>"));
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid Input',
                                html: errorMessages.join("<br>")
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again.'
                            });
                        }
                    }
                });
            });

            // Submit button event for Approval
            $('#approve-form').submit(function (e) {
                e.preventDefault();

                let isValid = true;
                let errorMessages = [];
                let missingFields = [];

                // Clear previous errors
                $(".is-invalid").removeClass("is-invalid");
                $(".error-message").remove();

                // Get input values
                let reviewComment2 = $('#reviewComment2');

                // Validation Rules
                if (!reviewComment2.val().trim()) {
                    isValid = false;
                    missingFields.push("Review Comment");
                    reviewComment2.addClass("is-invalid");
                    reviewComment2.after("<div class='error-message text-danger'>The comments are required.</div>");
                } else if (reviewComment2.val().length > 500) {
                    isValid = false;
                    errorMessages.push("The review comment must be at most 500 characters.");
                    reviewComment2.addClass("is-invalid");
                }

                // Show error messages if validation fails
                if (!isValid) {
                    let errorText = "";

                    if (missingFields.length > 0) {
                        errorText += `<strong>Missing Fields:</strong><br>• ${missingFields.join("<br>• ")}<br><br>`;
                    }
                    if (errorMessages.length > 0) {
                        errorText += `<strong>Errors:</strong><br>• ${errorMessages.join("<br>• ")}`;
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Input',
                        html: errorText,
                    });

                    return;
                }

                // Proceed with form submission via AJAX
                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ url('/documents/storeApprove') }}",
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

                            // Clear the input field after successful submission
                            $('#reviewComments').val('');
                            $('#rejectApproveButton').hide();
                            $('#approvedButton').hide();

                            // Optional: Refresh DataTable
                            if ($.fn.DataTable.isDataTable("#approve-dt")) {
                                $('#approve-dt').DataTable().ajax.reload();
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: res.errors
                            });
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = [];

                            $.each(errors, function (key, messages) {
                                errorMessages.push(messages.join("<br>"));
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid Input',
                                html: errorMessages.join("<br>")
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again.'
                            });
                        }
                    }
                });
            });

            // Lock and Unlock Fields
            $("#register-modal").on("show.bs.modal", function () {

                var modal = $(this);
                var docRefCode = $("#docRefCode2");
                var currentRevNo = $("#currentRevNo2");
                var requestTypeID = $("#requestTypeID2").val();
                var currentValue = parseInt(currentRevNo.val(), 10) || 0;

                if (requestTypeID === "1") {
                    docRefCode.val("").prop("readonly", false).attr("required", "required");
                    currentRevNo.val("0");
                } else {
                    docRefCode.prop("readonly", true) .removeAttr("required");
                    
                    if (!modal.data('opened')) {
                        currentRevNo.val(currentValue + 1);
                        modal.data('opened', true);
                    } else {
                        currentRevNo.val(currentValue);
                    }
                }
            });
            
            //Remove Error in File Upload
            $("#documentFile2").change(function () {
                var documentFile = $(this).val(); // Get entered value

                $(this).removeClass("is-invalid"); // Remove error styling
                $(this).next('.error-message').remove();
            });
        });

        // Submit Register
        $('#register-form').submit(function (e){
            e.preventDefault();

            let docRefCode = $('#docRefCode2').val().trim();
            let requestTypeID = $('#requestTypeID2').val();
            let errorField = $("#docRefCodeError2");

            if (docRefCode === "") {
                errorField.text("Document Reference Code is required.");
                $("#docRefCode2").addClass("is-invalid");
                return; // Stop form submission
            }

            // Call AJAX validation before proceeding
            checkDocRefCode(docRefCode, requestTypeID, function(isValid) {
                if (!isValid) {
                    return; // Stop form submission if invalid
                }

                let isValidForm = true;
                let missingFields = [];
                let errorMessages = [];
                let allowedFileType = ["application/pdf"];

                // Get input values
                let documentFile = $('#documentFile2')[0].files[0];

                // Clear previous errors
                $(".is-invalid").removeClass("is-invalid");
                $(".error-message").remove();

                // File validation
                if (!documentFile) {
                    isValidForm = false;
                    missingFields.push("Uploaded Document");
                    $('#documentFile2').addClass("is-invalid");
                    $('#documentFile2').after("<div class='error-message text-danger'>The Uploaded Document is required.</div>");
                } else if (!allowedFileType.includes(documentFile.type)) {
                    isValidForm = false;
                    errorMessages.push("The Uploaded Document must be a PDF file.");
                    $('#documentFile2').addClass("is-invalid");
                }

                // Show error messages if form is invalid
                if (!isValidForm) {
                    let errorText = "";

                    if (missingFields.length > 0) {
                        errorText += `<strong>Missing Fields:</strong><br>• ${missingFields.join("<br>• ")}<br><br>`;
                    }
                    if (errorMessages.length > 0) {
                        errorText += `<strong>Errors:</strong><br>• ${errorMessages.join("<br>• ")}`;
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Input',
                        html: errorText,
                    });

                    return;
                }

                // Proceed with form submission via AJAX if all fields are valid
                let formData = new FormData($('#register-form')[0]);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('/documents/register') }}",
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
                            $("#register-modal").modal('hide');
                            $("#register-form")[0].reset();
                            window.location.href = "{{ url('/documents') }}/";
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: res.errors
                            });
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = [];

                            $.each(errors, function (key, messages) {
                                errorMessages.push(messages.join("<br>"));
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid Input',
                                html: errorMessages.join("<br>")
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again.'
                            });
                        }
                    }
                });
            });
        });

        function checkDocRefCode(docRefCode, requestTypeID, callback) {
            if(requestTypeID == 1){
                $.ajax({
                    url: "{{ url('/check-docRefCode') }}",
                    type: "GET",
                    data: { docRefCode: docRefCode },
                    success: function (response) {
                        if (response.exists) {
                            $("#docRefCode2").addClass("is-invalid");
                            $("#docRefCodeError2").text("Document Reference Code already exists.");
                            callback(false); // Invalid case
                        } else {
                            $("#docRefCode2").removeClass("is-invalid");
                            $("#docRefCodeError2").text("");
                            callback(true); // Valid case
                        }
                    },
                    error: function () {
                        $("#docRefCodeError2").text("Error checking document reference code.");
                        $("#docRefCode2").addClass("is-invalid");
                        callback(false);
                    }
                });
            }
            else{
                callback(true);
            }
        }

        function cancelRequest(requestID) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger mx-2',
                    cancelButton: 'btn btn-default mx-2'
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
                        url: "/documents/cancel",
                        data: {
                            requestID: requestID,
                            _token: @json(csrf_token())
                            },
                            success: function(res) {

                                setTimeout(function() {
                                    swal.fire({
                                        icon: 'success',
                                        html: '<h5>Successfully Cancelled!</h5>'
                                    }).then(() => {
                                        window.history.back();
                                    });
                                }, 700);
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

        function reviewedRequest(requestID) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: 'btn btn-success mx-2', // ✅ Green button with margin
                cancelButton: 'btn btn-secondary mx-2'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Confirm Review?',
                text: "This document will be marked as reviewed.",
                icon: 'info', // ℹ️ Changed from warning to info
                showCancelButton: true,
                confirmButtonText: 'Yes, mark as reviewed',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire({
                        html: '<h6>Processing... Please wait</h6>',
                        showConfirmButton: false
                    });

                    $.ajax({
                        type: "POST",
                        url: "/documents/reviewed",
                        data: {
                            requestID: requestID,
                            _token: @json(csrf_token())
                        },
                        success: function(res) {
                            setTimeout(function() {
                                Swal.fire({
                                    icon: 'success', // 👍 Changed from success to thumbs-up
                                    html: '<h5>Successfully Marked as Reviewed!</h5>'
                                }).then(() => {
                                    window.history.back();
                                });
                            }, 700);
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr.info(
                        'Action is cancelled',
                        'Document remains pending'
                    );
                }
            });
        }

        function approvedRequest(requestID) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-secondary mx-2'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Confirm Approval?',
                text: "This document will be marked as approved.",
                icon: 'info', // ℹ️ Changed from warning to info
                showCancelButton: true,
                confirmButtonText: 'Yes, mark as approved',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire({
                        html: '<h6>Processing... Please wait</h6>',
                        showConfirmButton: false
                    });

                    $.ajax({
                        type: "POST",
                        url: "/documents/approved",
                        data: {
                            requestID: requestID,
                            _token: @json(csrf_token())
                        },
                        success: function(res) {
                            setTimeout(function() {
                                Swal.fire({
                                    icon: 'success', // 👍 Changed from success to thumbs-up
                                    html: '<h5>Successfully Marked as Approved!</h5>'
                                }).then(() => {
                                    window.history.back();
                                });
                            }, 700);
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr.info(
                        'Action is cancelled',
                        'Document remains   pending'
                    );
                }
            });
        }

        function forReview(requestID) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-secondary mx-2'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Confirm Submission?',
                text: "This document will be submitted for Review.",
                icon: 'info', // ℹ️ Changed from warning to info
                showCancelButton: true,
                confirmButtonText: 'Yes, Submit',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire({
                        html: '<h6>Processing... Please wait</h6>',
                        showConfirmButton: false
                    });

                    $.ajax({
                        type: "POST",
                        url: "{{ url('/documents/forReview') }}",
                        data: {
                            requestID: requestID,
                            _token: @json(csrf_token())
                        },
                        success: function(res) {
                            setTimeout(function() {
                                Swal.fire({
                                    icon: 'success', // 👍 Changed from success to thumbs-up
                                    html: '<h5>Successfully Submitted For Review!</h5>'
                                }).then(() => {
                                    window.history.back();
                                });
                            }, 700);
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    toastr.info(
                        'Action is cancelled',
                        'Document pending submission'
                    );
                }
            });
        }
    </script>
@endsection
