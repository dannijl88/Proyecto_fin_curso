# 2. Instalación

## Requisitos del sistema

- PHP 8.2 o superior
- MySQL 8.0 o superior (versión 110803)
- Extensiones PHP: PDO, MySQLi, session
- Servidor web (Apache/Nginx)
- 50MB espacio en disco mínimo

## Pasos de instalación

### 1. Clonar repositorio
```bash
git clone https://github.com/tuusuario/tienda-velas.git
cd tienda-velas
```
### 2. Configurar base de datos
-- Importar estructura y datos iniciales
mysql -u usuario -p bd_tienda < bd_proyecto.sql
Archivo SQL incluye:
- Estructura de tablas
- Datos de ejemplo
- Relaciones y claves foráneas
- Usuarios demo (admin/cliente)

### 3. Configurar conexión
Editar archivo de configuración principal (config.php o similar):

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'bd_tienda');
define('DB_USER', 'usuario');
define('DB_PASS', 'contraseña');
define('BASE_URL', 'http://localhost/tienda-velas/public');
```

### 4. Configurar permisos de carpetas
# Permisos para subida de imágenes
chmod 755 public/images/
chmod 755 public/images/productos/


### 5. Acceder a la aplicación

- URL principal: http://localhost/tienda-velas/public/
- Panel admin: http://localhost/tienda-velas/public/?c=admin
- Credenciales demo admin: admin@email.com / admin123

## Variables de entorno

- DB_HOST: Servidor de base de datos
- DB_NAME: Nombre de la base de datos  
- DB_USER: Usuario MySQL
- DB_PASS: Contraseña MySQL
- BASE_URL: URL base de la aplicación

## Solución de problemas

- Error de conexión a BD: Verificar credenciales en config.php
- Imágenes no se suben: Verificar permisos en public/images/productos/
- URLs incorrectas: Ajustar BASE_URL según entorno
- Archivos no encontrados: Verificar rutas relativas/absolutas
- Sesiones no funcionan: Verificar configuración PHP session.save_path
