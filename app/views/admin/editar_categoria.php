<?php 
$title = 'Editar Categoría';
$categoria = $data['categoria'] ?? null;
include __DIR__ . '/../layouts/header.php';

if (!$categoria): ?>
    <div class="container-fluid py-4">
        <div class="alert alert-danger">Categoría no encontrada</div>
        <a href="<?= BASE_URL ?>?c=categoria&a=index" class="btn btn-secondary">Volver</a>
    </div>
<?php else: ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Editar Categoría</h1>
                <a href="<?= BASE_URL ?>?c=categoria&a=index" class="btn btn-secondary">Volver</a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="<?= BASE_URL ?>?c=categoria&a=editar&id=<?= $categoria['id'] ?>" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                value="<?= htmlspecialchars($categoria['nombre']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="<?= BASE_URL ?>?c=categoria&a=index" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>