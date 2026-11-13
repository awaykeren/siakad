<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/salam', function () {
	return 'Salam Kenal';
});

Route::get('hello', 'App\Http\Controllers\HelloController@index'); 
Route::get('biodata', 'App\Http\Controllers\HelloController@biodata'); 

Route::get('/tampilmahasiswa', 'App\Http\Controllers\MahasiswaController@tampil');
Route::get('/detilmahasiswa/{id}', 'App\Http\Controllers\MahasiswaController@detil');
Route::get('/datamahasiswa', 'App\Http\Controllers\MahasiswaController@data');


use App\Http\Controllers\DashboardController;
Route::get('/dashboard', [DashboardController::class, 'index']);

use App\Http\Controllers\DosenController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\MahasiswaController;

Route::resource('mahasiswa', MahasiswaController::class)
    ->except(['show']);

// Halaman client yang memakai REST API /api/mahasiswa
Route::view('/mahasiswa-api', 'mahasiswa-api.index')->name('mahasiswa-api.index');

// Halaman client yang memakai REST API /api/dosen
Route::view('/dosen-api', 'dosen-api.index')->name('dosen-api.index');

Route::resource('dosen', DosenController::class)
    ->except(['show']);

Route::resource('mata-kuliah', MataKuliahController::class)
    ->except(['show'])
    ->parameters(['mata-kuliah' => 'mataKuliah']);

Route::get('/mahasiswa/{nama}', function ($nama) {
    return 'Nama mahasiswa: ' . $nama;
});

use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
