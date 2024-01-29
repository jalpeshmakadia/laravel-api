<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
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


Route::post('/jobs',[JobController::class, 'createJob']);

Route::get('/jobs/{id}',[JobController::class, 'getJob']);

Route::delete('/jobs/{id}',[JobController::class, 'deleteJob']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


