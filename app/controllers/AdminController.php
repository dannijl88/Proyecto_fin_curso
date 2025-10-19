<?php
class AdminController {
    private $productoModel;
    private $usuarioModel;
    private $pedidoModel;
    
    public function __construct() {
        // Verificar que es admin
        UsuarioController::requireAdmin();
        
        $this->productoModel = new ProductoModel();
        $this->pedidoModel = new PedidoModel();
    }
    
    public function index() {
        $data = [
            'title' => 'Panel de Administración',
            'totalProductos' => count($this->productoModel->getAll()),
            'productos' => $this->productoModel->getAll()
        ];
        
        require_once __DIR__ . '/../views/admin/index.php';
    }
    
    public function productos() {
        $productos = $this->productoModel->getAll();
        $categorias = $this->productoModel->getCategorias();
        
        $data = [
            'title' => 'Gestión de Productos',
            'productos' => $productos,
            'categorias' => $categorias
        ];
        
        require_once __DIR__ . '/../views/admin/productos.php';
    }
    
    public function editarProducto() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: ' . BASE_URL . '?c=admin&a=productos');
            exit;
        }
        
        $producto = $this->productoModel->getById($id);
        $categorias = $this->productoModel->getCategorias();
        
        if (!$producto) {
            header('Location: ' . BASE_URL . '?c=admin&a=productos');
            exit;
        }
        
        $data = [
            'title' => 'Editar Producto: ' . $producto['nombre'],
            'producto' => $producto,
            'categorias' => $categorias
        ];
        
        require_once __DIR__ . '/../views/admin/editar_producto.php';
    }
    
    public function nuevoProducto() {
        $categorias = $this->productoModel->getCategorias();
        
        $data = [
            'title' => 'Nuevo Producto',
            'categorias' => $categorias
        ];
        
        require_once __DIR__ . '/../views/admin/nuevo_producto.php';
    }

public function guardarProducto() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '?c=admin&a=productos');
        exit;
    }
    
    $imagenNombre = $this->subirImagen();
    
    $datos = [
        'categoria_id' => $_POST['categoria_id'],
        'nombre' => trim($_POST['nombre']),
        'descripcion' => trim($_POST['descripcion']),
        'precio' => floatval($_POST['precio']),
        'stock' => intval($_POST['stock']),
        'oferta' => $_POST['oferta'],
        'imagen' => $imagenNombre
    ];
    
    $resultado = $this->productoModel->crear($datos);
    
    if ($resultado['success']) {
        $_SESSION['mensaje'] = '✅ Producto creado exitosamente';
        header('Location: ' . BASE_URL . '?c=admin&a=productos');
    } else {
        $_SESSION['error'] = $resultado['error'];
        header('Location: ' . BASE_URL . '?c=admin&a=nuevoProducto');
    }
    exit;
}

public function actualizarProducto() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '?c=admin&a=productos');
        exit;
    }
    
    $id = $_POST['id'] ?? null;
    if (!$id) {
        header('Location: ' . BASE_URL . '?c=admin&a=productos');
        exit;
    }
    
    $imagenNombre = $this->subirImagen();
    
    $datos = [
        'categoria_id' => $_POST['categoria_id'],
        'nombre' => trim($_POST['nombre']),
        'descripcion' => trim($_POST['descripcion']),
        'precio' => floatval($_POST['precio']),
        'stock' => intval($_POST['stock']),
        'oferta' => $_POST['oferta'],
        'imagen' => $imagenNombre
    ];
    
    $resultado = $this->productoModel->actualizar($id, $datos);
    
    if ($resultado['success']) {
        $_SESSION['mensaje'] = '✅ Producto actualizado exitosamente';
    } else {
        $_SESSION['error'] = $resultado['error'];
    }
    
    header('Location: ' . BASE_URL . '?c=admin&a=productos');
    exit;
}

public function eliminarProducto() {
    // Verificar si es POST para seguridad
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '?c=admin&a=productos');
        exit;
    }
    
    $id = $_POST['id'] ?? null;
    
    if (!$id) {
        $_SESSION['error'] = 'ID de producto no válido';
        header('Location: ' . BASE_URL . '?c=admin&a=productos');
        exit;
    }
    
    $resultado = $this->productoModel->eliminar($id);
    
    if ($resultado['success']) {
        $_SESSION['mensaje'] = '✅ Producto eliminado correctamente';
    } else {
        $_SESSION['error'] = $resultado['error'] ?? 'Error al eliminar producto';
    }
    
    header('Location: ' . BASE_URL . '?c=admin&a=productos');
    exit;
}

private function subirImagen() {
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $archivo = $_FILES['imagen'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
    
    if (!in_array($extension, $extensionesPermitidas)) {
        return null;
    }
    
    $nombreImagen = uniqid() . '_' . time() . '.' . $extension;
    $rutaDestino = __DIR__ . '/../../public/images/productos/' . $nombreImagen;
    
    if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        return $nombreImagen;
    }
    
    return null;
}

public function pedidos() {
    $pedidos = $this->pedidoModel->getAllPedidos();
    $estadisticas = $this->pedidoModel->getEstadisticas();
    
    $data = [
        'title' => 'Gestión de Pedidos',
        'pedidos' => $pedidos,
        'estadisticas' => $estadisticas
    ];
    
    require_once __DIR__ . '/../views/admin/pedidos.php';
}

public function detallePedido() {
    $pedido_id = $_GET['id'] ?? null;
    
    if (!$pedido_id) {
        header('Location: ' . BASE_URL . '?c=admin&a=pedidos');
        exit;
    }
    
    $pedido = $this->pedidoModel->getPedidoById($pedido_id);
    $lineas_pedido = $this->pedidoModel->getLineasPedido($pedido_id);
    
    if (!$pedido) {
        $_SESSION['error'] = 'Pedido no encontrado';
        header('Location: ' . BASE_URL . '?c=admin&a=pedidos');
        exit;
    }
    
    $data = [
        'title' => 'Detalle Pedido #' . $pedido_id,
        'pedido' => $pedido,
        'lineas_pedido' => $lineas_pedido
    ];
    
    require_once __DIR__ . '/../views/admin/detalle_pedido.php';
}

public function actualizarEstadoPedido() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '?c=admin&a=pedidos');
        exit;
    }
    
    $pedido_id = $_POST['pedido_id'] ?? null;
    $estado = $_POST['estado'] ?? null;
    
    if (!$pedido_id || !$estado) {
        $_SESSION['error'] = 'Datos incompletos';
        header('Location: ' . BASE_URL . '?c=admin&a=pedidos');
        exit;
    }
    
    $resultado = $this->pedidoModel->actualizarEstado($pedido_id, $estado);
    
    if ($resultado['success']) {
        $_SESSION['mensaje'] = '✅ Estado del pedido actualizado correctamente';
    } else {
        $_SESSION['error'] = $resultado['error'] ?? 'Error al actualizar estado';
    }
    
    header('Location: ' . BASE_URL . '?c=admin&a=pedidos');
    exit;
}
}