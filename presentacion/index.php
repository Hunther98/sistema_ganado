<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/seguridad.php';
require_once __DIR__ . '/../config/redirecciones.php';

$titulo = 'Inicio - ' . APP_NAME;
$estadisticas = obtenerEstadisticasGenerales();

?>
<?php include 'template/header.php'; ?>

<!-- Banner de Bienvenida (Solo para usuarios autenticados) -->
<?php if (estaAutenticado()): ?>
    <div class="">
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
            <div class="row align-items-center">
                <div class="col">
                    <i class="fas fa-smile-wink fa-lg me-2"></i>
                    <strong>¡Bienvenido, <?php echo htmlspecialchars(obtenerUsuarioNombre()); ?>!</strong>
                    <br>
                    <small class="text-muted">Acceso como: <span class="badge bg-primary"><?php echo obtenerDescripcionRol(obtenerRolActual()); ?></span></small>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<!-- Sección Hero Mejorada -->
<section class="hero-section">
    <div class="container-fluid">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6 px-5 order-lg-1 order-2">
                <div class="fade-in">
                    <h1 class="display-3 fw-bold mb-4">
                        <i class=" me-3 text-primary"></i>
                        <?php echo APP_NAME; ?>
                    </h1>
                    <p class="lead fs-5 mb-4 text-muted" style="max-width: 500px;">
                         La plataforma número uno para comprar y vender ganado de calidad en toda la región
                    </p>
                    
                    <!-- CTA Principal -->
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="catalogo.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i> Explorar Catálogo
                        </a>
                        <?php if (!estaAutenticado()): ?>
                            <a href="pRegistro.php" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i> Regístrate Gratis
                            </a>
                        <?php elseif (tieneRol(ROLE_VENDEDOR)): ?>
                            <a href="pGanado.php?accion=agregar" class="btn btn-success btn-lg">
                                <i class="fas fa-plus me-2"></i> Publicar Ganado
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Características Destacadas -->
                    <div class="row g-4">
                        <div class="col-sm-auto">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt text-success fa-2x me-3"></i>
                                <div>
                                    <div class="fw-bold">100% Seguro</div>
                                    <small class="text-muted">Transacciones protegidas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users text-primary fa-2x me-3"></i>
                                <div>
                                    <div class="fw-bold"><?php echo number_format($estadisticas['total_usuarios'] ?? 0); ?>+</div>
                                    <small class="text-muted">Usuarios activos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-globe text-warning fa-2x me-3"></i>
                                <div>
                                    <div class="fw-bold"><?php echo number_format($estadisticas['total_ubicaciones'] ?? 0); ?> Regiones</div>
                                    <small class="text-muted">Cobertura nacional</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Imagen Hero (derecha) -->
            <div class="col-lg-6 order-lg-2 order-1 mb-5 mb-lg-0">
                <div class="position-relative slide-in">
                    <div class="bg-gradient rounded-lg overflow-hidden shadow-lg" style="height: 400px; display: flex; align-items: center; justify-content: center;">
                        <div class="text-center">
                            <i class=" fa-cow display-1 text-primary opacity-75" style="font-size: 10rem;"></i>
                            <p class="text-muted mt-3">Ganado de calidad para tu negocio</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección: Cómo Funciona -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">¿Cómo Funciona?</h2>
            <p class="lead text-muted">Conectamos compradores y vendedores de ganado de forma segura y confiable</p>
        </div>

        <div class="row g-4">
            <!-- Opción Para Vendedores -->
            <div class="col-lg-6">
                <div class="card feature-card h-100 border-0">
                    <div class="card-header bg-gradient">
                        <i class="fas fa-store me-2"></i>
                        <strong>Para Vendedores</strong>
                    </div>
                    <div class="card-body p-4">
                        <p class="card-text mb-4">Publica tu ganado y llega a compradores interesados en todo el país.</p>
                        
                        <div class="feature-list mb-4">
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Publicación gratuita de animales</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Control de vacunaciones</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Gestión de ubicaciones con mapas</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Seguimiento de ventas en tiempo real</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Historial completo de transacciones</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <?php if (!estaAutenticado()): ?>
                                <a href="pRegistro.php?tipo=vendedor" class="btn btn-success btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i> Registrarse como Vendedor
                                </a>
                            <?php elseif (tieneRol(ROLE_VENDEDOR)): ?>
                                <a href="pGanado.php?accion=agregar" class="btn btn-success btn-lg">
                                    <i class="fas fa-plus me-2"></i> Publicar Mi Ganado
                                </a>
                                <a href="mis_ventas.php" class="btn btn-outline-success">
                                    <i class="fas fa-chart-line me-2"></i> Ver Mis Ventas
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline-success" disabled>
                                    <i class="fas fa-lock me-2"></i> Solo para Vendedores
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opción Para Compradores -->
            <div class="col-lg-6">
                <div class="card feature-card h-100 border-0">
                    <div class="card-header bg-gradient">
                        <i class="fas fa-shopping-cart me-2"></i>
                        <strong>Para Compradores</strong>
                    </div>
                    <div class="card-body p-4">
                        <p class="card-text mb-4">Encuentra el ganado perfecto para tu negocio o finca.</p>
                        
                        <div class="feature-list mb-4">
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <span>Catálogo con filtros avanzados</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <span>Información completa de cada animal</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <span>Historial de vacunaciones verificado</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <span>Ubicación exacta con mapas interactivos</span>
                            </div>
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-primary me-2"></i>
                                <span>Contacto directo con vendedores</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="catalogo.php" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i> Explorar Catálogo Completo
                            </a>
                            <?php if (estaAutenticado() && tieneRol(ROLE_COMPRADOR)): ?>
                                <a href="mis_compras.php" class="btn btn-outline-primary">
                                    <i class="fas fa-shopping-bag me-2"></i> Ver Mis Compras
                                </a>
                            <?php elseif (!estaAutenticado()): ?>
                                <a href="pRegistro.php?tipo=comprador" class="btn btn-outline-primary">
                                    <i class="fas fa-user-plus me-2"></i> Registrarse como Comprador
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Estadísticas -->
<section class="py-5 position-relative" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
    <div class="position-absolute top-0 end-0 opacity-10" style="width: 300px; height: 300px; border-radius: 50%; background: var(--white); margin-top: -150px; margin-right: -150px;"></div>
    <div class="position-absolute bottom-0 start-0 opacity-10" style="width: 250px; height: 250px; border-radius: 50%; background: var(--white); margin-bottom: -125px; margin-left: -125px;"></div>
    
    <div class="container position-relative z-1">
        <div class="text-center mb-5">
            <h2 class="text-white mb-3">Cifras que Hablan</h2>
            <p class="text-white-50">Crece con nosotros en la comercialización de ganado</p>
        </div>

        <div class="row g-4">
            <!-- Usuarios Activos -->
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card border-0 overflow-hidden h-100 fade-in" style="animation-delay: 0.1s;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <h3 class="card-title display-4 fw-bold text-primary mb-2">
                            <?php echo number_format($estadisticas['total_usuarios'] ?? 0); ?>
                        </h3>
                        <p class="card-text text-muted mb-3">Usuarios Activos</p>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 75%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ganado en Catálogo -->
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card border-0 overflow-hidden h-100 fade-in" style="animation-delay: 0.2s;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-cow fa-2x text-success"></i>
                        </div>
                        <h3 class="card-title display-4 fw-bold text-success mb-2">
                            <?php echo number_format($estadisticas['total_ganado'] ?? 0); ?>
                        </h3>
                        <p class="card-text text-muted mb-3">Animales en Catálogo</p>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: 85%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transacciones -->
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card border-0 overflow-hidden h-100 fade-in" style="animation-delay: 0.3s;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-exchange-alt fa-2x text-info"></i>
                        </div>
                        <h3 class="card-title display-4 fw-bold text-info mb-2">
                            <?php echo number_format($estadisticas['total_ventas'] ?? 0); ?>
                        </h3>
                        <p class="card-text text-muted mb-3">Transacciones Realizadas</p>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-info" style="width: 90%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ubicaciones -->
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card border-0 overflow-hidden h-100 fade-in" style="animation-delay: 0.4s;">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt fa-2x text-warning"></i>
                        </div>
                        <h3 class="card-title display-4 fw-bold text-warning mb-2">
                            <?php echo number_format($estadisticas['total_ubicaciones'] ?? 0); ?>
                        </h3>
                        <p class="card-text text-muted mb-3">Ubicaciones Cubiertas</p>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-warning" style="width: 95%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Llamada a la Acción -->
        <div class="text-center mt-5 pt-3">
            <p class="text-white-50 mb-4">¿Listo para formar parte de nuestra comunidad?</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <?php if (!estaAutenticado()): ?>
                    <a href="pRegistro.php" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i> Registrarse Ahora
                    </a>
                    <a href="catalogo.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i> Explorar Catálogo
                    </a>
                <?php else: ?>
                    <a href="catalogo.php" class="btn btn-light btn-lg">
                        <i class="fas fa-search me-2"></i> Ver Catálogo
                    </a>
                    <a href="contacto.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i> Contactar Soporte
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Testimonios -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Testimonios de Usuarios</h2>
            <p class="lead text-muted">Lo que dicen nuestros clientes satisfechos</p>
        </div>

        <div class="row g-4">
            <!-- Testimonio 1: Vendedor -->
            <div class="col-md-4">
                <div class="card testimonial-card h-100 border-0 shadow-sm fade-in" style="animation-delay: 0.1s;">
                    <div class="card-body p-4">
                        <!-- Rating -->
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>

                        <!-- Testimonio -->
                        <p class="card-text fst-italic mb-4">
                            "Gracias a esta plataforma pude vender mi ganado a un precio justo y con total transparencia. El proceso fue muy sencillo y los compradores eran serios."
                        </p>

                        <!-- Usuario -->
                        <div class="d-flex align-items-center pt-3 border-top">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <strong class="d-block">Juan Pérez</strong>
                                <small class="text-muted">Productor Ganadero</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonio 2: Compradora -->
            <div class="col-md-4">
                <div class="card testimonial-card h-100 border-0 shadow-sm fade-in" style="animation-delay: 0.2s;">
                    <div class="card-body p-4">
                        <!-- Rating -->
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>

                        <!-- Testimonio -->
                        <p class="card-text fst-italic mb-4">
                            "Encontré exactamente lo que buscaba para mi finca. La información detallada de vacunaciones y el historial de cada animal fue crucial para mi decisión de compra."
                        </p>

                        <!-- Usuario -->
                        <div class="d-flex align-items-center pt-3 border-top">
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <strong class="d-block">María González</strong>
                                <small class="text-muted">Compradora</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testimonio 3: Inversionista -->
            <div class="col-md-4">
                <div class="card testimonial-card h-100 border-0 shadow-sm fade-in" style="animation-delay: 0.3s;">
                    <div class="card-body p-4">
                        <!-- Rating -->
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>

                        <!-- Testimonio -->
                        <p class="card-text fst-italic mb-4">
                            "El sistema de geolocalización me permitió ubicar ganado de calidad cerca de mi región, ahorrando significativamente en costos de transporte. Muy recomendado."
                        </p>

                        <!-- Usuario -->
                        <div class="d-flex align-items-center pt-3 border-top">
                            <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <strong class="d-block">Carlos Rodríguez</strong>
                                <small class="text-muted">Inversionista</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Final -->
        <div class="text-center mt-5 pt-4">
            <p class="mb-4">¿Quieres ser parte de nuestro éxito?</p>
            <a href="contacto.php" class="btn btn-primary btn-lg">
                <i class="fas fa-comment-dots me-2"></i> Contactar Soporte
            </a>
        </div>
    </div>
</section>
<script>
    // Animaciones e interactividad mejorada
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar animación fade-in a elementos visibles
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        // Observar todas las tarjetas
        document.querySelectorAll('.card, .stat-card, .feature-card').forEach(el => {
            observer.observe(el);
        });

        // Cerrar alertas automáticamente después de 5 segundos
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Efectos hover en botones
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseover', function() {
                this.style.transform = 'translateY(-2px)';
            });
            btn.addEventListener('mouseout', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });

    // Smooth scroll para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
<?php include 'template/footer.php'; ?>
