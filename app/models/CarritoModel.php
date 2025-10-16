<?php
class CarritoModel {
    
    public function __construct() {
        // Inicializar carrito en sesión si no existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [
                'productos' => [],
                'total' => 0,
                'total_items' => 0
            ];
        }
    }
    
    public function agregarProducto($producto_id, $cantidad = 1, $productoModel = null) {
    // Obtener información del producto si se proporciona el modelo
    $producto_info = [];
    if ($productoModel) {
        $producto_info = $productoModel->getById($producto_id);
    }
    
    $producto = [
        'id' => $producto_id,
        'cantidad' => $cantidad,
        'precio' => $producto_info['precio'] ?? 0,
        'nombre' => $producto_info['nombre'] ?? 'Producto',
        'imagen' => $producto_info['imagen'] ?? ''
    ];
    
    // Verificar si el producto ya está en el carrito
    $index = $this->buscarProductoEnCarrito($producto_id);
    
    if ($index !== false) {
        // Actualizar cantidad si ya existe
        $_SESSION['carrito']['productos'][$index]['cantidad'] += $cantidad;
    } else {
        // Agregar nuevo producto
        $_SESSION['carrito']['productos'][] = $producto;
    }
    
    $this->actualizarTotales();
    return true;
}
    
    public function eliminarProducto($producto_id) {
        $index = $this->buscarProductoEnCarrito($producto_id);
        
        if ($index !== false) {
            array_splice($_SESSION['carrito']['productos'], $index, 1);
            $this->actualizarTotales();
            return true;
        }
        
        return false;
    }
    
    public function actualizarCantidad($producto_id, $cantidad) {
        $index = $this->buscarProductoEnCarrito($producto_id);
        
        if ($index !== false) {
            if ($cantidad <= 0) {
                return $this->eliminarProducto($producto_id);
            }
            
            $_SESSION['carrito']['productos'][$index]['cantidad'] = $cantidad;
            $this->actualizarTotales();
            return true;
        }
        
        return false;
    }
    
    public function obtenerCarrito() {
        return $_SESSION['carrito'];
    }
    
    public function vaciarCarrito() {
        $_SESSION['carrito'] = [
            'productos' => [],
            'total' => 0,
            'total_items' => 0
        ];
    }
    
    private function buscarProductoEnCarrito($producto_id) {
        foreach ($_SESSION['carrito']['productos'] as $index => $producto) {
            if ($producto['id'] == $producto_id) {
                return $index;
            }
        }
        return false;
    }
    
    private function actualizarTotales() {
        $total = 0;
        $total_items = 0;
        
        foreach ($_SESSION['carrito']['productos'] as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
            $total_items += $producto['cantidad'];
        }
        
        $_SESSION['carrito']['total'] = $total;
        $_SESSION['carrito']['total_items'] = $total_items;
    }
}