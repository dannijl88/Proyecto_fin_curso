<?php 
$title = $data['title'] ?? 'Login';
$error = $data['error'] ?? '';
$registro_exitoso = isset($_GET['registro']) && $_GET['registro'] === 'exitoso';
include '../app/views/layouts/header.php'; 
?>

<section class="login py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <h1 class="card-title text-center mb-4">Iniciar Sesión</h1>
                        
                        <?php if ($registro_exitoso): ?>
                        <div class="alert alert-success">
                            ✅ ¡Registro exitoso! Ahora puedes iniciar sesión.
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?= BASE_URL ?>?c=usuario&a=login">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="recordar" name="recordar">
                                <label class="form-check-label" for="recordar">Recordarme</label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Iniciar Sesión</button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p>¿No tienes cuenta? <a href="<?= BASE_URL ?>?c=usuario&a=registro" class="text-decoration-none">Regístrate aquí</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/footer.php'; ?>