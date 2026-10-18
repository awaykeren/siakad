<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahMahasiswa = Mahasiswa::count();
        $jumlahDosen = Dosen::count();
        $jumlahMataKuliah = MataKuliah::count();

        return view('dashboard', compact(
            'jumlahMahasiswa',
            'jumlahDosen',
            'jumlahMataKuliah'
        ));
    }
}
