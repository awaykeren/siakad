# SIAKAD Sederhana — Laravel 12

Aplikasi Sistem Informasi Akademik (SIAKAD) sederhana untuk bahan praktikum Laravel. Aplikasi ini mengelola data **mahasiswa**, **dosen**, dan **mata kuliah**, serta berisi contoh **REST API** beserta halaman client yang memakai JavaScript `fetch()`.

## Fitur

| Menu | Keterangan |
|---|---|
| Dashboard | Ringkasan data |
| Mahasiswa | CRUD mahasiswa dengan Blade (pencarian, filter prodi, urut, paging) |
| Mahasiswa (REST API) | CRUD mahasiswa lewat `/api/mahasiswa` (Form Request, API Resource, paging) |
| Data Dosen | CRUD dosen dengan Blade |
| Dosen (REST API) | Contoh REST API sederhana lewat `/api/dosen` |
| Mata Kuliah | CRUD mata kuliah; satu dosen mengampu banyak mata kuliah |
| Login | Halaman login dan middleware `role` |

## Kebutuhan

- PHP **8.2** atau lebih baru (ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`)
- [Composer](https://getcomposer.org/)
- MySQL atau MariaDB (misalnya dari XAMPP)
- Git
- Node.js dan npm **tidak wajib**. Semua halaman SIAKAD memakai Bootstrap dari CDN.

## Cara Install

### 1. Clone repository

```bash
git clone https://github.com/awaykeren/siakad.git
cd siakad
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Buat file `.env`

Windows (PowerShell):

```powershell
Copy-Item .env.example .env
```

Git Bash / Linux / macOS:

```bash
cp .env.example .env
```

Lalu buat application key:

```bash
php artisan key:generate
```

### 4. Siapkan database

Buat database kosong bernama `siakad` melalui phpMyAdmin atau terminal MySQL:

```sql
CREATE DATABASE siakad;
```

Buka file `.env`, lalu ubah bagian database (secara bawaan masih `sqlite`). **Hapus tanda `#`** di depan baris `DB_HOST` sampai `DB_PASSWORD`, sehingga menjadi:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siakad
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_PORT`, `DB_USERNAME`, dan `DB_PASSWORD` dengan MySQL di komputer Anda. Jika MySQL berjalan di port lain (misalnya 3307), ganti `DB_PORT`.

### 5. Buat tabel dan isi data contoh

```bash
php artisan migrate
php artisan db:seed
php artisan db:seed --class=SiakadSeeder
php artisan db:seed --class=AdminUserSeeder
```

| Perintah | Isi data |
|---|---|
| `db:seed` | 50 mahasiswa acak |
| `--class=SiakadSeeder` | 3 dosen beserta mata kuliahnya |
| `--class=AdminUserSeeder` | Akun admin untuk login |

### 6. Jalankan aplikasi

```bash
php artisan serve
```

Buka di browser: <http://127.0.0.1:8000/dashboard>

## Akun Demo

| Email | Password |
|---|---|
| `admin@siakad.test` | `admin123` |

> Akun ini hanya untuk latihan. Jangan memakai password yang sama di server sungguhan.

## Alamat Penting

| Halaman | URL |
|---|---|
| Dashboard | <http://127.0.0.1:8000/dashboard> |
| Login | <http://127.0.0.1:8000/login> |
| Mahasiswa (REST API) | <http://127.0.0.1:8000/mahasiswa-api> |
| Dosen (REST API) | <http://127.0.0.1:8000/dosen-api> |
| JSON mahasiswa | <http://127.0.0.1:8000/api/mahasiswa> |
| JSON dosen | <http://127.0.0.1:8000/api/dosen> |

Jika memakai XAMPP tanpa `php artisan serve`, alamatnya menjadi `http://localhost/<nama-folder>/public/...`, misalnya `http://localhost/siakad/public/dashboard`.

## Endpoint REST API

Kirim header `Accept: application/json` pada setiap request. Untuk POST dan PUT, kirim body dalam format JSON.

### Mahasiswa — `/api/mahasiswa`

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/mahasiswa` | Daftar mahasiswa. Query opsional: `search`, `prodi`, `sort`, `direction`, `per_page`, `page` |
| POST | `/api/mahasiswa` | Tambah mahasiswa (`nim`, `nama`, `prodi`, `email`, `nohp`) |
| GET | `/api/mahasiswa/{id}` | Detail mahasiswa |
| PUT | `/api/mahasiswa/{id}` | Ubah mahasiswa |
| DELETE | `/api/mahasiswa/{id}` | Hapus mahasiswa |

### Dosen — `/api/dosen`

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/dosen` | Semua dosen |
| GET | `/api/dosen/{id}` | Detail dosen |
| POST | `/api/dosen` | Tambah dosen (`nidn`, `nama`, `prodi`) |
| PUT | `/api/dosen/{id}` | Ubah dosen |
| DELETE | `/api/dosen/{id}` | Hapus dosen (ditolak dengan status 409 jika masih mengampu mata kuliah) |

Contoh dengan curl:

```bash
curl -H "Accept: application/json" http://127.0.0.1:8000/api/dosen
```

## Menjalankan Test

Test memakai SQLite di memori, sehingga database `siakad` tidak tersentuh.

```bash
php artisan test
```

## Materi dan Panduan

| File | Isi |
|---|---|
| [docs/materi-rest-api-mahasiswa.docx](docs/materi-rest-api-mahasiswa.docx) | Materi REST API, dari perancangan endpoint sampai penerapan di client |
| [docs/tutorial-aplikasi-siakad.md](docs/tutorial-aplikasi-siakad.md) | Tutorial membangun aplikasi SIAKAD |
| [docs/panduan-git-lokal-mahasiswa.md](docs/panduan-git-lokal-mahasiswa.md) | Panduan Git lokal untuk mahasiswa |

## Masalah yang Sering Muncul

| Masalah | Penyebab dan solusi |
|---|---|
| `could not find driver` | Ekstensi `pdo_mysql` belum aktif. Aktifkan di `php.ini`, lalu restart terminal. |
| `SQLSTATE[HY000] [2002]` / connection refused | MySQL belum berjalan, atau `DB_HOST`/`DB_PORT` di `.env` salah. |
| `Unknown database 'siakad'` | Database belum dibuat (lihat langkah 4). |
| `No application encryption key has been specified` | Jalankan `php artisan key:generate`. |
| Perubahan `.env` tidak terbaca | Jalankan `php artisan config:clear`. |
| Dosen tidak bisa dihapus | Dosen masih mengampu mata kuliah. Hapus atau pindahkan mata kuliahnya terlebih dahulu. |
