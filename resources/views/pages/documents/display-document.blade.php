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
                <button type="button" class="btn btn-sm btn-secondary mx-1 btn-icon" href="{{ url('/documents') }}" data-bs-toggle="tooltip" title="For Approval">
                    <span class="material-icons" style="font-size: 20px;">chevron_left</span>
                    <span class="btn-label">Back</span>
                </button>
            </div>
            <div class="expandable-button">
                <button type="button" class="btn btn-sm btn-success mx-1 btn-icon" data-bs-toggle="tooltip" title="For Approval">
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
                <button class="btn btn-sm btn-danger btn-icon" href="javascript:void(0)" onClick="cancelRequest(' . $row->requestID . ')">
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
                    <table class="table table-bordered">
                        <tr>
                            <th>Request Type</th>
                            <td>{{ $document->requestTypeID }}</td>
                        </tr>
                        <tr>
                            <th>Document Type</th>
                            <td>{{ $document->docTypeID }}</td>
                        </tr>
                        <tr>
                            <th>Document Title</th>
                            <td>{{ $document->docTitle }}</td>
                        </tr>
                        <tr>
                            <th>Document Reference Code</th>
                            <td>{{ $document->docRefCode }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($document->requestStatus == 'Cancelled')
                                    <span class="badge bg-danger">{{ $document->requestStatus }}</span>
                                @elseif($document->requestStatus == 'For Review')
                                    <span class="badge bg-primary">{{ $document->requestStatus }}</span>
                                @elseif($document->requestStatus == 'For Approval')
                                    <span class="badge bg-warning text-dark">{{ $document->requestStatus }}</span>
                                @else
                                    <span class="badge bg-success">{{ $document->requestStatus }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Current Revision Number</th>
                            <td>{{ $document->currentRevNo }}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Request Reason</th>
                        </tr>
                        <tr>
                            <td colspan="2">{{ $document->requestReason }}</td>
                        </tr>
                        <tr>
                            <th>Requested By (User ID)</th>
                            <td>{{ $document->userID }}</td>
                        </tr>
                        <tr>
                            <th>Request Date</th>
                            <td>{{ $document->requestDate }}</td>
                        </tr>
                    </table>
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
                            <textarea class="form-control" rows="3" placeholder="Add your comment..."></textarea>
                            <button class="btn btn-sm btn-warning mt-2">Submit Comments</button>
                            <button class="btn btn-sm btn-secondary mt-2" data-bs-toggle="collapse" data-bs-target="#commentSection">Cancel</button>
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
                        <!-- You can add content here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
