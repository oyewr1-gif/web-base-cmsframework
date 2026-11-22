<?php
class AdminArtikelController extends CoreController {
    protected $viewPath = 'admin/artikel';
    protected $redirectPath = '/adminartikel';
    protected $layout = 'admin';
    protected $requireAuth = true;

    public function __construct() {
        // Wajib memanggil parent::__construct() agar pengecekan login berjalan
        parent::__construct();
        $this->module = new PostModule();
    }

    public function index() {
        $posts = $this->module->getAll();
        $this->render('index', [
            'title' => 'Kelola Artikel',
            'posts' => $posts['body']['data'] ?? []
        ]);
    }
}
