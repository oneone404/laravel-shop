<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CardDepositController;
use App\Http\Controllers\DiscountCodeController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ServiceHistoryController;
use App\Http\Controllers\GameKeyController;
use App\Http\Controllers\NapZingController;
use App\Http\Controllers\Api\CardCallbackController;
use App\Http\Controllers\Admin\GameHackController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::match(['GET', 'POST'],'/callback/card', [CardDepositController::class, 'handleCallback'])->name('callback.card');

Route::post('/nap-zing/insert-card', [NapZingController::class, 'insertCard']);
Route::post('/nap-zing', [NapZingController::class, 'nap']);
Route::post('/check-plus', [NapZingController::class, 'checkPlus']);
Route::get('/nap-zing/available-count', [NapZingController::class, 'countAvailableCards']);
Route::post('/nap-zing/gift-code', [NapZingController::class, 'getGiftcode']);

// Discount code validation
Route::post('/discount-codes/validate', [DiscountCodeController::class, 'validateCode']);

Route::get('/auto-bank-deposit', function () {
    Artisan::call('fetch:mb-transactions');
}); // Bảo vệ route bằng middleware auth

Route::get('/auto-bank-deposit-acb', function () {
    Artisan::call('fetch:acb-transactions');
    return response()->json(['status' => 'ok', 'message' => 'ACB FETCH TRIGGERED']);
});

Route::get('/process-transaction', [TransactionController::class, 'processTransaction']);
Route::get('/service-histories', [ServiceHistoryController::class, 'index']);
Route::get('/clearkey', [GameKeyController::class, 'clearInvalidKeys']);

Route::post('/add-keys', [GameHackController::class, 'apiAddKeys']);
Route::get('/key-stats', [GameHackController::class, 'apiStats']);

Route::post('/card/callback', [CardCallbackController::class, 'handle']);
