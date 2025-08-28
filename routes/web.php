<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ObatMasukController;
use App\Http\Controllers\ObatKeluarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Login & Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'process'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
| - Kalau belum login → ke halaman login
| - Kalau sudah login → ke dashboard
*/
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Routes dengan middleware auth
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Obat
    Route::resource('/obat', ObatController::class)
        ->middleware('permission:kelola obat');

    // Obat Masuk
    Route::resource('/obat-masuk', ObatMasukController::class)
        ->middleware('permission:kelola obat masuk');

    // Obat Keluar
    Route::resource('/obat-keluar', ObatKeluarController::class)
        ->middleware('permission:kelola obat keluar');

    // Stok (laporan realtime stok)
    Route::get('/stok', [LaporanController::class, 'stokRealtime'])
        ->name('stok.index')
        ->middleware('permission:laporan stok');

    // Laporan Stok
    Route::get('/laporan/stok', [LaporanController::class, 'laporanStok'])
        ->name('laporan.stok')
        ->middleware('permission:laporan stok');

    Route::get('/laporan/stok/pdf', [LaporanController::class, 'cetakStok'])
        ->name('laporan.stok.pdf')
        ->middleware('permission:laporan stok');

    // Laporan Keuangan
    Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])
        ->name('laporan.keuangan')
        ->middleware('permission:laporan keuangan');

    Route::get('/laporan/keuangan/pdf', [LaporanController::class, 'keuanganPdf'])
        ->name('laporan.keuangan.pdf')
        ->middleware('permission:laporan keuangan');

    Route::get('/laporan/keuangan/excel', [LaporanController::class, 'keuanganExcel'])->name('laporan.keuangan.excel');

    Route::get('/laporan/stok/excel', [LaporanController::class, 'stokExcel'])
    ->name('laporan.stok.excel')
    ->middleware('permission:laporan stok');

});
