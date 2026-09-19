<?php

use App\Http\Controllers\Admin\CompanyVerifikasiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KriteriaAndalalinController;
use App\Http\Controllers\Admin\ManajemenUserController;
use App\Http\Controllers\Admin\MasterDokumenController;
use App\Http\Controllers\Admin\MasterFasilitasController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Pemohon\CompanyController;
use App\Http\Controllers\Pemohon\DashboardController as PemohonDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth'])->group(function () {
    Route::prefix('pemohon')->name('pemohon.')
        ->middleware(['role:Pemohon'])
        ->group(function () {

            Route::prefix('dashboard')->group(function () {
                Route::get('/', [PemohonDashboardController::class, 'index'])->name('dashboard.index');
            });

            Route::prefix('company')->name('company.')->group(function () {
                Route::get('/',                   [CompanyController::class, 'index'])->name('index');
                Route::get('/create',             [CompanyController::class, 'create'])->name('create');
                Route::post('/store',             [CompanyController::class, 'store'])->name('store');
                Route::get('/edit',               [CompanyController::class, 'edit'])->name('edit');
                Route::post('/update',            [CompanyController::class, 'update'])->name('update');
                Route::post('/submit-verifikasi', [CompanyController::class, 'submitVerifikasi'])->name('submit-verifikasi');
            });
        });

    // ─── Admin ───────────────────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')
        ->middleware(['role:Admin'])
        ->group(function () {

            Route::prefix('dashboard')->group(function () {
                Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
            });

            Route::prefix('verifikasi-perusahaan')->name('verifikasi-perusahaan.')->group(function () {
                Route::get('/',              [CompanyVerifikasiController::class, 'index'])->name('index');
                Route::get('/{id}/show',     [CompanyVerifikasiController::class, 'show'])->name('show');
                Route::post('/{id}/approve', [CompanyVerifikasiController::class, 'approve'])->name('approve');
                Route::post('/{id}/reject',  [CompanyVerifikasiController::class, 'reject'])->name('reject');
            });

            Route::prefix('kriteria-andalalin')->name('kriteria-andalalin.')->group(function () {
                Route::get('/',                [KriteriaAndalalinController::class, 'index'])->name('index');
                Route::get('/{id}/show',       [KriteriaAndalalinController::class, 'show'])->name('show');
                Route::post('/store',          [KriteriaAndalalinController::class, 'store'])->name('store');
                Route::put('/{id}/update',     [KriteriaAndalalinController::class, 'update'])->name('update');
                Route::delete('/{id}/destroy', [KriteriaAndalalinController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('master-fasilitas')->name('master-fasilitas.')->group(function () {
                Route::get('/',                    [MasterFasilitasController::class, 'index'])->name('index');
                Route::get('/{id}/show',           [MasterFasilitasController::class, 'show'])->name('show');
                Route::post('/store',              [MasterFasilitasController::class, 'store'])->name('store');
                Route::put('/{id}/update',         [MasterFasilitasController::class, 'update'])->name('update');
                Route::post('/{id}/toggle-active', [MasterFasilitasController::class, 'toggleActive'])->name('toggle-active');
                Route::delete('/{id}/destroy',     [MasterFasilitasController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('master-dokumen')->name('master-dokumen.')->group(function () {
                Route::get('/',                    [MasterDokumenController::class, 'index'])->name('index');
                Route::get('/{id}/show',           [MasterDokumenController::class, 'show'])->name('show');
                Route::post('/store',              [MasterDokumenController::class, 'store'])->name('store');
                Route::put('/{id}/update',         [MasterDokumenController::class, 'update'])->name('update');
                Route::post('/{id}/toggle-active', [MasterDokumenController::class, 'toggleActive'])->name('toggle-active');
                Route::delete('/{id}/destroy',     [MasterDokumenController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/',                    [ManajemenUserController::class, 'index'])->name('index');
                Route::get('/{user}/show',         [ManajemenUserController::class, 'show'])->name('show');
                Route::post('/store',              [ManajemenUserController::class, 'store'])->name('store');
                Route::put('/{user}/update',       [ManajemenUserController::class, 'update'])->name('update');
                Route::post('/{user}/toggle-active', [ManajemenUserController::class, 'toggleActive'])->name('toggle-active');
                Route::delete('/{user}/destroy',   [ManajemenUserController::class, 'destroy'])->name('destroy');
            });
        });
});
