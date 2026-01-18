<?php


// Configuración
define('DB_HOST', 'localhost');
define('DB_NAME', 'u792436979_bd_ignia');
define('DB_USER', 'u792436979_dannijl88');
define('DB_PASS', 'Vx=WsUg8m+');

define('BASE_URL', 'https://proyecto.danijuan.com');

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