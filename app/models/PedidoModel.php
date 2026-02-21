<?php
class PedidoModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function crearPedido($usuario_id, $datos_envio, $carrito, $productoModel = null) {
    try {
        // Iniciar transacción
        $this->db->beginTransaction();
        
        // 1. Crear el pedido principal
        $stmt = $this->db->prepare("
            INSERT INTO pedidos (usuario_id, provincia, localidad, direccion, coste, estado, fecha, hora) 
            VALUES (?, ?, ?, ?, ?, 'pendiente', CURDATE(), CURTIME())
        ");
        
        $stmt->execute([
            $usuario_id,
            $datos_envio['provincia'],
            $datos_envio['localidad'],
            $datos_envio['direccion'],
            $carrito['total']
        ]);
        
        $pedido_id = $this->db->lastInsertId();
        
        // 2. Crear líneas del pedido Y ACTUALIZAR STOCK
        foreach ($carrito['productos'] as $producto) {
            // Insertar línea de pedido
            $stmt_linea = $this->db->prepare("
                INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades) 
                VALUES (?, ?, ?)
            ");
            
            $stmt_linea->execute([
                $pedido_id,
                $producto['id'],
                $producto['cantidad']
            ]);
            
            // 3. ACTUALIZAR STOCK del producto (si se pasó productoModel)
            if ($productoModel) {
                $productoModel->actualizarStock(
                    $producto['id'], 
                    -$producto['cantidad'] // Restar la cantidad comprada
                );
            }
        }
        
        // Confirmar transacción
        $this->db->commit();
        
        return [
            'success' => true,
            'pedido_id' => $pedido_id
        ];
        
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $this->db->rollBack();
        error_log("Error al crear pedido: " . $e->getMessage());
        return [
            'success' => false,
            'error' => 'Error al procesar el pedido'
        ];
    }
}
    
    public function getPedidosByUsuario($usuario_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT p.* 
                FROM pedidos p 
                WHERE p.usuario_id = ? 
                ORDER BY p.fecha DESC, p.hora DESC
            ");
            
            $stmt->execute([$usuario_id]);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Error al obtener pedidos: " . $e->getMessage());
            return [];
        }
    }
    
    public function getPedidoById($pedido_id, $usuario_id = null) {
    try {
        $sql = "
            SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email 
            FROM pedidos p 
            INNER JOIN usuarios u ON p.usuario_id = u.id 
            WHERE p.id = ?
        ";
        $params = [$pedido_id];
        
        // Si se proporciona usuario_id, verificar que el pedido le pertenece
        if ($usuario_id) {
            $sql .= " AND p.usuario_id = ?";
            $params[] = $usuario_id;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
        
    } catch (PDOException $e) {
        error_log("Error al obtener pedido: " . $e->getMessage());
        return null;
    }
}
    
    public function getLineasPedido($pedido_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT lp.*, pr.nombre, pr.precio, pr.imagen 
                FROM lineas_pedidos lp 
                INNER JOIN productos pr ON lp.producto_id = pr.id 
                WHERE lp.pedido_id = ?
            ");
            
            $stmt->execute([$pedido_id]);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Error al obtener líneas de pedido: " . $e->getMessage());
            return [];
        }
    }

    public function getAllPedidos() {
    try {
        $stmt = $this->db->query("
            SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email 
            FROM pedidos p 
            INNER JOIN usuarios u ON p.usuario_id = u.id 
            ORDER BY p.fecha DESC, p.hora DESC
        ");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error en getAllPedidos: " . $e->getMessage());
        return [];
    }
}

public function actualizarEstado($pedido_id, $estado) {
    try {
        $stmt = $this->db->prepare("
            UPDATE pedidos 
            SET estado = ? 
            WHERE id = ?
        ");
        $stmt->execute([$estado, $pedido_id]);
        return ['success' => true];
    } catch (PDOException $e) {
        error_log("Error en actualizarEstado: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

public function getEstadisticas() {
    try {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_pedidos,
                SUM(coste) as ingresos_totales,
                AVG(coste) as promedio_pedido,
                COUNT(CASE WHEN estado = 'completado' THEN 1 END) as pedidos_completados,
                COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pedidos_pendientes
            FROM pedidos
        ");
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error en getEstadisticas: " . $e->getMessage());
        return [];
    }
}

public function getAllConDetalles() {
    try {
        // Obtener todos los pedidos con datos de usuario
        $stmt = $this->db->query("
            SELECT p.*, u.nombre as cliente_nombre, u.email 
            FROM pedidos p 
            JOIN usuarios u ON p.usuario_id = u.id 
            ORDER BY p.fecha DESC
        ");
        $pedidos = $stmt->fetchAll();
        
        // Para cada pedido, obtener sus líneas
        foreach($pedidos as &$pedido) {
            $stmtLineas = $this->db->prepare("
                SELECT l.*, pr.nombre as producto_nombre, pr.precio 
                FROM lineas_pedidos l 
                JOIN productos pr ON l.producto_id = pr.id 
                WHERE l.pedido_id = ?
            ");
            $stmtLineas->execute([$pedido['id']]);
            $pedido['lineas'] = $stmtLineas->fetchAll();
            
            // Calcular subtotales si no existen
            foreach($pedido['lineas'] as &$linea) {
                $linea['subtotal'] = $linea['precio'] * $linea['unidades'];
            }
        }
        
        return $pedidos;
    } catch (PDOException $e) {
        error_log("Error en getAllConDetalles: " . $e->getMessage());
        return [];
    }
}
}