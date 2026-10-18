# Tutorial Aplikasi SIAKAD Sederhana

Tutorial ini mengikuti struktur proyek Laravel 13 ini. Aplikasi mengelola mahasiswa, dosen, dan mata kuliah. Relasi yang sudah dibuat adalah satu dosen mengampu banyak mata kuliah; fitur KRS mahasiswa belum termasuk.

## 1. Persiapan Lingkungan

### Kebutuhan

- PHP 8.3 atau lebih baru, sesuai `composer.json`.
- Composer.
- MySQL/MariaDB dan server lokal seperti XAMPP.
- Node.js dan npm hanya jika akan mengompilasi aset frontend proyek.

Buka terminal pada folder proyek:

```powershell
cd D:\htdocs\laravel13-app
composer install
```

Jika `.env` belum tersedia, salin konfigurasi contoh dan buat application key:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Buat database kosong, misalnya `laravel13_app`, kemudian atur `.env` sesuai akun MySQL lokal:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel13_app
DB_USERNAME=root
DB_PASSWORD=
```

Jangan membagikan file `.env` yang berisi kredensial.

## 2. Membuat Tabel dengan Migration

Migration berada di `database/migrations/`. Struktur tabel aplikasi:

| Tabel | Kolom utama | Keterangan |
|---|---|---|
| `mahasiswa` | `nim`, `nama`, `prodi`, `email`, `nohp` | NIM unik; email dan nomor HP boleh kosong |
| `dosen` | `nidn`, `nama`, `prodi` | NIDN unik |
| `mata_kuliah` | `kode`, `nama`, `sks`, `dosen_id` | Kode unik; `dosen_id` foreign key ke `dosen.id` |

Contoh definisi kolom relasi di migration mata kuliah:

```php
$table->foreignId('dosen_id')
    ->constrained('dosen')
    ->restrictOnDelete();
```

`restrictOnDelete()` menjaga agar dosen yang masih memiliki mata kuliah tidak dihapus begitu saja. Migration dosen membuat tabel khusus bernama `dosen`; fungsi `down()` menghapus tabel yang sama.

Jalankan migration:

```powershell
php artisan migrate
```

`migrate` hanya mengubah struktur tabel, tidak otomatis menjalankan seeder. `DatabaseSeeder` memanggil `MahasiswaSeeder` untuk membuat 50 mahasiswa. Data contoh dosen dan mata kuliah ada di `SiakadSeeder` dan saat ini perlu dipanggil langsung:

```powershell
php artisan db:seed
php artisan db:seed --class=SiakadSeeder
```

Hindari `migrate:fresh` pada database berisi data karena perintah itu menghapus seluruh tabel.

## 3. Membuat Model dan Relasi Eloquent

Model menghubungkan kode PHP dengan tabel database. `$fillable` membatasi kolom yang boleh diisi melalui `create()` atau `update()`.

### Mahasiswa

`app/Models/Mahasiswa.php` menggunakan tabel `mahasiswa`, `HasFactory`, dan atribut `nim`, `nama`, `prodi`, `nohp`, `email` pada `$fillable`.

### Dosen

`app/Models/Dosen.php` menetapkan nama tabel khusus `dosen`, atribut `nidn`, `nama`, `prodi`, serta relasi satu ke banyak:

```php
public function mataKuliah(): HasMany
{
    return $this->hasMany(MataKuliah::class, 'dosen_id');
}
```

### MataKuliah

`app/Models/MataKuliah.php` menggunakan tabel `mata_kuliah`, atribut `kode`, `nama`, `sks`, `dosen_id`, dan relasi balik:

```php
public function dosen(): BelongsTo
{
    return $this->belongsTo(Dosen::class, 'dosen_id');
}
```

## 4. View Layout

Layout bersama ada di `resources/views/layouts/app.blade.php`. File ini menyediakan kerangka HTML, Bootstrap 5, navbar, sidebar, area konten `@yield('content')`, serta slot `@stack('styles')` dan `@stack('scripts')`.

Komponen navigasi berada di:

- `resources/views/components/navbar.blade.php`
- `resources/views/components/sidebar.blade.php`

Setiap halaman memakai layout bersama, misalnya:

```blade
@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    {{-- Konten halaman --}}
@endsection
```

Sidebar menghubungkan pengguna ke route bernama dashboard, mahasiswa, dosen, dan mata kuliah. Memakai `route('mahasiswa.index')` membuat tautan tidak bergantung pada URL yang ditulis manual.

## 5. Dashboard

Route `/dashboard` di `routes/web.php` memanggil `DashboardController@index`. Controller menghitung jumlah data aktual dari database:

```php
$jumlahMahasiswa = Mahasiswa::count();
$jumlahDosen = Dosen::count();
$jumlahMataKuliah = MataKuliah::count();
```

Nilai dikirim ke `resources/views/dashboard.blade.php`, yang menampilkan ringkasan dalam tiga kartu. File utama dashboard:

- Route: `routes/web.php`
- Controller: `app/Http/Controllers/DashboardController.php`
- View: `resources/views/dashboard.blade.php`

## 6. CRUD Mahasiswa

CRUD mahasiswa mengelola NIM, nama, program studi, email, dan nomor HP.

### Model

`app/Models/Mahasiswa.php` menetapkan tabel `mahasiswa`, `$fillable`, dan `HasFactory` agar factory/seeder dapat membuat data contoh.

### Controller

`app/Http/Controllers/MahasiswaController.php` menangani operasi berikut:

- `index()`: mencari, memfilter, mengurutkan, dan membuat pagination daftar.
- `create()` dan `store()`: menampilkan form dan menyimpan data tervalidasi.
- `edit()` dan `update()`: memuat serta memperbarui mahasiswa. Validasi NIM unik mengabaikan record yang sedang diedit.
- `destroy()`: menghapus mahasiswa.

Validasi memastikan NIM unik, nama dan prodi wajib, email berformat benar bila diisi, dan panjang nilai sesuai batas kolom database.

Pencarian mencakup NIM, nama, program studi, email, dan nomor HP. Filter memilih program studi. Kolom pengurutan dibatasi ke NIM/nama/prodi dan arahnya ke `asc` atau `desc`. Daftar menampilkan 10 record per halaman:

```php
$mahasiswa = $query->orderBy($sort, $direction)
    ->paginate(10)
    ->withQueryString();
