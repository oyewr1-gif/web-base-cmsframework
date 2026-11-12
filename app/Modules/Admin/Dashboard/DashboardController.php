<?php
class DashboardController extends CoreController
{
    protected $area = 'Admin';
    protected $viewPath = 'dashboard';

    public function index()
    {
        // Wajib login sebelum masuk ke dasbor
        $this->requireAuth();

        $this->view("{$this->viewPath}/index", [
            'title' => 'Dashboard CMS'
        ]);
    }
}
