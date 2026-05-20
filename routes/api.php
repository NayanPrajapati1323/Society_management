<?php

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Maintenance Routes
    Route::prefix('maintenance')->group(function () {
        Route::get('/stats', [\App\Http\Controllers\Society\MaintenanceController::class, 'getStats']);
        Route::get('/bills', [\App\Http\Controllers\Society\MaintenanceController::class, 'index']);
        Route::post('/settings', [\App\Http\Controllers\Society\MaintenanceController::class, 'saveSettings']);
        Route::post('/generate', [\App\Http\Controllers\Society\MaintenanceController::class, 'generateBills']);
    });

    // Accounting Routes
    Route::prefix('accounting')->group(function () {
        Route::get('/passbook/society', [\App\Http\Controllers\Society\AccountingController::class, 'getSocietyPassbook']);
        Route::get('/passbook/user', [\App\Http\Controllers\Society\AccountingController::class, 'getUserPassbook']);
        Route::post('/expense', [\App\Http\Controllers\Society\AccountingController::class, 'addExpense']);
        Route::post('/advance-deposit', [\App\Http\Controllers\Society\AccountingController::class, 'depositAdvance']);
    });

    // Visitor Management Routes
    Route::prefix('visitors')->group(function () {
        Route::get('/', [\App\Http\Controllers\Society\VisitorController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Society\VisitorController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Society\VisitorController::class, 'update']);
        Route::post('/verify-otp', [\App\Http\Controllers\Society\VisitorController::class, 'verifyOtp']);
        Route::post('/verify-qr', [\App\Http\Controllers\Society\VisitorController::class, 'verifyQr']);
    });
});
