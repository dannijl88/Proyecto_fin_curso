<?php
class ProductoController {
    private $productoModel;
    
    public function __construct() {
        // Incluir manualmente el modelo
        require_once __DIR__ . '/../models/ProductoModel.php';
        require_once __DIR__ . '/../models/Database.php';
        
        $this->productoModel = new ProductoModel();
    }
    
    public function index() {
    $pagina = $_GET['pagina'] ?? 1;
    $productosPorPagina = 9; // 3x3 grid
    
    $resultado = $this->productoModel->getPaginated($pagina, $productosPorPagina);
    $categorias = $this->productoModel->getCategorias();
    
    $data = [
        'title' => 'Nuestras Velas',
        'productos' => $resultado['productos'],
        'categorias' => $categorias,
        'paginacion' => [
            'pagina_actual' => $resultado['pagina_actual'],
            'total_paginas' => $resultado['total_paginas'],
            'total_productos' => $resultado['total_productos']
        ]
    ];
    
    require_once __DIR__ . '/../views/productos/index.php';
}
    
    public function categoria() {
    $categoria_id = $_GET['id'] ?? null;
    $pagina = $_GET['pagina'] ?? 1;
    $productosPorPagina = 9;
    
    if (!$categoria_id) {
        header('Location: ' . BASE_URL . '?c=producto');
        exit;
    }
    
    // Necesitarías un método getPaginatedByCategoria en el modelo
    $productos = $this->productoModel->getByCategoria($categoria_id);
    $categorias = $this->productoModel->getCategorias();
    
    $data = [
        'title' => 'Productos por Categoría',
        'productos' => $productos,
        'categorias' => $categorias,
        'categoria_actual' => $categoria_id,
        'paginacion' => [
            'pagina_actual' => 1,
            'total_paginas' => 1,
            'total_productos' => count($productos)
        ]
    ];
    
    require_once __DIR__ . '/../views/productos/index.php';
}
    
    public function ver() {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        header('Location: ' . BASE_URL . '?c=producto');
        exit;
    }
    
    $producto = $this->productoModel->getById($id);
    
    if (!$producto) {
        die("Producto no encontrado");
    }
    
    // Obtener productos relacionados
    $productosRelacionados = $this->productoModel->getRelacionados(
        $producto['categoria_id'], 
        $id, 
        4 
    );
    
    $data = [
        'title' => $producto['nombre'],
        'producto' => $producto,
        'productosRelacionados' => $productosRelacionados
    ];
    
    require_once __DIR__ . '/../views/productos/ver.php';
}

    public function destacados() {
        // Productos más vendidos o mejor valorados
        $productos = $this->productoModel->getDestacados();
        header('Content-Type: application/json');
        echo json_encode($productos);
        exit;
    }

    public function ofertas() {
        // Productos en oferta
        $ofertas = $this->productoModel->getOfertas();
        header('Content-Type: application/json');
        echo json_encode($ofertas);
        exit;
    }
}