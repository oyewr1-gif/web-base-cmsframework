<?php
// 1. Definisi Global Path Constants
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);

// 2. Pengaturan Session yang Aman
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict'
    ]);
}
//echo "init.php";
// 3. Muat Environment Variables dari ROOT_PATH
require_once APP_PATH . 'Core/Env.php';
Env::load(ROOT_PATH . '.env');

// 4. Autoloader Otomatis (Core & Modules Public/Admin)
spl_autoload_register(function ($className) {
    // Prioritas 1: Cek di folder Core
    $coreFile = APP_PATH . "Core/{$className}.php";
    if (file_exists($coreFile)) {
        require_once $coreFile;
        return;
    }

    // Prioritas 2: Cari di dalam sub-folder Modules/Public/* dan Modules/Admin/*
    $areas = ['Public', 'Admin'];
    foreach ($areas as $area) {
        $moduleFolders = glob(APP_PATH . "Modules/{$area}/*", GLOB_ONLYDIR);
        if ($moduleFolders) {
            foreach ($moduleFolders as $folder) {
                $file = "{$folder}/{$className}.php";
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }
    }
});

// 5. Jalankan Rate Limiter Front-End
RateLimiter::check(
    (int) Env::get('RATE_LIMIT_MAX', 60),
    (int) Env::get('RATE_LIMIT_WINDOW', 60)
);
