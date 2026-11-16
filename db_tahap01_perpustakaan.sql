-- Sistem Perpustakaan Sekolah - Kelas XII RPL
DROP DATABASE IF EXISTS db_perpustakaan;
CREATE DATABASE db_perpustakaan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_perpustakaan;

CREATE TABLE kategori_buku(id_kategori INT AUTO_INCREMENT PRIMARY KEY,nama_kategori VARCHAR(100) NOT NULL);
CREATE TABLE buku(id_buku INT AUTO_INCREMENT PRIMARY KEY,kode_buku VARCHAR(20) NOT NULL UNIQUE,judul VARCHAR(150) NOT NULL,penulis VARCHAR(100) NOT NULL,penerbit VARCHAR(100),tahun_terbit YEAR,stok INT NOT NULL DEFAULT 0,id_kategori INT NOT NULL,FOREIGN KEY(id_kategori) REFERENCES kategori_buku(id_kategori));
CREATE TABLE anggota(id_anggota INT AUTO_INCREMENT PRIMARY KEY,nis VARCHAR(20) NOT NULL UNIQUE,nama VARCHAR(120) NOT NULL,kelas VARCHAR(20) NOT NULL,no_hp VARCHAR(20),alamat TEXT);
CREATE TABLE petugas(id_petugas INT AUTO_INCREMENT PRIMARY KEY,nama_petugas VARCHAR(120) NOT NULL,username VARCHAR(50) NOT NULL UNIQUE,password VARCHAR(255) NOT NULL);
CREATE TABLE peminjaman(id_peminjaman INT AUTO_INCREMENT PRIMARY KEY,id_anggota INT NOT NULL,id_petugas INT NOT NULL,tanggal_pinjam DATE NOT NULL,tanggal_jatuh_tempo DATE NOT NULL,status ENUM('dipinjam','selesai') DEFAULT 'dipinjam',FOREIGN KEY(id_anggota) REFERENCES anggota(id_anggota),FOREIGN KEY(id_petugas) REFERENCES petugas(id_petugas));
CREATE TABLE detail_peminjaman(id_detail INT AUTO_INCREMENT PRIMARY KEY,id_peminjaman INT NOT NULL,id_buku INT NOT NULL,jumlah INT DEFAULT 1,tanggal_kembali DATE NULL,denda DECIMAL(12,2) DEFAULT 0,FOREIGN KEY(id_peminjaman) REFERENCES peminjaman(id_peminjaman) ON DELETE CASCADE,FOREIGN KEY(id_buku) REFERENCES buku(id_buku));
INSERT INTO kategori_buku(nama_kategori) VALUES('Pelajaran'),('Novel'),('Teknologi'); INSERT INTO petugas(nama_petugas,username,password) VALUES('Admin Perpustakaan','admin','admin123');
