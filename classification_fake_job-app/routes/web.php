<?php

use App\Http\Controllers\JobAdController;

// Halaman utama
Route::get('/', function () {
    return view('upload');
});

// Proses kirim data ke AI
Route::post('/analyze', [JobAdController::class, 'analyze'])->name('analyze.job');

// Halaman hasil (Bisa ditaruh di controller juga nanti)
Route::get('/result', function () {
    return view('result');
})->name('result.page');