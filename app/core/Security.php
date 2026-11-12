<?php
class Security {
    // Bersihkan input dari spasi berlebih, null-byte, dan tag berbahaya
    public static function cleanInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'cleanInput'], $data);
        }
        $data = trim($data);
        $data = stripslashes($data);
        return strip_tags($data);
    }

    // Bersihkan input Rich Text Editor CMS dengan mempertahankan tag format HTML yang aman
    public static function cleanHtml($html) {
        if (empty($html)) {
            return '';
        }

        // Daftar tag HTML aman yang diizinkan untuk isi artikel
        $allowedTags = '<p><br><b><strong><i><em><u><s><h1><h2><h3><h4><h5><h6><ul><ol><li><blockquote><a><img><pre><code><table><thead><tbody><tr><th><td><hr>';
        $clean = strip_tags((string) $html, $allowedTags);

        // Hapus atribut event JavaScript (seperti onclick, onerror, onload) pada tag yang diizinkan
        $clean = preg_replace('/(<[^>]+)\s+on[a-z]+\s*=\s*(["\']).*?\2/i', '$1', $clean);
        $clean = preg_replace('/(<[^>]+)\s+on[a-z]+\s*=\s*[^\s>]+/i', '$1', $clean);

        // Blokir protokol berbahaya (javascript:, vbscript:, data:) pada atribut href dan src
        $clean = preg_replace('/(href|src)\s*=\s*(["\'])\s*(javascript|vbscript|data):.*?\2/i', '$1="#"', $clean);

        return trim($clean);
    }

    // Output encoding untuk mencegah XSS pada View
    public static function e($string) {
        return htmlspecialchars((string) $string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    // Generate & validasi CSRF Token
    public static function csrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string) $token)) {
            http_response_code(403);
            die('Akses ditolak: Token CSRF tidak valid.');
        }
    }
}
