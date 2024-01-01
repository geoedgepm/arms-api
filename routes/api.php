<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiskController;
use App\Http\Controllers\RiskMatrixController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    // 'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'auth:api',
], function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('dashboard/risk_count', [DashboardController::class, 'riskCount']);
    Route::get('dashboard/risk_treatment_by_categories', [DashboardController::class, 'getRiskTreatmentByCategory']);
    Route::get('dashboard/risk_summaries', [DashboardController::class, 'getRiskSummary']);
    Route::get('dashboard/risk_treatment_details', [DashboardController::class, 'getRiskTreatmentDetails']);
    Route::get('dashboard/select_options', [DashboardController::class, 'getSelectOptions']);
});

Route::post('accounts/login', [AuthController::class, 'authenticate']);
Route::post('accounts/register', [AuthController::class, 'register']);
Route::post('register',[AuthController::class,'register']);
Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');
Route::post('logout', [AuthController::class,'logout']);

// Risk
Route::resource('risks', RiskController::class);
Route::resource('risks_matrix', RiskMatrixController::class);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
