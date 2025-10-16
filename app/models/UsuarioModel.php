<?php
class UsuarioModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function registrar($datos) {
        try {
            // Verificar si el email ya existe
            if ($this->emailExiste($datos['email'])) {
                return ['success' => false, 'error' => 'El email ya está registrado'];
            }
            
            // Hash de la contraseña
            $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("
                INSERT INTO usuarios (nombre, apellidos, email, password, rol, imagen) 
                VALUES (?, ?, ?, ?, 'cliente', NULL)
            ");
            
            $stmt->execute([
                $datos['nombre'],
                $datos['apellidos'] ?? '',
                $datos['email'],
                $passwordHash
            ]);
            
            return ['success' => true, 'usuario_id' => $this->db->lastInsertId()];
            
        } catch (PDOException $e) {
            error_log("Error en registrar: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error al registrar usuario'];
        }
    }
    
    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, nombre, apellidos, email, password, rol, imagen 
                FROM usuarios 
                WHERE email = ?
            ");
            
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
            
            if ($usuario && password_verify($password, $usuario['password'])) {
                // Eliminar password del array por seguridad
                unset($usuario['password']);
                return ['success' => true, 'usuario' => $usuario];
            }
            
            return ['success' => false, 'error' => 'Email o contraseña incorrectos'];
            
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error en el login'];
        }
    }
    
    public function emailExiste($email) {
        try {
            $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Error en emailExiste: " . $e->getMessage());
            return false;
        }
    }
    
    public function getUsuarioById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT id, nombre, apellidos, email, rol, imagen 
                FROM usuarios 
                WHERE id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error en getUsuarioById: " . $e->getMessage());
            return null;
        }
    }
}