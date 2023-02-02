<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
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
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::get('/login', [LoginController::class, 'index']);
    Route::post('/login', [LoginController::class, 'authenticate'])->name('ceklogin');

    //untuk pendaftaran
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran');
    Route::get('/aktivasi-pengguna/{token}', [PendaftaranController::class, 'aktivasi_pengguna'])->name('aktivasi');
    Route::post('/simpan-pendaftaran', [PendaftaranController::class, 'simpanpendaftaran'])->name('simpan-pendaftaran');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::group(['middleware' => 'isAdmin'], function () {
        //daftar akun
        Route::get('/akun', [AkunController::class, 'index'])->name('akun');
        Route::post('/akun-create', [AkunController::class, 'create'])->name('akun-create');
        Route::post('/akun-read', [AkunController::class, 'read'])->name('akun-read');
        Route::post('/akun-update', [AkunController::class, 'update'])->name('akun-update');
        Route::post('/akun-delete', [AkunController::class, 'delete'])->name('akun-delete');
        Route::post('/akun-upload', [AkunController::class, 'delete'])->name('akun-upload');

        //master grup
        Route::get('/grup', [GrupController::class, 'index'])->name('grup');
        Route::post('/grup-create', [GrupController::class, 'create'])->name('grup-create');
        Route::post('/grup-read', [GrupController::class, 'read'])->name('grup-read');
        Route::post('/grup-update', [GrupController::class, 'update'])->name('grup-update');
        Route::post('/grup-delete', [GrupController::class, 'delete'])->name('grup-delete');

        Route::get('/hakakses', [HakaksesController::class, 'index'])->name('hakakses');
        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
    });
});
