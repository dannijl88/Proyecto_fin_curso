<?php 
$title = $data['title'] ?? 'Admin Panel';
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
                    <a href="<?= BASE_URL ?>?c=admin" class="list-group-item list-group-item-action active">
                        📊 Dashboard
                    </a>
                    <a href="<?= BASE_URL ?>?c=admin&a=productos" class="list-group-item list-group-item-action">
                        🕯️ Gestión de Productos
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
        
        <div class="col-md-9">
            <!-- Contenido principal -->
            <h1 class="h3 mb-4">Dashboard de Administración</h1>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Productos</h5>
                            <h2 class="display-4"><?= $data['totalProductos'] ?></h2>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Productos en Stock</h5>
                            <h2 class="display-4"><?= $data['totalProductos'] ?></h2>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5 class="card-title">En Oferta</h5>
                            <h2 class="display-4">0</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Últimos productos -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Últimos Productos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach(array_slice($data['productos'], 0, 5) as $producto): ?>
                                <tr>
                                    <td><?= $producto['id'] ?></td>
                                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                                    <td>€<?= number_format($producto['precio'], 2) ?></td>
                                    <td><?= $producto['stock'] ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?c=admin&a=editarProducto&id=<?= $producto['id'] ?>" 
                                        class="btn btn-sm btn-outline-primary">Editar</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>