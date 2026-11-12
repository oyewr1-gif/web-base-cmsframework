<?php
class RateLimiter {
    public static function check($maxRequests = 60, $windowSeconds = 60) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = 'rate_limit_' . md5($ip);
        $now = time();

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 1, 'start_time' => $now];
            return;
        }

        $elapsed = $now - $_SESSION[$key]['start_time'];

        if ($elapsed > $windowSeconds) {
            $_SESSION[$key] = ['count' => 1, 'start_time' => $now];
        } else {
            $_SESSION[$key]['count']++;
            if ($_SESSION[$key]['count'] > $maxRequests) {
                $retryAfter = $windowSeconds - $elapsed;
                header("Retry-After: $retryAfter");
                http_response_code(429);
                die("429 Too Many Requests: Terlalu banyak permintaan. Coba lagi dalam {$retryAfter} detik.");
            }
        }
    }
}
