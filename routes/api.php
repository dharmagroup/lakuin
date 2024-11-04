<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RFIDController;

// User registration route
Route::post('/register', [UserController::class, 'register']);

// User login route
Route::post('/login', [UserController::class, 'login']);

// Update password route
Route::post('/update-password', [UserController::class, 'changePassword']);

// Create order route
Route::post('/orders', [OrderController::class, 'createOrder']);

// Retrieve orders by userId route
Route::get('/orders', [OrderController::class, 'getOrdersByUserId']);

// Retrieve orders by shipper route
Route::get('/orders/shipper', [OrderController::class, 'getOrdersByShipper']);

// RFID routes can also be added here
Route::post('/rfid/register', [RFIDController::class, 'registerOrUpdate']);
Route::get('/rfid', [RFIDController::class, 'getUserById']);

Route::get('/verify-ktp/{ktpNumber}', [OrderController::class, 'verifyKTP']);