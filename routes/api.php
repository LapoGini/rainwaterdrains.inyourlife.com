<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\StreetController;
use App\Http\Controllers\Api\ItemController;

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
Route::get('login',[AuthController::class,'login']);
Route::post('/setCaditoia', [ItemController::class, 'setCaditoia'])->name('api.items.setCaditoia');
Route::post('/delete_caditoie', [ItemController::class, 'getCancellabili'])->name('api.items.getCancellabili');
Route::post('/delete_caditoie_id', [ItemController::class, 'setDeleted'])->name('api.items.setDeleted');
Route::post('/scansioni/', [ItemController::class, 'getCaditoieScansionate'])->name('api.items.getCaditoieScansionate');
Route::post('/scansioniPerVia/', [ItemController::class, 'getCaditoieScansionatePerVia'])->name('api.items.getCaditoieScansionatePerVia');
Route::post('/aggiungiVia', [StreetController::class, 'setVia'])->name('api.streets.setVia');

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
    Route::post('/item', [ItemController::class, 'set'])->name('api.items.set');
});
