@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1>Dashboard</h1>

    <div class="cards">

        <div class="card">
            <h3>Mahasiswa</h3>
            <h2>{{ $jumlahMahasiswa }}</h2>
        </div>

        <div class="card">
            <h3>Dosen</h3>
            <h2>{{ $jumlahDosen }}</h2>
        </div>

        <div class="card">
            <h3>Mata Kuliah</h3>
            <h2>{{ $jumlahMataKuliah }}</h2>
        </div>

    </div>

@endsection
