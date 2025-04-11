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
                    @if(in_array(Auth::user()->role_id, [1, 4, 5]))
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group mr-2">
                                <div class="container-login100-form-btn">
                                    <div class="wrap-login100-form-btn">
                                        <div class="login100-form-bgbtn"></div>
                                        <button class="login100-form-btn" data-bs-toggle="modal" data-bs-target="#request-modal">
                                            <i class="fa fa-plus pr-2"></i> New Request
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- TABS -->
            <div class="card card-primary card-tabs" style="margin: 10px">
                <div class="card-header p-0 pt-1 bg-dblue">
                    <ul class="nav nav-tabs" id="settings-tab" role="tablist">

                        <!-- Only Allow Admin, DMT, and User to View -->
                        @if(in_array(Auth::user()->role_id, [1, 2, 3, 4, 5]))
                            <li class="nav-item">
                                <a class="nav-link {{ in_array(Auth::user()->role->id, [1, 5]) ? 'active' : 'null' }}" id="request" data-toggle="pill" href="#requested-document" role="tab" aria-controls="requested-document" aria-selected="true">Requested</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{Auth::user()->role->id == 4 ? 'active' : 'null'}}" id="review" data-toggle="pill" href="#review-document" role="tab" aria-controls="review-document" aria-selected="false">For Review</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{Auth::user()->role->id == 3 ? 'active' : 'null'}}" id="approval" data-toggle="pill" href="#approval-document" role="tab" aria-controls="approval-document" aria-selected="false">For Approval</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{Auth::user()->role->id == 2 ? 'active' : 'null'}}" id="registration" data-toggle="pill" href="#registration-document" role="tab" aria-controls="registration-document" aria-selected="false">For Registration</a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="set-content">
                        @if(!in_array(Auth::user()->role_id, [0]))
                            <div class="tab-pane fade {{ in_array(Auth::user()->role->id, [1, 5]) ? 'show active' : 'null' }} " id="requested-document" role="tabpanel" aria-labelledby="request-tab">
                                @include('pages.documents.requested-document')
                            </div>
                        @endif
                        <div class="tab-pane fade {{Auth::user()->role->id == 4 ? 'show active' : 'null'}}" id="review-document" role="tabpanel" aria-labelledby="review-tab">
                            @include('pages.documents.review-document')
                        </div>
                        <div class="tab-pane fade {{Auth::user()->role->id == 3 ? 'show active' : 'null'}}" id="approval-document" role="tabpanel" aria-labelledby="approval-tab">
                            @include('pages.documents.approval-document')
                        </div>
                        <div class="tab-pane fade {{Auth::user()->role->id == 2 ? 'show active' : 'null'}}" id="registration-document" role="tabpanel" aria-labelledby="registration-tab">
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
                        <input type="hidden" name="requestFileOld" id="requestFileOld">

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
                                <label for="docRefCode" class="col-sm-4 control-label">Document Reference Code:<span
                                class="require">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="docRefCode" name="docRefCode" value="For Assigning" readonly required>
                                    <span id="docRefCodeError" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="currentRevNo" class="col-sm-4 control-label">Current Revision Number:<span
                                class="require">*</span></label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" id="currentRevNo" name="currentRevNo" min="0" value="0" required readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Document Title -->
                        <div class="form-group">
                            <label for="Document Title" class="col-sm-4 control-label">Document Title:<span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="docTitle" name="docTitle"
                                    placeholder="Enter Document Title" required>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="form-group">
                            <label for="Reason" class="col-sm-4 control-label">Reason/s for the Request:<span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="requestReason" name="requestReason"
                                    placeholder="Enter Reason for Request" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="requestFile" class="col-sm-4 control-label">Upload Document (PDF Only):<span
                                    class="require">*</span></label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" id="documentFile" name="documentFile" accept=".pdf" required>
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
                }
            });

            //Enable/Disable DocRefCode
            $("#requestTypeID").change(function () {
                var requestType = $(this).val(); // Get selected value
                var docRefCode = $("#docRefCode");
                var currentRevNo = $("#currentRevNo");
                var documentType = $("#docTypeID");
                if (requestType == "1") {
                    docRefCode.val("For Assigning").prop("readonly", true).removeAttr("required"); // Clear, disable, and remove required
                    currentRevNo.val("0");
                    documentType.prop("disabled", false).attr("required", "required"); 
                } else {
                    docRefCode.val("").prop("readonly", false).attr("required", "required"); // Enable and make required
                    documentType.prop("disabled", true).removeAttr("required");
                }
            });
        });

        // Submit button
        $('#request-form').submit(function (e){
            e.preventDefault();

            let errorField = $("#docRefCodeError");
            let errorMessages = [];
            let missingFields = []; // ✅ Declare the variable at the start
            //let fileSizeLimit = 2 * 1024 * 1024; // 2MB in bytes
            let allowedFileType = ["application/pdf"];

            let docRefCode = $('#docRefCode').val().trim();
            let requestTypeID = $('#requestTypeID').val();

            if (docRefCode === "") {
                errorField.text("Invalid Document Reference Code. Please check again.");
                $("#docRefCode").addClass("is-invalid");
                return; // Stop form submission
            }

            // Call AJAX validation before proceeding
            checkDuplicateRequest(docRefCode, requestTypeID, function(isValid) {
                if (!isValid) {
                    return; // Stop form submission if invalid
                }

                checkDocRefCode(docRefCode, requestTypeID, function(isValid) {
                    if (!isValid) {
                        return; // Stop form submission if invalid
                    }

                    // Get input values
                    let requestID = $('#requestID');
                    let currentRevNo = $('#currentRevNo');
                    let docTitle = $('#docTitle');
                    let requestReason = $('#requestReason');
                    let documentType = $('#docTypeID');
                    let documentFile = $('#documentFile')[0].files[0];
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

                    if(!requestID.val()){
                        if (!documentFile) {
                            isValid = false;
                            missingFields.push("Uploaded Document");
                            $('#documentFile').addClass("is-invalid");
                            $('#documentFile').after("<div class='error-message text-danger'>The Uploaded Document is required.</div>");
                        } else if (!allowedFileType.includes(documentFile.type)) {
                            isValid = false;
                            errorMessages.push("The Uploaded Document must be a PDF file.");
                            $('#documentFile').addClass("is-invalid");
                        }
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

                                // Refresh DataTable after saving
                                $('#request-dt').DataTable().ajax.reload(null, false);
                                $('#review-dt').DataTable().ajax.reload(null, false);
                                $('#approval-dt').DataTable().ajax.reload(null, false);
                                $('#registration-dt').DataTable().ajax.reload(null, false);

                                //$("#request-btn-save").html('Save Changes');
                                //$("#request-btn-save").attr("disabled", false);
                                $("#request-form")[0].reset();
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

        function checkDuplicateRequest(docRefCode, requestTypeID, callback) {
            if(requestTypeID != 1){
                $.ajax({
                    url: "{{ url('/check-docDuplicateRequest') }}",
                    type: "GET",
                    data: { docRefCode: docRefCode },
                    success: function (response) {
                        if (response.exists) {
                            $("#docRefCodeError").text("A Request with the same Doc Ref Code is already " + response.requestStatus + "!");
                            $("#currentRevNo").val("0"); 
                            $("#docRefCode").addClass("is-invalid"); // Add error styling
                            callback(false); // Invalid case
                        } else {
                            $("#currentRevNo").val(response.currentRevNo ?? ""); // ✅ Prevent undefined errors
                            $("#docRefCode").removeClass("is-invalid"); // Remove error styling
                            $("#docRefCodeError").text("");
                            callback(true); // Valid case
                        }
                    },
                    error: function () {
                        $("#docRefCodeError").text("Error checking document reference code.");
                        $("#docRefCode").addClass("is-invalid");
                        callback(false);
                    }
                });
            }
            else{
                callback(true);
            }
        }

        function checkDocRefCode(docRefCode, requestTypeID, callback) {
            if(requestTypeID != 1){
                $.ajax({
                    url: "{{ url('/check-docRefCode') }}",
                    type: "GET",
                    data: { docRefCode: docRefCode },
                    success: function (response) {
                        if (response.exists) {
                            $("#currentRevNo").val(response.currentRevNo ?? ""); // ✅ Prevent undefined errors
                            $("#docTypeID").val(response.docTypeID ?? "").trigger('change');; // ✅ Prevent undefined errors
                            $("#docRefCode").removeClass("is-invalid"); // Remove error styling
                            $("#docRefCodeError").text("");
                            callback(true); // Valid case
                        } else {
                            $("#docRefCodeError").text("Document Reference Code not found!");
                            $("#currentRevNo").val("0"); 
                            $("#docRefCode").addClass("is-invalid"); // Add error styling
                            callback(false); // Invalid case
                        }
                    },
                    error: function () {
                        $("#docRefCodeError").text("Error checking document reference code.");
                        $("#docRefCode").addClass("is-invalid");
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

        // displayRequest function
        function displayRequest(requestID) {
            window.location.href = "{{ url('/documents/view') }}/" + requestID;
        }

        // editRequest function
        function editRequest(requestID){
            window.location.href = "{{ url('/documents/view/edit') }}/" + requestID;
        }

        /* function editRequest(requestID) {
            $.ajax({
                type: "POST",
                url: "{{ url('/documents/edit') }}",
                data: { requestID: requestID, 
                    _token: "{{ csrf_token() }}"},
                dataType: 'json',
                success: function(res) {
                    let requestStatus = 'Requested';
                    if(res.requestStatus === 'For Review' || res.requestStatus === 'For Approval'){
                        Swal.fire({
                            icon: 'info',
                            title: 'Document cannot be edited while being checked!'
                        });
                    }
                    else if(res.requestStatus === 'For Registration'){
                        Swal.fire({
                            icon: 'info',
                            title: 'Document is already for Registration!'
                        });
                    }
                    else{
                        
                        if(res.requestStatus === 'For Revision'){
                            let requestStatus = 'For Review';
                        }
                        else if(res.requestStatus === 'For Revision (Approval)'){
                            let requestStatus = 'For Approval';
                        }

                        $('#request-modal-title').html("Request Document");
                        $('#request-modal').modal('show');
                        $('#requestID').val(res.requestID);
                        $('#docRefCode').val(res.docRefCode);
                        $('#currentRevNo').val(res.currentRevNo);
                        $('#docTitle').val(res.docTitle);
                        $('#requestReason').val(res.requestReason);
                        $('#requestFileOld').val(res.requestFile);
                        $('#requestStatus').val(requestStatus);

                        // Load data first, then set the selected value in a callback
                        loadRequestType(res.requestTypeID, function () {
                            $("#requestTypeID").val(res.requestTypeID).change(); // Ensure the correct selection
                        });
                        
                        loadDocType(res.docTypeID, function () {
                            $("#docTypeID").val(res.docTypeID).change(); // Ensure the correct selection
                        });

                        // Refresh DataTable immediately after saving
                        $('#request-dt').DataTable().ajax.reload(null, false);
                        $('#review-dt').DataTable().ajax.reload(null, false);
                        $('#approval-dt').DataTable().ajax.reload(null, false);
                        $('#registration-dt').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        } */

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