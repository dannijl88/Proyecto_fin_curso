<?php


// Configuración
define('DB_HOST', 'localhost');
define('DB_NAME', 'u202108071_proyectofinal');
define('DB_USER', 'u202108071_dannijl88');
define('DB_PASS', 'y&2SDDt+uY');

define('BASE_URL', 'https://proyectofinal.danijuan.com');

session_start();

// Helper function para rutas de vistas
function view($viewPath, $data = []) {
    extract($data);
    
    // Incluir la vista específica
    require_once APP_ROOT . '/app/views/' . $viewPath . '.php';
}

// Helper para assets
function asset($path) {
    return BASE_URL . '/public/' . ltrim($path, '/');
}