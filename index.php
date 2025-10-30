<?php
// Punto de entrada PRINCIPAL - Raíz del proyecto

// Mostrar errores temporalmente
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constante de ruta base
define('APP_ROOT', __DIR__);

// Cargar configuración
require_once APP_ROOT . '/app/config/config.php';

// Autoloader
spl_autoload_register(function ($className) {
    $paths = [
        APP_ROOT . '/app/controllers/',
        APP_ROOT . '/app/models/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Obtener controlador y acción desde la URL
$controllerName = $_GET['c'] ?? 'home';
$action = $_GET['a'] ?? 'index';

// Sanitizar y capitalizar
$controller = ucfirst(strtolower($controllerName)) . 'Controller';
$action = strtolower($action);

// Ruta al archivo del controlador
$controllerFile = APP_ROOT . "/app/controllers/$controller.php";

// Verificar si existe el controlador
if (file_exists($controllerFile)) {
    // Crear instancia del controlador
    $controllerInstance = new $controller();
    
    // Verificar si existe la acción
    if (method_exists($controllerInstance, $action)) {
        // Ejecutar la acción
        $controllerInstance->$action();
    } else {
        http_response_code(404);
        die("Error 404: La página no existe");
    }
} else {
    http_response_code(404);
    die("Error 404: La página no existe");
}