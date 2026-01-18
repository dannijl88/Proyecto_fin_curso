<?php 

if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: ' . BASE_URL . '/?c=usuario&a=login');
    exit;
}
$title = 'Gestión de Categorías';
$categorias = $data['categorias'] ?? [];
include __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
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
                    <a href="<?= BASE_URL ?>?c=categoria&a=index" class="list-group-item list-group-item-action active">
                        📁 Gestión de Categorías
                    </a>
                    <a href="<?= BASE_URL ?>?c=producto" class="list-group-item list-group-item-action">
                        👁️ Ver Tienda
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Contenido principal -->
        <div class="col-md-9" style="min-height: 78vh;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestión de Categorías</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearCategoria">
                    + Nueva Categoría
                </button>
            </div>

            <!-- Mensajes de éxito/error -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    ✅ Categoría guardada correctamente
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    ❌ <?= htmlspecialchars($_GET['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Tabla de categorías -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($categorias)): ?>
                        <p class="text-muted text-center py-4">No hay categorías creadas</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($categorias as $categoria): ?>
                                    <tr>
                                        <td><?= $categoria['id'] ?></td>
                                        <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                                        <td><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>?c=categoria&a=editar&id=<?= $categoria['id'] ?>" 
                                            class="btn btn-sm btn-outline-primary">Editar</a>
                                            <button onclick="confirmarEliminacion(<?= $categoria['id'] ?>, '<?= htmlspecialchars($categoria['nombre']) ?>')" 
                                                    class="btn btn-sm btn-outline-danger">Eliminar</button>
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

<!-- Modal para crear categoría -->
<div class="modal fade" id="modalCrearCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>?c=categoria&a=crear" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(id, nombre) {
    if (confirm(`¿Estás seguro de que quieres eliminar la categoría "${nombre}"?`)) {
        window.location.href = `<?= BASE_URL ?>?c=categoria&a=eliminar&id=${id}`;
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>