<?php
require_once '../config/config.php';
if ($_SESSION['usuario_tipo'] != 'admin' && $_SESSION['usuario_tipo'] != 'vendedor' && $_SESSION['usuario_tipo'] != 'comprador') {
    header('Location: index.php?error=permisos');
    exit;
}
$titulo = APP_NAME;
?>
<?php include 'template/header.php'; ?>

<!-- Sección Hero -->
<section class="hero-section imagen-hero">
    <div class="container">
        <h1 class="display-4 mb-4">
            <i class="fas fa-cow"></i>
            <?php echo APP_NAME; ?>
        </h1>
        <p class="lead mb-4">La mejor plataforma para comprar y vender ganado de calidad</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="catalogo.php" class="btn btn-light btn-lg">
                <i class="fas fa-search"></i> Explorar Catálogo
            </a>
            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <a href="pRegistro.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user-plus"></i> Regístrate Gratis
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Sección de Funciones -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col">
                <h2>¿Cómo funciona?</h2>
                <p class="text-muted">Conectamos compradores y vendedores de ganado de forma segura y confiable</p>
            </div>
        </div>

        <div class="row">
            <!-- Para Vendedores -->
            <div class="col-lg-6 mb-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-store fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Para Vendedores</h5>
                        <p class="card-text">Publica tu ganado y llega a compradores interesados en todo el país.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success"></i> Publicación gratuita de animales</li>
                            <li><i class="fas fa-check text-success"></i> Control de vacunaciones</li>
                            <li><i class="fas fa-check text-success"></i> Gestión de ubicaciones con mapas</li>
                            <li><i class="fas fa-check text-success"></i> Seguimiento de ventas</li>
                        </ul>
                        <?php if (!isset($_SESSION['usuario_id'])): ?>
                            <a href="pRegistro.php?tipo=vendedor" class="btn btn-success mt-3">Registrarse como Vendedor</a>
                        <?php elseif ($_SESSION['usuario_tipo'] == 'vendedor'): ?>
                            <a href="pGanado.php?accion=agregar" class="btn btn-success mt-3">Publicar Ganado</a>
                        <?php else: ?>
                            <a href="perfil.php" class="btn btn-outline-success mt-3">Cambiar a Cuenta de Vendedor</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Para Compradores -->
            <div class="col-lg-6 mb-4">
                <div class="card feature-card">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-shopping-cart fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Para Compradores</h5>
                        <p class="card-text">Encuentra el ganado perfecto para tu negocio o finca.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-primary"></i> Catálogo con filtros avanzados</li>
                            <li><i class="fas fa-check text-primary"></i> Información completa de cada animal</li>
                            <li><i class="fas fa-check text-primary"></i> Historial de vacunaciones</li>
                            <li><i class="fas fa-check text-primary"></i> Ubicación exacta con mapas</li>
                        </ul>
                        <a href="catalogo.php" class="btn btn-primary mt-3">Explorar Catálogo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>t
</section>

<!-- Sección de Estadísticas -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col">
                <h3>Nuestros Números</h3>
                <p class="text-muted">Comunidad en crecimiento</p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h4 id="total-usuarios">-</h4>
                        <p>Usuarios Registrados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-cow fa-2x mb-2"></i>
                        <h4 id="total-ganado">-</h4>
                        <p>Animales Registrados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <h4 id="total-ventas">-</h4>
                        <p>Ventas Realizadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                        <h4 id="total-ubicaciones">-</h4>
                        <p>Ubicaciones</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Testimonios -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col">
                <h2>Testimonios</h2>
                <p class="text-muted">Lo que dicen nuestros usuarios</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-quote-left fa-2x text-muted"></i>
                        </div>
                        <p class="card-text">"Gracias a esta plataforma pude vender mi ganado a un precio justo y con total transparencia."</p>
                        <div class="mt-3">
                            <strong>Juan Pérez</strong><br>
                            <small class="text-muted">Productor Ganadero</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-quote-left fa-2x text-muted"></i>
                        </div>
                        <p class="card-text">"Encontré exactamente lo que buscaba para mi finca. La información de vacunaciones fue crucial para mi decisión."</p>
                        <div class="mt-3">
                            <strong>María González</strong><br>
                            <small class="text-muted">Compradora</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-quote-left fa-2x text-muted"></i>
                        </div>
                        <p class="card-text">"El sistema de mapas me permitió ubicar ganado cerca de mi región, ahorrando costos de transporte."</p>
                        <div class="mt-3">
                            <strong>Carlos Rodríguez</strong><br>
                            <small class="text-muted">Inversionista</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Cargar estadísticas
    //document.addEventListener('DOMContentLoaded', function() {
        // Simular datos (en un sistema real estos vendrían de una API)
    /*    document.getElementById('total-usuarios').textContent = '1 +';
        document.getElementById('total-ganado').textContent = '2 +';
        document.getElementById('total-ventas').textContent = '0 +';
        document.getElementById('total-ubicaciones').textContent = '1 +';
        document.getElementById('total-compras').textContent = '0 +';

        // Simular carga de datos
        setTimeout(function() {
            document.getElementById('total-usuarios').textContent = '1,250 +';
            document.getElementById('total-ganado').textContent = '3,800 +';
            document.getElementById('total-ventas').textContent = '950 +';
            document.getElementById('total-ubicaciones').textContent = '120 +';
            document.getElementById('total-compras').textContent = '300 +';
        }, 1000);
       
    });*/ 
    // Cargar datos reales desde el servidor
    fetch('obtener_estadisticas.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-usuarios').textContent = data.usuarios + ' 1+';
            document.getElementById('total-ganado').textContent = data.ganado + ' 1+';
            document.getElementById('total-ventas').textContent = data.ventas + ' 1+';
            document.getElementById('total-ubicaciones').textContent = data.ubicaciones + ' 1+';
        })
        .catch(error => console.error('Error cargando estadísticas:', error));
    
</script>

<?php include 'template/footer.php'; ?>
