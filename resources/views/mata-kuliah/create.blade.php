@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="mb-4">
        <h2 class="mb-2">Tambah Mata Kuliah</h2>
        <p class="text-muted mb-0">Tambahkan mata kuliah beserta dosen pengampunya.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            @if ($dosens->isEmpty())
                <div class="alert alert-warning">Tambahkan data dosen terlebih dahulu sebelum membuat mata kuliah.</div>
                <a href="{{ route('dosen.create') }}" class="btn btn-primary">Tambah Dosen</a>
                <a href="{{ route('mata-kuliah.index') }}" class="btn btn-secondary">Kembali</a>
            @else
                <form action="{{ route('mata-kuliah.store') }}" method="POST">
                    @csrf
                    @include('mata-kuliah._form', ['submitLabel' => 'Simpan'])
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
