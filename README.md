# rute-php
A simple PHP routing library with middleware, rate limiting, authentication, and static file handling.
Library ini adalah implementasi sederhana dari sebuah router dan sistem tampilan (view) untuk aplikasi PHP. Didesain untuk memudahkan pengelolaan rute HTTP dan rendering tampilan menggunakan layout dan fragment. Dengan pendekatan berbasis statis, library ini menyediakan cara yang fleksibel dan sederhana untuk menangani rute dan tampilan.

## Penggunaan

### Menyiapkan Route

Untuk menggunakan rute, pertama-tama Anda harus mendefinisikan rute HTTP yang dapat diproses:

```php
use Hillzacky\Route;
use Hillzacky\View;
use Hillzacky\Utils;

Route::use([Utils::class, 'logReq']);  // Middleware global
Route::use([Utils::class, 'auth']);    // Middleware untuk autentikasi

Route::get('/home', function() {
    $data = [
        'title' => 'Halaman Utama',
        'body' => '<p>Selamat datang di halaman utama kami!</p>'
    ];

    // Render tampilan dengan layout
    View::render('home', $data)->layout('default');
});
```
### Menambahkan Middleware
Middleware digunakan untuk memfilter request, baik secara global ataupun per route.
```php
// Middleware Global
Route::use([Utils::class, 'logReq']);  // Logging request
Route::use([Utils::class, 'rateLim']); // Pembatasan permintaan

// Middleware untuk Route Tertentu
Route::get('/admin', function() {
    // Route admin hanya bisa diakses oleh pengguna yang sudah login
    Route::use([Utils::class, 'authz'], 'admin');
    
    // Render tampilan
    $data = ['message' => 'Selamat datang di dashboard admin!'];
    View::render('admin_dashboard', $data)->layout('admin');
});
```

### Fungsi Utama
Route::get(): Menangani permintaan HTTP GET.
Route::post(): Menangani permintaan HTTP POST.
Route::put(): Menangani permintaan HTTP PUT.
Route::delete(): Menangani permintaan HTTP DELETE.
Route::patch(): Menangani permintaan HTTP PATCH.
Route::head(): Menangani permintaan HTTP HEAD.
Route::options(): Menangani permintaan HTTP OPTIONS.
