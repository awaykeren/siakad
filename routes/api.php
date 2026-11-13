<?php

use App\Http\Controllers\Api\DosenApiController;
use App\Http\Controllers\Api\MahasiswaApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua route di file ini otomatis diberi prefix "/api" dan memakai
| middleware group "api" (tanpa session & CSRF).
|
| apiResource membuat 5 endpoint sekaligus:
|   GET    /api/mahasiswa              -> index   (daftar mahasiswa)
|   POST   /api/mahasiswa              -> store   (tambah mahasiswa)
|   GET    /api/mahasiswa/{mahasiswa}  -> show    (detail mahasiswa)
|   PUT    /api/mahasiswa/{mahasiswa}  -> update  (ubah mahasiswa)
|   DELETE /api/mahasiswa/{mahasiswa}  -> destroy (hapus mahasiswa)
|
| ->names('api.mahasiswa') memberi nama route api.mahasiswa.index, dst.
| supaya tidak bentrok dengan route web "mahasiswa.index".
*/

Route::apiResource('mahasiswa', MahasiswaApiController::class)
    ->names('api.mahasiswa');

/*
| Contoh sederhana: API dosen
|   GET    /api/dosen       -> semua dosen
|   GET    /api/dosen/{id}  -> satu dosen
|   POST   /api/dosen       -> tambah dosen
|   PUT    /api/dosen/{id}  -> edit dosen
|   DELETE /api/dosen/{id}  -> hapus dosen
*/
Route::get('/dosen', [DosenApiController::class, 'index'])->name('api.dosen.index');
Route::get('/dosen/{id}', [DosenApiController::class, 'show'])->name('api.dosen.show');
Route::post('/dosen', [DosenApiController::class, 'store'])->name('api.dosen.store');
Route::put('/dosen/{id}', [DosenApiController::class, 'update'])->name('api.dosen.update');
Route::delete('/dosen/{id}', [DosenApiController::class, 'destroy'])->name('api.dosen.destroy');