```

`withQueryString()` menjaga kata pencarian, filter, dan pengurutan ketika pengguna berpindah halaman.

### View

View berada di `resources/views/mahasiswa/`:

- `index.blade.php`: tabel mahasiswa, form pencarian/filter/pengurutan, pagination, dan tombol aksi.
- `create.blade.php`: form tambah.
- `edit.blade.php`: form edit dengan method `PUT`.
- `_form.blade.php`: partial field bersama untuk tambah dan edit.

Form memakai `@csrf`; penghapusan memakai `@method('DELETE')`. Pesan validasi tampil dekat field masing-masing.

## 7. CRUD Dosen

CRUD dosen mengelola NIDN, nama, dan program studi. Relasinya satu dosen memiliki banyak mata kuliah.

### Model

`app/Models/Dosen.php` menentukan tabel `dosen`, atribut `$fillable`, dan `mataKuliah(): HasMany`. Relasi eksplisit memakai foreign key `dosen_id`.

### Controller

`app/Http/Controllers/DosenController.php` menangani daftar, form tambah/edit, penyimpanan, pembaruan, dan penghapusan. `index()` memuat relasi dengan `Dosen::with('mataKuliah')` agar data mata kuliah tersedia tanpa query tambahan untuk setiap dosen.

Daftar dosen mendukung pencarian NIDN/nama/prodi, filter prodi, pengurutan NIDN/nama/prodi, serta pagination 10 data per halaman. Nama kolom sorting dipilih dari whitelist. `withQueryString()` mempertahankan parameter saat pindah halaman.

### View

View berada di `resources/views/dosen/`:

- `index.blade.php`: tabel dosen, mata kuliah pengampu, pencarian/filter/sorting/pagination, dan aksi.
- `create.blade.php`: form tambah dosen.
- `edit.blade.php`: form perubahan data dosen.

Kolom mata kuliah menampilkan kode dan nama tiap mata kuliah. Jika belum ada relasi, halaman menampilkan keterangan bahwa dosen belum memiliki mata kuliah. Foreign key membatasi penghapusan dosen yang masih menjadi pengampu.

## 8. CRUD Mata Kuliah

CRUD mata kuliah mengelola kode, nama, jumlah SKS, serta dosen pengampu. Pengguna membuat dosen lebih dahulu, lalu memilih dosen dari form mata kuliah.

### Model

`app/Models/MataKuliah.php` menetapkan tabel `mata_kuliah`, `$fillable` termasuk `dosen_id`, dan relasi `dosen(): BelongsTo`.

### Controller

`app/Http/Controllers/MataKuliahController.php` menyediakan daftar, tambah, simpan, edit, update, dan hapus. `index()` memuat dosen dengan `MataKuliah::with('dosen')`. Form tambah/edit mengambil daftar dosen dari database.

Validasi memeriksa kode unik, nama wajib, SKS bilangan bulat, serta `dosen_id` harus cocok dengan record pada tabel dosen. Saat update, validasi kode unik mengabaikan mata kuliah yang sedang diedit.

### View

View berada di `resources/views/mata-kuliah/`:

- `index.blade.php`: tabel kode, nama, SKS, dosen pengampu, dan aksi CRUD.
- `create.blade.php`: form mata kuliah baru.
- `edit.blade.php`: form pembaruan.
- `_form.blade.php`: partial field termasuk dropdown dosen.

### DataTables dengan jQuery

Daftar mata kuliah menggunakan DataTables di browser. CSS DataTables Bootstrap 5 dimasukkan melalui `@push('styles')`; jQuery 3.7.1 dimuat sebelum file DataTables core dan adapter Bootstrap 5 melalui `@push('scripts')`.

Inisialisasi memakai plugin jQuery dan bahasa bawaan DataTables:

```javascript
$('#mata-kuliah-table').DataTable({
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    order: [[2, 'asc']],
    columnDefs: [
        { targets: [0, 5], orderable: false, searchable: false }
    ]
});
```

DataTables menyediakan search, sorting, pengaturan jumlah baris, dan pagination di browser. Kolom nomor dan aksi dikecualikan dari pencarian/pengurutan; nomor baris dihitung ulang setiap kali tabel digambar. Bahasa memakai default karena tidak ada pengaturan `language`. Tabel kosong ditangani oleh DataTables, jadi jangan tambahkan row `colspan` di `<tbody>`.

## 9. Cara Menjalankan Aplikasi

Pastikan MySQL berjalan, database pada `.env` tersedia, dan migration sudah diterapkan. Jalankan server pengembangan Laravel:

```powershell
php artisan serve
```

Buka URL lokal yang dicetak oleh Artisan, biasanya `http://127.0.0.1:8000`. Untuk mencoba aplikasi, buka `/dashboard`, `/mahasiswa`, `/dosen`, atau `/mata-kuliah`.

