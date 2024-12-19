<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Stripe3Controller;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PaymentController::class, 'payment'])->name('payment.payment');
Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/success', [PaymentController::class,'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class,'cancel'])->name('payment.failure');
Route::post('/stripe/payment-intent', [Stripe3Controller::class, 'createPaymentIntent'])->name('stripe.paymentIntent');
Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook']);

// Route::get('/payment', [TestController::class, "index"]);
// Route::post('/charge', [TestController::class, "pay"]);

// Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);
