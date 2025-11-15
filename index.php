<?php
require_once 'config/config.php';
verificarAutenticacion();
$titulo = APP_NAME;
$estadisticas = obtenerEstadisticasGenerales();

?>
<?php include 'presentacion/template/header.php'; ?>

<!-- como ver que usuario ha iniciado sesion en la pagina y su nombre -->
 
<!-- Sección Hero -->
<section class="hero-section imagen-hero ">
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
    </div>
</section>

<!-- Sección de Estadísticas -->
<section class="m-5 py-5 bg-gradient rounded ">
    <div class="container-fluid">
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
                        <h4 id="total-usuarios">
                            <?php echo $estadisticas['total_usuarios'] ?? 0; ?>
                        </h4>
                        <p>Usuarios Registrados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-cow fa-2x mb-2"></i>
                        <h4 id="total-ganado">
                            <?php echo $estadisticas['total_ganado'] ?? 0; ?>
                        </h4>
                        <p>Animales Registrados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <h4 id="total-ventas">
                            <?php echo $estadisticas['total_ventas'] ?? 0; ?>
                        </h4>
                        <p>Ventas Realizadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                        <h4 id="total-ubicaciones">
                            <?php echo $estadisticas['total_ubicaciones'] ?? 0; ?>
                        </h4>
                        <p>Ubicaciones</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Testimonios -->
<section class="py-5">
    <div class="container bg-gradient rounded">
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
<?php include 'template/footer.php'; ?>
