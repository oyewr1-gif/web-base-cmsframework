<?php
class ArtikelModule extends CoreModule
{
    protected $endpoint = '/artikel';
    protected $authRules = [
        'index'  => false, // Artikel publik tidak perlu JWT
        'detail' => false,
        'store'  => true,
        'update' => true,
        'delete' => true
    ];
}
