<?php
class CategoriaController {
    private $categoriaModel;
    
    public function __construct() {
        require_once __DIR__ . '/../models/CategoriaModel.php';
        require_once __DIR__ . '/../models/Database.php';
        
        $this->categoriaModel = new CategoriaModel();
    }
    
    public function index() {
        UsuarioController::requireAdmin();
        
        $categorias = $this->categoriaModel->getAll();
        
        $data = [
            'title' => 'Gestión de Categorías',
            'categorias' => $categorias
        ];
        
        require_once __DIR__ . '/../views/admin/categorias.php';
    }
    
    public function crear() {
        UsuarioController::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resultado = $this->categoriaModel->crear([
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? '')
            ]);
            
            if ($resultado['success']) {
                header('Location: ' . BASE_URL . '?c=categoria&a=index&success=1');
            } else {
                header('Location: ' . BASE_URL . '?c=categoria&a=index&error=' . urlencode($resultado['error']));
            }
            exit;
        }
    }
    
    public function editar() {
        UsuarioController::requireAdmin();
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: ' . BASE_URL . '?c=categoria&a=index');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resultado = $this->categoriaModel->actualizar($id, [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? '')
            ]);
            
            if ($resultado['success']) {
                header('Location: ' . BASE_URL . '?c=categoria&a=index&success=1');
            } else {
                header('Location: ' . BASE_URL . '?c=categoria&a=index&error=' . urlencode($resultado['error']));
            }
            exit;
        }
        
        // Mostrar formulario de edición
        $categoria = $this->categoriaModel->getById($id);
        
        if (!$categoria) {
            header('Location: ' . BASE_URL . '?c=categoria&a=index');
            exit;
        }
        
        $data = [
            'title' => 'Editar Categoría',
            'categoria' => $categoria
        ];
        
        require_once __DIR__ . '/../views/admin/editar_categoria.php';
    }
    
    public function eliminar() {
        UsuarioController::requireAdmin();
        
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: ' . BASE_URL . '?c=categoria&a=index');
            exit;
        }
        
        $resultado = $this->categoriaModel->eliminar($id);
        
        if ($resultado['success']) {
            header('Location: ' . BASE_URL . '?c=categoria&a=index&success=1');
        } else {
            header('Location: ' . BASE_URL . '?c=categoria&a=index&error=' . urlencode($resultado['error']));
        }
        exit;
    }
}