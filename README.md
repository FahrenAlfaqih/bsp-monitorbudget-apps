# 📊 Sistem Pengelola Anggaran Perjalanan Dinas

Sistem ini dikembangkan untuk membantu Departemen HCM dalam memonitor dan merekap anggaran perjalanan dinas (SPD) serta DPD (Deklarasi Perjalanan Dinas). Sistem ini memungkinkan pengelolaan data perjalanan dinas, status pengajuan, penerbitan DPD dari Finance, dan pembuatan laporan Excel untuk keperluan rekapitulasi.

## 🎯 Tujuan Sistem

-   Memantau realisasi anggaran perjalanan dinas tiap departemen
-   Mempermudah proses rekapitulasi dan pelaporan DPD
-   Mengurangi kesalahan input dan perhitungan manual
-   Menyediakan informasi status SPD dan DPD secara real-time

## 🧪 Contoh Alur Kerja

-   Admin HCM login ke dalam sistem
-   Kelola data departemen dan data pegawai
-   Membuat rekapan SPD yang akan diajukan
-   Mengajukan SPD
-   Admin FINEC menyetujuiu/menolak pengajuan, jika ditolak maka menerbitkan dpd dan jika tidak status spd menjadi ditolak dan bisa diajukan kembbali
-   Setelah DPD terbit, pada setiap dashboard admin Departemen akan muncul data statistik karyawan yang melaksanakan dinas.

## 🧩 Fitur Utama

-   Login sistem untuk admin HCM, admin departemen, Technical Manager HCM
-   Manajemen data SPD (tambah, ubah, filter)
-   Monitoring sisa anggaran tiap departemen
-   Peringatan otomatis jika melebihi anggaran
-   Dashboard status pengajuan dan pelaporan

## 🔧 Teknologi yang Digunakan

-   Laravel 10 (PHP Framework)
-   MySQL / PhpMyadmin
-   Tailwind CSS + SweetAlert2 (Frontend UI)
-   Laravel Excel (untuk ekspor data ke format excel)
-   doompdf (untuk ekspor data ke format pdf)

## 🛠️ Instalasi dan Konfigurasi

1. Clone project:

    ```bash
    git clone https://github.com/FahrenAlFaqih/bsp-final-project.git
    cd bsp-final-project

    ```

2. composer install
3. cp .env.example .env
4. php artisan migrate
5. php artisan db:seed
6. php artisan serve (terminal)
7. npm run build (git bash)
8. npm install && npm run dev (git bash)

## 🧑‍💼 Role Pengguna

Admin HCM: mengelola data departemen, pegawai, spd dan dpd
Admin FINEC : menerbitkan DPD,
Technical Manager HCM : mengelola periode pengajuan anggaran, mengelola rancangan anggaran perjalanan dinas
Admin Departemen selain HCM dan FINEC : melakukan rancangan anggaran perjalanan dinas, melihat spd dan dpd per departemen

(Optional) Viewer: hanya melihat laporan dan grafik

## 📜 Lisensi

Proyek ini dikembangkan untuk kebutuhan internal PT. Bumi Siak Pusako. Tidak diperkenankan mendistribusikan ulang tanpa izin resmi.
