@extends('layouts.app', [
    'page' => 'Document Request',
    'title' => 'Submit Document Request'
])

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Submit Document Request</h2>
            <!-- Action Buttons -->
            <div class="d-flex">
                <a href="{{ url('/documents') }}" class="btn btn-secondary mx-1">Back</a>
                <button type="button" class="btn btn-sm btn-success mx-1" data-bs-toggle="tooltip" title="For Approval">
                    <span class="material-icons" style="font-size: 20px;">check_circle</span>
                </button>
                <button type="button" class="btn btn-sm btn-warning mx-1" data-bs-toggle="collapse" data-bs-target="#commentSection" title="For Revision">
                    <span class="material-icons" style="font-size: 20px;">comment</span>
                </button>
                <button class="btn btn-sm btn-info mx-1" href="javascript:void(0)" onClick="' . $onClickFunction . '">
                    <span class="material-icons" style="font-size: 20px;">edit</span>
                </button>
                <button class="btn btn-sm btn-danger mx-1" href="javascript:void(0)" onClick="cancelRequest({{ $document->requestID }})">
                    <span class="material-icons" style="font-size: 20px;">delete</span>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Left Side: Form Fields -->
                <div class="col-md-6">
                    <form action="{{ url('/documents/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="requestID" id="requestID" value="{{ $document->requestID }}">
                        <input type="hidden" name="requestFileOld" id="requestFileOld" value="{{ $document->requestFile }}">

                        <!-- Request Type & Document Type -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="requestTypeID" class="form-label"><strong>Request For:</strong><span class="require">*</span></label>
                                <select name="requestTypeID" id="requestTypeID" class="form-control">
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
                                <select name="docTypeID" id="docTypeID" class="form-control">
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
                                <input type="text" class="form-control" id="docRefCode" name="docRefCode" value="{{$document->docRefCode}}">
                            </div>
                            <div class="col-md-6">
                                <label for="currentRevNo" class="form-label"><strong>Current Revision Number:</strong><span class="require">*</span></label>
                                <input type="number" class="form-control" id="currentRevNo" name="currentRevNo" value="{{ $document->currentRevNo }}" min="0" required>
                            </div>
                        </div>

                        <!-- Document Title -->
                        <div class="form-group">
                            <label for="docTitle" class="form-label"><strong>Document Title:</strong><span class="require">*</span></label>
                            <input type="text" class="form-control" id="docTitle" name="docTitle" value="{{ $document->docTitle }}" required>
                        </div>

                        <!-- Reason for Request -->
                        <div class="form-group">
                            <label for="requestReason" class="form-label"><strong>Reason/s for the Request:</strong><span class="require">*</span></label>
                            <input type="text" class="form-control" id="requestReason" name="requestReason" value="{{ $document->requestReason }}" >
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
                                <input type="file" class="form-control" id="documentFile" name="documentFile" accept=".pdf" value="{{ basename($document->requestFile) }}">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                            <a href="{{ url('/documents') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>

                <!-- Right Side: File Preview -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>File Preview</h4>
                        </div>
                        <div class="card-body text-center">
                            @if($document->requestFile)
                                <iframe src="{{ asset('storage/' . $document->requestFile) }}" 
                                    width="100%" height="500px" style="border: none;">
                                </iframe>
                            @else
                                <p>No File Uploaded</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collapsible Comment Section -->
            <div class="collapse mt-3" id="commentSection">
                <div class="card border-warning">
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
        </div>
    </div> 
</div>

<script>
    function previewFile() {
        const file = document.getElementById('documentFile').files[0];
        const preview = document.getElementById('filePreview');
        const message = document.getElementById('noFileMessage');

        if (file) {
            const fileURL = URL.createObjectURL(file);
            preview.src = fileURL;
            preview.style.display = "block";
            message.style.display = "none";
        } else {
            preview.style.display = "none";
            message.style.display = "block";
        }
    }
</script>
@endsection