📚 Aplikasi Manajemen Perpustakaan Sekolah

Aplikasi Perpustakaan Sekolah adalah sistem informasi berbasis web yang dirancang untuk mempermudah pengelolaan data buku, anggota (siswa/guru), transaksi peminjaman dan pengembalian, serta pembuatan laporan perpustakaan secara digital.

Tim yang mengembangkan aplikasi ini :
• Saya Sendiri ( Reeve ) - Fullstack Frontend & Backend Developer
• Fizzahra ( Fizza ) - Model / Database & Features Qa Developer
• Farid ( Farid ) - Backend Developer & Features Qa Devleoper


🛠️ Tech Stack (Teknologi yang Digunakan)

• Backend / Bahasa Pemrograman: PHP ( CodeIgniter4 )
• Database: MySQL / MariaDB
• Frontend: HTML5, CSS3, JavaScript, Bootstrap, Morvin Template
• Server Lokal: XAMPP / Laragon


🚀 Cara Instalasi & Menjalankan Proyek

1. Clone atau Unduh Repository inibash
git clone https://github.com/reevemore/perpustakaan_sekolah.git
Gunakan kode dengan hati-hati.
2. Pindahkan Folder
Simpan folder proyek ke dalam direktori server lokal kamu (Contoh: htdocs jika menggunakan XAMPP).
3. Import Database
	• Buka phpMyAdmin di browser (http://localhost/phpmyadmin).
	• Buat database baru dengan nama: db_perpustakaan.
	• Import file SQL (db_perpustakaan.sql) yang tersedia di dalam folder proyek.
4. Konfigurasi Koneksi Database
Sesuaikan pengaturan database pada file config/database.php:php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_perpustakaan";
Gunakan kode dengan hati-hati.
5. Jalankan Aplikasi
Buka browser dan akses alamat:text
http://localhost/perpus-sekolah
Gunakan kode dengan hati-hati.

🔑 Hak Akses Default (Login)

• Username Admin: admin
• Password: admin123 (Sesuaikan dengan pengaturan database Kamu)

🤝 Kontribusi

Kontribusi, saran, dan masukan sangat dipersilakan. Silakan buat Pull Request atau ajukan Issue jika menemukan bug pada sistem ini.