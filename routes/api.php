<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, CategoryController, BrandController, ProductController,
    CustomerController, InvoiceController, PasswordResetController
};

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/send-otp', [PasswordResetController::class, 'sendOtp']);
Route::post('/password/verify-otp', [PasswordResetController::class, 'verifyOtp']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);

// Protected Routes
Route::middleware('auth:api')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('invoices', InvoiceController::class);

    Route::get('invoices/{id}/pdf', [InvoiceController::class, 'generatePDF']);
});
