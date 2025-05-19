<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\EmployeeController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\ActionController;




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


Route::controller(AuthController::class)->group(function(){
    Route::prefix('auth')->group(function(){
        Route::post('register','register')->name('auth.register');
        Route::post('login','login')->name('auth.login')->middleware('throttle:10,1');
        Route::post('logout','logout')->name('auth.logout')->middleware('auth:sanctum');
    });
});
Route::controller(EmployeeController::class)->group(function(){
    Route::prefix('admin')->group(function (){
        Route::post('/add-employee','addEmployee')->name('admin.add-employee')->middleware(['auth:sanctum','role:admin']);
    });
});
Route::controller(CustomerController::class)->group(function (){
    Route::prefix('customer')->group(function(){
        Route::post('/add-customer','addCustomer')->name('customer.add-customer')->middleware(['auth:sanctum']);
    });
});
Route::controller(ActionController::class)->middleware(['auth:sanctum','role:employee'])->group(function (){
    Route::prefix('action')->group(function(){
        Route::post('/add-action','addAction')->name('action.add-action');
        Route::get('/get-actions','getActions')->name('action.get-actions');
        Route::put('/update-action/{id}','updateAction')->name('action.update-action');
    });
});