<?php

namespace Hillzacky;

class View
{
    // Path untuk lokasi tampilan dan layout
    protected static $viewPath;
    protected static $layout = 'default'; // Layout default
    protected static $fragments = [];     // Fragment seperti header, footer, dll
    protected static $content = '';       // Konten halaman yang dirender

    // Menetapkan path tampilan jika perlu
    public static function setViewPath($path)
    {
        self::$viewPath = $path;
    }

    // Mengatur layout yang akan digunakan
    public static function layout($layout)
    {
        self::$layout = $layout;
        return new static; // Mengembalikan objek View untuk mendukung chaining
    }

    // Menambahkan fragment untuk halaman ini (seperti header, footer, dll)
    public static function setFragment($name, $view, $data = [])
    {
        self::$fragments[$name] = ['view' => $view, 'data' => $data];
        return new static; // Mengembalikan objek View untuk mendukung chaining
    }

    // Merender tampilan dengan data dan layout
    public static function render($view, $data = [])
    {
        // Ekstrak data untuk membuatnya menjadi variabel lokal
        extract($data);

        // Render tampilan dan simpan hasilnya ke dalam konten
        ob_start();
        self::renderView($view, $data);
        self::$content = ob_get_clean(); // Menyimpan hasil render tampilan

        // Render layout dengan konten halaman
        self::renderLayout();
        
        return new static; // Mengembalikan objek View untuk chaining lebih lanjut
    }

    // Merender tampilan tanpa layout
    protected static function renderView($view, $data = [])
    {
        $viewFile = self::$viewPath . $view . '.php';

        if (!file_exists($viewFile)) {
            Error::error(404, "View tidak ditemukan: $view");
            return;
        }

        extract($data);
        require_once $viewFile;
    }

    // Merender layout utama dengan konten di dalamnya
    protected static function renderLayout()
    {
        // Tentukan file layout yang digunakan
        $layoutFile = self::$viewPath . 'layouts/' . self::$layout . '.php';

        if (!file_exists($layoutFile)) {
            Error::error(404, "Layout tidak ditemukan: " . self::$layout);
            return;
        }

        // Ambil fragmen-fragmen yang dibutuhkan
        $header = self::$fragments['header'] ?? null;
        $footer = self::$fragments['footer'] ?? null;
        $nav = self::$fragments['nav'] ?? null;

        // Ekstrak fragment dan konten
        extract(['header' => $header, 'footer' => $footer, 'nav' => $nav, 'content' => self::$content]);

        require_once $layoutFile;
    }

    // Menambahkan fragment default (header, footer, nav) ke dalam layout
    public static function addDefaultFragments()
    {
        self::setFragment('header', 'fragments/header')
             ->setFragment('footer', 'fragments/footer')
             ->setFragment('nav', 'fragments/nav');
    }
}
