
@extends('layouts.app')

@section('title', 'Edit Dosen')

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- Header halaman --}}
    <div class="mb-4">
        <h2 class="mb-2">Edit Data Dosen</h2>
        <p class="text-muted mb-0">
            Perbarui informasi dosen yang telah tersimpan.
        </p>
    </div>

    {{-- Form edit dosen --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <form action="{{ route('dosen.update', $dosen->id) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nidn" class="form-label">
                        NIDN <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="nidn"
                        id="nidn"
                        value="{{ old('nidn', $dosen->nidn) }}"
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
                        value="{{ old('nama', $dosen->nama) }}"
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
                        value="{{ old('prodi', $dosen->prodi) }}"
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
                        Update
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
