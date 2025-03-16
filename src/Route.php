<?php

namespace Hillzacky;

use Hillzacky\Error;
use Hillzacky\Utils;
use Hillzacky\Files;

class Route
{
    private $method;
    private $path;
    private $handler;
    private $middleware = [];
    private static $middlewareGlobal = [];
    private static $staticDirs = [];
    private static $routes = [];

    // Konstruktor untuk mendefinisikan metode, path, dan handler
    public function __construct($method, $path, $handler) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
    }

    // Menambahkan middleware untuk route ini
    public function middleware($handler) {
        $this->middleware[] = $handler;
        return $this;
    }

    // Menambahkan middleware global
    public static function use($handler) {
        self::$middlewareGlobal[] = $handler;
    }

    // Menangani permintaan dan eksekusi handler serta middleware
    public function __destruct() {
        // Menjalankan middleware global terlebih dahulu
        foreach (self::$middlewareGlobal as $middleware) {
            call_user_func($middleware);
        }

        // Menjalankan middleware spesifik untuk route ini
        foreach ($this->middleware as $middleware) {
            call_user_func($middleware);
        }

        // Menjalankan handler route
        call_user_func($this->handler);
    }

    // Menambahkan route dengan metode tertentu
    public static function addRoute($method, $path, $handler) {
        self::$routes[] = new self($method, $path, $handler);
        return end(self::$routes);
    }

    // Menangani pemanggilan metode dinamis (get, post, dll)
    public static function __callStatic($method, $args) {
        $validMethods = ['get', 'post', 'put', 'delete', 'patch', 'head', 'options'];

        if (in_array(strtolower($method), $validMethods)) {
            return self::addRoute(strtoupper($method), $args[0], $args[1]);
        }
    }

    // Menampilkan daftar rute yang tersedia
    public static function showRoutes() {
        $routes = [];
        foreach (self::$routes as $route) {
            $routes[] = [
                'method' => $route->method,
                'path' => $route->path
            ];
        }
        return $routes;
    }

    // Menangani error 404 atau error lainnya
    public static function notFound() {
        Error::error(404, 'Route tidak ditemukan!');
    }

    // Fungsi untuk memproses rute yang diminta
    public static function processRequest($requestMethod, $requestUri) {
        $routeFound = false;

        foreach (self::$routes as $route) {
            // Jika metode dan path cocok
            if (strtoupper($requestMethod) == $route->method && $requestUri == $route->path) {
                $routeFound = true;
                new $route($route->method, $route->path, $route->handler);
                break;
            }
        }

        // Jika tidak ada rute yang ditemukan
        if (!$routeFound) {
            self::notFound(); // Panggil notFound jika tidak ada rute yang cocok
        }
    }

    // Fungsi yang dipanggil otomatis saat aplikasi dimulai
    public static function init() {
        // Mengambil metode dan path dari permintaan HTTP
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        // Menangani rute yang diminta
        self::processRequest($requestMethod, $requestUri);
    }
}

// Panggil otomatis ketika aplikasi dimulai
Route::init();
