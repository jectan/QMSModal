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
            <div class="expandable-button">
                <a href="{{ url('/documents') }}" class="btn btn-sm btn-secondary mx-1 btn-icon" data-bs-toggle="tooltip">
                    <span class="material-icons" style="font-size: 20px;">chevron_left</span>
                    <span class="btn-label">Back</span>
                </a>
            </div>
            <div class="expandable-button">
                <button type="button" class="btn btn-sm btn-success mx-1 btn-icon" href="javascript:void(0)"  onClick="reviewedRequest('{{ $document->requestID }}')">
                    <span class="material-icons" style="font-size: 20px;">check_circle</span>
                    <span class="btn-label">Reviewed</span>
                </button>
            </div>
            <div class="expandable-button">
                <button type="button" class="btn btn-sm btn-warning mx-1 btn-icon" data-bs-toggle="collapse" data-bs-target="#commentSection" title="For Revision" >
                    <span class="material-icons" style="font-size: 20px;">comment</span>
                    <span class="btn-label">For Revision</span>
                </button>
            </div>
            <div class="expandable-button">
                <button class="btn btn-sm btn-info mx-1 btn-icon" href="javascript:void(0)" onClick="' . $onClickFunction . '">
                    <span class="material-icons" style="font-size: 20px;">edit</span>
                    <span class="btn-label">Edit</span>
                </button>
            </div>
            <div class="expandable-button">
                <button class="btn btn-sm btn-danger btn-icon" href="javascript:void(0)" onClick="cancelRequest('{{ $document->requestID }}')">
                    <span class="material-icons" style="font-size: 20px;">delete</span>
                    <span class="btn-label">Delete</span>
                </button>
            </div>
    


        </div>
    </div>

    <!-- Content Section -->
    <div class="row">
        <!-- Left Side: Document Details -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                <form action="{{ url('/documents/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="requestID" id="requestID" value="{{ $document->requestID }}">
                        <input type="hidden" name="requestFileOld" id="requestFileOld" value="{{ $document->requestFile }}">

                        <!-- Request Type & Document Type -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="requestTypeID" class="form-label"><strong>Request For:</strong><span class="require">*</span></label>
                                <select name="requestTypeID" id="requestTypeID" class="form-control" readonly>
                                    <option value="">Select Request</option>
                                        @foreach($requestType as $row)
                                            @if($row->requestTypeID == $document->requestTypeID)
                                                <option value="{{ $row->requestTypeID }}" selected>{{ $row->requestTypeDesc }}</option>
                                            @else
                                                <option value="{{ $row->requestTypeID }}">{{ $row->requestTypeDesc }}</option>
                                            @endif
                                        @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="docTypeID" class="form-label"><strong>Document Type:</strong><span class="require">*</span></label>
                                <select name="docTypeID" id="docTypeID" class="form-control" readonly>
                                    @foreach($docType as $row)
                                        @if($row->docTypeID == $document->docTypeID)
                                        <option value="{{ $row->docTypeID }}" selected>{{ $row->docTypeDesc }}</option>
                                        @else
                                            <option value="{{ $row->docTypeID }}">{{ $row->docTypeDesc }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Document Reference Code & Current Revision Number -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="docRefCode" class="form-label"><strong>Document Reference Code:</strong></label>
                                <input type="text" class="form-control" id="docRefCode" name="docRefCode" value="{{$document->docRefCode}}" readonly>
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

                        <!-- File Upload -->
                        <div class="form-group">
                            <label for="requestFile" class="col-sm-4 control-label">Upload Document (PDF Only):<span class="require">*</span></label>
                            <div class="col-sm-12">
                                <!-- Display existing file -->
                                @if ($document->requestFile)
                                    <p>Current File: <a href="{{ asset('storage/' . $document->requestFile) }}" target="_blank">{{ basename($document->requestFile) }}</a></p>
                                @endif

                                <!-- Allow uploading a new file -->
                                <input type="file" class="form-control" id="documentFile" name="documentFile" accept=".pdf" value="{{ basename($document->requestFile) }}" hidden>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group text-center mt-3">
                            <!-- <button type="submit" class="btn btn-primary">Submit Request</button>
                            <a href="{{ url('/documents') }}" class="btn btn-secondary">Cancel</a> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: PDF Preview -->
        <div class="col-md-8 mb-3">
            <div class="card">
                <!-- Collapsible Comment Section -->
                <div class="collapse" id="commentSection">
                    <div class="card border-warning m-3">
                        <div class="card-header bg-warning text-white">
                            <strong>Comments for Revision</strong>
                        </div>
                        <div class="card-body">
                            <form action="javascript:void(0)" id="review-form" name="review-form" class="form-horizontal" method="POST">
                                @csrf
                                <input type="hidden" name="requestID" id="requestID" value="{{ $document->requestID }}">
                                <textarea class="form-control" rows="3" id="reviewComment" name="reviewComment" placeholder="Add your comment..."></textarea>
                                <button class="btn btn-sm btn-warning mt-2">Submit Comments</button>
                                <button type="button" class="btn btn-sm btn-secondary mt-2" data-bs-toggle="collapse" data-bs-target="#commentSection">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- PDF Viewer -->
                <div class="card-body text-center">
                    @if($document->requestFile)
                        <iframe src="{{ asset('storage/' . $document->requestFile) }}" 
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
                                <div class="card">
                                    <div class="card-body table-responsive">
                                        <table class="table table-striped w-100" id="review-dt" style="font-size: 14px">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10%">No</th>
                                                    <th style="width: 20%">Comments</th>
                                                    <th style="width: 15%">Action</th>
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

        $(document).ready(function() {
            $('#review-dt').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/documents/view/review') }}/{{ $document->requestID }}",
                columns: [
                    { data: 'reviewID', name: 'reviewID' },
                    { data: 'reviewComment', name: 'reviewComment' },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']]
            });
        });

        // Clear reviewComment field on page refresh
        $('#reviewComment').val('');

        // Submit button event
        $('#review-form').submit(function (e) {
            e.preventDefault();

            let isValid = true;
            let errorMessages = [];
            let missingFields = [];

            // Clear previous errors
            $(".is-invalid").removeClass("is-invalid");
            $(".error-message").remove();

            // Get input values
            let reviewComment = $('#reviewComment');

            // Validation Rules
            if (!reviewComment.val().trim()) {
                isValid = false;
                missingFields.push("Review Comment");
                reviewComment.addClass("is-invalid");
                reviewComment.after("<div class='error-message text-danger'>The review comment is required.</div>");
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
                        $('#reviewComment').val('');

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
    });

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
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-secondary'
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
</script>
@endsection
