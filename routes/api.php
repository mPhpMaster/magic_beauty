<?php

use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\PharmacistController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SettingsController;
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

    Route::post('category/search', [CategoryController::class, 'search_for_category']);
    Route::post('category/list', [CategoryController::class, 'index']);
    Route::apiResource('category', CategoryController::class);

    Route::post('product/change_qty/{product}', [ProductController::class, 'change_qty']);
    Route::post('product/search', [ProductController::class, 'search_for_product']);
    Route::post('product/list', [ProductController::class, 'index']);
    Route::post('product/{product}/show', [ProductController::class, 'show']);
    Route::apiResource('product', ProductController::class);
    // tmp link
    Route::get('ten_products', [ProductController::class, 'show_ten_products']);
    Route::get('product_template_excel', [ProductController::class, 'product_template_excel']);
    Route::post('product_import_excel', [ProductController::class, 'product_import_excel']);

    Route::post('doctor_import_excel', [DoctorController::class, 'doctor_import_excel']);
    Route::post('doctor/search', [DoctorController::class, 'search_for_doctor']);
    Route::post('doctor/list', [DoctorController::class, 'index']);
    Route::apiResource('doctor', DoctorController::class);

    Route::post('pharmacist/search', [PharmacistController::class, 'search_for_pharmacist']);
    Route::post('pharmacist/list', [PharmacistController::class, 'index']);
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

    Route::get('order/success_index', [OrderController::class, 'success_index']);
    Route::get('order/failed_index', [OrderController::class, 'failed_index']);
    Route::get('order/finished_index', [OrderController::class, 'finished_index']);
    Route::get('order/canceled_index', [OrderController::class, 'canceled_index']);
    Route::get('order/pending_index', [OrderController::class, 'pending_index']);
    Route::put('order/{order}/success', [OrderController::class, 'success']);
    Route::put('order/{order}/failed', [OrderController::class, 'failed']);
    Route::put('order/{order}/pending', [OrderController::class, 'pending']);
    Route::put('order/{order}/finished', [OrderController::class, 'finished']);
    Route::put('order/{order}/canceled', [OrderController::class, 'canceled']);
    Route::apiResource('order', OrderController::class);

    Route::get('branch/user/{pharmacist}', [BranchController::class, 'get_branch_id_by_pharmacist']);
    Route::post('branch/search', [BranchController::class, 'search_for_branch']);
    Route::post('branch/list', [BranchController::class, 'index']);
    Route::apiResource('branch', BranchController::class);

    Route::post('reports/product_qty', [ReportController::class, 'product_qty']);
    Route::post('reports/prescriptions', [ReportController::class, 'prescriptions']);

    Route::get('refresh-token', [LoginController::class, 'refreshToken']);
    Route::get('logout', [LoginController::class, 'logout']);

    Route::post('favorite', [FavoriteController::class, 'add_product']);
    Route::delete('favorite', [FavoriteController::class, 'remove_product']);
    Route::get('favorite', [FavoriteController::class, 'index']);

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

Route::post('register', [UserController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/social_media', [SettingsController::class, 'social_media']);
Route::get('/support_email', [SettingsController::class, 'support_email']);
