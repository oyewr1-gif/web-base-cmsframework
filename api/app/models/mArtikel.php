<?php
class mArtikel extends Model {
    protected $table = 'artikel'; 
    // Hanya kolom 'judul',' slug', 'konten', 'kategori_id' dan 'status' yang boleh diisi melalui API
    protected $fillable = ['judul', 'slug', 'konten', 'kategori_id', 'status']; 
    
    // Tentukan cetakan (skema) SQL untuk tabel ini jika belum ada
    protected $schema = "
        CREATE TABLE IF NOT EXISTS artikel (
            id INT AUTO_INCREMENT PRIMARY KEY,
            judul VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            konten TEXT NOT NULL,
            kategori_id INT NOT NULL,     
            status ENUM('draft','publish') NOT NULL,     
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
}
