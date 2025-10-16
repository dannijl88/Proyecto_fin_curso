<?php
class ProductoModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        try {
            $stmt = $this->db->query("
                SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p 
                INNER JOIN categorias c ON p.categoria_id = c.id 
                ORDER BY p.nombre
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en getAll: " . $e->getMessage());
            return [];
        }
    }
    
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p 
                INNER JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en getById: " . $e->getMessage());
            return null;
        }
    }
    
    public function getByCategoria($categoria_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p 
                INNER JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.categoria_id = ? 
                ORDER BY p.nombre
            ");
            $stmt->execute([$categoria_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en getByCategoria: " . $e->getMessage());
            return [];
        }
    }
    
    public function getCategorias() {
        try {
            $stmt = $this->db->query("SELECT * FROM categorias ORDER BY nombre");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en getCategorias: " . $e->getMessage());
            return [];
        }
    }

    // En ProductoModel.php - añade después de getCategorias()

    public function crear($datos) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, oferta, fecha, imagen) 
                VALUES (?, ?, ?, ?, ?, ?, CURDATE(), ?)
            ");
            
            $stmt->execute([
                $datos['categoria_id'],
                $datos['nombre'],
                $datos['descripcion'],
                $datos['precio'],
                $datos['stock'],
                $datos['oferta'],
                $datos['imagen'] ?? null
            ]);
            
            return ['success' => true, 'producto_id' => $this->db->lastInsertId()];
            
        } catch (PDOException $e) {
            error_log("Error en crear producto: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al crear producto'];
        }
    }

    public function actualizar($id, $datos) {
        try {
            $sql = "UPDATE productos SET 
                    categoria_id = ?, nombre = ?, descripcion = ?, precio = ?, 
                    stock = ?, oferta = ?, imagen = COALESCE(?, imagen) 
                    WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                $datos['categoria_id'],
                $datos['nombre'],
                $datos['descripcion'],
                $datos['precio'],
                $datos['stock'],
                $datos['oferta'],
                $datos['imagen'] ?? null,
                $id
            ]);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Error en actualizar producto: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al actualizar producto'];
        }
    }

    public function eliminar($id) {
        try {
            // Primero obtener info de la imagen para borrarla del servidor
            $producto = $this->getById($id);
            
            $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            
            // Si tenía imagen, borrarla
            if ($producto && $producto['imagen']) {
                $rutaImagen = __DIR__ . '/../../public/images/productos/' . $producto['imagen'];
                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Error en eliminar producto: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al eliminar producto'];
        }
    }

    // Paginación
    public function getPaginated($pagina = 1, $productosPorPagina = 9) {
        try {
            $offset = ($pagina - 1) * $productosPorPagina;
            
            // Contar total de productos
            $stmtTotal = $this->db->query("SELECT COUNT(*) as total FROM productos");
            $totalProductos = $stmtTotal->fetch()['total'];
            $totalPaginas = ceil($totalProductos / $productosPorPagina);
            
            // Obtener productos de la página actual
            $stmt = $this->db->prepare("
                SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p 
                INNER JOIN categorias c ON p.categoria_id = c.id 
                ORDER BY p.id DESC 
                LIMIT ? OFFSET ?
            ");
            
            $stmt->bindValue(1, $productosPorPagina, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $productos = $stmt->fetchAll();
            
            return [
                'productos' => $productos,
                'pagina_actual' => $pagina,
                'total_paginas' => $totalPaginas,
                'total_productos' => $totalProductos,
                'productos_por_pagina' => $productosPorPagina
            ];
            
        } catch (PDOException $e) {
            error_log("Error en getPaginated: " . $e->getMessage());
            return [
                'productos' => [],
                'pagina_actual' => 1,
                'total_paginas' => 1,
                'total_productos' => 0,
                'productos_por_pagina' => $productosPorPagina
            ];
        }
    }

    // Añade estos métodos a tu ProductoModel class:

public function getDestacados($limite = 6) {
    try {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            INNER JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.stock > 0 
            ORDER BY p.id DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error en getDestacados: " . $e->getMessage());
        return [];
    }
}

public function getOfertas($limite = 4) {
    try {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre 
            FROM productos p 
            INNER JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.oferta = 'SI' AND p.stock > 0 
            ORDER BY p.id DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error en getOfertas: " . $e->getMessage());
        return [];
    }
}
}