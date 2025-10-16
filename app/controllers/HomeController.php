<?php
class HomeController {
    public function index() {

        // Cookie de última visita
        if (!isset($_COOKIE['ultima_visita'])) {
            setcookie('ultima_visita', date('Y-m-d H:i:s'), time() + (365 * 24 * 60 * 60), '/');
        }

        $data = [
            'title' => 'Inicio - Tienda de Velas'
        ];

        
        
        require_once '../app/views/home/index.php';
    }
}