<?php
class UsuarioController {
    private $usuarioModel;
    
    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }
    
    public function registro() {
        $data = [
            'title' => 'Registro - Tienda de Velas'
        ];
        
        // Si es POST, procesar registro
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resultado = $this->procesarRegistro();
            if ($resultado['success']) {
                header('Location: ' . BASE_URL . '?c=usuario&a=login&registro=exitoso');
                exit;
            } else {
                $data['error'] = $resultado['error'];
                $data['form_data'] = $_POST;
            }
        }
        
        view('usuarios/registro', $data);
    }

    public static function isAdmin() {
        return isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin';
    }

    public static function requireAdmin() {
    if (!self::isAdmin()) {
        header('Location: ' . BASE_URL . '?c=usuario&a=login');
        exit;
        }
    }
    
    public function login() {
    $data = [
        'title' => 'Login - Tienda de Velas'
    ];
    
    // Si ya está logueado, redirigir
    if (isset($_SESSION['usuario'])) {
        header('Location: ' . BASE_URL);
        exit;
    }
    
    // Auto-completar email desde cookie "recordarme"
    if (!$_SERVER['REQUEST_METHOD'] === 'POST' && isset($_COOKIE['usuario_email'])) {
        $data['email_recordado'] = $_COOKIE['usuario_email'];
    }
    
    // Si es POST, procesar login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $resultado = $this->procesarLogin();
        if ($resultado['success']) {
            $this->iniciarSesion($resultado['usuario']);
            
            // Redirigir según rol
            if ($resultado['usuario']['rol'] === 'admin') {
                header('Location: ' . BASE_URL . '?c=admin');
            } else {
                header('Location: ' . BASE_URL);
            }
            exit;
        } else {
            $data['error'] = $resultado['error'];
        }
    }
    
    view('usuarios/login', $data);
}
    public function logout() {
        // Destruir sesión
        session_destroy();
        
        // Redirigir al home
        header('Location: ' . BASE_URL);
        exit;
    }
    
    public function perfil() {
        // Verificar que el usuario está logueado
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '?c=usuario&a=login');
            exit;
        }
        
        $data = [
            'title' => 'Mi Perfil - Tienda de Velas',
            'usuario' => $_SESSION['usuario']
        ];
        
        view('usuarios/perfil', $data);
    }
    
    private function procesarRegistro() {
        $datos = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? ''
        ];
        
        // Validaciones básicas
        if (empty($datos['nombre']) || empty($datos['email']) || empty($datos['password'])) {
            return ['success' => false, 'error' => 'Todos los campos son obligatorios'];
        }
        
        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'El email no es válido'];
        }
        
        if ($datos['password'] !== $datos['password_confirm']) {
            return ['success' => false, 'error' => 'Las contraseñas no coinciden'];
        }
        
        if (strlen($datos['password']) < 6) {
            return ['success' => false, 'error' => 'La contraseña debe tener al menos 6 caracteres'];
        }
        
        return $this->usuarioModel->registrar($datos);
    }
    
    private function procesarLogin() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            return ['success' => false, 'error' => 'Email y contraseña son obligatorios'];
        }
        
        return $this->usuarioModel->login($email, $password);
    }
    
    private function iniciarSesion($usuario) {
    $_SESSION['usuario'] = $usuario;
    
    // Cookie "recordarme" (opcional)
    if (isset($_POST['recordar'])) {
        setcookie('usuario_email', $usuario['email'], time() + (30 * 24 * 60 * 60), '/');
    }
}
}