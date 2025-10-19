<?php 
$title = $data['title'] ?? 'Gestión de Pedidos';
$pedidos = $data['pedidos'] ?? [];
$estadisticas = $data['estadisticas'] ?? [];
include '../app/views/layouts/header.php'; 
?>

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
                    <a href="<?= BASE_URL ?>?c=admin&a=pedidos" class="list-group-item list-group-item-action active">
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

            <!-- Estadísticas -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">📈 Estadísticas Pedidos</h6>
                </div>
                <div class="card-body">
                    <small class="d-block">Total Pedidos: <strong><?= $estadisticas['total_pedidos'] ?? 0 ?></strong></small>
                    <small class="d-block">Ingresos: <strong>€<?= number_format($estadisticas['ingresos_totales'] ?? 0, 2) ?></strong></small>
                    <small class="d-block">Completados: <strong><?= $estadisticas['pedidos_completados'] ?? 0 ?></strong></small>
                    <small class="d-block">Pendientes: <strong><?= $estadisticas['pedidos_pendientes'] ?? 0 ?></strong></small>
                </div>
            </div>
        </div>
        
        <div class="col-md-9" style="min-height: 78vh;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestión de Pedidos</h1>
            </div>

            <!-- Mensajes de feedback -->
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['mensaje'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Lista de pedidos -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($pedidos)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">No hay pedidos registrados</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($pedidos as $pedido): ?>
                                    <tr>
                                        <td>#<?= $pedido['id'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($pedido['usuario_nombre']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= htmlspecialchars($pedido['usuario_email']) ?></small>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y', strtotime($pedido['fecha'])) ?>
                                            <br>
                                            <small class="text-muted"><?= $pedido['hora'] ?></small>
                                        </td>
                                        <td>
                                            <strong class="text-success">€<?= number_format($pedido['coste'], 2) ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                <?= $pedido['estado'] == 'completado' ? 'bg-success' : '' ?>
                                                <?= $pedido['estado'] == 'pendiente' ? 'bg-warning text-dark' : '' ?>
                                                <?= $pedido['estado'] == 'cancelado' ? 'bg-danger' : '' ?>
                                            ">
                                                <?= ucfirst($pedido['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= BASE_URL ?>?c=admin&a=detallePedido&id=<?= $pedido['id'] ?>" 
                                                   class="btn btn-outline-primary" title="Ver detalle">
                                                    👁️
                                                </a>
                                                
                                                <!-- Formulario para cambiar estado -->
                                                <form method="POST" action="<?= BASE_URL ?>?c=admin&a=actualizarEstadoPedido" 
                                                      class="d-inline" onchange="this.submit()">
                                                    <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                                    <select name="estado" class="form-select form-select-sm">
                                                        <option value="pendiente" <?= $pedido['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                        <option value="completado" <?= $pedido['estado'] == 'completado' ? 'selected' : '' ?>>Completado</option>
                                                        <option value="cancelado" <?= $pedido['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>