<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('payment', [PaymentController::class, 'showPaymentPage'])->name('payment.page');
Route::post('payment/order', [PaymentController::class, 'createOrder'])->name('payment.order');
Route::post('payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');
