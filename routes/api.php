<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FailedJobController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function() {
    $data = ['message' => "Welcome to our API"];
    return response()->json($data, 200);
});

Route::middleware('api')->prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('profile', [AuthController::class, 'profile']);
});

//Add Failed Job API
Route::post('addFailedJob', [FailedJobController::class, 'addFailedJob']);

//Add Failed Job API
Route::post('updateFailedJob', [FailedJobController::class, 'updateFailedJob']);

//Add Failed Job API
Route::post('deleteFailedJobs/{id}', [FailedJobController::class, 'deleteFailedJobs']);

//Add Failed Job API
Route::get('getFailedJobs', [FailedJobController::class, 'getFailedJobs']);

//Add Failed Job API
Route::get('getFailedJobsById/{id}', [FailedJobController::class, 'getFailedJobsById']);

