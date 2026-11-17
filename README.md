# SIPAS - Sistem Informasi Pengelolaan Sampah dan Penentuan Prioritas Pengangkutan

Aplikasi web berbasis **Laravel** dan **MySQL** yang menentukan urutan prioritas pengangkutan sampah di tiap lokasi/TPS memakai metode **Simple Additive Weighting (SAW)**.

## Anggota Kelompok

| Nama | NIM | Akun GitHub | Tugas |
|---|---|---|---|
| (isi) | (isi) | @(isi) | Setup project, autentikasi, role, layout |
| (isi) | (isi) | @(isi) | CRUD Lokasi dan Armada |
| (isi) | (isi) | @(isi) | CRUD Kriteria dan Data Sampah |
| (isi) | (isi) | @(isi) | SawService, halaman ranking, penugasan, pengujian |

**Link repository:** https://github.com/(isi)/(isi)

## Fitur

- Login dengan dua peran: **Admin** (semua fitur) dan **Petugas** (data sampah, perhitungan SAW, penugasan).
- CRUD: Lokasi/TPS, Armada, Kriteria dan bobot, Data kondisi sampah, Penugasan pengangkutan.
- Perhitungan SAW otomatis dengan **langkah lengkap** (kriteria, matriks X, normalisasi R, nilai V, ranking).
- Validasi: total bobot kriteria harus tepat 1 sebelum SAW berjalan.
- Simpan hasil ranking harian (riwayat), tombol "Jadwalkan Angkut" langsung dari ranking.
- Penugasan berstatus **Selesai** mereset hitungan "hari sejak terakhir diangkut".
- Dashboard ringkasan dan 5 prioritas teratas.

## Metode SAW

| Kode | Kriteria | Tipe | Bobot |
|---|---|---|---:|
| C1 | Volume sampah (kg) | Benefit | 0,35 |
| C2 | Jarak ke TPA (km) | Cost | 0,15 |
| C3 | Hari sejak terakhir diangkut | Benefit | 0,30 |
| C4 | Kepadatan penduduk sekitar | Benefit | 0,20 |

1. Matriks keputusan X dari data terbaru tiap lokasi.
2. Normalisasi: benefit `r = x / max(x)`, cost `r = min(x) / x`.
3. Nilai preferensi `V = Σ (w × r)`.
4. Urutkan V terbesar ke terkecil: peringkat 1 = diangkut paling dulu.

**Contoh (data seeder):**

| Lokasi | Volume | Jarak | Hari | Penduduk | V | Rank |
|---|---:|---:|---:|---:|---:|---:|
| TPS Lampineung | 500 | 10 | 5 | 3000 | 0,7593 | 1 |
| TPS Ulee Kareng | 400 | 8 | 7 | 2000 | 0,7538 | 2 |
| TPS Peunayong | 300 | 5 | 3 | 5000 | 0,6886 | 3 |

Logika SAW ada di `app/Services/SawService.php` (fungsi `proses()` murni tanpa database, diuji di `tests/Unit/SawServiceTest.php`).

## Rancangan Database

`users` (+role) · `locations` · `vehicles` · `criterias` · `waste_records` · `pickups` · `saw_results`

Relasi: `locations` 1-N `waste_records`, `locations` 1-N `pickups`, `vehicles` 1-N `pickups`, `locations` 1-N `saw_results`, `users` 1-N `waste_records`/`pickups`.

## Instalasi

Prasyarat: PHP 8.2+, Composer, MySQL, Git.

```bash
# 1. Buat project Laravel baru (11 atau 12), lalu salin file SIPAS
composer create-project laravel/laravel sipas
./install.sh ../sipas          # atau salin folder app, database, resources, routes, tests secara manual
cd ../sipas

# 2. Buat database di MySQL
mysql -u root -e "CREATE DATABASE sipas CHARACTER SET utf8mb4;"

# 3. Atur .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=sipas
#    DB_USERNAME=root
#    DB_PASSWORD=

# 4. Migrasi dan data contoh
php artisan migrate:fresh --seed

# 5. Jalankan
php artisan serve
```

Buka http://127.0.0.1:8000

| Peran | Email | Password |
|---|---|---|
| Admin | admin@sipas.test | password |
| Petugas | petugas@sipas.test | password |

Jalankan pengujian: `php artisan test`

## Skenario Demo

1. Login sebagai admin, lihat **Kriteria** (total bobot = 1).
2. Buka **Perhitungan SAW**: tunjukkan langkah 1-4 dan ranking, cocokkan dengan hitungan manual.
3. Ubah satu bobot sehingga total ≠ 1, SAW menolak berjalan.
4. Login petugas, input **Data Sampah** baru, ranking berubah.
5. Klik **Jadwalkan Angkut** pada peringkat 1, ubah status ke **Selesai**, lihat nilai hari C3 kembali ke 0 dan ranking berubah.

## Alur Kerja GitHub

- Satu repository kelompok, semua anggota ditambahkan sebagai *collaborator*.
- Tiap anggota bekerja di branch sendiri (`feature/...`) dengan akun masing-masing, lalu Pull Request ke `main`.
- Pesan commit jelas: `feat: tambah CRUD lokasi`, `fix: validasi bobot`, `docs: update README`.
- Commit berkala, bukan satu commit besar di akhir.

## Screenshot

(Tempel screenshot: login, dashboard, halaman SAW, CRUD, penugasan)

## Kesimpulan

SAW membuat penentuan prioritas pengangkutan menjadi objektif dan transparan karena setiap lokasi dinilai dari beberapa kriteria berbobot. Bobot dapat diubah admin tanpa mengubah kode.
