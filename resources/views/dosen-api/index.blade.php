@extends('layouts.app')

@section('title', 'Dosen (REST API)')

@section('content')
<div class="container-fluid py-4 px-4">
    <h2 class="mb-2">Dosen (REST API)</h2>
    <p class="text-muted">
        Data di halaman ini diambil dari <code>/api/dosen</code> menggunakan JavaScript <code>fetch()</code>.
    </p>

    {{-- Kotak detail dosen (awalnya disembunyikan) --}}
    <div id="detail-dosen" class="alert alert-info d-none"></div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">No.</th>
                        <th>NIDN</th>
                        <th>Nama Dosen</th>
                        <th>Program Studi</th>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-dosen">
                    <tr>
                        <td colspan="5" class="text-center text-muted">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const API_URL = "{{ url('/api/dosen') }}";

    // 1. Ambil semua dosen: GET /api/dosen
    async function tampilkanDosen() {
        const tbody = document.getElementById('tabel-dosen');

        try {
            const response = await fetch(API_URL, {
                headers: { 'Accept': 'application/json' }
            });
            const hasil = await response.json();

            if (hasil.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">Belum ada data dosen.</td></tr>';
                return;
            }

            let baris = '';
            hasil.data.forEach(function (dosen, index) {
                baris += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${dosen.nidn}</td>
                        <td>${dosen.nama}</td>
                        <td>${dosen.prodi}</td>
                        <td class="text-center">
                            <button class="btn btn-info btn-sm" onclick="lihatDetail(${dosen.id})">Detail</button>
                        </td>
                    </tr>`;
            });
            tbody.innerHTML = baris;
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal mengambil data dari API.</td></tr>';
        }
    }

    // 2. Ambil satu dosen: GET /api/dosen/{id}
    async function lihatDetail(id) {
        const kotak = document.getElementById('detail-dosen');

        const response = await fetch(`${API_URL}/${id}`, {
            headers: { 'Accept': 'application/json' }
        });
        const hasil = await response.json();

        kotak.classList.remove('d-none');

        if (!hasil.success) {
            kotak.innerHTML = hasil.message;
            return;
        }

        kotak.innerHTML = `
            <strong>Detail Dosen</strong><br>
            NIDN: ${hasil.data.nidn}<br>
            Nama: ${hasil.data.nama}<br>
            Program Studi: ${hasil.data.prodi}`;
    }

    // Jalankan saat halaman dibuka
    tampilkanDosen();
</script>
@endpush
