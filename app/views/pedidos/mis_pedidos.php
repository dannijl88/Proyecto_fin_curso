<?php 
$title = $data['title'] ?? 'Mis Pedidos';
$pedidos = $data['pedidos'] ?? [];
include '../app/views/layouts/header.php'; 
?>

<section class="mis-pedidos py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-5">📦 Mis Pedidos</h1>
                
                <?php if (empty($pedidos)): ?>
                    <!-- Sin pedidos -->
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <span style="font-size: 4rem;">📭</span>
                            <h3 class="mt-3">No tienes pedidos aún</h3>
                            <p class="text-muted">Cuando realices un pedido, aparecerá aquí</p>
                            <a href="<?= BASE_URL ?>?c=producto" class="btn btn-primary btn-lg mt-3">
                                🛍️ Comenzar a Comprar
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Lista de pedidos -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Historial de Pedidos</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php foreach($pedidos as $pedido): ?>
                            <div class="pedido-item border-bottom p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <h6 class="mb-1">Pedido #<?= $pedido['id'] ?></h6>
                                        <small class="text-muted">
                                            <?= $pedido['fecha'] ?> <?= $pedido['hora'] ?>
                                        </small>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <span class="badge 
                                            <?= $pedido['estado'] == 'pendiente' ? 'bg-warning' : '' ?>
                                            <?= $pedido['estado'] == 'procesado' ? 'bg-info' : '' ?>
                                            <?= $pedido['estado'] == 'enviado' ? 'bg-primary' : '' ?>
                                            <?= $pedido['estado'] == 'entregado' ? 'bg-success' : '' ?>
                                            text-capitalize">
                                            <?= $pedido['estado'] ?>
                                        </span>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <strong>€<?= number_format($pedido['coste'], 2) ?></strong>
                                    </div>
                                    
                                    <div class="col-md-3 text-end">
                                        <a href="<?= BASE_URL ?>?c=pedido&a=detalle&id=<?= $pedido['id'] ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            👁️ Ver Detalle
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Dirección de envío -->
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <small class="text-muted">
                                            📍 <?= htmlspecialchars($pedido['direccion']) ?>, 
                                            <?= htmlspecialchars($pedido['localidad']) ?>, 
                                            <?= htmlspecialchars($pedido['provincia']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="<?= BASE_URL ?>?c=producto" class="btn btn-outline-primary">
                            ← Seguir Comprando
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/footer.php'; ?>