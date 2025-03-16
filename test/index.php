<?php
// require_once 'vendor/autoload.php';

use Hillzacky\Route;
use Hillzacky\Utils;
use Hillzacky\Error;
use Hillzacky\Files;

// Menambahkan Middleware Global dengan Route::use
Route::use([Utils::class, 'logReq']);  
Route::use([Utils::class, 'rateLim']); // Membatasi permintaan
Route::use([Utils::class, 'auth']);    // Autentikasi
Route::use([Utils::class, 'authz'], 'admin'); // Otorisasi

// Mendefinisikan route menggunakan metode dinamis
Route::get('/home', function() {
    echo "Selamat datang di halaman utama!";
});

Route::post('/submit', function() {
    echo "Form berhasil disubmit!";
});

// Menambahkan route untuk melayani file statis
Files::static('/assets', __DIR__ . '/assets');

// Menyajikan file statis
Files::serve('/assets/image.png');

