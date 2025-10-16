<?php 
$title = $data['title'] ?? 'Editar Producto';
$producto = $data['producto'] ?? [];
$categorias = $data['categorias'] ?? [];
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
                <h1 class="h3 mb-0">Editar Producto</h1>
                <a href="<?= BASE_URL ?>?c=admin&a=productos" class="btn btn-outline-secondary">
                    ← Volver a Productos
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="<?= BASE_URL ?>?c=admin&a=actualizarProducto" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Producto *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción *</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="precio" class="form-label">Precio (€) *</label>
                                            <input type="number" class="form-control" id="precio" name="precio" 
                                                   step="0.01" min="0" value="<?= $producto['precio'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">Stock *</label>
                                            <input type="number" class="form-control" id="stock" name="stock" 
                                                   min="0" value="<?= $producto['stock'] ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="categoria_id" class="form-label">Categoría *</label>
                                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                                        <option value="">Seleccionar categoría</option>
                                        <?php foreach($categorias as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" 
                                            <?= $cat['id'] == $producto['categoria_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nombre']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="oferta" class="form-label">Oferta</label>
                                    <select class="form-select" id="oferta" name="oferta">
                                        <option value="NO" <?= $producto['oferta'] == 'NO' ? 'selected' : '' ?>>No</option>
                                        <option value="SI" <?= $producto['oferta'] == 'SI' ? 'selected' : '' ?>>Sí</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="imagen" class="form-label">Imagen del Producto</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" 
                                           accept="image/*">
                                    <div class="form-text">Dejar vacío para mantener la imagen actual</div>
                                </div>
                                
                                <!-- Imagen actual -->
                                <?php if($producto['imagen']): ?>
                                <div class="mb-3">
                                    <label class="form-label">Imagen Actual</label>
                                    <div>
                                        <img src="/images/productos/<?= $producto['imagen'] ?>"  
                                        class="img-fluid rounded" 
                                        style="max-height: 150px;"
                                        alt="<?= htmlspecialchars($producto['nombre']) ?>">
                                        <div class="mt-2">
                                            <small class="text-muted"><?= $producto['imagen'] ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= BASE_URL ?>?c=admin&a=productos" class="btn btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Actualizar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/views/layouts/footer.php'; ?>