<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/generate', function () {
//     dd(\App\Models\Menu::Generate());
// });

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [LoginController::class, 'index'])->name('login.lnk');
    Route::get('/login', [LoginController::class, 'index']);
    Route::post('/login', [LoginController::class, 'authenticate'])->name('ceklogin.lnk');

    //untuk pendaftaran
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.lnk');
    Route::get('/aktivasi-pengguna/{token}', [PendaftaranController::class, 'aktivasi_pengguna'])->name('aktivasi.lnk');
    Route::post('/simpan-pendaftaran', [PendaftaranController::class, 'simpanpendaftaran'])->name('simpan-pendaftaran.lnk');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.lnk');

    //master grup
    Route::get('/grup', [GrupController::class, 'index'])->name('grup.lnk');
    Route::post('/grup-read', [GrupController::class, 'read'])->name('grup-read.lnk');
    Route::post('/grup-update', [GrupController::class, 'update'])->name('grup-update.lnk');
    Route::post('/grup-delete', [GrupController::class, 'delete'])->name('grup-delete.lnk');
    Route::post('/grup-save', [GrupController::class, 'store'])->name('grup-save.lnk');


    Route::get('/hakakses', [HakaksesController::class, 'index'])->name('hakakses.lnk');
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.lnk');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout.lnk');
});
