# <img src="./public/assets/images/logo.png" width="28" height="28" align="absmiddle" /> Aplikasi Manajemen Perpustakaan Sekolah

Aplikasi Perpustakaan Sekolah adalah sistem informasi berbasis web yang dirancang untuk mempermudah pengelolaan data buku, anggota (siswa/guru), transaksi peminjaman dan pengembalian, serta pembuatan laporan perpustakaan secara digital.

### Tim Pengembang
- **Rafif ( Reeve )** - Fullstack Frontend & Backend Developer
- **Fizzahra (Fizza )** - Model / Database & Features QA Developer
- **Farid** - Backend Developer & Features QA Developer

---

## <img src="./public/assets/images/maintenance.png" width="24" height="24" align="absmiddle" /> Tech Stack (Teknologi yang Digunakan)

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter_4-EF4223?style=for-the-badge&logo=codeigniter&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

- **Backend / Bahasa Pemrograman:** PHP (CodeIgniter 4)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap, Morvin Template
- **Server Lokal:** XAMPP / Laragon

---



## <img src="./public/assets/images/coming-soon.png" width="24" height="24" align="absmiddle" /> Cara Instalasi & Menjalankan Proyek

1. **Clone atau Unduh Repository ini**
   ```bash
   git clone [https://github.com/reevemore/perpustakaan_sekolah.git](https://github.com/reevemore/perpustakaan_sekolah.git)
Gunakan kode dengan hati-hati.
2. **Pindahkan Folder**
Simpan folder proyek ke dalam direktori server lokal kamu (Contoh: htdocs jika menggunakan XAMPP).
3. **Import Database**
	• Buka phpMyAdmin di browser (http://localhost/phpmyadmin).
	• Buat database baru dengan nama: db_perpustakaan.
	• Import file SQL (db_perpustakaan.sql) yang tersedia di dalam folder proyek.
4. **Konfigurasi Koneksi Database**
Sesuaikan pengaturan database pada file env
database.default.hostname = localhost
database.default.database = nama_database
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
5. **Jalankan Aplikasi**
Buka browser dan akses alamat:text
http://localhost/perpus-sekolah
Gunakan kode dengan hati-hati.

 Hak Akses Default (Login)

• Username Admin: admin
• Password: admin123 (Sesuaikan dengan pengaturan database Kamu)
