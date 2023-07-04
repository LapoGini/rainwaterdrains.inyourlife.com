<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Sync\SyncController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('{domain}/tags', TagController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy'])->middleware(['auth', 'verified', 'role:admin']);
Route::resource('streets', StreetController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy'])->middleware(['auth', 'verified', 'role:admin']);
Route::resource('cities', CityController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy'])->middleware(['auth', 'verified', 'role:admin']);
Route::resource('users', UserController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy'])->middleware(['auth', 'verified', 'role:admin']);
Route::resource('items', ItemController::class)->only(['index', 'create', 'edit', 'update', 'destroy'])->middleware(['auth', 'verified', 'role:admin']);
Route::get('items/filterData', [ItemController::class, 'filterData'])->name('items.filterData');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('sync',[SyncController::class,'index']);

require __DIR__.'/auth.php';
