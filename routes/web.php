<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CallerTypeController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DocTypeController;
use App\Http\Controllers\RequestTypeController;
use App\Http\Controllers\CallerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FeedbacktrackController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardV2Controller;
use App\Http\Controllers\MasterlistController;


    Route::any('slug', function()
    {
        return view('auth.page404');
    });

//About Us
Route::get('/aboutus', [App\Http\Controllers\AboutController::class, 'index']);

//Masterlist
Route::get('/masterlist', [MasterlistController::class, 'index'])->name('masterlist.index');
Route::get('/masterlist/data-request/{dataTable}', [MasterlistController::class, 'getDataRequest']); //called by masterlist data tables
Route::get('/masterlist/computeTotal',[MasterlistController::class, 'documentTally']);

Route::get('/', [FeedbackController::class, 'publichome']);
Route::get('/about-us', [FeedbackController::class, 'about']);


Route::get('/login',[AuthController::class, 'login'])->name('login');
Route::post('/login',[AuthController::class, 'authenticate']);

//tracks
Route::get('/tracks', [PublicController::class, 'index']);
Route::get('/tracks/search/{id}', [PublicController::class, 'searchticket']);
Route::post('/home/track-view',[PublicController::class, 'showTicket']);
Route::get('/tracks/view', [PublicController::class, 'view']);

//feedbacktrack
Route::get('/feedbacktracks', [FeedbackTrackController::class, 'index']);
Route::get('/feedbacktracks/search/{id}', [FeedbackTrackController::class, 'searchfeedbackticket']);
Route::post('/feedback',[FeedbackTrackController::class, 'showFeedback']);
Route::get('/feedbacktracks/view', [FeedbackTrackController::class, 'view']);
Route::post('/feedback/save',[FeedbackTrackController::class, 'savefeedback']);

//Feedback
Route::get('/feedback', [FeedbackController::class, 'index']);
Route::get('/feedback/rating', [FeedbackController::class, 'rate']);
Route::get('/e-sumbong-form', [FeedbackController::class, 'showform']);
Route::post('/home/save-form', [FeedbackController::class, 'saveForm']);


//closing ticket administrator and admin staff
Route::group(['middleware' => 'role:1,3'], function (){
Route::get('/documents/close/{id}', [DocumentController::class, 'closeticket']);
});

//Document Tally
Route::get('/documents/computeTotal',[DocumentController::class, 'documentTally']);
     
Route::get('logout',[AuthController::class, 'logout'])->name('logout');
Route::get('/setpassword', [AuthController::class, 'setPassword']);
Route::post('/setpassword', [AuthController::class, 'storePassword']);
//dashboard
Route::get('/dashboard/computeTotal',[DashboardV2Controller::class, 'documentTally']);
Route::get('/dashboard', [DashboardV2Controller::class, 'index']);
Route::get('/dashboard/data-request/{dataTable}', [DashboardV2Controller::class, 'getDataRequest']); //called by dashboard data tables

    Route::group(['middleware' => 'role:1'], function (){
    //Accounts (CRUD: Staff, User, UserRole)
Route::get('/accounts',[AccountController::class, 'index']);
Route::get('/accounts/create',[AccountController::class, 'create']);
Route::post('/accounts',[AccountController::class, 'store']);
Route::get('/accounts/{id}',[AccountController::class, 'show']);
Route::post('/accounts/update',[AccountController::class, 'update']);
Route::delete('/accounts/delete/{id}',[AccountController::class, 'destroy']);
Route::post('/accounts/users/deactivate',[AccountController::class, 'deactivate']);
Route::post('/accounts/users/status',[AccountController::class, 'status']);
Route::get('/accounts/users/reset-password/{id}',[AccountController::class, 'resetPassword']);
Route::get('/accounts/user-roles/{id}',[AccountController::class, 'getUserRole']);
Route::post('/accounts/user-roles/add',[AccountController::class, 'addUserRole']);
Route::delete('/accounts/user-roles/remove/{id}',[AccountController::class, 'removeUserRole']);
  
//Document
Route::get('/documents/create', [DocumentController::class, 'create']);
Route::get('/documents/view/{id}', [DocumentController::class, 'view']);
Route::post('/documents/cancel', [DocumentController::class, 'cancel']);
Route::delete('/documents/delete/{id}', [DocumentController::class, 'destroy']);
Route::post('/documents/assign/add',[DocumentController::class, 'assignedOffice']);
Route::get('/documents/show-assign/{id}',[DocumentController::class, 'getOffice']);

//Roles
Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles/store', [RoleController::class, 'store']);
Route::post('/roles/edit', [RoleController::class, 'edit']);
Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy']);

//Division
Route::get('/divisions', [DivisionController::class, 'index']);
Route::post('/divisions/store', [DivisionController::class, 'store']);
Route::post('/divisions/update', [DivisionController::class, 'edit']);
Route::delete('/divisions/delete/{id}', [DivisionController::class, 'destroy']);

//Unit
Route::get('/units', [UnitController::class, 'index']);
Route::get('/get-divisions', [UnitController::class, 'getDivisions']);
Route::post('/units/store', [UnitController::class, 'store']);
Route::post('/units/update', [UnitController::class, 'edit']);
Route::delete('/units/delete/{id}', [UnitController::class, 'destroy']);

//DocType
Route::get('/docTypes', [DocTypeController::class, 'index']);
Route::post('/docTypes/store', [DocTypeController::class, 'store']);
Route::post('/docTypes/update', [DocTypeController::class, 'edit']);
Route::delete('/docTypes/delete/{id}', [DocTypeController::class, 'destroy']);

//RequestType
Route::get('/requestTypes', [RequestTypeController::class, 'index']);
Route::post('/requestTypes/store', [RequestTypeController::class, 'store']);
Route::post('/requestTypes/update', [RequestTypeController::class, 'edit']);
Route::delete('/requestTypes/delete/{id}', [RequestTypeController::class, 'destroy']);
});

