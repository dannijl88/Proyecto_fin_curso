<?php 
$title = $data['title'] ?? 'Gestión de Productos';
$productos = $data['productos'] ?? [];
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
                    <a href="<?= BASE_URL ?>?c=admin&a=productos" class="list-group-item list-group-item-action active">
                        🕯️ Gestión de Productos
                    </a>
                    <a href="<?= BASE_URL ?>?c=admin&a=nuevoProducto" class="list-group-item list-group-item-action">
                        ➕ Nuevo Producto
                    </a>
                    <a href="<?= BASE_URL ?>?c=producto" class="list-group-item list-group-item-action">
                        👁️ Ver Tienda
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Gestión de Productos</h1>
                <a href="<?= BASE_URL ?>?c=admin&a=nuevoProducto" class="btn btn-success">
                    ➕ Añadir Producto
                </a>
            </div>

            <!-- Lista de productos -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($productos)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">No hay productos registrados</p>
                            <a href="<?= BASE_URL ?>?c=admin&a=nuevoProducto" class="btn btn-primary">
                                Crear primer producto
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Oferta</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($productos as $producto): ?>
                                    <tr>
                                        <td><?= $producto['id'] ?></td>
                                        <td>
                                            <?php if($producto['imagen']): ?>
                                                <img src="/images/productos/<?= $producto['imagen'] ?>"   
                                                alt="<?= htmlspecialchars($producto['nombre']) ?>" 
                                                style="width: 50px; height: 50px; object-fit: cover;" 
                                                class="rounded">
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    🕯️
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($producto['nombre']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= substr($producto['descripcion'], 0, 50) ?>...</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?= $producto['categoria_nombre'] ?></span>
                                        </td>
                                        <td>
                                            <strong class="text-success">€<?= number_format($producto['precio'], 2) ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge <?= $producto['stock'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $producto['stock'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($producto['oferta'] == 'SI'): ?>
                                                <span class="badge bg-warning text-dark">OFERTA</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted">No</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= BASE_URL ?>?c=admin&a=editarProducto&id=<?= $producto['id'] ?>" 
                                            class="btn btn-outline-primary" title="Editar">
                                                ✏️
                                            </a>
                                                <a href="<?= BASE_URL ?>?c=producto&a=ver&id=<?= $producto['id'] ?>" 
                                                   class="btn btn-outline-info" title="Ver">
                                                    👁️
                                                </a>
                                                <button class="btn btn-outline-danger" title="Eliminar" 
                                                        onclick="confirmarEliminacion(<?= $producto['id'] ?>, '<?= htmlspecialchars($producto['nombre']) ?>')">
                                                    🗑️
                                                </button>
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

<script>
function confirmarEliminacion(id, nombre) {
    if (confirm(`¿Estás seguro de que quieres eliminar el producto "${nombre}"?`)) {
        // Aquí irá la lógica para eliminar
        alert('Funcionalidad de eliminar pendiente - ID: ' + id);
    }
}
</script>

<?php include '../app/views/layouts/footer.php'; ?>