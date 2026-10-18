@extends('layouts.app')

@section('title', 'Data Mata Kuliah')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/3.1.4/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-2">Data Mata Kuliah</h2>
            <p class="text-muted mb-0">Daftar mata kuliah dan dosen pengampu</p>
        </div>
        <a href="{{ route('mata-kuliah.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Mata Kuliah
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="mata-kuliah-table" class="table table-bordered table-striped table-hover align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="60">No.</th>
                            <th>Kode</th>
                            <th>Nama Mata Kuliah</th>
                            <th width="80">SKS</th>
                            <th>Dosen Pengampu</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mataKuliah as $mk)
                            <tr>
                                <td></td>
                                <td>{{ $mk->kode }}</td>
                                <td>{{ $mk->nama }}</td>
                                <td>{{ $mk->sks }}</td>
                                <td>{{ $mk->dosen->nama }}</td>
                                <td class="text-center">
                                    <a href="{{ route('mata-kuliah.edit', $mk) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('mata-kuliah.destroy', $mk) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/3.1.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/3.1.4/js/dataTables.bootstrap5.js"></script>
    <script>
        $(function () {
            $('#mata-kuliah-table').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [[2, 'asc']],
                columnDefs: [
                    { targets: [0, 5], orderable: false, searchable: false }
                ],
                drawCallback: function () {
                    const api = this.api();
                    const pageInfo = api.page.info();
                    api.column(0, { page: 'current' }).nodes().each(function (cell, index) {
                        cell.textContent = pageInfo.start + index + 1;
                    });
                }
            });
        });
    </script>
@endpush
