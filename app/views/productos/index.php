<?php 
$title = $data['title'] ?? 'Productos';
$productos = $data['productos'] ?? [];
$categorias = $data['categorias'] ?? [];
$categoria_actual = $data['categoria_actual'] ?? null;
include __DIR__ . '/../layouts/header.php';
?>

<section class="productos">
    <header class="text-center mb-5">
        <h1>Nuestras Velas</h1>
        <p class="lead text-muted">Descubre nuestra colección de velas artesanales</p>
    </header>

    <!-- Panel de filtros -->
    <aside class="filtros bg-light p-4 rounded mb-5">
        <header>
            <h2 class="h4 mb-3">Filtrar Productos</h2>
        </header>
        
        <div class="row">
            <div class="col-md-6">
                <label for="buscador" class="form-label">Buscar velas</label>
                <input type="text" class="form-control" id="buscador" placeholder="🔍 Escribe el nombre...">
            </div>
            <div class="col-md-6">
                <label for="filtro-categoria" class="form-label">Categoría</label>
                <select class="form-select" id="filtro-categoria">
                    <option value="">Todas las categorías</option>
                    <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" 
                        <?= ($categoria_actual == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </aside>
                        
    <!-- Grid de productos -->
    <section class="catalogo" style="min-height: 50vh;">
        <?php if (empty($productos)): ?>
            <article class="col-12">
                <div class="alert alert-warning text-center">
                    <h3 class="h4">No se encontraron productos</h3>
                    <p class="mb-0">Prueba con otros filtros o categorías</p>
                </div>
            </article>
        <?php else: ?>
            <div class="row" id="productos-container">
                <?php foreach($productos as $producto): ?>
                <article class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-150 shadow-sm">
                        <!-- Etiqueta oferta -->
                        <?php if($producto['oferta'] == 'SI'): ?>
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">OFERTA</span>
                        <?php endif; ?>
                        
                        <?php if($producto['imagen']): ?>
                        <img src="<?= BASE_URL ?>/public/images/productos/<?= $producto['imagen']?>" 
                            class="card-img-top" 
                            alt="<?= htmlspecialchars($producto['nombre']) ?>"
                            style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                        <img src="/images/productos/<?= $producto['imagen'] ?>"   
                            class="card-img-top" 
                            alt="<?= htmlspecialchars($producto['nombre']) ?>"
                            style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <header>
                                <h2 class="h5 card-title"><?= htmlspecialchars($producto['nombre']) ?></h2>
                                <span class="badge bg-secondary mb-2"><?= htmlspecialchars($producto['categoria_nombre']) ?></span>
                            </header>
                            
                            <p class="card-text flex-grow-1"><?= htmlspecialchars($producto['descripcion']) ?></p>
                            
                            <footer class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="h5 text-primary mb-0">€<?= number_format($producto['precio'], 2) ?></p>
                                    <small class="text-muted">Stock: <?= $producto['stock'] ?></small>
                                </div>
                                <div class="btn-group w-100" role="group">
                                    <a href="<?= BASE_URL ?>?c=producto&a=ver&id=<?= $producto['id'] ?>" 
                                    class="btn btn-outline-primary btn-sm">Ver detalles</a>
                                    <button class="btn btn-primary btn-sm btn-add-cart" 
                                        data-id="<?= $producto['id'] ?>"
                                        data-nombre="<?= htmlspecialchars($producto['nombre']) ?>"
                                        data-precio="<?= $producto['precio'] ?>"
                                        data-stock="<?= $producto['stock'] ?>">
                                        Añadir al carrito
                                    </button>
                                </div>
                            </footer>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
        <!-- PAGINACIÓN -->
    <?php if ($data['paginacion']['total_paginas'] > 1): ?>
    <nav aria-label="Paginación de productos" class="mt-5">
        <ul class="pagination justify-content-center">
            <!-- Botón Anterior -->
            <li class="page-item <?= $data['paginacion']['pagina_actual'] == 1 ? 'disabled' : '' ?>">
                <a class="page-link" 
                href="?c=producto&pagina=<?= $data['paginacion']['pagina_actual'] - 1 ?>" 
                aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            
            <!-- Números de página -->
            <?php for ($i = 1; $i <= $data['paginacion']['total_paginas']; $i++): ?>
                <li class="page-item <?= $i == $data['paginacion']['pagina_actual'] ? 'active' : '' ?>">
                    <a class="page-link" href="?c=producto&pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            
            <!-- Botón Siguiente -->
            <li class="page-item <?= $data['paginacion']['pagina_actual'] == $data['paginacion']['total_paginas'] ? 'disabled' : '' ?>">
                <a class="page-link" 
                href="?c=producto&pagina=<?= $data['paginacion']['pagina_actual'] + 1 ?>" 
                aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
        
        <!-- Información de la paginación -->
        <div class="text-center mt-2">
            <small class="text-muted">
                Mostrando página <?= $data['paginacion']['pagina_actual'] ?> de <?= $data['paginacion']['total_paginas'] ?> 
                (<?= $data['paginacion']['total_productos'] ?> productos totales)
            </small>
        </div>
    </nav>
    <?php endif; ?>
</section>
</section>

<script>
// Filtro por categoría
$(document).ready(function() {
    $('#filtro-categoria').change(function() {
        const categoriaId = $(this).val();
        if (categoriaId) {
            window.location.href = '<?= BASE_URL ?>?c=producto&a=categoria&id=' + categoriaId;
        } else {
            window.location.href = '<?= BASE_URL ?>?c=producto';
        }
    });
});

// Búsqueda en tiempo real
$('#buscador').on('input', function() {
    const query = $(this).val().toLowerCase();
    
    if (query.length >= 2) {
        filtrarProductosLocalmente(query);
    } else if (query.length === 0) {
        $('.col-lg-4').show();
    }
});

// Filtrado local (sin recargar página)
function filtrarProductosLocalmente(query) {
    $('.col-lg-4').each(function() {
        const nombre = $(this).find('.card-title').text().toLowerCase();
        const descripcion = $(this).find('.card-text').text().toLowerCase();
        const categoria = $(this).find('.badge.bg-secondary').text().toLowerCase();
        
        if (nombre.includes(query) || descripcion.includes(query) || categoria.includes(query)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

// Búsqueda en servidor
function buscarEnServidor(query) {
    $.ajax({
        url: '<?= BASE_URL ?>?c=producto&a=buscar',
        method: 'GET',
        data: { q: query },
        success: function(response) {
            $('#productos-container').html(response);
        }
    });
}

// Añadir al carrito con AJAX
$(document).on('click', '.btn-add-cart', function() {
    const boton = $(this);
    const productoId = boton.data('id');
    const productoNombre = boton.data('nombre');
    const productoPrecio = boton.data('precio');
    const stock = boton.data('stock');
    
    // Deshabilitar botón temporalmente para evitar múltiples clicks
    boton.prop('disabled', true).html('🔄 Añadiendo...');
    
    $.ajax({
        url: '<?= BASE_URL ?>?c=carrito&a=agregar',
        method: 'POST',
        data: {
            producto_id: productoId,
            cantidad: 1
        },
        success: function(response) {
            // Mostrar notificación de éxito
            mostrarNotificacion('✅ ' + productoNombre + ' añadido al carrito');
            
            // Actualizar contador del carrito en el navbar
            actualizarContadorCarrito();
            
            // Restaurar botón
            setTimeout(() => {
                boton.prop('disabled', false).html('Añadir al carrito');
            }, 1000);
        },
        error: function() {
            mostrarNotificacion('❌ Error al añadir al carrito', 'error');
            boton.prop('disabled', false).html('Añadir al carrito');
        }
    });
});

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'success') {
    // Crear notificación
    const notificacion = $('<div class="alert alert-' + (tipo === 'error' ? 'danger' : 'success') + ' alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">' +
        mensaje +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>');
    
    // Añadir al body y auto-eliminar después de 3 segundos
    $('body').append(notificacion);
    setTimeout(() => {
        notificacion.alert('close');
    }, 3000);
}

// Función para actualizar contador del carrito
function actualizarContadorCarrito() {
    $.ajax({
        url: '<?= BASE_URL ?>?c=carrito&a=obtenerInfo',
        method: 'GET',
        success: function(info) {
            $('.cart-count').text(info.total_items || 0);
        }
    });
}

// Actualizar contador al cargar la página
$(document).ready(function() {
    actualizarContadorCarrito();
});

</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>