<?php
class HomeController {
    public function index() {
    $productoModel = new ProductoModel();
    $productos = $productoModel->getDestacados();
    
    $data = [
        'title' => 'Inicio - Tienda de Velas',
        'productos' => $productos,
        'totalProductos' => count($productos)
    ];
    
    // NUEVA forma de cargar vistas
    view('home/index', $data);
}
}