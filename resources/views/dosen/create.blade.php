
@extends('layouts.app')

@section('title', 'Tambah Dosen')

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- Header halaman --}}
    <div class="mb-4">
        <h2 class="mb-2">Tambah Dosen</h2>
        <p class="text-muted mb-0">
            Tambahkan data dosen baru ke dalam sistem.
        </p>
    </div>

    {{-- Form tambah dosen --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <form action="{{ route('dosen.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nidn" class="form-label">
                        NIDN <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="nidn"
                        id="nidn"
                        value="{{ old('nidn') }}"
                        class="form-control @error('nidn') is-invalid @enderror"
                        placeholder="Masukkan NIDN"
                    >

                    @error('nidn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">
                        Nama Dosen <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        value="{{ old('nama') }}"
                        class="form-control @error('nama') is-invalid @enderror"
                        placeholder="Masukkan nama dosen"
                    >

                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="prodi" class="form-label">
                        Program Studi <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="prodi"
                        id="prodi"
                        value="{{ old('prodi') }}"
                        class="form-control @error('prodi') is-invalid @enderror"
                        placeholder="Masukkan program studi"
                    >

                    @error('prodi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                    <a href="{{ route('dosen.index') }}"
                       class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
