<?php
class CarritoController {
    private $carritoModel;
    private $productoModel;
    
    public function __construct() {
        $this->carritoModel = new CarritoModel();
        $this->productoModel = new ProductoModel();
    }
    
    public function index() {
    $carrito = $this->carritoModel->obtenerCarrito();
    
    // Obtener información completa de los productos del carrito
    $productosCompletos = [];
    foreach ($carrito['productos'] as $producto) {
        $productoInfo = $this->productoModel->getById($producto['id']);
        if ($productoInfo) {
            $productosCompletos[] = array_merge($producto, $productoInfo);
        }
    }
    
    $data = [
        'title' => 'Mi Carrito de Compras',
        'carrito' => $carrito,
        'productos' => $productosCompletos
    ];
    
    require_once __DIR__ . '/../views/carrito/index.php';
}
    
    public function agregar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $producto_id = $_POST['producto_id'] ?? null;
        $cantidad = $_POST['cantidad'] ?? 1;
        
        if ($producto_id) {
            // Pasar el modelo de producto para obtener información real
            $this->carritoModel->agregarProducto($producto_id, $cantidad, $this->productoModel);
            
            // Para AJAX, devolver éxito
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                echo json_encode(['success' => true]);
                exit;
            } else {
                header('Location: ' . BASE_URL . '?c=carrito');
                exit;
            }
        }
    }
    
    header('Location: ' . BASE_URL . '?c=producto');
    exit;
}
    
    public function eliminar() {
        $producto_id = $_GET['id'] ?? null;
        
        if ($producto_id) {
            $this->carritoModel->eliminarProducto($producto_id);
        }
        
        header('Location: ' . BASE_URL . '?c=carrito');
        exit;
    }
    
    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $producto_id = $_POST['producto_id'] ?? null;
            $cantidad = $_POST['cantidad'] ?? 1;
            
            if ($producto_id) {
                $this->carritoModel->actualizarCantidad($producto_id, $cantidad);
            }
        }
        
        header('Location: ' . BASE_URL . '?c=carrito');
        exit;
    }
    
    public function vaciar() {
        $this->carritoModel->vaciarCarrito();
        header('Location: ' . BASE_URL . '?c=carrito');
        exit;
    }

    public function obtenerInfo() {
        $carrito = $this->carritoModel->obtenerCarrito();
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'total_items' => $carrito['total_items'],
                'total' => $carrito['total']
            ]);
            exit;
        }
    }

    public function checkout() {
    $carrito = $this->carritoModel->obtenerCarrito();
    
    // Verificar que el carrito no esté vacío
    if (empty($carrito['productos'])) {
        $_SESSION['error'] = 'El carrito está vacío. Añade productos antes de proceder al checkout.';
        header('Location: ' . BASE_URL . '?c=carrito');
        exit;
    }
    
    // Redirigir al proceso de checkout
    header('Location: ' . BASE_URL . '?c=pedido&a=checkout');
    exit;
}
}