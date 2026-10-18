
@extends('layouts.app')

@section('title', 'Data Dosen')

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- Header halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-2">Data Dosen</h2>
            <p class="text-muted mb-0">
                Daftar dosen Program Studi
            </p>
        </div>

        <a href="{{ route('dosen.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah Dosen
        </a>
    </div>

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('dosen.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-lg-4">
                    <label for="search" class="form-label">Cari dosen</label>
                    <input type="search" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Cari NIDN, nama, atau program studi">
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
                    <a href="{{ route('dosen.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel data dosen --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <p class="text-muted small mb-3">Menampilkan {{ $dosen->firstItem() ?? 0 }}–{{ $dosen->lastItem() ?? 0 }} dari {{ $dosen->total() }} dosen.</p>

            <div class="table-responsive">
                <table class="table table-bordered table-striped
                              table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th width="60">No.</th>
                            <th>NIDN</th>
                            <th>Nama Dosen</th>
                            <th>Program Studi</th>
                            <th>Mata Kuliah</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($dosen as $d)
                            <tr>
                                <td>{{ $dosen->firstItem() + $loop->index }}</td>
                                <td>{{ $d->nidn }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->prodi }}</td>
                                <td>
                                    @forelse ($d->mataKuliah as $mataKuliah)
                                        <span class="d-block">
                                            {{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}
                                        </span>
                                    @empty
                                        <span class="text-muted">Belum ada mata kuliah</span>
                                    @endforelse
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('dosen.edit', $d->id) }}"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('dosen.destroy', $d->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus dosen ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted py-4">
                                    @if (request()->hasAny(['search', 'prodi']))
                                        Tidak ada dosen yang cocok dengan pencarian atau filter.
                                    @else
                                        Belum ada data dosen.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            @if ($dosen->hasPages())
                <div class="mt-3">
                    {{ $dosen->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
