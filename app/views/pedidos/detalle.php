<?php 
$title = $data['title'] ?? 'Detalle del Pedido';
$pedido = $data['pedido'] ?? [];
$lineas_pedido = $data['lineas_pedido'] ?? [];
include '../app/views/layouts/header.php'; 
?>

<section class="detalle-pedido py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Detalle del Pedido #<?= $pedido['id'] ?></h1>
                    <a href="<?= BASE_URL ?>?c=pedido&a=misPedidos" class="btn btn-outline-secondary">
                        ← Volver a Mis Pedidos
                    </a>
                </div>
                
                <!-- Información del pedido -->
                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">📋 Información del Pedido</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Número de Pedido:</strong> #<?= $pedido['id'] ?></p>
                                <p><strong>Fecha:</strong> <?= $pedido['fecha'] ?> <?= $pedido['hora'] ?></p>
                                <p><strong>Estado:</strong> 
                                    <span class="badge 
                                        <?= $pedido['estado'] == 'pendiente' ? 'bg-warning' : '' ?>
                                        <?= $pedido['estado'] == 'procesado' ? 'bg-info' : '' ?>
                                        <?= $pedido['estado'] == 'enviado' ? 'bg-primary' : '' ?>
                                        <?= $pedido['estado'] == 'entregado' ? 'bg-success' : '' ?>
                                        text-capitalize">
                                        <?= $pedido['estado'] ?>
                                    </span>
                                </p>
                                <p><strong>Total:</strong> €<?= number_format($pedido['coste'], 2) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">📦 Dirección de Envío</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Provincia:</strong> <?= htmlspecialchars($pedido['provincia']) ?></p>
                                <p><strong>Localidad:</strong> <?= htmlspecialchars($pedido['localidad']) ?></p>
                                <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Productos del pedido -->
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">🛍️ Productos del Pedido</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php foreach($lineas_pedido as $linea): ?>
                        <div class="pedido-producto border-bottom p-3">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <?php if(!empty($linea['imagen'])): ?>
                                    <img src="/images/productos/<?= $linea['imagen'] ?>" 
                                         alt="<?= htmlspecialchars($linea['nombre']) ?>"
                                         class="img-fluid rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        🕯️
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-5">
                                    <h6 class="mb-1"><?= htmlspecialchars($linea['nombre']) ?></h6>
                                    <small class="text-muted">Precio unitario: €<?= number_format($linea['precio'], 2) ?></small>
                                </div>
                                
                                <div class="col-md-2 text-center">
                                    <span class="fw-bold">Cantidad: <?= $linea['unidades'] ?></span>
                                </div>
                                
                                <div class="col-md-3 text-end">
                                    <strong>€<?= number_format($linea['precio'] * $linea['unidades'], 2) ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Total -->
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>Total:</span>
                                    <span>€<?= number_format($pedido['coste'], 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/footer.php'; ?>