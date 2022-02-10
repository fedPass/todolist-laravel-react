<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{TodoListController,TodoController, AuthController};

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

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
    Route::post('register', [AuthController::class,'register']);

});

//aggiungo i middleware alle risorse così verrà inviato il token quando verranno inviate delle richieste
Route::resource('lists',TodoListController::class)->middleware(['auth:api']);
Route::resource('todos',TodoController::class)->middleware(['auth:api']);
