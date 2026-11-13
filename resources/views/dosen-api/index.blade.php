@extends('layouts.app')

@section('title', 'Dosen (REST API)')

@section('content')
<div class="container-fluid py-4 px-4">
    <h2 class="mb-2">Dosen (REST API)</h2>
    <p class="text-muted">
        Data di halaman ini diambil dan dikirim ke <code>/api/dosen</code> menggunakan JavaScript <code>fetch()</code>.
    </p>

    {{-- Tempat pesan sukses / gagal --}}
    <div id="pesan"></div>

    {{-- Kotak detail dosen (awalnya disembunyikan) --}}
    <div id="detail-dosen" class="alert alert-info d-none"></div>

    {{-- Form tambah / edit --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 id="judul-form" class="mb-3">Tambah Dosen</h5>
            <form id="form-dosen" class="row g-3 align-items-end">
                {{-- id terisi = mode edit, kosong = mode tambah --}}
                <input type="hidden" id="dosen-id">
                <div class="col-md-3">
                    <label for="nidn" class="form-label">NIDN</label>
                    <input type="text" id="nidn" class="form-control" placeholder="Masukkan NIDN">
                </div>
                <div class="col-md-4">
                    <label for="nama" class="form-label">Nama Dosen</label>
                    <input type="text" id="nama" class="form-control" placeholder="Masukkan nama dosen">
                </div>
                <div class="col-md-3">
                    <label for="prodi" class="form-label">Program Studi</label>
                    <input type="text" id="prodi" class="form-control" placeholder="Masukkan program studi">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Simpan</button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel data --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">No.</th>
                        <th>NIDN</th>
                        <th>Nama Dosen</th>
                        <th>Program Studi</th>
                        <th width="200" class="text-center">Aksi</th>
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

    // Menampilkan pesan hijau (sukses) atau merah (gagal)
    function tampilkanPesan(teks, sukses) {
        const warna = sukses ? 'success' : 'danger';
        document.getElementById('pesan').innerHTML =
            `<div class="alert alert-${warna}">${teks}</div>`;
    }

    // =====================================================
    // 1. TAMPILKAN SEMUA DOSEN: GET /api/dosen
    // =====================================================
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
                            <button class="btn btn-warning btn-sm" onclick="editDosen(${dosen.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="hapusDosen(${dosen.id})">Hapus</button>
                        </td>
                    </tr>`;
            });
            tbody.innerHTML = baris;
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal mengambil data dari API.</td></tr>';
        }
    }

    // =====================================================
    // 2. TAMPILKAN SATU DOSEN: GET /api/dosen/{id}
    // =====================================================
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

    // =====================================================
    // 3. TAMBAH (POST) & EDIT (PUT)
    // =====================================================

    // Mengosongkan form dan kembali ke mode "Tambah"
    function resetForm() {
        document.getElementById('form-dosen').reset();
        document.getElementById('dosen-id').value = '';
        document.getElementById('judul-form').textContent = 'Tambah Dosen';
    }

    // Tombol Edit: ambil data dosen dari API lalu isi ke form
    async function editDosen(id) {
        const response = await fetch(`${API_URL}/${id}`, {
            headers: { 'Accept': 'application/json' }
        });
        const hasil = await response.json();

        if (!hasil.success) {
            tampilkanPesan(hasil.message, false);
            return;
        }

        document.getElementById('dosen-id').value = hasil.data.id;
        document.getElementById('nidn').value = hasil.data.nidn;
        document.getElementById('nama').value = hasil.data.nama;
        document.getElementById('prodi').value = hasil.data.prodi;
        document.getElementById('judul-form').textContent = 'Edit Dosen';
    }

    // Tombol Simpan: POST jika tambah, PUT jika edit
    document.getElementById('form-dosen').addEventListener('submit', async function (event) {
        event.preventDefault(); // jangan reload halaman

        const id = document.getElementById('dosen-id').value;

        // Data yang dikirim ke API
        const data = {
            nidn: document.getElementById('nidn').value,
            nama: document.getElementById('nama').value,
            prodi: document.getElementById('prodi').value,
        };

        const url = id ? `${API_URL}/${id}` : API_URL;
        const method = id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        const hasil = await response.json();

        // 422 = validasi gagal, tampilkan semua pesan error
        if (response.status === 422) {
            const daftarError = Object.values(hasil.errors).flat().join('<br>');
            tampilkanPesan(daftarError, false);
            return;
        }

        if (!response.ok) {
            tampilkanPesan(hasil.message, false);
            return;
        }

        tampilkanPesan(hasil.message, true);
        resetForm();
        tampilkanDosen(); // muat ulang tabel
    });

    // =====================================================
    // 4. HAPUS: DELETE /api/dosen/{id}
    // =====================================================
    async function hapusDosen(id) {
        if (!confirm('Yakin ingin menghapus dosen ini?')) {
            return;
        }

        const response = await fetch(`${API_URL}/${id}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json' }
        });
        const hasil = await response.json();

        tampilkanPesan(hasil.message, hasil.success);
        tampilkanDosen(); // muat ulang tabel
    }

    // Jalankan saat halaman dibuka
    tampilkanDosen();
</script>
@endpush
