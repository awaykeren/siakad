@extends('layouts.app')

@section('title', 'Edit Mata Kuliah')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="mb-4">
        <h2 class="mb-2">Edit Mata Kuliah</h2>
        <p class="text-muted mb-0">Perbarui informasi mata kuliah dan dosen pengampu.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('mata-kuliah.update', $mataKuliah) }}" method="POST">
                @csrf
                @method('PUT')
                @include('mata-kuliah._form', ['submitLabel' => 'Update'])
            </form>
        </div>
    </div>
</div>
@endsection
