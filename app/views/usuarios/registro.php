<?php 
$title = $data['title'] ?? 'Registro';
$error = $data['error'] ?? '';
$form_data = $data['form_data'] ?? [];
include __DIR__ . '/../layouts/header.php';
?>

<section class="registro py-5" style="min-height: 82vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h1 class="card-title text-center mb-4">Crear Cuenta</h1>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?= BASE_URL ?>?c=usuario&a=registro">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                    value="<?= htmlspecialchars($form_data['nombre'] ?? '') ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                    value="<?= htmlspecialchars($form_data['apellidos'] ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="form-text text-muted">Mínimo 6 caracteres</small>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password_confirm" class="form-label">Confirmar Contraseña *</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Registrarse</button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p>¿Ya tienes cuenta? <a href="<?= BASE_URL ?>?c=usuario&a=login" class="text-decoration-none">Inicia sesión aquí</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript de validación -->
<script>
$(document).ready(function() {
    // Validación formulario registro
    $('#form-registro').on('submit', function(e) {
        let errores = [];
        const nombre = $('#nombre').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const passwordConfirm = $('#password_confirm').val();
        
        // Validar nombre
        if (nombre.length < 2) {
            errores.push('El nombre debe tener al menos 2 caracteres');
            $('#nombre').addClass('is-invalid');
        }
        
        // Validar email
        if (!email.includes('@') || !email.includes('.')) {
            errores.push('El email no es válido');
            $('#email').addClass('is-invalid');
        }
        
        // Validar contraseña (mínimo 6 caracteres)
        if (password.length < 6) {
            errores.push('La contraseña debe tener al menos 6 caracteres');
            $('#password').addClass('is-invalid');
        }
        
        // Validar que las contraseñas coinciden
        if (password !== passwordConfirm) {
            errores.push('Las contraseñas no coinciden');
            $('#password_confirm').addClass('is-invalid');
        }
        
        // Si hay errores, prevenir envío
        if (errores.length > 0) {
            e.preventDefault();
            mostrarErrores(errores);
        }
    });
    
    // Quitar clases de error al escribir
    $('#nombre, #email, #password, #password_confirm').on('input', function() {
        $(this).removeClass('is-invalid');
    });
    
    function mostrarErrores(errores) {
        // Crear o actualizar contenedor de errores
        let $errorContainer = $('#errores-validacion');
        if ($errorContainer.length === 0) {
            $errorContainer = $('<div id="errores-validacion" class="alert alert-danger mt-3"></div>');
            $('form').before($errorContainer);
        }
        
        // Mostrar errores
        $errorContainer.html('<strong>Por favor corrige los siguientes errores:</strong><ul></ul>');
        errores.forEach(error => {
            $errorContainer.find('ul').append('<li>' + error + '</li>');
        });
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>