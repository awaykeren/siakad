# Panduan Git dan Menjalankan SIAKAD di Localhost Mahasiswa

Panduan ini menjelaskan cara mengambil source code dari GitHub, menjalankan aplikasi Laravel 13 di komputer Windows masing-masing, dan bekerja menggunakan Git tanpa mengubah branch utama bersama. Repository kelas: [github.com/awaykeren/siakad](https://github.com/awaykeren/siakad).

## 1. Siapkan perangkat lunak

Pastikan komputer memiliki:

- Git for Windows.
- PHP 8.3 atau lebih baru dan Composer.
- MySQL/MariaDB; XAMPP dapat dipakai untuk menyediakan MySQL dan phpMyAdmin.
- Browser web.

Pastikan Git, PHP, dan Composer dapat dipanggil dari PowerShell. Contoh pengecekan:

```powershell
git --version
php --version
composer --version
```

## 2. Clone repository

Buka PowerShell, lalu clone repository ke folder kerja. Jangan clone ke dalam project Laravel lain.

```powershell
cd D:\htdocs
git clone https://github.com/awaykeren/siakad.git
cd siakad
```

Perintah `git clone` membuat folder `D:\htdocs\siakad` dan mengunduh branch `main`. Jika folder `siakad` sudah ada, pilih folder tujuan lain atau pindahkan/rename folder lama terlebih dahulu.

## 3. Siapkan dependency dan file environment

File `.env` tidak disimpan di Git karena setiap komputer memakai konfigurasi dan password lokal. Buat `.env` dari file contoh, lalu pasang dependency Laravel:

```powershell
Copy-Item .env.example .env
composer install
php artisan key:generate
```

Jangan mengunggah atau membagikan `.env`. Jangan menjalankan `git add .env`.

## 4. Buat database lokal

Jalankan MySQL dari XAMPP. Di phpMyAdmin, buat database baru, misalnya `siakad_local`. Kemudian buka `.env` dan atur koneksi database. Contoh konfigurasi XAMPP default:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siakad_local
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL memakai password atau port berbeda, sesuaikan nilainya. Setelah mengubah `.env`, konfigurasi Laravel dapat dibersihkan dengan:

```powershell
php artisan config:clear
```

## 5. Buat tabel dan data contoh

Jalankan migration untuk membuat tabel:

```powershell
php artisan migrate
```

Seeder default membuat 50 data mahasiswa. Seeder dosen dan mata kuliah dijalankan dengan perintah terpisah:

```powershell
php artisan db:seed
php artisan db:seed --class=SiakadSeeder
```

Urutan ini membuat dosen serta mata kuliah pengampu, selain mahasiswa. Untuk database baru, jangan memakai `migrate:fresh` kecuali memang siap menghapus seluruh tabel dan datanya.

## 6. Jalankan aplikasi di localhost

Dari folder proyek, jalankan server Laravel:

```powershell
php artisan serve
```

Buka alamat yang dicetak Artisan, biasanya [http://127.0.0.1:8000](http://127.0.0.1:8000). Menu utama tersedia di:

- `/dashboard`
- `/mahasiswa`
- `/dosen`
- `/mata-kuliah`

Untuk menghentikan server, tekan `Ctrl+C` di terminal. MySQL harus tetap aktif saat aplikasi memakai database.

Saat ini Bootstrap dan DataTables dimuat dari CDN, jadi browser perlu koneksi internet untuk menampilkan gaya halaman dan DataTables. `npm install` tidak diperlukan untuk menjalankan CRUD yang sudah ada. Jika nanti mengubah aset Vite/CSS/JavaScript proyek, pasang Node.js lalu jalankan `npm install` dan `npm run dev`.

## 7. Workflow Git untuk tugas individu

Repository kelas dapat dibaca dan di-clone semua mahasiswa, tetapi mahasiswa biasanya tidak memiliki izin push langsung ke branch `main` milik repository kelas. Gunakan **fork** untuk mengirim tugas:

1. Buka repository kelas di GitHub dan klik **Fork**.
2. Clone fork milik akun sendiri. Ganti `USERNAME` dengan nama akun GitHub mahasiswa.

```powershell
cd D:\htdocs
git clone https://github.com/USERNAME/siakad.git
cd siakad
git remote add upstream https://github.com/awaykeren/siakad.git
git remote -v
```

Jika Git belum mengetahui identitas pembuat commit, atur nama dan email GitHub di komputer sendiri:

```powershell
git config --global user.name "Nama Mahasiswa"
git config --global user.email "email-github@example.com"
```

`origin` menunjuk ke fork mahasiswa; `upstream` menunjuk ke repository kelas. Siapkan `.env`, dependency, dan database pada clone tersebut seperti langkah sebelumnya.

Buat branch baru untuk setiap tugas, lalu commit dan push ke fork:

```powershell
git switch -c fitur/nama-tugas
# edit file aplikasi
git status
git add app resources routes database docs
git commit -m "Tambahkan fitur yang dikerjakan"
git push -u origin fitur/nama-tugas
```

Setelah push, buka GitHub dan buat Pull Request dari branch di fork menuju branch `main` pada `awaykeren/siakad`. Jangan push tugas langsung ke `main` repository kelas.

## 8. Ambil pembaruan dari repository kelas

Sebelum mulai tugas baru, ambil perubahan terbaru dari upstream:

```powershell
git switch main
git pull upstream main
git switch fitur/nama-tugas
git merge main
```

Jika Git menyatakan ada konflik, buka file yang ditandai, pilih hasil gabungan yang benar, kemudian:

```powershell
git add nama-file-yang-sudah-diperbaiki
git commit -m "Selesaikan konflik merge"
git push
```

Jika tidak memakai fork dan hanya meng-clone repository kelas untuk belajar, perbarui salinan lokal dengan `git pull origin main`. Clone biasa bersifat untuk membaca; perubahan tetap berada di komputer sampai di-commit dan dikirim ke remote yang dapat ditulisi.

## 9. Perintah Git yang sering digunakan

```powershell
git status
git branch
git switch nama-branch
git add nama-file
git commit -m "Pesan commit"
git pull upstream main
git push
```

Gunakan `git status` sebelum commit agar tahu file yang berubah. Commit hanya source code yang diperlukan. Pastikan `.env`, password, token, dan database lokal tidak ikut commit.

## 10. Troubleshooting

- **`composer` atau `php` tidak dikenali**: pastikan PHP/Composer terpasang dan PATH Windows sudah dikonfigurasi; buka terminal baru setelah mengubah PATH.
- **`No application encryption key has been specified`**: jalankan `php artisan key:generate`.
- **Database connection refused**: pastikan MySQL XAMPP aktif, lalu cek host, port, nama database, username, dan password di `.env`.
- **Access denied for user**: kredensial di `.env` tidak cocok dengan akun MySQL lokal.
- **Tabel belum ada**: jalankan `php artisan migrate`.
- **Dosen belum tersedia di pilihan mata kuliah**: jalankan `php artisan db:seed --class=SiakadSeeder` atau tambahkan dosen melalui menu Dosen.
- **Port 8000 sedang digunakan**: jalankan `php artisan serve --port=8001`, lalu buka `http://127.0.0.1:8001`.
- **Push ditolak**: pastikan remote `origin` adalah fork sendiri, branch bukan `main`, dan akun GitHub sudah terautentikasi melalui Git Credential Manager.

## 11. Ringkasan alur pertama kali

```powershell
cd D:\htdocs
git clone https://github.com/awaykeren/siakad.git
cd siakad
Copy-Item .env.example .env
composer install
php artisan key:generate
# Buat database siakad_local di phpMyAdmin, lalu atur .env
php artisan migrate
php artisan db:seed
php artisan db:seed --class=SiakadSeeder
php artisan serve
```
