<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::middleware(['auth', 'role', 'firstSetup'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/transaksi', [App\Http\Controllers\TransaksiController::class, 'index'])->name('transaksi');
    Route::post('/transaksi/upload', [App\Http\Controllers\TransaksiController::class, 'upload'])->name('transaksi.upload');
    Route::post('/transaksi/import', [App\Http\Controllers\TransaksiController::class, 'import'])->name('transaksi.import');
    Route::post('/transaksi/calculate', [App\Http\Controllers\TransaksiController::class, 'calculate'])->name('transaksi.calculate');
    Route::get('/transaksi/{file_id}/getSetting', [App\Http\Controllers\TransaksiController::class, 'getSetting'])->name('transaksi.getSetting');
    Route::post('/transaksi/setSetting', [App\Http\Controllers\TransaksiController::class, 'setSetting'])->name('transaksi.setSetting');


    Route::get('/analisa', [App\Http\Controllers\AnalisaController::class, 'index'])->name('analisa');
    Route::get('/analisa/{file_name}/toExcel', [App\Http\Controllers\AnalisaController::class, 'toExcel'])->name('analisa.toExcel');
    Route::get('/analisa/{file_name}/toPdf', [App\Http\Controllers\AnalisaController::class, 'toPdf'])->name('analisa.toPdf');


    Route::get('/pengaturan', [App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan', [App\Http\Controllers\PengaturanController::class, 'store'])->name('pengaturan.store');
    Route::post('/pengaturan/changePassword', [App\Http\Controllers\PengaturanController::class, 'changePassword'])->name('pengaturan.changePassword');
});

Route::get('sandbox', [App\Http\Controllers\TesterController::class, 'index']);
