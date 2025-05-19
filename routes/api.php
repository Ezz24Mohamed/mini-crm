<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\EmployeeController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\ActionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of
| them will be assigned to the "api" middleware group.
|
*/

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('auth.register');
    Route::post('login', 'login')->name('auth.login')->middleware('throttle:10,1');
    Route::post('logout', 'logout')->name('auth.logout')->middleware('auth:sanctum');
});

Route::prefix('admin')->controller(EmployeeController::class)->middleware('auth:sanctum')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::post('employees', 'addEmployee')->name('admin.employees.store');
        Route::get('employees', 'getEmployees')->name('admin.employees.index');
        Route::delete('employees/{id}', 'deleteEmployee')->name('admin.employees.destroy');
    });

    Route::put('employees/{id}', 'updateEmployee')->name('admin.employees.update');
});

Route::prefix('customers')->controller(CustomerController::class)->middleware('auth:sanctum')->group(function () {
    Route::post('/', 'addCustomer')->name('customers.store');
    Route::get('/', 'getCustomers')->name('customers.index');
    Route::put('/{id}', 'updateCustomer')->name('customers.update');
    Route::delete('/{id}', 'deleteCustomer')->name('customers.destroy');
});

Route::prefix('actions')->controller(ActionController::class)->middleware([
    'auth:sanctum',
    'role:employee'
])->group(function () {
    Route::post('/', 'addAction')->name('actions.store');
    Route::get('/', 'getActions')->name('actions.index');
    Route::put('/{id}', 'updateAction')->name('actions.update');
});