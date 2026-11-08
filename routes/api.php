<?php

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
