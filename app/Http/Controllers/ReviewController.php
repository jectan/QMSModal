<?php

namespace App\Http\Controllers;
use App\Models\DocType;
use App\Models\RequestDocument;
use App\Models\ReviewDocument;
use App\Models\RequestType;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function __construct(SeriesService $series_service) 
    {
        $this->series_service = $series_service;
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
        'requestID' => 'required|integer|exists:requestID',
        'reviewComment' => 'required|string|max:500',
        ]);

        if($request->reviewStatus == 'Approved')
        {
            $requestStatus = 'For Approval';
        }
        else
        {
            $requestStatus = 'For Revision';
        }

        RequestDocument::where('requestID', $request->requestID)
            ->update(['requestStatus' => $requestStatus]);

        $reviewDocument = ReviewDocument::updateOrCreate(['reviewID' => $request->reviewID],
        [
            'requestID' => $request->requestID,
            'reviewComment' => $request->reviewComment,
            'userID' => Auth::id(),
            'reviewDate' => Carbon::now(),
            'reviewStatus' => $request->reviewStatus,                  
        ]);
                        
        return response()->json(['success'=> 'Successfully saved.', 'ReviewDocument' => $reviewDocument]);
    }
}
