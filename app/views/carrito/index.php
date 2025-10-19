<?php 
$title = $data['title'] ?? 'Carrito de Compras';
$carrito = $data['carrito'] ?? [];
$productos = $data['productos'] ?? [];
include '../app/views/layouts/header.php'; 
?>

<section class="carrito py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-5">🛒 Mi Carrito de Compras</h1>
                
                <?php if (empty($productos)): ?>
                    <!-- Carrito vacío -->
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <span style="font-size: 4rem;">🛒</span>
                            <h3 class="mt-3">Tu carrito está vacío</h3>
                            <p class="text-muted">Añade algunos productos increíbles a tu carrito</p>
                            <a href="<?= BASE_URL ?>?c=producto" class="btn btn-primary btn-lg mt-3">
                                Continuar Comprando
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Carrito con productos -->
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Lista de productos -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Productos en el carrito (<?= $carrito['total_items'] ?> items)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <?php foreach($productos as $producto): ?>
                                    <div class="carrito-item border-bottom p-3" id="item-<?= $producto['id'] ?>">
                                        <div class="row align-items-center">
                                            <!-- Imagen -->
                                            <div class="col-md-2">
                                                <?php if(!empty($producto['imagen'])): ?>
                                                <img src="/images/productos/<?= $producto['imagen'] ?>" 
                                                    alt="<?= htmlspecialchars($producto['nombre']) ?>"
                                                    class="img-fluid rounded" 
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                                <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                    style="width: 80px; height: 80px;">
                                                    🕯️
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Información del producto -->
                                            <div class="col-md-4">
                                                <h6 class="mb-1"><?= htmlspecialchars($producto['nombre']) ?></h6>
                                                <p class="text-muted mb-0 small"><?= htmlspecialchars($producto['categoria_nombre']) ?></p>
                                                <p class="text-success mb-0 fw-bold">€<?= number_format($producto['precio'], 2) ?></p>
                                            </div>
                                            
                                            <!-- Cantidad -->
                                            <div class="col-md-3">
                                                <div class="input-group input-group-sm" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary btn-update-cantidad" 
                                                            type="button" 
                                                            data-id="<?= $producto['id'] ?>" 
                                                            data-action="decrement">-</button>
                                                    <input type="number" 
                                                        class="form-control text-center cantidad-input"
                                                        value="<?= $producto['cantidad'] ?>" 
                                                        min="1" 
                                                        max="<?= $producto['stock'] ?>"
                                                        data-id="<?= $producto['id'] ?>"
                                                        data-precio="<?= $producto['precio'] ?>">
                                                    <button class="btn btn-outline-secondary btn-update-cantidad" 
                                                            type="button" 
                                                            data-id="<?= $producto['id'] ?>" 
                                                            data-action="increment">+</button>
                                                </div>
                                                <small class="text-muted">Stock: <?= $producto['stock'] ?></small>
                                            </div>
                                            
                                            <!-- Subtotal -->
                                            <div class="col-md-2 text-center">
                                                <p class="h6 mb-0 subtotal" id="subtotal-<?= $producto['id'] ?>">
                                                    €<?= number_format($producto['precio'] * $producto['cantidad'], 2) ?>
                                                </p>
                                            </div>
                                            
                                            <!-- Eliminar -->
                                            <div class="col-md-1 text-end">
                                                <button class="btn btn-outline-danger btn-sm btn-eliminar" 
                                                        data-id="<?= $producto['id'] ?>"
                                                        data-nombre="<?= htmlspecialchars($producto['nombre']) ?>">
                                                    🗑️
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Vaciar carrito -->
                                <div class="card-footer">
                                    <form action="<?= BASE_URL ?>?c=carrito&a=vaciar" method="POST" 
                                        onsubmit="return confirm('¿Estás seguro de que quieres vaciar el carrito?')">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            🗑️ Vaciar Carrito
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Resumen del pedido -->
                        <div class="col-lg-4 mt-4 mt-lg-0">
                            <div class="card sticky-top" style="top: 20px;">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Resumen del Pedido</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span id="subtotal-carrito">€<?= number_format($carrito['total'], 2) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Envío:</span>
                                        <span>€0.00</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <strong>Total:</strong>
                                        <strong id="total-carrito">€<?= number_format($carrito['total'], 2) ?></strong>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="<?= BASE_URL ?>?c=producto" class="btn btn-outline-primary">
                                            ← Seguir Comprando
                                        </a>
                                        <a href="<?= BASE_URL ?>?c=pedido&a=checkout" class="btn btn-success btn-lg <?= empty($productos) ? 'disabled' : '' ?>">
                                            🛍️ Proceder al Checkout
                                        </a>
                                    </div>
                                    
                                    <div class="mt-3 text-center">
                                        <small class="text-muted">
                                            ✅ Envío gratis en pedidos superiores a €50
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript para el carrito -->
<script>
$(document).ready(function() {
    // Actualizar cantidad
    $('.btn-update-cantidad').click(function() {
        const productoId = $(this).data('id');
        const action = $(this).data('action');
        const input = $(`.cantidad-input[data-id="${productoId}"]`);
        let cantidad = parseInt(input.val());
        const precio = parseFloat(input.data('precio'));
        const stock = parseInt(input.attr('max'));
        
        if (action === 'increment' && cantidad < stock) {
            cantidad++;
        } else if (action === 'decrement' && cantidad > 1) {
            cantidad--;
        }
        
        input.val(cantidad);
        actualizarCantidadProducto(productoId, cantidad, precio);
    });
    
    // Actualizar cantidad al cambiar input
    $('.cantidad-input').on('change', function() {
        const productoId = $(this).data('id');
        const cantidad = parseInt($(this).val());
        const precio = parseFloat($(this).data('precio'));
        const stock = parseInt($(this).attr('max'));
        
        if (cantidad < 1) {
            $(this).val(1);
            return;
        }
        
        if (cantidad > stock) {
            $(this).val(stock);
            alert('No hay suficiente stock disponible');
            return;
        }
        
        actualizarCantidadProducto(productoId, cantidad, precio);
    });
    
    // Eliminar producto
    $('.btn-eliminar').click(function() {
        const productoId = $(this).data('id');
        const productoNombre = $(this).data('nombre');
        
        if (confirm(`¿Eliminar "${productoNombre}" del carrito?`)) {
            $.ajax({
                url: '<?= BASE_URL ?>?c=carrito&a=eliminar&id=' + productoId,
                method: 'GET',
                success: function() {
                    $(`#item-${productoId}`).fadeOut(300, function() {
                        $(this).remove();
                        actualizarContadorCarrito();
                        location.reload(); // Recargar para actualizar resumen
                    });
                }
            });
        }
    });
    
    // Validación antes de checkout
    $('#btn-checkout').click(function(e) {
        // Si el carrito está vacío, prevenir el click
        if (<?= empty($productos) ? 'true' : 'false' ?>) {
            e.preventDefault();
            alert('❌ El carrito está vacío. Añade productos antes de proceder al checkout.');
            return false;
        }
        
        // Si hay productos, permitir la navegación normal
        return true;
    });
    
    function actualizarCantidadProducto(productoId, cantidad, precio) {
        $.ajax({
            url: '<?= BASE_URL ?>?c=carrito&a=actualizar',
            method: 'POST',
            data: {
                producto_id: productoId,
                cantidad: cantidad
            },
            success: function() {
                // Actualizar subtotal del producto
                const subtotal = precio * cantidad;
                $(`#subtotal-${productoId}`).text('€' + subtotal.toFixed(2));
                
                // Recargar para actualizar totales
                location.reload();
            }
        });
    }
});
</script>

<?php include '../app/views/layouts/footer.php'; ?>