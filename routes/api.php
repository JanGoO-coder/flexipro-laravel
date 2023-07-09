<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FailedJobController;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\SkillController;

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

// Authentication API Routes
Route::middleware('api')->prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('profile', [AuthController::class, 'profile']);
    Route::post('editProfile', [AuthController::class, 'editProfile']);
});

// User Skills API Routes
Route::middleware('api')->prefix('skills')->group(function () {
    Route::get('/', [SkillController::class, 'getUserSkills']);
    Route::post('/add', [SkillController::class, 'addUserSkill']);
    Route::post('/remove', [SkillController::class, 'removeUserSkill']);
});

// Company Job Posting API Routes
Route::middleware('api')->prefix('jobs')->group(function () {
    Route::get('/', [JobController::class, 'getJobs']);
    Route::get('/get/{id}', [JobController::class, 'getJobById']);
    Route::post('add', [JobController::class, 'addJob']);
    Route::post('update', [JobController::class, 'updateJob']);
    Route::post('delete/{id}', [JobController::class, 'deleteJobs']);
});

//Add Order API
Route::post('addOrder', [OrderController::class, 'addOrder']);

//Update Order API
Route::post('updateOrder', [OrderController::class, 'updateOrder']);

//Delete Order API
Route::post('deleteOrder/{id}', [OrderController::class, 'deleteOrder']);

//Get All Order API
Route::get('getOrders', [OrderController::class, 'getOrders']);

//Get By Id Order API
Route::get('getOrderById/{id}', [OrderController::class, 'getOrderById']);

//Add Rating API
Route::post('addRating', [RatingController::class, 'addRating']);

//Update Rating API
Route::post('updateRating', [RatingController::class, 'updateRating']);

//Delete Rating API
Route::post('deleteRating/{id}', [RatingController::class, 'deleteRating']);

//Get All Rating API
Route::get('getRatings', [RatingController::class, 'getRatings']);

//Get By Id Rating API
Route::get('getRatingById/{id}', [RatingController::class, 'getRatingById']);

//Add Transaction API
Route::post('addTransaction', [TransactionController::class, 'addTransaction']);

//Update Transaction API
Route::post('updateTransaction', [TransactionController::class, 'updateTransaction']);

//Delete Transaction API
Route::post('deleteTransaction/{id}', [TransactionController::class, 'deleteTransaction']);

//Get All Transaction API
Route::get('getTransaction', [TransactionController::class, 'getTransaction']);

//Get By Id Transaction API
Route::get('getTransactionById/{id}', [TransactionController::class, 'getTransactionById']);

//Add Wallet API
Route::post('addWallet', [WalletController::class, 'addWallet']);

//Update Wallet API
Route::post('updateWallet', [WalletController::class, 'updateWallet']);

//Delete Wallet API
Route::post('deleteWallet/{id}', [WalletController::class, 'deleteWallet']);

//Get All Wallet API
Route::get('getWallet', [WalletController::class, 'getWallet']);

//Get By Id Wallet API
Route::get('getWalletById/{id}', [WalletController::class, 'getWalletById']);
