<?php 
$title = $data['title'] ?? 'Nuevo Producto';
$categorias = $data['categorias'] ?? [];
include '../app/views/layouts/header.php'; 
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar (mismo que en productos.php) -->
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
                    <a href="<?= BASE_URL ?>?c=admin&a=nuevoProducto" class="list-group-item list-group-item-action active">
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
                <h1 class="h3 mb-0">Nuevo Producto</h1>
                <a href="<?= BASE_URL ?>?c=admin&a=productos" class="btn btn-outline-secondary">
                    ← Volver a Productos
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="<?= BASE_URL ?>?c=admin&a=guardarProducto" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Información básica -->
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Producto *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción *</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="precio" class="form-label">Precio (€) *</label>
                                            <input type="number" class="form-control" id="precio" name="precio" 
                                                   step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock" class="form-label">Stock *</label>
                                            <input type="number" class="form-control" id="stock" name="stock" 
                                                   min="0" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Categoría e imagen -->
                                <div class="mb-3">
                                    <label for="categoria_id" class="form-label">Categoría *</label>
                                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                                        <option value="">Seleccionar categoría</option>
                                        <?php foreach($categorias as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="oferta" class="form-label">Oferta</label>
                                    <select class="form-select" id="oferta" name="oferta">
                                        <option value="NO">No</option>
                                        <option value="SI">Sí</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="imagen" class="form-label">Imagen del Producto</label>
                                    <input type="file" class="form-control" id="imagen" name="imagen" 
                                           accept="image/*">
                                    <div class="form-text">Formatos: JPG, PNG, WEBP. Máx: 2MB</div>
                                </div>
                                
                                <!-- Vista previa de imagen -->
                                <div class="mb-3 text-center">
                                    <div id="vista-previa" class="border rounded p-3 bg-light" style="display: none;">
                                        <img id="preview-img" class="img-fluid rounded" style="max-height: 200px;">
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="eliminarVistaPrevia()">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= BASE_URL ?>?c=admin&a=productos" class="btn btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-success">Guardar Producto</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Vista previa de imagen
document.getElementById('imagen').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('vista-previa').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

function eliminarVistaPrevia() {
    document.getElementById('imagen').value = '';
    document.getElementById('vista-previa').style.display = 'none';
}
</script>

<?php include '../app/views/layouts/footer.php'; ?>