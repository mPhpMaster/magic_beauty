<?php

use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PharmacistController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;
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
        Route::get('device_token/{user}', [UserController::class, 'getDeviceToken']);
    });
    Route::post('product/search', [ProductController::class, 'search_for_product']);
    Route::apiResource('product', ProductController::class);
    // tmp link
    Route::get('ten_products', [ProductController::class, 'show_ten_products']);
    Route::get('product_template_excel', [ProductController::class, 'product_template_excel']);
    Route::post('product_import_excel', [ProductController::class, 'product_import_excel']);

    Route::post('doctor_import_excel', [DoctorController::class, 'doctor_import_excel']);
    Route::post('doctor/search', [DoctorController::class, 'search_for_doctor']);
    Route::apiResource('doctor', DoctorController::class);

    Route::post('pharmacist/search', [PharmacistController::class, 'search_for_pharmacist']);
    Route::apiResource('pharmacist', PharmacistController::class);

    Route::post('patient/search', [PatientController::class, 'search_for_patient']);
//    Route::apiResource('patient', PatientController::class);

    Route::post('prescription/auto_create_patient', [PrescriptionController::class, 'auto_create_patient']);
    Route::get('prescription/patient_index', [PrescriptionController::class, 'patient_index']);
    Route::get('prescription/pharmacist_index', [PrescriptionController::class, 'pharmacist_index']);
    Route::get('prescription/doctor_index', [PrescriptionController::class, 'doctor_index']);
    Route::get('prescription/pending_index', [PrescriptionController::class, 'pending_index']);
    Route::put('prescription/{prescription}/cancel', [PrescriptionController::class, 'cancel']);
    Route::put('prescription/{prescription}/finish', [PrescriptionController::class, 'finish']);
    Route::get('prescription/{prescription}/histories', [PrescriptionController::class, 'prescription_histories_index']);
    Route::apiResource('prescription', PrescriptionController::class);

    Route::post('branch/search', [BranchController::class, 'search_for_branch']);
    Route::apiResource('branch', BranchController::class);

    Route::get('refresh-token', [LoginController::class, 'refreshToken']);
    Route::get('logout', [LoginController::class, 'logout']);


    Route::group([
        'prefix' => 'notifications',
    ], function () {
        Route::post('save-token', [NotificationController::class, 'saveToken'])->name('save-token');
//        Route::post('send-notification', [NotificationController::class, 'sendNotification'])->name('send.notification');

        Route::get('list', [NotificationController::class,'index']);
        Route::get('unread_count', [NotificationController::class, 'unread_count']);
        Route::get('{my_notification}/mark-as-read', [NotificationController::class, 'mark_as_read']);
        Route::delete('{my_notification}/destroy', [NotificationController::class,'destroy']);
    });

});

Route::post('/login', [LoginController::class, 'login']);
