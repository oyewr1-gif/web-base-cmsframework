<?php
class App {
    protected $area = 'Public';
    protected $module = 'Home';
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // 1. Deteksi apakah URL diawali dengan 'admin'
        if (!empty($url[0]) && strtolower($url[0]) === 'admin') {
            $this->area = 'Admin';
            $this->module = 'Dashboard'; // Modul default saat mengakses /admin
            $this->controller = 'DashboardController';
            array_shift($url); // Geser indeks array sehingga $url[0] menjadi nama modul
        }

        // 2. Cek segmen modul ($url[0]) di dalam folder Modules/{$this->area}/
        if (!empty($url[0])) {
            $modName = ucfirst(strtolower($url[0]));
            $ctrlPath = APP_PATH . "/Modules/{$this->area}/{$modName}/{$modName}Controller.php";
            
            if (file_exists($ctrlPath)) {
                $this->module = $modName;
                $this->controller = "{$modName}Controller";
                unset($url[0]);
            }
        }

        // Pastikan file controller dimuat (fallback jika autoloader melewatkannya)
        $targetFile = APP_PATH . "Modules/{$this->area}/{$this->module}/{$this->controller}.php";
        if (file_exists($targetFile)) {
            require_once $targetFile;
        }

        // Cek apakah class benar-benar ada sebelum instansiasi
        if (!class_exists($this->controller)) {
            http_response_code(404);
            die("Error: Controller <b>{$this->controller}</b> tidak ditemukan di folder <code>Modules/{$this->area}/{$this->module}/</code>.");
        }

        // Instansiasi Controller (Autoloader akan memuat file sesuai $this->area)
        $this->controller = new $this->controller;

        // 3. Cek segmen method ($url[1])
        if (!empty($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        // 4. Sisa segmen menjadi parameter
        $this->params = $url ? array_values($url) : [];
        
        // Jalankan controller & method
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            $cleanUrl = Security::cleanInput(rtrim($_GET['url'], '/'));
            return explode('/', filter_var($cleanUrl, FILTER_SANITIZE_URL));
        }
        return [];
    }
}
