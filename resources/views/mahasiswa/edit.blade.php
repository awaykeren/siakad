@extends('layouts.app')

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="mb-4">
        <h2 class="mb-2">Edit Data Mahasiswa</h2>
        <p class="text-muted mb-0">Perbarui informasi mahasiswa yang telah tersimpan.</p>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('mahasiswa.update', $mahasiswa) }}" method="POST">
                @csrf
                @method('PUT')
                @include('mahasiswa._form', ['submitLabel' => 'Update'])
            </form>
        </div>
    </div>
</div>
@endsection