Alur relasi yang disarankan:

1. Tambahkan dosen melalui `/dosen/create`.
2. Tambahkan mata kuliah melalui `/mata-kuliah/create` dan pilih dosen pengampu.
3. Periksa mata kuliah di halaman daftar dosen.
4. Tambahkan mahasiswa melalui `/mahasiswa/create`.

Halaman index mata kuliah memuat DataTables dari CDN, sehingga browser perlu koneksi internet untuk memuat aset jQuery/DataTables.

## 10. Pengecekan Aplikasi

Periksa alur ini di browser:

1. Pastikan tiap halaman daftar terbuka saat tabel berisi data atau kosong.
2. Buat data valid untuk mahasiswa, dosen, dan mata kuliah; pastikan record tampil.
3. Kirim form tanpa field wajib dan periksa pesan validasi.
4. Coba NIM, NIDN, atau kode mata kuliah duplikat.
5. Edit record tanpa mengganti nilai uniknya.
6. Cari, filter, urutkan, dan pindah halaman pada daftar mahasiswa/dosen.
7. Pada mata kuliah, uji search, sorting, jumlah baris, pagination, serta kolom aksi DataTables.
8. Pastikan mata kuliah yang dibuat muncul pada halaman dosen yang dipilih.
9. Coba menghapus dosen yang masih mengampu mata kuliah; relasi foreign key harus mencegahnya.

## 11. Perintah Artisan yang Berguna

```powershell
php artisan route:list
php artisan migrate:status
php artisan migrate
php artisan db:seed
php artisan db:seed --class=SiakadSeeder
php artisan serve
```

`php artisan migrate:rollback` membatalkan batch migration terakhir; gunakan hanya jika paham dampaknya. Hindari `migrate:fresh` pada database yang datanya ingin dipertahankan.
