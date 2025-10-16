<?php
class CategoriaModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM categorias ORDER BY nombre");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en getAll categorias: " . $e->getMessage());
            return [];
        }
    }
    
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en getById categoria: " . $e->getMessage());
            return null;
        }
    }
    
    public function crear($datos) {
        try {
            // Validar que el nombre no esté vacío
            if (empty($datos['nombre'])) {
                return ['success' => false, 'error' => 'El nombre es obligatorio'];
            }
            
            // SOLO nombre, sin descripción
            $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (?)");
            $stmt->execute([$datos['nombre']]);
            
            return ['success' => true, 'categoria_id' => $this->db->lastInsertId()];
            
        } catch (PDOException $e) {
            // Verificar si es error de duplicado
            if ($e->getCode() == 23000) {
                return ['success' => false, 'error' => 'Ya existe una categoría con ese nombre'];
            }
            
            error_log("Error en crear categoria: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al crear categoría'];
        }
    }
    
    public function actualizar($id, $datos) {
        try {
            if (empty($datos['nombre'])) {
                return ['success' => false, 'error' => 'El nombre es obligatorio'];
            }
            
            // SOLO nombre, sin descripción
            $stmt = $this->db->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
            $stmt->execute([$datos['nombre'], $id]);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['success' => false, 'error' => 'Ya existe una categoría con ese nombre'];
            }
            
            error_log("Error en actualizar categoria: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al actualizar categoría'];
        }
    }
    
    public function eliminar($id) {
        try {
            // Verificar si hay productos usando esta categoría
            $stmtCheck = $this->db->prepare("SELECT COUNT(*) as total FROM productos WHERE categoria_id = ?");
            $stmtCheck->execute([$id]);
            $result = $stmtCheck->fetch();
            
            if ($result['total'] > 0) {
                return ['success' => false, 'error' => 'No se puede eliminar la categoría porque tiene productos asociados'];
            }
            
            $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = ?");
            $stmt->execute([$id]);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Error en eliminar categoria: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar categoría'];
        }
    }
}