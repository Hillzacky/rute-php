<?php

namespace Hillzacky;
use Hillzacky\Error;
class Files
{
    private static $staticDirs = [];
    // Menambahkan file statis
    public static function static($path, $dir) {
        self::$staticDirs[$path] = $dir;
    }

    // Menyajikan file statis
    public static function serve($urlPath) {
        foreach (self::$staticDirs as $path => $dir) {
            if ($urlPath == $path) {
                $file = $dir . $_SERVER['REQUEST_URI'];
                if (file_exists($file)) {
                    header("Content-Type: " . mime_content_type($file));
                    readfile($file);
                    exit();
                } else {
                    Error::error(404, 'Route tidak ditemukan!');
                }
            }
        }
    }
}
