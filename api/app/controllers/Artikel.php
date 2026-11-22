<?php
class Artikel extends Controller {
    // Bebaskan akses publik hanya untuk melihat daftar dan detail produk
    protected $exceptAuth = ['index', 'detail'];

    // Aturan validasi otomatis saat method store() dipanggil
    protected $validationRules = [
        'judul'  => ['required', 'min:3'],
        'konten' => ['required', 'required']
    ];

    public function __construct() {
        parent::__construct(); // Wajib dipanggil agar proteksi JWT aktif
        $this->modelInstance = $this->model('mArtikel');
    }

    // Method index(), detail(), store(), ubah(), dan hapus() 
    // sudah otomatis tersedia dari Core Controller!
}
