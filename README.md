# 🎓 SISTA - Sistem Informasi Skripsi & Tugas Akhir

**SISTA** adalah aplikasi berbasis web yang dikembangkan untuk Universitas Insan Cita Indonesia (UICI) guna mendigitalkan proses administrasi tugas akhir. Aplikasi ini mempermudah Mahasiswa, Dosen, dan Admin dalam mengelola pengajuan judul, bimbingan, hingga penilaian sidang secara terintegrasi dan *paperless*.

---

## 🚀 Fitur Unggulan

Aplikasi ini memiliki 3 hak akses (Role) dengan fitur spesifik:

### 👨‍🎓 Mahasiswa

* **Pengajuan Proposal:** Upload judul dan file proposal (PDF) secara online.
* **Bimbingan Daring/Luring:** Mengajukan jadwal bimbingan dan mendapatkan *link meet* jika daring.
* **Upload Dokumen Akhir:** Mengunggah file skripsi per Bab (Bab 1, Bab 2, dst) untuk direview dosen.
* **Cek Nilai & Status:** Melihat transkrip nilai proposal, skripsi, dan status revisi secara *real-time*.
* **Download Template:** Mengunduh panduan penulisan skripsi resmi.

### 👩‍🏫 Dosen Pembimbing

* **Review Proposal:** Menerima atau menolak proposal dengan catatan revisi.
* **Kelola Jadwal:** Menyetujui permintaan bimbingan mahasiswa.
* **Input Nilai:** Memberikan nilai (Grade A-E) untuk proposal dan setiap bab dokumen akhir.
* **Monitoring:** Memantau progres mahasiswa bimbingan lewat dashboard.

### 👮 Admin Prodi

* **Manajemen User:** Mengelola data Dosen dan Mahasiswa.
* **Pengaturan Sistem:** Mengelola jadwal sidang, pengumuman, dan template dokumen.

---

## 🛠 Teknologi yang Digunakan

* **Framework Backend:** [Laravel 11](https://laravel.com) (PHP)
* **Database:** MySQL
* **Frontend Styling:** [Tailwind CSS](https://tailwindcss.com) (Modern & Responsif)
* **Interaktivitas:** [Alpine.js](https://alpinejs.dev) (Ringan & Cepat)
* **Notifikasi:** [SweetAlert2](https://sweetalert2.github.io/) (Pop-up cantik)

---

## 💻 Persiapan Awal (Prerequisites)

Sebelum menjalankan aplikasi, pastikan komputer Anda sudah terinstall:

1. **XAMPP / Laragon**: Untuk menjalankan PHP dan Database MySQL.
* *Pastikan PHP versi 8.2 atau lebih baru.*


2. **Composer**: Untuk menginstall *library* PHP (Laravel). [Download di sini](https://getcomposer.org/).
3. **Node.js & NPM**: Untuk mengurus tampilan (CSS/JS). [Download di sini](https://nodejs.org/).
4. **Git**: (Opsional) Untuk mengunduh kode program.

---

## ⚙️ Cara Instalasi (Langkah demi Langkah)

Ikuti langkah ini satu per satu di **Terminal** (Command Prompt / PowerShell / Git Bash):

### 1. Download/Clone Project

Buka terminal di folder tujuan (misal: `C:\xampp\htdocs`), lalu jalankan:

```bash
git clone https://github.com/risky037/SISTA.git
cd sista

```

### 2. Install Library PHP (Backend)

Perintah ini akan mendownload semua kebutuhan Laravel:

```bash
composer install

```

### 3. Install Library Javascript (Frontend)

Perintah ini menyiapkan alat untuk tampilan web:

```bash
npm install

```

### 4. Konfigurasi Environment

Duplikat file pengaturan `.env.example` menjadi `.env`:

```bash
cp .env.example .env

```

*(Atau lakukan manual: copy file `.env.example`, paste, lalu rename jadi `.env`)*

### 5. Generate Key Aplikasi

Ini adalah kunci keamanan enkripsi Laravel:

```bash
php artisan key:generate

```

### 6. Konfigurasi Database

1. Buka **XAMPP**, nyalakan **Apache** dan **MySQL**.
2. Buka `phpMyAdmin` (biasanya di `http://localhost/phpmyadmin`).
3. Buat database baru dengan nama: **`sista_db`**.
4. Buka file `.env` di teks editor (VS Code), cari bagian ini dan sesuaikan:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sista_db
DB_USERNAME=root
DB_PASSWORD=

```



### 7. Migrasi & Data Dummy (Seeding)

Perintah ini akan membuat tabel-tabel di database dan mengisinya dengan akun contoh (Admin/Dosen/Mahasiswa):

```bash
php artisan migrate --seed

```

### 8. Link Storage

Agar file proposal/foto yang diupload bisa dibuka di browser:

```bash
php artisan storage:link

```

---

## ▶️ Cara Menjalankan Aplikasi

Anda perlu membuka **2 Terminal** berbeda agar aplikasi berjalan lancar:

**Terminal 1 (Menjalankan Server PHP):**

```bash
php artisan serve

```

*Jangan tutup terminal ini. Aplikasi akan berjalan di `http://127.0.0.1:8000*`

**Terminal 2 (Menjalankan Compile CSS/JS):**

```bash
npm run dev

```

*Ini penting agar tampilan (Tailwind CSS) muncul dengan benar.*

---

## 🔑 Akun Login (Default)

Jika Anda menjalankan perintah `php artisan migrate --seed`, database akan terisi akun berikut (Cek `database/seeders/DummyDataSeeder.php` untuk lebih detail):

| Role | Email / NIM | Password |
| --- | --- | --- |
| **Admin** | `admin@email.com` | `password` |
| **Dosen** | `dosen@email.com` | `password` |
| **Mahasiswa** | `12345678901` | `password` |

*(silakan cek seeder jika gagal).*

---

## ❓ Troubleshooting (Masalah Umum)

**1. Tampilan berantakan / Putih Polos?**

* Pastikan `npm run dev` sedang berjalan di terminal kedua.

**2. Error "No application encryption key has been specified"?**

* Jalankan `php artisan key:generate`.

**3. Error Database "Connection Refused"?**

* Pastikan XAMPP (MySQL) sudah dinyalakan.
* Cek nama database di file `.env` apakah sudah sesuai dengan yang di phpMyAdmin.

**4. Gagal Upload File?**

* Pastikan folder `storage/app/public` ada.
* Coba jalankan ulang `php artisan storage:link`.

