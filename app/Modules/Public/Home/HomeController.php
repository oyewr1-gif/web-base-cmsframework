<?php
class HomeController extends CoreController
{
    protected $area = 'Public';
    protected $viewPath = 'home';

    public function index()
    {
        // Ambil artikel publik tanpa mengirim token JWT ($useAuth = false)
        $res = ApiClient::request('GET', '/posts', [], false);

        $this->view("{$this->viewPath}/index", [
            'title' => 'Beranda Blog',
            'posts' => $res['body']['data'] ?? []
        ]);
    }
}
