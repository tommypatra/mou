<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AksesController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenggunaController;
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
        //akun
        Route::get('/akun', [AkunController::class, 'index'])->name('akun');
        Route::post('/akun-create', [AkunController::class, 'create'])->name('akun-create');
        Route::post('/akun-read', [AkunController::class, 'read'])->name('akun-read');
        Route::post('/akun-update', [AkunController::class, 'update'])->name('akun-update');
        Route::post('/akun-delete', [AkunController::class, 'delete'])->name('akun-delete');
        Route::post('/akun-upload', [AkunController::class, 'delete'])->name('akun-upload');
        Route::post('/akun-bagian', [AkunController::class, 'bagianakun'])->name('akun-bagian');
        Route::post('/akun-grup', [AkunController::class, 'grupakun'])->name('akun-grup');
        Route::post('/akun-atur', [AkunController::class, 'pengaturan'])->name('akun-atur');

        //master grup
        Route::get('/grup', [GrupController::class, 'index'])->name('grup');
        Route::post('/grup-create', [GrupController::class, 'create'])->name('grup-create');
        Route::post('/grup-read', [GrupController::class, 'read'])->name('grup-read');
        Route::post('/grup-update', [GrupController::class, 'update'])->name('grup-update');
        Route::post('/grup-delete', [GrupController::class, 'delete'])->name('grup-delete');

        //master bagian
        Route::get('/bagian', [BagianController::class, 'index'])->name('bagian');
        Route::post('/bagian-create', [BagianController::class, 'create'])->name('bagian-create');
        Route::post('/bagian-read', [BagianController::class, 'read'])->name('bagian-read');
        Route::post('/bagian-update', [BagianController::class, 'update'])->name('bagian-update');
        Route::post('/bagian-delete', [BagianController::class, 'delete'])->name('bagian-delete');

        //master jenis
        Route::get('/jenis', [JenisController::class, 'index'])->name('jenis');
        Route::post('/jenis-create', [JenisController::class, 'create'])->name('jenis-create');
        Route::post('/jenis-read', [JenisController::class, 'read'])->name('jenis-read');
        Route::post('/jenis-update', [JenisController::class, 'update'])->name('jenis-update');
        Route::post('/jenis-delete', [JenisController::class, 'delete'])->name('jenis-delete');

        //master kategori
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
        Route::post('/kategori-create', [KategoriController::class, 'create'])->name('kategori-create');
        Route::post('/kategori-read', [KategoriController::class, 'read'])->name('kategori-read');
        Route::post('/kategori-update', [KategoriController::class, 'update'])->name('kategori-update');
        Route::post('/kategori-delete', [KategoriController::class, 'delete'])->name('kategori-delete');

        //master menu
        Route::get('/menu', [MenuController::class, 'index'])->name('menu');
        Route::post('/menu-create', [MenuController::class, 'create'])->name('menu-create');
        Route::post('/menu-read', [MenuController::class, 'read'])->name('menu-read');
        Route::post('/menu-update', [MenuController::class, 'update'])->name('menu-update');
        Route::post('/menu-delete', [MenuController::class, 'delete'])->name('menu-delete');
        Route::post('/menu-search', [MenuController::class, 'search'])->name('menu-search');

        //master modul
        Route::get('/modul', [ModulController::class, 'index'])->name('modul');
        Route::post('/modul-create', [ModulController::class, 'create'])->name('modul-create');
        Route::post('/modul-read', [ModulController::class, 'read'])->name('modul-read');
        Route::post('/modul-update', [ModulController::class, 'update'])->name('modul-update');
        Route::post('/modul-delete', [ModulController::class, 'delete'])->name('modul-delete');

        //master akses
        Route::get('/akses', [AksesController::class, 'index'])->name('akses');
        Route::post('/akses-create', [AksesController::class, 'create'])->name('akses-create');
        Route::post('/akses-read', [AksesController::class, 'read'])->name('akses-read');
        Route::post('/akses-update', [AksesController::class, 'update'])->name('akses-update');
        Route::post('/akses-delete', [AksesController::class, 'delete'])->name('akses-delete');

        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
    });
});
