<?php
class PedidoModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function crearPedido($usuario_id, $datos_envio, $carrito) {
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
            
            // 2. Crear líneas del pedido
            foreach ($carrito['productos'] as $producto) {
                $stmt_linea = $this->db->prepare("
                    INSERT INTO lineas_pedidos (pedido_id, producto_id, unidades) 
                    VALUES (?, ?, ?)
                ");
                
                $stmt_linea->execute([
                    $pedido_id,
                    $producto['id'],
                    $producto['cantidad']
                ]);
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
            $sql = "SELECT p.* FROM pedidos p WHERE p.id = ?";
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
}