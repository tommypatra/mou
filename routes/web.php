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
use App\Http\Controllers\PihakController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KerjaSamaController;
use App\Http\Controllers\JenisPihakController;
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

    //login google
    Route::get('/auth/redirect', [GoogleController::class, 'redirect'])->name('auth');
    Route::get('/auth/callback', [GoogleController::class, 'callback']);

    //untuk pendaftaran
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran');
    Route::get('/aktivasi-pengguna/{token}', [PendaftaranController::class, 'aktivasi_pengguna'])->name('aktivasi');
    Route::post('/simpan-pendaftaran', [PendaftaranController::class, 'simpanpendaftaran'])->name('simpan-pendaftaran');
});

//route yg bebas di akses untuk pencarian
Route::post('/grup-search', [GrupController::class, 'search'])->name('grup-search');
Route::post('/provinsi-search', [ProvinsiController::class, 'search'])->name('provinsi-search');
Route::post('/kabupaten-search', [KabupatenController::class, 'search'])->name('kabupaten-search');
Route::post('/modul-search', [ModulController::class, 'search'])->name('modul-search');
Route::post('/menu-search', [MenuController::class, 'search'])->name('menu-search');
Route::post('/bagian-search', [BagianController::class, 'search'])->name('bagian-search');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    //master pihak
    Route::middleware(['hakAkses'])->group(function () {
        Route::get('/pihak', [PihakController::class, 'index'])->name('pihak');
        Route::post('/pihak-read', [PihakController::class, 'read'])->name('pihak-read');
        Route::post('/pihak-update', [PihakController::class, 'update'])->name('pihak-update');
        Route::post('/pihak-create', [PihakController::class, 'create'])->name('pihak-create');
        Route::post('/pihak-delete', [PihakController::class, 'delete'])->name('pihak-delete');
        Route::post('/pihak-search', [PihakController::class, 'search'])->name('pihak-search');

        //master kerjasama
        Route::get('/kerjasama', [KerjaSamaController::class, 'index'])->name('kerjasama');
        Route::post('/kerjasama-create', [KerjaSamaController::class, 'create'])->name('kerjasama-create');
        Route::post('/kerjasama-read', [KerjaSamaController::class, 'read'])->name('kerjasama-read');
        Route::post('/kerjasama-update', [KerjaSamaController::class, 'update'])->name('kerjasama-update');
        Route::post('/kerjasama-delete', [KerjaSamaController::class, 'delete'])->name('kerjasama-delete');
        Route::post('/kerjasama-upload', [KerjaSamaController::class, 'upload'])->name('kerjasama-upload');
        Route::post('/kerjasama-upload-delete', [KerjaSamaController::class, 'uploadDelete'])->name('kerjasama-upload-delete');
        Route::post('/kerjasama-load-resources', [KerjaSamaController::class, 'loadResources'])->name('kerjasama-load-resources');

        Route::middleware(['isAdmin'])->group(function () {
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

            //master jenispihak
            Route::get('/jenispihak', [JenisPihakController::class, 'index'])->name('jenispihak');
            Route::post('/jenispihak-create', [JenisPihakController::class, 'create'])->name('jenispihak-create');
            Route::post('/jenispihak-read', [JenisPihakController::class, 'read'])->name('jenispihak-read');
            Route::post('/jenispihak-update', [JenisPihakController::class, 'update'])->name('jenispihak-update');
            Route::post('/jenispihak-delete', [JenisPihakController::class, 'delete'])->name('jenispihak-delete');

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

            //master kabupaten
            Route::get('/kabupaten', [KabupatenController::class, 'index'])->name('kabupaten');
            Route::post('/kabupaten-create', [KabupatenController::class, 'create'])->name('kabupaten-create');
            Route::post('/kabupaten-read', [KabupatenController::class, 'read'])->name('kabupaten-read');
            Route::post('/kabupaten-update', [KabupatenController::class, 'update'])->name('kabupaten-update');
            Route::post('/kabupaten-delete', [KabupatenController::class, 'delete'])->name('kabupaten-delete');

            //master provinsi
            Route::get('/provinsi', [ProvinsiController::class, 'index'])->name('provinsi');
            Route::post('/provinsi-create', [ProvinsiController::class, 'create'])->name('provinsi-create');
            Route::post('/provinsi-read', [ProvinsiController::class, 'read'])->name('provinsi-read');
            Route::post('/provinsi-update', [ProvinsiController::class, 'update'])->name('provinsi-update');
            Route::post('/provinsi-delete', [ProvinsiController::class, 'delete'])->name('provinsi-delete');

            Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
        });
    });
});
