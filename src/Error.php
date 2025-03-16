<?php

namespace Hillzacky;

class Error
{
    // Menampilkan error dengan kode dan pesan tertentu
    public static function error($code, $message) {
        http_response_code($code);
        echo json_encode(['error' => $message]);
    }
}
