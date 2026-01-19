# 4. Arquitectura

## Diagrama de componentes


## Explicación técnica

### Arquitectura MVC personalizada

El proyecto implementa una arquitectura Modelo-Vista-Controlador (MVC) desarrollada desde cero:

**Modelos (Models)**
- Ubicación: `app/models/`
- Responsabilidad: Acceso a datos y lógica de negocio
- Conexión única a base de datos mediante clase Database
- Ejemplos: ProductoModel, UsuarioModel, PedidoModel

**Vistas (Views)**
- Ubicación: `app/views/`
- Responsabilidad: Presentación de datos al usuario
- Separadas por funcionalidad: admin, productos, carrito, usuarios
- Layouts reutilizables en `app/views/layouts/`

**Controladores (Controllers)**
- Ubicación: `app/controllers/`
- Responsabilidad: Intermediario entre modelos y vistas
- Procesan peticiones HTTP y preparan datos
- Ejemplos: ProductoController, AdminController

### Flujo de peticiones

1. **Entrada:** Usuario accede a `public/index.php?c=producto&a=index`
2. **Enrutamiento:** `index.php` identifica controlador y acción
3. **Controlador:** Instancia `ProductoController` y ejecuta método `index()`
4. **Modelo:** Controlador consulta `ProductoModel` para obtener datos
5. **Vista:** Controlador pasa datos a vista `productos/index.php`
6. **Salida:** Vista renderizada con datos se envía al navegador

## Tecnologías utilizadas

### Backend
- **PHP 8.2**: Lenguaje principal del servidor
- **Arquitectura MVC**: Patrón de diseño implementado manualmente
- **MySQL 8.0**: Sistema de gestión de bases de datos
- **Sesiones PHP**: Gestión de estado de usuario y carrito
- **PDO**: Interfaz para acceso a base de datos

### Frontend
- **Bootstrap 5**: Framework CSS para diseño responsive
- **JavaScript**: Interactividad del lado del cliente
- **jQuery**: Simplificación de manipulaciones DOM
- **AJAX**: Peticiones asíncronas para carrito y validaciones
- **HTML5/CSS3**: Estándares web modernos

### Seguridad
- **Validación server-side**: En controladores y modelos
- **Validación client-side**: En formularios con JavaScript
- **Protección contra XSS**: `htmlspecialchars()` en salida de datos
- **Protección contra SQL Injection**: Consultas preparadas con PDO
- **Control de acceso**: Verificación de roles en sesiones

### Base de datos

#### Diagrama de relaciones
usuarios ──────┐
│ │
│ │
categorias pedidos
│ │
│ │
productos ←── lineas_pedidos

#### Principales tablas
- **usuarios**: Datos de clientes y administradores
- **productos**: Catálogo completo de velas
- **categorias**: Clasificación de productos
- **pedidos**: Encabezados de pedidos
- **lineas_pedidos**: Detalle de productos en cada pedido

#### Relaciones clave
- `productos.categoria_id` → `categorias.id`
- `pedidos.usuario_id` → `usuarios.id`
- `lineas_pedidos.pedido_id` → `pedidos.id`
- `lineas_pedidos.producto_id` → `productos.id`

## Patrones de diseño implementados

### Front Controller
Todas las peticiones pasan por `public/index.php` que actúa como controlador frontal.

### Singleton (Database)
La clase Database implementa patrón Singleton para una única instancia de conexión.

### Session State
Uso de sesiones PHP para mantener estado de usuario y carrito entre peticiones.

### Transaction Script
Patrón utilizado en procesamiento de pedidos para mantener integridad de datos.

## Decisiones técnicas justificadas

### PHP nativo vs Framework
Se eligió PHP nativo para demostrar comprensión profunda del lenguaje y arquitectura web.

### MVC personalizado vs Framework existente
Desarrollo manual de MVC para aprendizaje de patrones arquitectónicos.

### Bootstrap para frontend
Elegido por rapidez de desarrollo y responsividad automática.

### jQuery para JavaScript
Compatibilidad amplia y simplificación de código AJAX.

### MySQL como SGBD
Relacional, ampliamente usado y suficiente para necesidades del proyecto.

## Estructura de directorios
raiz/
├── app/ # Código de aplicación
│ ├── config/ # Configuración
│ ├── controllers/ # Controladores
│ ├── models/ # Modelos
│ └── views/ # Vistas organizadas por módulo
│
├── public/ # Punto de entrada público
│ ├── css/ # Estilos
│ ├── images/ # Imágenes
│ ├── js/ # JavaScript
│ └── index.php # Front controller
│
└── assets/ # Recursos adicionales

## Configuración del servidor

### Requisitos de despliegue
- **PHP 8.2+** con extensiones: pdo_mysql, mysqli, session
- **MySQL 8.0+** con InnoDB como motor de almacenamiento
- **Apache** con mod_rewrite habilitado o **Nginx** con configuración similar
- **Permisos de escritura** en `public/images/productos/`

### Configuración recomendada
```apache
# Apache .htaccess (en public/)
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```
## Consideraciones de rendimiento
### Optimizaciones implementadas
- Consultas preparadas para reutilización
- Índices en claves foráneas de base de datos
- Cache de sesiones en servidor
- Minificación de assets CSS/JS

### Posibles mejoras
- Implementación de cache de consultas frecuentes
- CDN para recursos estáticos
- Compresión GZIP de respuestas
- Optimización de imágenes automática
