@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="mb-4">
        <h2 class="mb-2">Tambah Mahasiswa</h2>
        <p class="text-muted mb-0">Tambahkan data mahasiswa baru ke dalam sistem.</p>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('mahasiswa.store') }}" method="POST">
                @csrf
                @include('mahasiswa._form', ['submitLabel' => 'Simpan'])
            </form>
        </div>
    </div>
</div>
@endsection
