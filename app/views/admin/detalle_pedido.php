<?php 
$title = $data['title'] ?? 'Detalle del Pedido';
$pedido = $data['pedido'] ?? null;
$lineas_pedido = $data['lineas_pedido'] ?? [];
include __DIR__ . '/../layouts/header.php';

if (!$pedido): ?>
    <div class="container-fluid py-4">
        <div class="alert alert-danger">
            <h4>Pedido no encontrado</h4>
            <a href="<?= BASE_URL ?>?c=admin&a=pedidos" class="btn btn-primary">Volver a pedidos</a>
        </div>
    </div>
<?php else: ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🛠️ Panel de Control</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= BASE_URL ?>?c=admin" class="list-group-item list-group-item-action">
                        📊 Dashboard
                    </a>
                    <a href="<?= BASE_URL ?>?c=admin&a=productos" class="list-group-item list-group-item-action">
                        🕯️ Gestión de Productos
                    </a>
                    <a href="<?= BASE_URL ?>?c=admin&a=pedidos" class="list-group-item list-group-item-action">
                        📦 Gestión de Pedidos
                    </a>
                    <a href="<?= BASE_URL ?>?c=admin&a=nuevoProducto" class="list-group-item list-group-item-action">
                        ➕ Nuevo Producto
                    </a>
                    <a href="<?= BASE_URL ?>?c=categoria&a=index" class="list-group-item list-group-item-action">
                        📁 Gestión de Categorías
                    </a>
                    <a href="<?= BASE_URL ?>?c=producto" class="list-group-item list-group-item-action">
                        👁️ Ver Tienda
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9" style="min-height: 78vh;">
            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Pedido #<?= $pedido['id'] ?></h1>
                    <small class="text-muted">Realizado el <?= date('d/m/Y', strtotime($pedido['fecha'])) ?> a las <?= $pedido['hora'] ?></small>
                </div>
                <a href="<?= BASE_URL ?>?c=admin&a=pedidos" class="btn btn-outline-secondary">
                    ← Volver a pedidos
                </a>
            </div>

            <!-- Estado del pedido -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Estado del Pedido</h5>
                            <form method="POST" action="<?= BASE_URL ?>?c=admin&a=actualizarEstadoPedido" class="d-flex align-items-center gap-3">
                                <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                <select name="estado" class="form-select" style="width: auto;">
                                    <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="completado" <?= $pedido['estado'] == 'completado' ? 'selected' : '' ?>>Completado</option>
                                    <option value="cancelado" <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Información del Cliente</h5>
                            <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars($pedido['usuario_nombre'] ?? 'N/A') ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($pedido['usuario_email'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dirección de envío -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">📦 Dirección de Envío</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Provincia:</strong> <?= htmlspecialchars($pedido['provincia']) ?></p>
                    <p class="mb-1"><strong>Localidad:</strong> <?= htmlspecialchars($pedido['localidad']) ?></p>
                    <p class="mb-0"><strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion']) ?></p>
                </div>
            </div>

            <!-- Productos del pedido -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🛒 Productos del Pedido</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($lineas_pedido)): ?>
                        <p class="text-muted">No hay productos en este pedido</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio Unitario</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($lineas_pedido as $linea): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if($linea['imagen']): ?>
                                                    <img src="/images/productos/<?= $linea['imagen'] ?>" 
                                                        alt="<?= htmlspecialchars($linea['nombre']) ?>" 
                                                        class="rounded me-3"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div>
                                                    <strong><?= htmlspecialchars($linea['nombre']) ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>€<?= number_format($linea['precio'], 2) ?></td>
                                        <td><?= $linea['unidades'] ?></td>
                                        <td><strong>€<?= number_format($linea['precio'] * $linea['unidades'], 2) ?></strong></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total del Pedido:</strong></td>
                                        <td><strong>€<?= number_format($pedido['coste'], 2) ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>