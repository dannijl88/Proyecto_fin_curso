<?php 
$title = $data['title'] ?? 'Checkout';
$carrito = $data['carrito'] ?? [];
$usuario = $data['usuario'] ?? [];
$errores = $_SESSION['errores_checkout'] ?? [];
$datos_formulario = $_SESSION['datos_formulario'] ?? [];

// Limpiar errores de sesión después de mostrarlos
unset($_SESSION['errores_checkout']);
unset($_SESSION['datos_formulario']);

include __DIR__ . '/../layouts/header.php';
?>

<section class="checkout py-5" style="min-height: 82vh;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-5">✅ Finalizar Compra</h1>
                
                <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); endif; ?>
                
                <div class="row">
                    <!-- Formulario de envío -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">📦 Datos de Envío</h5>
                            </div>
                            <div class="card-body">
                                <form action="<?= BASE_URL ?>?c=pedido&a=procesar" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="nombre" 
                                                value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" 
                                                readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" 
                                                value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" 
                                                readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="provincia" class="form-label">Provincia *</label>
                                        <input type="text" class="form-control <?= isset($errores['provincia']) ? 'is-invalid' : '' ?>" 
                                            id="provincia" name="provincia" 
                                            value="<?= htmlspecialchars($datos_formulario['provincia'] ?? '') ?>" 
                                            required>
                                        <?php if (isset($errores['provincia'])): ?>
                                        <div class="invalid-feedback"><?= $errores['provincia'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="localidad" class="form-label">Localidad *</label>
                                        <input type="text" class="form-control <?= isset($errores['localidad']) ? 'is-invalid' : '' ?>" 
                                            id="localidad" name="localidad" 
                                            value="<?= htmlspecialchars($datos_formulario['localidad'] ?? '') ?>" 
                                            required>
                                        <?php if (isset($errores['localidad'])): ?>
                                        <div class="invalid-feedback"><?= $errores['localidad'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección *</label>
                                        <textarea class="form-control <?= isset($errores['direccion']) ? 'is-invalid' : '' ?>" 
                                                id="direccion" name="direccion" rows="3" 
                                                required><?= htmlspecialchars($datos_formulario['direccion'] ?? '') ?></textarea>
                                        <?php if (isset($errores['direccion'])): ?>
                                        <div class="invalid-feedback"><?= $errores['direccion'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <a href="<?= BASE_URL ?>?c=carrito" class="btn btn-outline-secondary me-md-2">
                                            ← Volver al Carrito
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg">
                                            🛍️ Confirmar Pedido
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resumen del pedido -->
                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="card sticky-top" style="top: 20px;">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">📋 Resumen del Pedido</h5>
                            </div>
                            <div class="card-body">
                                <!-- Productos -->
                                <?php foreach($carrito['productos'] as $producto): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <div>
                                        <small class="fw-bold"><?= htmlspecialchars($producto['nombre']) ?></small>
                                        <br>
                                        <small class="text-muted">Cantidad: <?= $producto['cantidad'] ?></small>
                                    </div>
                                    <small>€<?= number_format($producto['precio'] * $producto['cantidad'], 2) ?></small>
                                </div>
                                <?php endforeach; ?>
                                
                                <!-- Totales -->
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Subtotal:</span>
                                        <span>€<?= number_format($carrito['total'], 2) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Envío:</span>
                                        <span>€0.00</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <span>€<?= number_format($carrito['total'], 2) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>