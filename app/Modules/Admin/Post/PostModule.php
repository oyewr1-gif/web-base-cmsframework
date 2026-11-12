<?php
class PostModule extends CoreModule
{
    protected $endpoint = '/posts'; // Sesuaikan dengan endpoint di REST API Anda
    protected $authRules = [
        'index'  => true, // Di dasbor admin, ambil semua artikel (termasuk draft) menggunakan JWT
        'detail' => true,
        'store'  => true,
        'update' => true,
        'delete' => true
    ];
}
