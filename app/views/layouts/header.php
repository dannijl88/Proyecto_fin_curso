<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ignia - <?= $title ?? 'Inicio' ?></title>
    
    <!-- Bootstrap CDN -->
    <!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/index.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>">
                🕯️ Ignia
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>?c=producto">Productos</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <!-- USUARIO LOGUEADO -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                👋 Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
                                <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
                                    <span class="badge bg-warning ms-1">Admin</span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>?c=usuario&a=perfil">👤 Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>?c=pedido&a=misPedidos">📦 Mis Pedidos</a>
                                </li>
                                
                                <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-warning" href="<?= BASE_URL ?>?c=admin">
                                    🛠️ Panel Admin
                                </a></li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>?c=usuario&a=logout">
                                    🚪 Cerrar Sesión
                                </a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>?c=carrito">
                                🛒 Carrito <span class="badge bg-primary cart-count">0</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- USUARIO NO LOGUEADO -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>?c=usuario&a=login">
                                🔑 Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>?c=usuario&a=registro">
                                📝 Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
    
    <main class="container mt-4 pl-4" style="max-width: 1800px; margin: 0 auto;">
    