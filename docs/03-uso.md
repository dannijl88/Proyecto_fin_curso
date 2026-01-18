# 3. Uso

## Manual de usuario

### Para clientes

#### Registro y acceso
1. Acceder a la página de registro desde el menú principal
2. Completar formulario con datos personales
3. Confirmar correo electrónico
4. Iniciar sesión con credenciales creadas

#### Navegación por catálogo
- Ver todos los productos en página principal
- Filtrar por categorías usando menú lateral
- Ver detalles de producto haciendo clic en su tarjeta
- Añadir productos al carrito desde página de detalle

#### Carrito de compras
- Acceder al carrito desde icono en cabecera
- Modificar cantidades de productos
- Eliminar productos individualmente
- Proceder al checkout con botón "Finalizar compra"

#### Proceso de compra
1. Revisar productos en carrito
2. Introducir datos de envío
3. Confirmar pedido
4. Recibir confirmación por pantalla
5. Ver historial de pedidos en perfil de usuario

### Para administradores

#### Acceso al panel
1. Iniciar sesión con credenciales de administrador
2. Acceder a panel desde enlace en menú o URL `?c=admin`

#### Gestión de productos
- Ver listado completo de productos
- Añadir nuevo producto con formulario
- Editar productos existentes
- Eliminar productos del catálogo
- Gestionar stock disponible

#### Gestión de pedidos
- Ver todos los pedidos realizados
- Consultar detalles de cada pedido
- Cambiar estado (pendiente, enviado, completado, cancelado)
- Ver información de cliente y envío

#### Gestión de categorías
- Crear nuevas categorías
- Editar categorías existentes
- Eliminar categorías no utilizadas

#### Gestión de usuarios
- Ver listado de usuarios registrados
- Consultar datos de contacto
- Asignar roles (admin/cliente)

## Casos de uso

### UC-001: Cliente realiza compra
1. Cliente navega por catálogo
2. Selecciona productos
3. Añade al carrito
4. Realiza checkout
5. Sistema registra pedido y actualiza stock

### UC-002: Administrador gestiona inventario
1. Admin accede a panel
2. Consulta productos con stock bajo
3. Actualiza cantidades disponibles
4. Sistema refleja cambios en tienda

### UC-003: Cliente consulta historial
1. Cliente inicia sesión
2. Accede a sección "Mis pedidos"
3. Visualiza pedidos anteriores
4. Consulta estado de envíos

## Usuarios tipo

### Administrador
- Responsable de la tienda
- Gestiona productos, pedidos y usuarios
- Acceso completo al sistema

### Cliente registrado
- Usuario frecuente de la tienda
- Realiza compras periódicamente
- Consulta historial de pedidos

### Cliente invitado
- Usuario no registrado
- Navega por catálogo
- Añade productos al carrito
- Debe registrarse para finalizar compra
