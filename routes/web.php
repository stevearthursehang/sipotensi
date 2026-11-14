<?php

use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MapController::class, 'index']);

// Fallback & Standard Login Route for Laravel Auth Middleware
Route::get('/login', [InvestorController::class, 'showLoginForm'])->name('login');

// Portal Akun & Dashboard Investor
Route::prefix('investor')->name('investor.')->group(function () {
    Route::get('/login', [InvestorController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [InvestorController::class, 'login']);
    Route::get('/register', [InvestorController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [InvestorController::class, 'register']);
    Route::post('/logout', [InvestorController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [InvestorController::class, 'dashboard'])->name('dashboard');
        Route::post('/profile', [InvestorController::class, 'updateProfile'])->name('profile.update');
    });
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['app_locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/potensi/bandingkan', [MapController::class, 'comparePotensi'])->name('potensi.compare');
Route::get('/statistik', [MapController::class, 'showPublicStatistics'])->name('public.statistics');
Route::get('/potensi/{id}', [MapController::class, 'showPotensiDetail'])->name('potensi.detail');
Route::get('/potensi/{id}/pdf', [MapController::class, 'exportPdf'])->name('potensi.pdf');
Route::get('/potensi/{id}/pdf-preview', function($id) {
    $potensi = \App\Models\PotensiInvestasi::with(['kelurahan.kecamatan'])->findOrFail($id);
    return view('pdf.prospektus', compact('potensi'));
})->name('potensi.pdf-preview');
Route::get('/diagram/{name}', function($name) {
    if (view()->exists('diagrams.' . $name)) {
        return view('diagrams.' . $name);
    }
    abort(404);
});
Route::post('/api/minat-investasi', [MapController::class, 'storeMinatInvestasi'])->middleware('api.protect')->name('api.minat-investasi');
Route::get('/api/kelurahans', [MapController::class, 'getKelurahans'])->middleware('api.protect')->name('api.kelurahans');
Route::get('/api/potensi-investasis', [MapController::class, 'getPotensiInvestasis'])->middleware('api.protect')->name('api.potensi-investasis');
Route::get('/api/potensi-investasi', [MapController::class, 'getPotensiInvestasis'])->middleware('api.protect')->name('api.potensi-investasi'); // Alias for frontend compatibility
Route::get('/api/potensi-investasis/{id}', [MapController::class, 'showPotensiInvestasi'])->middleware('api.protect');
Route::get('/api/potensi-investasi/{id}', [MapController::class, 'showPotensiInvestasi'])->middleware('api.protect'); // Alias for frontend compatibility
Route::get('/api/statistics', [MapController::class, 'getStatistics'])->middleware('api.protect');
Route::get('/api/cuaca-bahari', [MapController::class, 'getCuacaBahari'])->name('api.cuaca-bahari');

// Import/Export Routes
Route::get('/export/kelurahans', [ImportExportController::class, 'exportKelurahans']);
Route::get('/export/potensi-investasis', [ImportExportController::class, 'exportPotensiInvestasis']);
Route::post('/import/potensi-investasis', [ImportExportController::class, 'importPotensiInvestasis']);
Route::get('/export/template', [ImportExportController::class, 'exportTemplate']);
