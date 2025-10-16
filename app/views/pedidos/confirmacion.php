<?php 
$title = $data['title'] ?? 'Pedido Confirmado';
$pedido = $data['pedido'] ?? [];
$lineas_pedido = $data['lineas_pedido'] ?? [];
include '../app/views/layouts/header.php'; 
?>

<section class="confirmacion-pedido py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card text-center border-success">
                    <div class="card-header bg-success text-white">
                        <h2 class="mb-0">🎉 ¡Pedido Confirmado!</h2>
                    </div>
                    <div class="card-body py-5">
                        <div class="mb-4">
                            <span style="font-size: 4rem;">✅</span>
                        </div>
                        <h3 class="card-title">Gracias por tu compra</h3>
                        <p class="card-text lead">Tu pedido ha sido procesado exitosamente.</p>
                        
                        <div class="alert alert-info mt-4">
                            <h5>Número de Pedido: <strong>#<?= $pedido['id'] ?? 'N/A' ?></strong></h5>
                            <p class="mb-0">Fecha: <?= $pedido['fecha'] ?? '' ?> <?= $pedido['hora'] ?? '' ?></p>
                        </div>
                        
                        <!-- Resumen del pedido -->
                        <div class="mt-4 text-start">
                            <h5>Resumen del Pedido:</h5>
                            <?php foreach($lineas_pedido as $linea): ?>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span><?= htmlspecialchars($linea['nombre']) ?> x<?= $linea['unidades'] ?></span>
                                <span>€<?= number_format($linea['precio'] * $linea['unidades'], 2) ?></span>
                            </div>
                            <?php endforeach; ?>
                            
                            <div class="d-flex justify-content-between mt-3 fw-bold">
                                <span>Total:</span>
                                <span>€<?= number_format($pedido['coste'] ?? 0, 2) ?></span>
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <p class="text-muted">
                                Recibirás un email de confirmación shortly.
                            </p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="<?= BASE_URL ?>?c=producto" class="btn btn-primary me-md-2">
                                🛍️ Seguir Comprando
                            </a>
                            <a href="<?= BASE_URL ?>?c=pedido&a=misPedidos" class="btn btn-outline-primary">
                                📦 Ver Mis Pedidos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/footer.php'; ?>