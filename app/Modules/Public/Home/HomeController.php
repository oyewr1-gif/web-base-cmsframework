<?php
class HomeController extends CoreController
{
    protected $area = 'Public';
    protected $viewPath = 'home';

    public function index()
    {
        // Ambil artikel publik tanpa mengirim token JWT ($useAuth = false)
        $res = ApiClient::request('GET', '/artikel', [], false);

        $this->view("{$this->viewPath}/index", [
            'title'    => 'Beranda Artikel',
            'articles' => $res['body']['data'] ?? []
        ]);
    }
}
