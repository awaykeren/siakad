@extends('layouts.app')
@section('title', 'Mahasiswa')
@section('content')

    <h1>Data Mahasiswa</h1>
    @foreach($mahasiswa as $mhs)
        <p>{{ $mhs->nama }} - {{ $mhs->prodi }}</p>
    @endforeach
    <h2>Tabel Data Mahasiswa</h2>
    <table border="1" width='50%'>
        <thead>
            <tr>
                <th>No</th><th>Nama</th><th>Prodi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($mahasiswa as $mhs)
            <tr><td>{{ $loop->iteration }}</td><td>{{ $mhs->nama }}</td><td>{{ $mhs->prodi }}</td></tr>
        @endforeach
        </tbody>
    </table>

@endsection