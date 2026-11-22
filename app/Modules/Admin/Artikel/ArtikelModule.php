<?php
class ArtikelModule extends CoreModule
{
    protected $endpoint = '/artikel'; // Mengarah ke controller Artikel di REST API
    protected $authRules = [
        'index'  => true, // Di dasbor admin, ambil semua artikel (termasuk draft) menggunakan JWT
        'detail' => true,
        'store'  => true,
        'update' => true,
        'delete' => true
    ];
}
