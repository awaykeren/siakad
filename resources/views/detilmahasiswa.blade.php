@extends('layouts.app')
@section('title', 'Mahasiswa')
@section('content')
    <h2>Informasi Mahasiswa</h2>
   <p>NIM : {{$mahasiswa->nim}}</p>
   <p>Nama : {{$mahasiswa->nama}}</p>
   <p>Program Studi : {{$mahasiswa->prodi}}</p>
   <p>Email : {{$mahasiswa->email}}</p>
   <p>No HP :
        @if($mahasiswa->nohp)
            {{ $mahasiswa->nohp }}
        @else
            Belum Diisi
        @endif
    </p>
@endsection