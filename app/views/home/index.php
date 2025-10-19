<?php 
$title = $data['title'] ?? 'Tienda de Velas';
include '../app/views/layouts/header.php'; 
?>

<!-- Hero Section -->
<section class="hero bg-dark text-white py-5 mb-5">
    <div class="container">
        <header>
            <h1 class="display-4 fw-bold">Ilumina Tus Momentos</h1>
            <p class="lead fs-5">Velas artesanales con esencias únicas que transforman tus espacios en santuarios de paz y armonía</p>
        </header>
        <div class="mt-4">
            <a href="<?= BASE_URL ?>?c=producto" class="btn btn-primary btn-lg me-3">Explorar Colección</a>
            <a href="#ofertas" class="btn btn-outline-light btn-lg">Ver Ofertas</a>
        </div>
    </div>
</section>

<!-- Sección de Beneficios -->
<section class="benefits py-5 bg-light">
    <div class="container">
        <header class="text-center mb-5">
            <h2>¿Por Qué Elegir Nuestras Velas?</h2>
            <p class="text-muted">Calidad premium que marca la diferencia</p>
        </header>
        
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                <div class="benefit-icon mb-3">
                    <span style="font-size: 3rem;">🌿</span>
                </div>
                <h4>100% Natural</h4>
                <p class="text-muted">Cera de soja y esencias naturales libres de tóxicos</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="benefit-icon mb-3">
                    <span style="font-size: 3rem;">⏱️</span>
                </div>
                <h4>+60 Horas</h4>
                <p class="text-muted">Duración extendida para máximo disfrute</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="benefit-icon mb-3">
                    <span style="font-size: 3rem;">🚚</span>
                </div>
                <h4>Envío Gratis</h4>
                <p class="text-muted">En compras superiores a €50</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="benefit-icon mb-3">
                    <span style="font-size: 3rem;">💎</span>
                </div>
                <h4>Hecho a Mano</h4>
                <p class="text-muted">Cada vela es una pieza única y artesanal</p>
            </div>
        </div>
    </div>
</section>

<!-- Productos Destacados -->
<section class="featured-products py-5">
    <div class="container">
        <header class="text-center mb-5">
            <h2>Los Más Vendidos</h2>
            <p class="text-muted">Descubre las velas favoritas de nuestros clientes</p>
        </header>
        
        <div class="row" id="featured-products-container">
            <!-- Los productos via AJAX -->
            <div class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando productos...</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Ofertas -->
<section id="ofertas" class="offers py-5 bg-warning bg-opacity-10">
    <div class="container">
        <header class="text-center mb-5">
            <h2>Ofertas Especiales</h2>
            <p class="text-muted">Aprovecha nuestros descuentos por tiempo limitado</p>
        </header>
        
        <div class="row" id="offers-container">
            <!-- Las ofertas via AJAX -->
        </div>
    </div>
</section>

<!-- Testimonios -->
<section class="testimonials py-5 bg-light">
    <div class="container">
        <header class="text-center mb-5">
            <h2>Lo Que Dicen Nuestros Clientes</h2>
            <p class="text-muted">Experiencias reales de amantes de las velas</p>
        </header>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-warning mb-3">★★★★★</div>
                        <p class="card-text">"Las velas de lavanda han transformado mi rutina de yoga. El aroma es increíble y la duración excelente."</p>
                        <footer class="blockquote-footer mt-3">María G.</footer>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-warning mb-3">★★★★★</div>
                        <p class="card-text">"Pedí la caja de regalo para cumpleaños y fue un éxito total. La presentación es preciosa."</p>
                        <footer class="blockquote-footer mt-3">Carlos R.</footer>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-warning mb-3">★★★★☆</div>
                        <p class="card-text">"Calidad premium y atención al cliente excepcional. Volveré a comprar sin duda."</p>
                        <footer class="blockquote-footer mt-3">Ana M.</footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="newsletter py-5 bg-secondary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>¡No Te Pierdas Nada!</h3>
                <p class="mb-0">Recibe ofertas exclusivas y novedades sobre nuevos aromas</p>
            </div>
            <div class="col-md-6">
                <form class="d-flex">
                    <input type="email" class="form-control me-2" placeholder="Tu email" required>
                    <button type="submit" class="btn btn-light">Suscribirme</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
// Cargar productos destacados
$(document).ready(function() {
    cargarProductosDestacados();
    cargarOfertas();
});

function cargarProductosDestacados() {
    $.ajax({
        url: '<?= BASE_URL ?>?c=producto&a=destacados',
        method: 'GET',
        success: function(productos) {
            let html = '';
            if (productos.length > 0) {
                productos.forEach(producto => {
                    html += `
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            ${producto.oferta === 'SI' ? '<span class="badge bg-danger position-absolute top-0 start-0 m-2">OFERTA</span>' : ''}
                            <img src="/images/productos/${producto.imagen}" class="card-img-top" alt="${producto.nombre}" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${producto.nombre}</h5>
                                <p class="card-text flex-grow-1">${producto.descripcion.substring(0, 100)}...</p>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 text-primary mb-0">€${parseFloat(producto.precio).toFixed(2)}</span>
                                        <a href="<?= BASE_URL ?>?c=producto&a=ver&id=${producto.id}" class="btn btn-outline-primary btn-sm">Ver</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
            } else {
                html = '<div class="col-12 text-center"><p>No hay productos destacados</p></div>';
            }
            $('#featured-products-container').html(html);
        }
    });
}

function cargarOfertas() {
    $.ajax({
        url: '<?= BASE_URL ?>?c=producto&a=ofertas',
        method: 'GET',
        success: function(ofertas) {
            let html = '';
            if (ofertas.length > 0) {
                ofertas.forEach(producto => {
                    html += `
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 shadow border-danger">
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">-20%</span>
                            <img src="/images/productos/${producto.imagen}" class="card-img-top" alt="${producto.nombre}" style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title">${producto.nombre}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="h6 text-danger">€${parseFloat(producto.precio).toFixed(2)}</span>
                                        <small class="text-muted text-decoration-line-through d-block">€${(parseFloat(producto.precio) * 1.2).toFixed(2)}</small>
                                    </div>
                                    <a href="<?= BASE_URL ?>?c=producto&a=ver&id=${producto.id}" class="btn btn-danger btn-sm">Comprar</a>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
            } else {
                html = '<div class="col-12 text-center"><p>No hay ofertas disponibles</p></div>';
            }
            $('#offers-container').html(html);
        }
    });
}
</script>

<?php include '../app/views/layouts/footer.php'; ?>