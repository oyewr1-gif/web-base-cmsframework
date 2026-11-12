<?php
class AuthController extends CoreController
{
    protected $area = 'Admin';
    protected $viewPath = 'auth';

    public function __construct()
    {
        $this->module = new AuthModule();
    }

    public function index()
    {
        $this->redirect('/admin/auth/login');
    }

    public function login()
    {
        // Jika sudah punya token sesi, langsung arahkan ke dasbor
        if (!empty($_SESSION['jwt_token'])) {
            $this->redirect('/admin');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf($_POST['csrf_token'] ?? '');
            $cleanPost = Security::cleanInput($_POST);

            $res = $this->module->login([
                'username' => $cleanPost['username'] ?? '',
                'password' => $_POST['password'] ?? '' // Password tidak di-strip tag agar karakter khusus tetap utuh
            ]);

            if ($res['status_code'] === 200 && !empty($res['body']['token'])) {
                session_regenerate_id(true);
                $_SESSION['jwt_token'] = $res['body']['token'];
                $this->redirect('/admin');
            }

            // Render form login tanpa layout admin ($useLayout = false)
            $this->view("{$this->viewPath}/login", [
                'error' => $res['body']['message'] ?? 'Username atau password salah.'
            ], false);
            return;
        }

        $this->view("{$this->viewPath}/login", [], false);
    }

    public function logout()
    {
        unset($_SESSION['jwt_token']);
        session_destroy();
        $this->redirect('/admin/auth/login');
    }
}
