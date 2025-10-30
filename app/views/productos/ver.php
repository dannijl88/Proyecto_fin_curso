<?php 
$title = $data['title'] ?? 'Detalles del Producto';
$producto = $data['producto'] ?? null;
include __DIR__ . '/../layouts/header.php';

if (!$producto): ?>
    <section class="container py-5">
        <div class="alert alert-danger text-center">
            <h2>Producto no encontrado</h2>
            <a href="<?= BASE_URL ?>?c=producto" class="btn btn-primary mt-3">Volver a la tienda</a>
        </div>
    </section>
<?php else: ?>
    <section class="container py-5">
        <!-- Migas de pan -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?c=producto">Productos</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($producto['nombre']) ?></li>
            </ol>
        </nav>

        <div class="row">
            <!-- Galería de imágenes -->
            <div class="col-md-6">
                <div class="product-gallery">
                    <!-- Imagen principal -->
                    <div class="main-image mb-3">
                        <img src="<?= BASE_URL ?>/public/images/productos/<?= $producto['imagen']?>" 
                            alt="<?= htmlspecialchars($producto['nombre']) ?>" 
                            class="img-fluid rounded shadow-sm" 
                            id="main-product-image"
                            style="max-height: 500px; object-fit: cover;">
                    </div>
                    
                    <!-- Miniaturas -->
                    <div class="thumbnails d-flex gap-2">
                        <img src="<?= BASE_URL ?>/public/images/productos/<?= $producto['imagen']?>" 
                            alt="<?= htmlspecialchars($producto['nombre']) ?>" 
                            class="img-thumbnail active"
                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                            onclick="cambiarImagen(this)">
                    </div>
                </div>
            </div>

            <!-- Información del producto -->
            <div class="col-md-6">
                <header class="product-header mb-4">
                    <?php if($producto['oferta'] == 'SI'): ?>
                        <span class="badge bg-danger mb-2">EN OFERTA</span>
                    <?php endif; ?>
                    
                    <h1 class="h2"><?= htmlspecialchars($producto['nombre']) ?></h1>
                    <span class="badge bg-secondary"><?= htmlspecialchars($producto['categoria_nombre']) ?></span>
                </header>

                <!-- Precio -->
                <div class="price-section mb-4">
                    <p class="h3 text-primary">€<?= number_format($producto['precio'], 2) ?></p>
                    <?php if($producto['oferta'] == 'SI'): ?>
                        <small class="text-muted text-decoration-line-through">€<?= number_format($producto['precio'] * 1.2, 2) ?></small>
                        <span class="badge bg-success ms-2">20% OFF</span>
                    <?php endif; ?>
                </div>

                <!-- Descripción -->
                <div class="description mb-4">
                    <h3 class="h5">Descripción</h3>
                    <p class="text-muted"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
                </div>

                <!-- Formulario de compra -->
                <div class="purchase-form">
                    <form id="add-to-cart-form">
                        <!-- Selector de cantidad -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <select class="form-select" id="cantidad" name="cantidad">
                                    <?php for($i = 1; $i <= min($producto['stock'], 10); $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <small class="text-muted">
                                    <?php if($producto['stock'] > 0): ?>
                                        <span class="text-success">✓ En stock (<?= $producto['stock'] ?> disponibles)</span>
                                    <?php else: ?>
                                        <span class="text-danger">✗ Agotado</span>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" 
                                    class="btn btn-primary btn-lg flex-fill"
                                    id="btn-add-cart"
                                    data-id="<?= $producto['id'] ?>"
                                    data-nombre="<?= htmlspecialchars($producto['nombre']) ?>"
                                    data-precio="<?= $producto['precio'] ?>"
                                    data-stock="<?= $producto['stock'] ?>"
                                    <?= $producto['stock'] <= 0 ? 'disabled' : '' ?>>
                                <?= $producto['stock'] > 0 ? '🛒 Añadir al carrito' : 'Producto agotado' ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Información adicional -->
                <div class="product-info mt-4 pt-4 border-top">
                    <div class="row text-center">
                        <div class="col-4">
                            <small class="text-muted d-block">🛡️ Garantía</small>
                            <small>30 días</small>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">🚚 Envío</small>
                            <small>24-48h</small>
                        </div>
                        <div class="col-4">
                            <small class="text-muted d-block">↩️ Devolución</small>
                            <small>14 días</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos relacionados -->
                <?php if (!empty($productosRelacionados)): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="h4 mb-4">Productos relacionados</h3>
                        <div class="row">
                            <?php foreach ($productosRelacionados as $relacionado): ?>
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card h-100 product-card">
                                    <!-- Imagen del producto -->
                                    <a href="<?= BASE_URL ?>?c=producto&a=ver&id=<?= $relacionado['id'] ?>">
                                        <img src="<?= BASE_URL ?>/public/images/productos/<?= $relacionado['imagen']?>" 
                                            class="card-img-top" 
                                            alt="<?= htmlspecialchars($relacionado['nombre']) ?>"
                                            style="height: 200px; object-fit: cover;">
                                    </a>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <!-- Categoría -->
                                        <span class="badge bg-secondary mb-2"><?= htmlspecialchars($relacionado['categoria_nombre']) ?></span>
                                        
                                        <!-- Nombre -->
                                        <h5 class="card-title">
                                            <a href="<?= BASE_URL ?>?c=producto&a=ver&id=<?= $relacionado['id'] ?>" 
                                            class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($relacionado['nombre']) ?>
                                            </a>
                                        </h5>
                                        
                                        <!-- Precio -->
                                        <div class="price-section mt-auto">
                                            <p class="h5 text-primary mb-1">€<?= number_format($relacionado['precio'], 2) ?></p>
                                            <?php if($relacionado['oferta'] == 'SI'): ?>
                                                <small class="text-muted text-decoration-line-through">€<?= number_format($relacionado['precio'] * 1.2, 2) ?></small>
                                                <span class="badge bg-success ms-1">20% OFF</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Stock -->
                                        <small class="text-muted mt-2">
                                            <?php if($relacionado['stock'] > 0): ?>
                                                <span class="text-success">✓ En stock</span>
                                            <?php else: ?>
                                                <span class="text-danger">✗ Agotado</span>
                                            <?php endif; ?>
                                        </small>
                                        
                                        <!-- Botón rápido -->
                                        <div class="mt-3">
                                            <a href="<?= BASE_URL ?>?c=producto&a=ver&id=<?= $relacionado['id'] ?>" 
                                            class="btn btn-outline-primary btn-sm w-100">
                                                Ver detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
<?php endif; ?>
    </section>
<?php endif; ?>

<script>
// Cambiar imagen principal al hacer clic en miniatura
function cambiarImagen(miniatura) {
    const nuevaImagen = miniatura.src;
    document.getElementById('main-product-image').src = nuevaImagen;
    
    // Remover clase active de todas las miniaturas
    document.querySelectorAll('.thumbnails .img-thumbnail').forEach(img => {
        img.classList.remove('active');
    });
    
    // Añadir clase active a la miniatura clickeada
    miniatura.classList.add('active');
}

// Añadir al carrito desde la página de producto
$(document).ready(function() {
    $('#add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        
        const boton = $('#btn-add-cart');
        const productoId = boton.data('id');
        const productoNombre = boton.data('nombre');
        const productoPrecio = boton.data('precio');
        const stock = boton.data('stock');
        const cantidad = $('#cantidad').val();
        
        if (stock <= 0) {
            mostrarNotificacion('❌ Producto agotado', 'error');
            return;
        }
        
        // Deshabilitar botón temporalmente
        boton.prop('disabled', true).html('🔄 Añadiendo...');
        
        $.ajax({
            url: '<?= BASE_URL ?>?c=carrito&a=agregar',
            method: 'POST',
            data: {
                producto_id: productoId,
                cantidad: cantidad
            },
            success: function(response) {
                mostrarNotificacion('✅ ' + cantidad + ' x ' + productoNombre + ' añadido al carrito');
                actualizarContadorCarrito();
                
                // Restaurar botón
                setTimeout(() => {
                    boton.prop('disabled', false).html('🛒 Añadir al carrito');
                }, 1000);
            },
            error: function() {
                mostrarNotificacion('❌ Error al añadir al carrito', 'error');
                boton.prop('disabled', false).html('🛒 Añadir al carrito');
            }
        });
    });
});

// Reutilizar las funciones de notificación y carrito de tu archivo anterior
function mostrarNotificacion(mensaje, tipo = 'success') {
    const notificacion = $('<div class="alert alert-' + (tipo === 'error' ? 'danger' : 'success') + ' alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">' +
        mensaje +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>');
    
    $('body').append(notificacion);
    setTimeout(() => {
        notificacion.alert('close');
    }, 3000);
}

function actualizarContadorCarrito() {
    $.ajax({
        url: '<?= BASE_URL ?>?c=carrito&a=obtenerInfo',
        method: 'GET',
        success: function(info) {
            $('.cart-count').text(info.total_items || 0);
        }
    });
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>