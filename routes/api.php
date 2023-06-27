<?php

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

//Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/users', [UserController::class, 'getAll'])->name('api.users.all');
    Route::get('/user/{id}', [UserController::class, 'getById'])->name('api.users.one');
    Route::get('/users/{role}', [UserController::class, 'getByRole'])->name('api.users.role');

    Route::get('/tags/{domain}', [TagController::class, 'getAll'])->name('api.tags.all');
    Route::get('/tags/{domain}/{type}', [TagController::class, 'getByType'])->name('api.tags.type');

    Route::get('/cities', [CityController::class, 'getAll'])->name('api.cities.all');

    Route::get('/streets', [StreetController::class, 'getAll'])->name('api.streets.all');

    Route::get('/items', [ItemController::class, 'getAll'])->name('api.items.all');
    Route::post('/item', [ItemController::class, 'set'])->name('api.item.set');
});
