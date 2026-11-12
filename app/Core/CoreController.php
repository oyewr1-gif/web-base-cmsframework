<?php
abstract class CoreController {
    protected $module;
    protected $area = 'Public'; // 'Public' atau 'Admin'
    protected $viewPath = '';
    protected $redirectPath = '';

    // Mapping input $_POST ke payload JSON API (di-override di child controller)
    protected function mapPayload(array $cleanPost) {
        return $cleanPost;
    }

    protected function view($view, $data = [], $useLayout = true) {
        extract($data);

        // Otomatis sisipkan $this->viewPath jika belum disertakan dalam $view
        $targetView = !empty($this->viewPath) && strpos($view, '/') === false
            ? "{$this->viewPath}/{$view}"
            : $view;
        
        // Jalur view otomatis menyesuaikan folder Public atau Admin
        $viewFile = APP_PATH . "Views/{$this->area}/{$targetView}.php";

        if (!file_exists($viewFile)) {
            http_response_code(404);
            die("View tidak ditemukan: " . Security::e("{$this->area}/{$view}"));
        }

        if ($useLayout) {
            // Layout juga otomatis mengambil dari Views/Public/layouts/ atau Views/Admin/layouts/
            require_once APP_PATH . "/Views/{$this->area}/layouts/header.php";
            require_once $viewFile;
            require_once APP_PATH . "/Views/{$this->area}/layouts/footer.php";
        } else {
            require_once $viewFile;
        }
    }

    protected function redirect($path) {
        header("Location: " . rtrim(Env::get('APP_URL'), '/') . $path);
        exit;
    }

    protected function requireAuth() {
        if (empty($_SESSION['jwt_token'])) {
            // Arahkan ke rute login admin jika modul Auth dipindahkan ke folder Admin
            $loginRoute = ($this->area === 'Admin') ? '/admin/auth/login' : '/auth/login';
            $this->redirect($loginRoute);
        }
    }

    // --- CRUD TERPUSAT ---
    public function index() {
        // Jika berada di area Admin, wajib login untuk melihat daftar data di dasbor
        if ($this->area === 'Admin') {
            $this->requireAuth();
        }

        $res = $this->module->getAll();
        $this->view("{$this->viewPath}/index", [
            'items' => $res['body']['data'] ?? []
        ]);
    }

    public function create() {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf($_POST['csrf_token'] ?? '');
            $cleanPost = Security::cleanInput($_POST);
            $payload = $this->mapPayload($cleanPost);

            $res = $this->module->store($payload);

            if ($res['status_code'] === 201) {
                $this->redirect($this->redirectPath);
            }

            $this->view("{$this->viewPath}/form", [
                'action' => 'create',
                'errors' => $res['body']['errors'] ?? [$res['body']['message'] ?? 'Gagal menyimpan data'],
                'old' => $cleanPost
            ]);
            return;
        }

        $this->view("{$this->viewPath}/form", ['action' => 'create']);
    }

    public function edit($id) {
        $this->requireAuth();
        $id = (int) $id;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf($_POST['csrf_token'] ?? '');
            $cleanPost = Security::cleanInput($_POST);
            $payload = $this->mapPayload($cleanPost);

            $res = $this->module->update($id, $payload);

            if ($res['status_code'] === 200) {
                $this->redirect($this->redirectPath);
            }

            $this->view("{$this->viewPath}/form", [
                'action' => 'edit',
                'item' => array_merge(['id' => $id], $cleanPost),
                'errors' => $res['body']['errors'] ?? [$res['body']['message'] ?? 'Gagal memperbarui data']
            ]);
            return;
        }

        $detail = $this->module->getById($id);
        $this->view("{$this->viewPath}/form", [
            'action' => 'edit',
            'item' => $detail['body']['data'] ?? []
        ]);
    }

    public function delete($id) {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::verifyCsrf($_POST['csrf_token'] ?? '');
            $this->module->delete((int) $id);
        }
        $this->redirect($this->redirectPath);
    }
}
