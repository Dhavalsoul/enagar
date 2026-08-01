<?php

use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::controller(CertificateVerificationController::class)->group(function () {
    Route::get('/verify-certificate', 'index')->name('verify-certificate');
    Route::post('/verify-certificate', 'verify')->name('verify-certificate.verify');
    Route::get('/verify-certificate/captcha', 'captcha')->name('verify-certificate.captcha');
    Route::get('/verify-certificate/{certificate}/download/{type}', 'download')
        ->name('verify-certificate.download');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/payment-receipts/{paymentReceipt}/qr', [WelcomeController::class, 'generateQr'])
        ->name('payment-receipts.qr');
});
Route::get('/getQrCode', [WelcomeController::class, 'getQrCode'])->name('generate.qr');

require __DIR__.'/auth.php';
