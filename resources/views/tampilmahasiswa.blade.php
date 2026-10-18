@extends('layouts.app')
@section('title', 'Mahasiswa')
@section('content')
    <h2>Tabel Data Mahasiswa</h2>
    <table border="1" width='50%'>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($mahasiswa as $mhs)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mhs->nama }}</td>
                <td>{{ $mhs->prodi }}</td>
                <td>{{ $mhs->email }}</td>
                <td><a href="detilmahasiswa/{{ $mhs->id }}">Detail</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection