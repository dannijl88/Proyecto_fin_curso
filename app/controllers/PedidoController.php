<?php
class PedidoController {
    private $pedidoModel;
    private $carritoModel;
    
    public function __construct() {
        // Verificar que el usuario está logueado
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '?c=usuario&a=login');
            exit;
        }
        
        $this->pedidoModel = new PedidoModel();
        $this->carritoModel = new CarritoModel();
    }
    
    public function checkout() {
        $carrito = $this->carritoModel->obtenerCarrito();
        
        // Verificar que el carrito no esté vacío
        if (empty($carrito['productos'])) {
            $_SESSION['error'] = 'El carrito está vacío';
            header('Location: ' . BASE_URL . '?c=carrito');
            exit;
        }
        
        $data = [
            'title' => 'Checkout - Finalizar Compra',
            'carrito' => $carrito,
            'usuario' => $_SESSION['usuario']
        ];
        
        require_once __DIR__ . '/../views/pedidos/checkout.php';
    }
    
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?c=carrito');
            exit;
        }
        
        $carrito = $this->carritoModel->obtenerCarrito();
        
        // Verificar que el carrito no esté vacío
        if (empty($carrito['productos'])) {
            $_SESSION['error'] = 'El carrito está vacío';
            header('Location: ' . BASE_URL . '?c=carrito');
            exit;
        }
        
        // Validar datos de envío
        $datos_envio = [
            'provincia' => trim($_POST['provincia'] ?? ''),
            'localidad' => trim($_POST['localidad'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? '')
        ];
        
        $errores = $this->validarDatosEnvio($datos_envio);
        
        if (!empty($errores)) {
            $_SESSION['errores_checkout'] = $errores;
            $_SESSION['datos_formulario'] = $datos_envio;
            header('Location: ' . BASE_URL . '?c=pedido&a=checkout');
            exit;
        }
        
        // Crear pedido
        $resultado = $this->pedidoModel->crearPedido(
            $_SESSION['usuario']['id'],
            $datos_envio,
            $carrito
        );
        
        if ($resultado['success']) {
            // Vaciar carrito después del pedido exitoso
            $this->carritoModel->vaciarCarrito();
            
            $_SESSION['pedido_exitoso'] = $resultado['pedido_id'];
            header('Location: ' . BASE_URL . '?c=pedido&a=confirmacion');
            exit;
        } else {
            $_SESSION['error'] = $resultado['error'];
            header('Location: ' . BASE_URL . '?c=pedido&a=checkout');
            exit;
        }
    }
    
    public function confirmacion() {
        if (!isset($_SESSION['pedido_exitoso'])) {
            header('Location: ' . BASE_URL . '?c=producto');
            exit;
        }
        
        $pedido_id = $_SESSION['pedido_exitoso'];
        $pedido = $this->pedidoModel->getPedidoById($pedido_id);
        $lineas_pedido = $this->pedidoModel->getLineasPedido($pedido_id);
        
        // Limpiar sesión después de mostrar confirmación
        unset($_SESSION['pedido_exitoso']);
        
        $data = [
            'title' => '¡Pedido Confirmado!',
            'pedido' => $pedido,
            'lineas_pedido' => $lineas_pedido
        ];
        
        require_once __DIR__ . '/../views/pedidos/confirmacion.php';
    }
    
    public function misPedidos() {
        $pedidos = $this->pedidoModel->getPedidosByUsuario($_SESSION['usuario']['id']);
        
        $data = [
            'title' => 'Mis Pedidos',
            'pedidos' => $pedidos
        ];
        
        require_once __DIR__ . '/../views/pedidos/mis_pedidos.php';
    }
    
    public function detalle() {
        $pedido_id = $_GET['id'] ?? null;
        
        if (!$pedido_id) {
            header('Location: ' . BASE_URL . '?c=pedido&a=misPedidos');
            exit;
        }
        
        $pedido = $this->pedidoModel->getPedidoById($pedido_id, $_SESSION['usuario']['id']);
        
        if (!$pedido) {
            $_SESSION['error'] = 'Pedido no encontrado';
            header('Location: ' . BASE_URL . '?c=pedido&a=misPedidos');
            exit;
        }
        
        $lineas_pedido = $this->pedidoModel->getLineasPedido($pedido_id);
        
        $data = [
            'title' => 'Detalle del Pedido #' . $pedido_id,
            'pedido' => $pedido,
            'lineas_pedido' => $lineas_pedido
        ];
        
        require_once __DIR__ . '/../views/pedidos/detalle.php';
    }
    
    private function validarDatosEnvio($datos) {
        $errores = [];
        
        if (empty($datos['provincia'])) {
            $errores['provincia'] = 'La provincia es obligatoria';
        }
        
        if (empty($datos['localidad'])) {
            $errores['localidad'] = 'La localidad es obligatoria';
        }
        
        if (empty($datos['direccion'])) {
            $errores['direccion'] = 'La dirección es obligatoria';
        }
        
        return $errores;
    }
}