<?php

use App\Http\Controllers\Api\KelurahanController;
use App\Http\Controllers\Api\PotensiInvestasiController;
use App\Http\Controllers\MapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes for frontend investor
Route::get('/kelurahan/geojson', [KelurahanController::class, 'geojson']);
Route::get('/kelurahan/filters', [KelurahanController::class, 'filters']);
Route::get('/potensi-investasi', [MapController::class, 'getPotensiInvestasis']);
Route::get('/potensi-investasi/geojson', [PotensiInvestasiController::class, 'geojson']);

// Legacy & Portal routes
Route::get('/kelurahans', [MapController::class, 'getKelurahans']);
Route::get('/cuaca-bahari', [MapController::class, 'getCuacaBahari']);
Route::post('/minat-investasi', [MapController::class, 'storeMinatInvestasi'])->name('api.minat-investasi');
Route::get('/potensi-investasis', [MapController::class, 'getPotensiInvestasis']);
Route::get('/uploaded-geojson', [MapController::class, 'getAllUploadedGeoJson']);