Route::group(['middleware' => 'role:1,2'], function (){
//Caller
Route::get('/caller', [CallerController::class, 'index']);
Route::get('/caller/create', [CallerController::class, 'create']);
Route::post('/caller/store', [CallerController::class, 'store']);
Route::get('/caller/{id}', [CallerController::class, 'show']);
Route::get('/caller/view/{id}', [CallerController::class, 'view']);
Route::post('/caller/update', [CallerController::class, 'update']);
Route::delete('/caller/delete/{id}', [CallerController::class, 'destroy']);
Route::get('/feedback',[FeedbackController::class, 'index']);
});

    // Admin staffS
    Route::group(['middleware' => 'role:1,2,3,4,5'], function (){
//Documents 
Route::get('/documents', [DocumentController::class, 'index']);
Route::post('/documents/store', [DocumentController::class, 'store']); //called from index
Route::post('/documents/edit', [DocumentController::class, 'edit']); //called from index
Route::post('/documents/storeEdit', [DocumentController::class, 'storeEdit']); //called from display-document
Route::get('/documents/view/review/{id}', [DocumentController::class, 'getReview']); //called from display-document
Route::get('/documents/view/review2/{id}', [DocumentController::class, 'getApprove']); //called from display-document
Route::post('/documents/storeReview', [DocumentController::class, 'storeReview']); //called from display-document
Route::post('/documents/reviewed', [DocumentController::class, 'reviewed']); //called from display-document
Route::post('/documents/storeApprove', [DocumentController::class, 'storeApprove']); //called from display-document
Route::post('/documents/approved', [DocumentController::class, 'approved']); //called from display-document
Route::post('/documents/forReview', [DocumentController::class, 'forReview']); //called from display-document
Route::post('/documents/register', [DocumentController::class, 'register']); //called from display-document
Route::get('/documents/view/{id}', [DocumentController::class, 'view']); //called from display-document
Route::get('/documents/view/edit/{id}', [DocumentController::class, 'viewEdit']); //called from display-document
Route::get('/documents/data-request{status?}', [DocumentController::class, 'getDataRequest'])->name('documents.data-request'); //called by tabs
Route::post('/documents/cancel', [DocumentController::class, 'cancel']); //This is it!
Route::get('/get-requestType', [DocumentController::class, 'getRequestType']);
Route::get('/get-docType', [DocumentController::class, 'getDocType']);
Route::get('/check-docRefCode', [DocumentController::class, 'checkDocRefCode']); //called from index to check if exist
Route::get('/documents/show/{id}', [DocumentController::class, 'show']); //delete this soon
Route::post('/documents/update', [DocumentController::class, 'update']); //delete this soon
Route::get('/documents/test/{id}', [DocumentController::class, 'test']); // delete this soon
Route::post('/documents/assign/add',[DocumentController::class, 'assignedOffice']); // delete this soon
Route::get('/documents/show-assign/{id}',[DocumentController::class, 'getOffice']); // delete this soon   
});