@ -1,15 +1,7 @@
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

// Routes Publik untuk Pelaporan Anonim
Route::get('/lapor', [ReportController::class, 'create'])->name('lapor.create');
Route::post('/lapor', [ReportController::class, 'store'])->name('lapor.store');
Route::get('/lapor/sukses', [ReportController::class, 'sukses'])->name('lapor.sukses');
Route::get('/track', [ReportController::class, 'showTrackForm'])->name('track.form');
Route::post('/track', [ReportController::class, 'track'])->name('track'); 