<?php
class ArtikelController extends CoreController
{
    protected $area = 'Public';
    protected $viewPath = 'artikel';

    public function __construct()
    {
        $this->module = new ArtikelModule();
    }

    /**
     * Halaman arsip/daftar semua artikel publik
     * Route: /blog
     */
    public function index()
    {
        $res = $this->module->getAll();

        $this->view("{$this->viewPath}/index", [
            'title'    => 'Semua Artikel',
            'articles' => $res['body']['data'] ?? []
        ]);
    }

    /**
     * Halaman detail satu artikel
     * Route: /blog/detail/{id}
     */
    public function detail($id = null)
    {
        if (!$id) {
            $this->redirect('/artikel');
        }

        $res = $this->module->getById((int) $id);

        if ($res['status_code'] !== 200 || empty($res['body']['data'])) {
            $this->view("{$this->viewPath}/detail", [
                'title'   => 'Artikel Tidak Ditemukan',
                'article' => null,
                'error'   => $res['body']['message'] ?? 'Artikel tidak ditemukan.'
            ]);
            return;
        }

        $article = $res['body']['data'];
        $this->view("{$this->viewPath}/detail", [
            'title'   => $article['judul'] ?? 'Detail Artikel',
            'article' => $article
        ]);
    }
}
