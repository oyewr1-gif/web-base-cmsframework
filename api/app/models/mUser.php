<?php
class mUser extends Model {
    protected $table = 'users';
    // Hanya kolom 'nama' dan 'harga' yang boleh diisi melalui API
    protected $fillable = ['username', 'email', 'password', 'role'];

    // Skema tabel otomatis saat model pertama kali dipanggil
    protected $schema = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(20) DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    // Menimpa method all() bawaan agar kolom password tidak ikut tampil
    public function all() {
        $stmt = $this->db->query("SELECT id, username, email, role, created_at FROM {$this->table}");
        return $stmt->fetchAll();
    }

    // Menimpa method find() bawaan agar kolom password tidak ikut tampil
    public function find($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, role, created_at FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Mencari pengguna berdasarkan username
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    // Mencari pengguna berdasarkan email
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
}
