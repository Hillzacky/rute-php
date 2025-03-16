<?php

namespace Hillzacky;

class Utils
{
    // Middleware untuk log request
    public static function logReq() {
        $log = '[' . date('Y-m-d H:i:s') . '] ' . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . PHP_EOL;
        file_put_contents('logs.txt', $log, FILE_APPEND);
    }

    // Middleware untuk rate-limiting
    public static function rateLim($lim = 10, $timeFrame = 60) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $now = time();
        $reqs = json_decode(file_get_contents('rate_limit.json'), true) ?: [];

        foreach ($reqs as $k => $r) {
            if ($r['t'] + $timeFrame < $now) {
                unset($reqs[$k]);
            }
        }

        $userReqs = array_filter($reqs, fn($r) => $r['ip'] === $ip);
        if (count($userReqs) >= $lim) {
            Error::error(429, 'Terlalu Banyak Permintaan');
            exit;
        }

        $reqs[] = ['ip' => $ip, 't' => $now];
        file_put_contents('rate_limit.json', json_encode($reqs));
    }

    // Middleware untuk autentikasi
    public static function auth() {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!$token || $token !== 'your-secret-token') {
            Error::error(401, 'Tidak Terotorisasi');
            exit;
        }
    }

    // Middleware untuk otorisasi berdasarkan peran
    public static function authz($role) {
        $roleUser = 'admin'; 
        if ($roleUser !== $role) {
            Error::error(403, 'Akses Dilarang');
            exit;
        }
    }
}
