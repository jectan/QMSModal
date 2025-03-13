<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ActionDocumentController;
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


//Masterlist
Route::get('/masterlist', [MasterlistController::class, 'index'])->name('masterlist.index');

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
Route::get('/ticket/close/{id}', [DocumentController::class, 'closeticket']);
});

//Office Ticket Tally
Route::get('/ticket/computeTotal',[DocumentController::class, 'documentTally']);
     
Route::get('logout',[AuthController::class, 'logout'])->name('logout');
Route::get('/setpassword', [AuthController::class, 'setPassword']);
Route::post('/setpassword', [AuthController::class, 'storePassword']);
//dashboard
Route::get('/dashboard/computeTotal',[DashboardController::class, 'compute']);
Route::get('/dashboard',[DashboardController::class, 'index']);
Route::get('/dashboardv2', [DashboardV2Controller::class, 'index']);

Route::get('/dashboard/topTenHighOffices', [DashboardController::class, 'topTenHighOffices']);
Route::get('/dashboard/topTenLowOffices', [DashboardController::class, 'topTenLowOffices']);
Route::get('/dashboard/topFiveUnresolved', [DashboardController::class, 'topFiveUnresolved']);
Route::get('/dashboard/topFiveResolved', [DashboardController::class, 'topFiveResolved']);
Route::get('/dashboard/topLowResolved', [DashboardController::class, 'topLowResolved']);


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
  
//Ticket
Route::get('/ticket', [DocumentController::class, 'index']);
Route::post('/ticket/store', [DocumentController::class, 'store']);
Route::get('caller/{caller_id}/ticket/create', [DocumentController::class, 'proceedTicket']);
Route::get('/ticket/show/{id}', [DocumentController::class, 'show']);
Route::post('/ticket/update', [DocumentController::class, 'update']);
Route::get('/ticket/view/{id}', [DocumentController::class, 'view']);
Route::post('/ticket/cancel', [DocumentController::class, 'cancel']);
Route::delete('/ticket/delete/{id}', [DocumentController::class, 'destroy']);
Route::post('/ticket/assign/add',[DocumentController::class, 'assignedOffice']);
Route::get('/ticket/show-assign/{id}',[DocumentController::class, 'getOffice']);

//action ticket
Route::post('/ticket/update/store', [ActionDocumentController::class, 'store']);
Route::post('/ticket/update/working', [DocumentController::class, 'startWorking']);
Route::post('/ticket/update/processed', [DocumentController::class, 'ticketProcessed']);
Route::get('/ticket/update/assigned/{id}', [DocumentController::class, 'assigned']);
Route::get('/ticket/update/action-status/{id}', [DocumentController::class, 'actionStatus']);
Route::delete('/ticket/update/remove/{id}', [DocumentController::class, 'removeUserOffice']);

//Position
Route::get('/position', [PositionController::class, 'index']);
Route::post('/position/store', [PositionController::class, 'PositionStore']);
Route::post('/position/update', [PositionController::class, 'editOffice']);
Route::delete('/position/delete/{id}', [PositionController::class, 'destroyOffice']);

//Caller-type
Route::get('/caller-type', [CallerTypeController::class, 'index']);
Route::post('/caller-type/store', [CallerTypeController::class, 'store']);
Route::post('/caller-type/proceedTicket', [CallerTypeController::class, 'proceedTicket']);
Route::post('/caller-type/edit', [CallerTypeController::class, 'edit']);
Route::delete('/caller-type/delete/{id}', [CallerTypeController::class, 'destroy']);

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
    Route::group(['middleware' => 'role:all'], function (){
//ticket  
Route::get('/ticket', [DocumentController::class, 'index']);
Route::post('/ticket/store', [DocumentController::class, 'store']);
Route::get('caller/{caller_id}/ticket/create', [DocumentController::class, 'proceedTicket']);
Route::get('/ticket/show/{id}', [DocumentController::class, 'show']);
Route::post('/ticket/update', [DocumentController::class, 'update']);
Route::get('/ticket/view/{id}', [DocumentController::class, 'view']);
Route::post('/ticket/cancel/', [DocumentController::class, 'cancel']);
Route::delete('/ticket/delete/{id}', [DocumentController::class, 'destroy']);
Route::post('/ticket/assign/add',[DocumentController::class, 'assignedOffice']);
Route::get('/ticket/show-assign/{id}',[DocumentController::class, 'getOffice']);


//action ticket
//Route::post('/ticket/update/store', [ActionDocumentController::class, 'store']);
//Route::post('/ticket/update/working', [DocumentController::class, 'startWorking']);
//Route::post('/ticket/update/processed', [DocumentController::class, 'ticketProcessed']);
//Route::get('/ticket/update/assigned/{id}', [DocumentController::class, 'assigned']);
//Route::get('/ticket/update/action-status/{id}', [DocumentController::class, 'actionStatus']);
//Route::delete('/ticket/update/remove/{id}', [DocumentController::class, 'removeUserOffice']);

       
});

