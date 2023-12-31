<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StreetController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Sync\SyncController;
use App\Http\Controllers\AddCitiesAndStreetsByCsvController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;


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

Route::get('/add-cities-and-streets', [AddCitiesAndStreetsByCsvController::class, 'index'])
    ->name('add-cities-and-streets.index')
    ->middleware(['auth', 'verified', 'role:admin']);

Route::post('/add-cities-and-streets', [AddCitiesAndStreetsByCsvController::class, 'importCsv'])
    ->name('add-cities-and-streets.import')
    ->middleware(['auth', 'verified', 'role:admin']);

Route::get('/download-esempio-csv', function(){
    $file = public_path('esempioCSV/TestCsv.csv');
    return Response::download($file, 'TestCsv.csv');
})->name('download-esempio-csv');


Route::post('{domain}/tags/addNewTag', [TagController::class, 'addNewTag'])->name('addNewTag')->middleware(['auth', 'verified', 'role:admin']);
Route::resource('{domain}/tags', TagController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy'])->middleware(['auth', 'verified', 'role:admin']);
Route::resource('streets', StreetController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy'])->middleware(['auth', 'verified', 'role:admin']);
Route::resource('cities', CityController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy'])->middleware(['auth', 'verified', 'role:admin']);
Route::resource('users', UserController::class)->only(['index', 'create', 'edit', 'store', 'update', 'destroy'])->middleware(['auth', 'verified', 'role:admin']);
Route::resource('items', ItemController::class)->only(['index', 'create', 'edit', 'update'])->middleware(['auth', 'verified', 'role:admin']);
Route::get('/items/deletable', [ItemController::class, 'deleteSewers'])->name('items.deleteSewers')->middleware(['auth', 'verified', 'role:admin']);
Route::get('items/{id}', [ItemController::class, 'destroy'])->name('items.destroy')->middleware(['auth', 'verified', 'role:admin']);
Route::get('/items/view/{item}', [ItemController::class, 'view'])->name('items.view')->middleware(['auth', 'verified', 'role:admin']);

//Route::get('items/filterData', [ItemController::class, 'filterData'])->name('items.filterData')->middleware(['auth', 'verified', 'role:admin']);
Route::get('items/city_id/{client}', [ItemController::class, 'getHtmlCityByClient'])->name('items.city_id')->middleware(['auth', 'verified', 'role:admin']);
Route::get('items/street/{city_id}', [ItemController::class, 'getHtmlStreetByCity'])->name('items.street')->middleware(['auth', 'verified', 'role:admin']);
Route::post('/save-ids-to-session', [ItemController::class, 'saveIdsToSession'])->name('items.saveIdsToSession')->middleware(['auth', 'verified', 'role:admin']);
Route::get('/download-zip', [ItemController::class, 'createZipFileFromImg_Items'])->name('items.downloadZip')->middleware(['auth', 'verified', 'role:admin']);
Route::get('/items/{item}/edit/previous', [ItemController::class, 'previous'])->name('items.previous')->middleware(['auth', 'verified', 'role:admin']);
Route::get('/items/{item}/edit/next', [ItemController::class, 'next'])->name('items.next')->middleware(['auth', 'verified', 'role:admin']);


//IMPORTAZIONE VECCHIO DB NEL NUOVO DB
Route::get('/crea-tabella-comuni', [\App\Http\Controllers\ComuneController::class, 'importaDati'])->middleware(['auth', 'verified', 'role:admin']);
Route::get('/crea-tabella-strade', [\App\Http\Controllers\StradeController::class, 'importaDati'])->middleware(['auth', 'verified', 'role:admin']);

Route::get('/crea-tabella-caditoie/{da}/{a}', [\App\Http\Controllers\CaditoieController::class, 'importaDati'])->middleware(['auth', 'verified', 'role:admin']);

Route::get('/crea-tabella-item_tag', [\App\Http\Controllers\ItemTagController::class, 'importaDati'])->middleware(['auth', 'verified', 'role:admin']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('sync',[SyncController::class,'index']);

require __DIR__.'/auth.php';
