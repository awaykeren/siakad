@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-2">Data Mahasiswa</h2>
            <p class="text-muted mb-0">Daftar mahasiswa Program Studi</p>
        </div>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Mahasiswa
        </a>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('mahasiswa.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-lg-4">
                    <label for="search" class="form-label">Cari mahasiswa</label>
                    <input type="search" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Cari NIM, nama, prodi, email, atau nomor HP">
                </div>
                <div class="col-12 col-md-4 col-lg-2">
                    <label for="prodi" class="form-label">Filter program studi</label>
                    <select name="prodi" id="prodi" class="form-select">
                        <option value="">Semua prodi</option>
                        @foreach ($programStudi as $namaProdi)
                            <option value="{{ $namaProdi }}" @selected(request('prodi') === $namaProdi)>{{ $namaProdi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <label for="sort" class="form-label">Urutkan berdasarkan</label>
                    <select name="sort" id="sort" class="form-select">
                        @foreach ($sortOptions as $column => $label)
                            <option value="{{ $column }}" @selected($sort === $column)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <label for="direction" class="form-label">Urutan</label>
                    <select name="direction" id="direction" class="form-select">
                        <option value="asc" @selected($direction === 'asc')>A–Z</option>
                        <option value="desc" @selected($direction === 'desc')>Z–A</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Terapkan</button>
                    <a href="{{ route('mahasiswa.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <p class="text-muted small mb-3">Menampilkan {{ $mahasiswa->firstItem() ?? 0 }}–{{ $mahasiswa->lastItem() ?? 0 }} dari {{ $mahasiswa->total() }} mahasiswa.</p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No.</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Email</th>
                            <th>Nomor HP</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswa as $mhs)
                            <tr>
                                <td>{{ $mahasiswa->firstItem() + $loop->index }}</td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->nama }}</td>
                                <td>{{ $mhs->prodi }}</td>
                                <td>{{ $mhs->email ?: '-' }}</td>
                                <td>{{ $mhs->nohp ?: '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('mahasiswa.edit', $mhs) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('mahasiswa.destroy', $mhs) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    @if (request()->hasAny(['search', 'prodi']))
                                        Tidak ada mahasiswa yang cocok dengan pencarian atau filter.
                                    @else
                                        Belum ada data mahasiswa.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($mahasiswa->hasPages())
                <div class="mt-3">
                    {{ $mahasiswa->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
