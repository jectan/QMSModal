@extends('layouts.app',[
    'page' => 'Manage Documents',
    'title' => 'Manage Documents'
])

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Document Details</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Request ID</th>
                    <td>{{ $document->requestID }}</td>
                </tr>
                <tr>
                    <th>Request Type</th>
                    <td>{{ $document->requestTypeID }}</td>
                </tr>
                <tr>
                    <th>Document Type</th>
                    <td>{{ $document->docTypeID }}</td>
                </tr>
                <tr>
                    <th>Document Reference Code</th>
                    <td>{{ $document->docRefCode }}</td>
                </tr>
                <tr>
                    <th>Current Revision Number</th>
                    <td>{{ $document->currentRevNo }}</td>
                </tr>
                <tr>
                    <th>Document Title</th>
                    <td>{{ $document->docTitle }}</td>
                </tr>
                <tr>
                    <th>Request Reason</th>
                    <td>{{ $document->requestReason }}</td>
                </tr>
                <tr>
                    <th>Requested By (User ID)</th>
                    <td>{{ $document->userID }}</td>
                </tr>
                <tr>
                    <th>Request File</th>
                    <td>
                        @if($document->requestFile)
                            <a href="{{ asset('storage/' . $document->requestFile) }}" target="_blank">View File</a>
                        @else
                            No File Uploaded
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Request Date</th>
                    <td>{{ $document->requestDate }}</td>
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
            </table>
            
            <a href="{{ url('/documents') }}" class="btn btn-secondary mt-3">Back</a>

        </div>
    </div>
</div>
@endsection