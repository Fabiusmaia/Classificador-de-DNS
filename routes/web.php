<?php

use App\Http\Controllers\DnsLogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dns', [DnsLogController::class, 'index'])->name('dns.logs');
    Route::get('/dns/upload-csv', [DnsLogController::class, 'showUploadForm'])->name('csv.upload.form');
    Route::post('/dns/upload-csv', [DnsLogController::class, 'uploadCsv'])->name('dns.upload-csv');
});

require __DIR__ . '/auth.php';
