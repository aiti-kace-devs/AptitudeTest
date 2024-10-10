<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentOperation;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/student/register', [AdminController::class, 'add_new_students'])->middleware('apikey.check');
Route::post('/student/admit', [StudentOperation::class, 'admit_student'])->middleware('apikey.check');
