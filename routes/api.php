<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClinicHistoryController;
use App\Http\Controllers\ConsultationReasonController;
use App\Http\Controllers\SchedulerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DocumentFileController;
use App\Http\Controllers\TherapyController;
use App\Http\Controllers\TherapeuticBehaviorController;
use App\Http\Controllers\TherapyDetailController;
use App\Http\Controllers\VitalSignsController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('get/actual/images', [AdController::class, 'fetchAds']);

Route::post('login', [AuthController::class, 'authenticate']);
Route::post('generate/reset/password/link', [AuthController::class, 'generatePasswordResentLink']);
Route::post('reset/password', [AuthController::class, 'resetPassword']);
Route::post('test', function () {
    return response()->json([
        'message' => 'Exito'
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('dashboard', [AuthController::class, 'dashboard']);

    Route::get('ads', [AdController::class, 'index']);
    Route::post('ads/search', [AdController::class, 'search']);
    Route::post('ads', [AdController::class, 'store']);
    Route::post('ads/{id}', [AdController::class, 'update']);
    Route::delete('ads/{id}', [AdController::class, 'delete']);
    Route::post('ads/toggle/{id}', [AdController::class, 'toggle']);
    Route::post('ads/statue/toggle/{ad}', [AdController::class, 'toggleState']);

    // Route::post('therapy/details/{therapyId}/sessions', [TherapyDetailController::class, 'calculateSessionNumber']);
    // Route::post('therapy/details', [TherapyDetailController::class, 'store']);
    // Route::post('therapy/details/{clientId}/history/details', [TherapyDetailController::class, 'fetchHistoryDetails']);
    // Route::post('therapy/details/{id}', [TherapyDetailController::class, 'update']);
    // Route::delete('therapy/details/{id}', [TherapyDetailController::class, 'delete']);
    // Route::post('therapy/details/{id}/change/status', [TherapyDetailController::class, 'changeStatus']);
    // Route::post('therapy/details/client/{clientId}', [TherapyDetailController::class, 'indexUserDetails']);
    // Route::get('therapy/details/client/{clientId}/export/pdf', [TherapyDetailController::class, 'exportClientTherapies']);

    // Route::get('scheduler', [SchedulerController::class, 'index']);
    // Route::post('scheduler/search', [SchedulerController::class, 'search']);
    // Route::post('scheduler', [SchedulerController::class, 'store']);
    // Route::post('scheduler/{id}', [SchedulerController::class, 'update']);
    // Route::delete('scheduler/{id}', [SchedulerController::class, 'delete']);
    // Route::post('scheduler/toggle/{id}', [SchedulerController::class, 'toggle']);
    // Route::post('scheduler/{id}/mark/as/attended', [SchedulerController::class, 'markAsAttended']);

    // Route::post('vital/signs/search', [VitalSignsController::class, 'search']);
    // Route::post('vital/signs', [VitalSignsController::class, 'store']);
    // Route::post('vital/signs/{id}', [VitalSignsController::class, 'update']);
    // Route::delete('vital/signs/{id}', [VitalSignsController::class, 'delete']);

    // Route::post('consultation/reasons/search', [ConsultationReasonController::class, 'search']);
    // Route::post('consultation/reasons/last', [ConsultationReasonController::class, 'last']);
    // Route::post('consultation/reasons', [ConsultationReasonController::class, 'store']);
    // Route::post('consultation/reasons/{id}', [ConsultationReasonController::class, 'update']);
    // Route::delete('consultation/reasons/{id}', [ConsultationReasonController::class, 'delete']);

    // Route::get('document/files', [DocumentFileController::class, 'index']);
    // Route::post('document/files/search', [DocumentFileController::class, 'search']);
    // Route::post('document/files', [DocumentFileController::class, 'store']);
    // Route::post('document/files/{id}', [DocumentFileController::class, 'update']);
    // Route::delete('document/files/{id}', [DocumentFileController::class, 'delete']);
    // Route::post('document/files/toggle/{id}', [DocumentFileController::class, 'toggle']);

    // Route::post('report/yearly', [ReportController::class, 'fetchYearlyReport']);
    // Route::post('report/birtdays', [ReportController::class, 'getUsersWithBirthdayToday']);
});
