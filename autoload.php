<?php

/**
 * Autoloader Manual untuk Hillzacky Rute-php
 * Gunakan ini jika Anda tidak menggunakan Composer.
 */

spl_autoload_register(function ($class) {
    // Prefix untuk namespace library
    $prefix = 'Hillzacky\\';
    $base_dir = __DIR__ . '/src/';

    // Cek apakah kelas menggunakan prefix namespace kita
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Ambil nama kelas relatif (tanpa prefix)
    $relative_class = substr($class, $len);

    // Ubah namespace separator (\) menjadi directory separator (/)
    // Lalu tambahkan ekstensi .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Jika file ada, muat file tersebut
    if (file_exists($file)) {
        require $file;
    }
});
