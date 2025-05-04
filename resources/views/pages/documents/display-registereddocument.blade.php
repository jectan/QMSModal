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
                    <a href="{{ url('/masterlist') }}" class="btn btn-sm btn-secondary mx-1 btn-icon" data-bs-toggle="tooltip">
                        <span class="material-icons" style="font-size: 20px;">chevron_left</span>
                        <span class="btn-label">Back</span>
                    </a>
                </div>

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
                @if(Auth::user()->role_id == 1 && (in_array($document->requestStatus, ['Registered', 'Obsolete'])))
                    <div class="expandable-button">
                        <button class="btn btn-sm btn-info mx-1 btn-icon" id="editButton" data-bs-toggle="modal" data-bs-target="#request-modal">
                            <span class="material-icons" style="font-size: 20px;">edit</span>
                            <span class="btn-label">Edit</span>
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
                                </div>
                                <div class="col-md-6">
                                    <label for="docTypeID" class="form-label"><strong>Document Type:</strong><span class="require">*</span></label>
                                    <input type="text" class="form-control" id="docType" name="docType" value="{{$document->DocumentType->docTypeDesc}}" readonly>
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
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side: PDF Preview -->
            <div class="col-md-8 mb-3">
                <div class="card">

                    <!-- PDF Viewer -->
                    <div class="card-body text-center">
                        @if($document->requestFile)
                            <iframe id="documentPreview" src="{{ asset('storage/' . $regDoc->docFile) }}" 
                                width="100%" height="700px" style="border: none;">
                            </iframe>
                        @else
                            <p>No File Uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

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

        <!-- Revision History Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card border-warning">
                            <div class="card-header bg-dblue text-white">
                                <strong>Document Revision History</strong>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-body table-responsive">
                                        <table class="table table-striped w-100" id="rh-dt" style="font-size: 14px">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10%">Doc Ref Code</th>
                                                    <th style="width: 10%">Request Type</th>
                                                    <th style="width: 10%">Doc Title</th>
                                                    <th style="width: 10%">Revision No</th>
                                                    <th style="width: 10%">Effectivity Date</th>
                                                    <th style="width: 20%">Reason</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        <input type="hidden" name="requestIDEdit" id="requestIDEdit" value="{{$document->requestID}}">
                        <input type="hidden" name="requestFileOldEdit" id="requestFileOldEdit" value="{{$document->requestFile}}">
                        <input type="hidden" id="docRefCodeEdit2" name="docRefCodeEdit2" value="{{$document->docRefCode}}" readonly required> 
                        <input type="hidden" name="requestStatusEdit" id="requestStatusEdit" value="{{$document->requestStatus}}">

                        <!-- Request/Document Type Dropdown -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="requestType" class="form-label"><strong>Request For:</strong><span
                                class="require">*</span></label>
                                <select name="requestTypeIDEdit" id="requestTypeIDEdit" class="form-control">
                                    <option value="">Select Request</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="docType" class="form-label"><strong>Document Type:</strong><span
                                class="require">*</span></label>
                                <select name="docTypeIDEdit" id="docTypeIDEdit" class="form-control">
                                    <option value="">Select Document Type</option>
                                </select>
                            </div>
                        </div>

                        <!-- DocRefCode and CurrentRevNum -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="docRefCode" class="col-sm-4 control-label">Document Reference Code:<span
                                class="require">*</span></label>
                                <div class="col-sm-12">
                                    
                                    @if($document->requestTypeID == 1)
                                        <input type="text" class="form-control" id="docRefCodeEdit" name="docRefCodeEdit" value="{{$document->docRefCode}}" readonly required> 
                                    @else
                                        <input type="text" class="form-control" id="docRefCodeEdit" name="docRefCodeEdit" value="{{$document->docRefCode}}" required> 
                                    @endif
                                    <span id="docRefCodeErrorEdit" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="currentRevNoEdit" class="col-sm-4 control-label">Current Revision Number:<span
                                class="require">*</span></label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" id="currentRevNoEdit" name="currentRevNoEdit" min="0" value="{{$document->currentRevNo}}" required readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Document Title -->
                        <div class="form-group">
                            <label for="Document Title" class="col-sm-4 control-label">Document Title:<span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="docTitleEdit" name="docTitleEdit" value="{{ $document->docTitle }}" required>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="form-group">
                            <label for="Reason" class="col-sm-4 control-label">Reason/s for the Request:<span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="requestReasonEdit" name="requestReasonEdit" value="{{ $document->requestReason }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="requestFile" class="col-sm-4 control-label">Update Document (PDF Only):</label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" id="documentFileEdit" name="documentFileEdit" accept=".pdf">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info" id="request-btn-save">Save</button>
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

            $('#rh-dt').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthChange: false,
                searching: false,
                ajax: "{{ url('/masterlist/view/revision') }}/{{ $document->docRefCode }}",
                columns: [
                    { data: 'docRefCode', name: 'docRefCode' },
                    { data: 'requestType', name: 'requestType' },
                    { data: 'docTitle', name: 'docTitle' },
                    { data: 'currentRevNo', name: 'currentRevNo' },
                    { data: 'effectivityDate', name: 'effectivityDate' },
                    { data: 'requestReason', name: 'requestReason' }
                ],
                order: [[3, 'desc']],
                language: {
                    emptyTable: 'There are no Revision History yet'
                }
            });

            if ("{{ $isEdit }}" == 1){
                $('#request-modal').modal('toggle');
            }
        });

        $("#cancelEditButton").click(function () {
            // Reload the page after 1 second (optional delay for better UX)
            setTimeout(function () {
                window.location.href = "{{ url('/documents/view/edit') }}/" + {{ $document->requestID }};
            }, 1000); // Adjust time as needed
        });

        //Enable/Disable DocRefCode - Request
        $("#requestTypeIDEdit").change(function () {
            console.log("Change RequestType Request");
            var requestType = $(this).val(); // Get selected value
            var docRefCode = $("#docRefCodeEdit");
            var currentRevNo = $("#currentRevNoEdit");
            var documentType = $("#docTypeIDEdit");
            if (requestType == "1") {
                docRefCode.val("For Assigning").prop("readonly", true).removeAttr("required"); // Clear, disable, and remove required
                currentRevNo.val("0");
                documentType.prop("disabled", false).attr("required", "required"); 
            } else {
                docRefCode.val("").prop("readonly", false).attr("required", "required"); // Enable and make required
                documentType.prop("disabled", true).removeAttr("required");
            }
        });

        //Auto Current Revision Number - REGISTRATION
        $("#docRefCode").change(function () {
            console.log("Change DocRefCode");
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
            console.log("Change DocumentFile");
            var documentFile = this.files[0]; // Get the File object
            var documentPreview = $("#documentPreview");
            documentPreview.attr("src", URL.createObjectURL(documentFile));
        });
        
        // Submit button Edit
        $('#request-form').submit(function (e){
            e.preventDefault();

            let errorField = $("#docRefCodeErrorEdit");
            let errorMessages = [];
            let missingFields = []; // ✅ Declare the variable at the start
            let allowedFileType = ["application/pdf"];
            let docRefCode = $('#docRefCodeEdit').val().trim();
            let currentDocRefCode = $('#docRefCodeEdit2').val().trim();
            let requestTypeID = $('#requestTypeIDEdit').val();

            if (docRefCode === "" || (docRefCode == "For Assigning" && requestTypeID != 1)) {
                errorField.text("Invalid Document Reference Code. Please check again.");
                $("#docRefCodeEdit").addClass("is-invalid");
                return; // Stop form submission
            }

            // Call AJAX validation before proceeding
            checkDuplicateRequest(docRefCode, requestTypeID, currentDocRefCode, function(isValid) {
                if (!isValid) {
                    return; // Stop form submission if invalid
                }

                checkDocRefCode(docRefCode, requestTypeID, function(isValid) {
                    if (!isValid) {
                        return; // Stop form submission if invalid
                    }
                    // Get input values
                    let requestID = $('#requestIDEdit');
                    let currentRevNo = $('#currentRevNoEdit');
                    let docTitle = $('#docTitleEdit');
                    let requestReason = $('#requestReasonEdit');
                    let documentType = $('#docTypeIDEdit');
                    let documentFile = $('#documentFileEdit')[0].files[0];
                    // Clear previous errors
                    $(".is-invalid").removeClass("is-invalid");
                    $(".error-message").remove();

                    // Validation Rules
                    if (!currentRevNo.val()) {
                        isValid = false;
                        missingFields.push("Revision Number");
                        currentRevNo.addClass("is-invalid");
                        currentRevNo.after("<div class='error-message text-danger'>The Revision Number is required.</div>");
                    } else if (isNaN(currentRevNo.val()) || currentRevNo.val() < 0) {
                        isValid = false;
                        errorMessages.push("The Revision Number must be a number & greater than or equal to 0.");
                        currentRevNo.addClass("is-invalid");
                    }

                    if (!docTitle.val()) {
                        isValid = false;
                        missingFields.push("Document Title");
                        docTitle.addClass("is-invalid");
                        docTitle.after("<div class='error-message text-danger'>The Document Title is required.</div>");
                    } else if (docTitle.val().length > 255) {
                        isValid = false;
                        errorMessages.push("The Document Title must be at most 255 characters.");
                        docTitle.addClass("is-invalid");
                    }

                    if (!requestReason.val()) {
                        isValid = false;
                        missingFields.push("Reason for Request");
                        requestReason.addClass("is-invalid");
                        requestReason.after("<div class='error-message text-danger'>The Reason for Request is required.</div>");
                    } else if (requestReason.val().length > 500) {
                        isValid = false;
                        errorMessages.push("The Reason for Request must be at most 500 characters.");
                        requestReason.addClass("is-invalid");
                    }

                    // If validation fails, show a detailed error message
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

                    //re-enable Document Type Select
                    
                    documentType.prop("disabled", false).attr("required", "required"); 

                    // Proceed with form submission via AJAX if all fields are valid
                    let formData = new FormData($('#request-form')[0]);

                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/documents/storeEdit') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (res) {
                            if (res.success) {
                                $("#request-modal").modal('hide');
                                $("#request-form")[0].reset();
                                Swal.fire({
                                    icon: 'success',
                                    title: res.success
                                }).then((result) => {
                                    // Check if the user clicked OK
                                    if (result.isConfirmed) {
                                        window.location.href = "/documents/view/" + $('#requestIDEdit').val();
                                        //window.location.reload(); // Refresh the page
                                    }
                                });
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
        });

        // Submit button event for Review
        $('#review-form').submit(function (e) {
            console.log("Submit Review");
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
                        }).then((result) => { // Added .then() to handle after the alert
                            if (result.isConfirmed) { // Check if the user confirmed (clicked OK)

                                // Clear the input field after successful submission
                                $('#reviewComments').val('');
                                $('#rejectButton').hide();
                                $('#reviewedButton').hide();
                                window.location.href = "{{ url('/documents') }}/";
                            }
                        });
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
            console.log("Submit Approve");
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
                        }).then((result) => { // Added .then() to handle after the alert
                            if (result.isConfirmed) { // Check if the user confirmed (clicked OK)
                                // Clear the input field after successful submission
                                $('#reviewComments').val('');
                                $('#rejectApproveButton').hide();
                                $('#approvedButton').hide();
                                window.location.href = "{{ url('/documents') }}/";
                            }
                        });
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
            console.log("Show Register Modal");
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
            
        //Remove Error in File Upload - REGISTER
        $("#documentFile2").change(function () {
            console.log("Change DocumentFile2");
            var documentFile = $(this).val(); // Get entered value
            $(this).removeClass("is-invalid"); // Remove error styling
            $(this).next('.error-message').remove();
        });

        // Submit Register
        $('#register-form').submit(function (e){
            console.log("Submit Register");
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

        function checkDuplicateRequest(docRefCode, requestTypeID, currentDocRefCode, callback) {
            console.log("checkDuplicateRequest");
            if(requestTypeID != 1){
                $.ajax({
                    url: "{{ url('/check-docDuplicateRequest') }}",
                    type: "GET",
                    data: { docRefCode: docRefCode },
                    success: function (response) {
                        if (response.exists && docRefCode != currentDocRefCode) {
                            $("#docRefCodeErrorEdit").text("A Request with the same Doc Ref Code is already " + response.requestStatus + "!");
                            $("#currentRevNoEdit").val("0"); 
                            $("#docRefCodeEdit").addClass("is-invalid"); // Add error styling
                            callback(false); // Invalid case
                        } else {
                            $("#currentRevNoEdit").val(response.currentRevNo ?? ""); // ✅ Prevent undefined errors
                            $("#docRefCodeEdit").removeClass("is-invalid"); // Remove error styling
                            $("#docRefCodeErrorEdit").text("");
                            callback(true); // Valid case
                        }
                    },
                    error: function () {
                        $("#docRefCodeErrorEdit").text("Error checking document reference code.");
                        $("#docRefCodeEdit").addClass("is-invalid");
                        callback(false);
                    }
                });
            }
            else{
                callback(true);
            }
        }

        //For Modal
        function checkDocRefCodeEdit(docRefCode, requestTypeID, callback) {
            console.log("checkDocRefCodeEdit");
            if(requestTypeID != 1){
                $.ajax({
                    url: "{{ url('/check-docRefCode') }}",
                    type: "GET",
                    data: { docRefCode: docRefCode },
                    success: function (response) {
                        if (response.exists) {
                            $("#currentRevNoEdit").val(response.currentRevNo ?? ""); // ✅ Prevent undefined errors
                            $("#docTypeIDEdit").val(response.docTypeID ?? "").trigger('change');; // ✅ Prevent undefined errors
                            $("#docRefCodeEdit").removeClass("is-invalid"); // Remove error styling
                            $("#docRefCodeErrorEdit").text("");
                            callback(true); // Valid case
                        } else {
                            $("#docRefCodeErrorEdit").text("Document Reference Code not found!");
                            $("#currentRevNoEdit").val("0"); 
                            $("#docRefCodeEdit").addClass("is-invalid"); // Add error styling
                            callback(false); // Invalid case
                        }
                    },
                    error: function () {
                        $("#docRefCodeErrorEdit").text("Error checking document reference code.");
                        $("#docRefCodeEdit").addClass("is-invalid");
                        callback(false);
                    }
                });
            }
            else{
                callback(true);
            }
        }

        // Define loadRequestType before using it
        function loadRequestType(selectedrequestTypeID = null, callback = null) {
            console.log("loadRequestType");
            $.ajax({
                url: "/get-requestType",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    console.log("Response received:", response);

                    if (response.data && response.data.length > 0) {
                        let options = '';
                        let requestTypeID = "{{ $document->requestTypeID }}";


                        response.data.forEach(function (requestType) {
                            let selected = (requestType.requestTypeID == requestTypeID) ? 'selected' : ''; // Check if it matches
                            options += `<option value="${requestType.requestTypeID}" ${selected}>${requestType.requestTypeDesc}</option>`;
                        });

                        $("#requestTypeIDEdit").html(options);

                        // Execute callback after setting dropdown options
                        if (callback) {
                            callback();
                        }
                    } else {
                        $("#requestTypeIDEdit").html('<option value="">Please Check Libraries</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        }

        // Define loadDocType before using it
        function loadDocType(selecteddocTypeID = null, callback = null) {
            console.log("loadDocType");
            $.ajax({
                url: "/get-docType",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    console.log("Response received:", response);

                    if (response.data && response.data.length > 0) {
                        let options = '';
                        let docTypeID = "{{ $document->docTypeID }}";

                        response.data.forEach(function (docType) {
                            let selected = (docType.docTypeID == docTypeID) ? 'selected' : ''; // Check if it matches
                            options += `<option value="${docType.docTypeID}" ${selected}>${docType.docTypeDesc}</option>`;
                        });

                        $("#docTypeIDEdit").html(options);

                        // Execute callback after setting dropdown options
                        if (callback) {
                            callback();
                        }
                    } else {
                        $("#docTypeIDEdit").html('<option value="">Please Check Libraries</option>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        }

        function checkDocRefCode(docRefCode, requestTypeID, callback) {
            console.log("checkDocRefCode");
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
                                        window.location.href = "{{ url('/documents') }}/";
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
                                    window.location.href = "{{ url('/documents') }}/";
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
                                    window.location.href = "{{ url('/documents') }}/";
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
                                    window.location.href = "{{ url('/documents') }}/";
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

        // Load dropdown when modal opens
        $("#request-modal").on("show.bs.modal", function () {
            loadRequestType();
            loadDocType();
        });
    </script>
@endsection