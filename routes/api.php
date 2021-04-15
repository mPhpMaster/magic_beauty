<?php

use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PharmacistController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    'middleware' => 'auth:sanctum',
], function () {
    Route::group([
        'prefix' => 'user',
    ], function () {
        Route::get('profile', [UserController::class, 'show']);
        Route::put('profile', [UserController::class, 'update']);
    });

    Route::apiResource('doctor', DoctorController::class);
    Route::apiResource('pharmacist', PharmacistController::class);
    Route::apiResource('prescription', PrescriptionController::class);
    Route::get('prescription/patient_index', [PrescriptionController::class, 'patient_index']);
    Route::get('prescription/pharmacist_index', [PrescriptionController::class, 'pharmacist_index']);
    Route::get('prescription/doctor_index', [PrescriptionController::class, 'doctor_index']);
    Route::put('prescription/{prescription}/cancel', [PrescriptionController::class, 'cancel']);
    Route::put('prescription/{prescription}/finish', [PrescriptionController::class, 'finish']);

    Route::get('refresh-token', [LoginController::class, 'refreshToken']);
    Route::get('logout', [LoginController::class, 'logout']);
});

Route::post('/login', [LoginController::class, 'login']);
