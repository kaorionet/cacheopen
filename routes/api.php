<?php

use App\Models\Iocache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\IOCacheController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users',[UserController::class,'index']);

Route::post('setcache', 'App\Http\Controllers\Api\IOCacheController@SetCache');
// Route::put('setcache', [IOCacheController::class,'SetCache']);

Route::get('getcache/{client}/{keyvalue}', [IOCacheController::class,'GetCache']);

