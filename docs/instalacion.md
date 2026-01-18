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
