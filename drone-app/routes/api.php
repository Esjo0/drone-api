<?php
use App\Http\Controllers\DroneController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\AuthController;
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

//Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



//Protected 
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/drones', [DroneController::class, 'index']);
    Route::get('/medications', [MedicationController::class, 'index']);
    Route::get('/drones/{id}', [DroneController::class, 'show']);
    Route::get('/drones/medications/{id}', [DroneController::class, 'meds']);
    Route::get('/drones/search/{serial_number}', [DroneController::class, 'search']);
    Route::post('/drones', [DroneController::class, 'store']);
    Route::get('/drones/status/{id}', [DroneController::class, 'status']);
    Route::put('/drones/{id}', [DroneController::class, 'update']);
    Route::delete('/drones/{id}', [DroneController::class, 'destroy']);
    Route::post('/medications', [MedicationController::class, 'store']);
    Route::put('/medications/{id}', [MedicationController::class, 'update']);
    Route::delete('/medications/{id}', [MedicationController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/load', [DroneController::class, 'load']);
    Route::post('/loaded', [DroneController::class, 'loaded']);

});
