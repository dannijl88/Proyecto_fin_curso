<?php 
$title = $data['title'] ?? 'Perfil';
$usuario = $data['usuario'] ?? [];
include __DIR__ . '/../layouts/header.php';
?>

<section class="perfil py-5" style="min-height: 82vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h1 class="card-title text-center mb-4">Mi Perfil</h1>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Información Personal</h5>
                                <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
                                <p><strong>Apellidos:</strong> <?= htmlspecialchars($usuario['apellidos'] ?? '') ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                                <p><strong>Rol:</strong> <?= htmlspecialchars($usuario['rol']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Preferencias</h5>
                                
                                <!-- Mostrar cookies -->
                                <?php if (isset($_COOKIE['ultima_visita'])): ?>
                                <p><strong>🍪 Última visita:</strong> <?= $_COOKIE['ultima_visita'] ?></p>
                                <?php endif; ?>
                                
                                <?php if (isset($_COOKIE['usuario_email'])): ?>
                                <p><strong>🔑 Sesión recordada:</strong> Sí</p>
                                <?php endif; ?>
                                
                                <div class="d-grid gap-2">
                                    <a href="<?= BASE_URL ?>?c=usuario&a=logout" class="btn btn-outline-danger">
                                        Cerrar Sesión
                                    </a>
                                    <a href="<?= BASE_URL ?>" class="btn btn-outline-primary">
                                        Volver al Inicio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>