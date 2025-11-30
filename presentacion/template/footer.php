    <!-- Footer (ahora integrado dentro de <main>) -->

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container-fluid">
            <!-- Contenido Principal Footer -->
            <div class="row py-4">
                <!-- Información de la Empresa -->
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-3">
                        <i class="fas fa-cow text-primary"></i> <?php echo APP_NAME; ?>
                    </h5>
                    <p class="text-muted">
                        Plataforma especializada en la compra y venta de ganado de calidad, 
                        conectando vendedores y compradores en toda la región.
                    </p>
                    <!-- Redes Sociales -->
                    <div class="mt-3">
                        <a href="#" class="text-muted me-3" title="Facebook"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-muted me-3" title="Twitter"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-muted me-3" title="Instagram"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-muted" title="WhatsApp"><i class="fab fa-whatsapp fa-lg"></i></a>
                    </div>
                </div>
                
                <!-- Enlaces Rápidos -->
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h6 class="mb-3">Enlaces Rápidos</h6>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-muted text-decoration-none">Inicio</a></li>
                        <li><a href="catalogo.php" class="text-muted text-decoration-none">Catálogo</a></li>
                        <li><a href="contacto.php" class="text-muted text-decoration-none">Contacto</a></li>
                        <li><a href="perfil.php" class="text-muted text-decoration-none">Mi Perfil</a></li>
                    </ul>
                </div>
                
                <!-- Información Legal -->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h6 class="mb-3">Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Términos de Servicio</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Política de Privacidad</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Política de Cookies</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Aviso Legal</a></li>
                    </ul>
                </div>
                
                <!-- Contacto -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="mb-3">Contáctanos</h6>
                    <p class="text-muted mb-2">
                        <i class="fas fa-map-marker-alt"></i> Venezuela
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope"></i> 
                        <a href="mailto:info@sistemaganado.com" class="text-muted text-decoration-none">
                            info@sistemaganado.com
                        </a>
                    </p>
                    <p class="text-muted">
                        <i class="fas fa-clock"></i> Lun - Vie: 8:00 AM - 6:00 PM
                    </p>
                </div>
            </div>
            
            <!-- Divisor -->
            <hr class="bg-light opacity-25 my-3">
            
            <!-- Copyright -->
            <div class="row py-3">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        &copy; 2025 <?php echo APP_NAME; ?>. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        Versión <?php echo APP_VERSION; ?> | 
                        Desarrollado con <i class="fas fa-heart text-danger"></i>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts Personalizados -->
    <script>
        // ====================================================================
        // Gestión del Sidebar
        // ====================================================================
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
            const dropdownToggles = document.querySelectorAll('.sidebar-nav .dropdown-toggle');

            // Toggle Sidebar
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
            }

            // Cerrar sidebar al hacer clic en overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            // Cerrar sidebar al hacer clic en un enlace (móvil)
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 991) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    }
                });
            });

            // Dropdown Menu Handler
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const submenu = document.querySelector(this.getAttribute('href'));
                    if (submenu) {
                        submenu.classList.toggle('show');
                        // Cerrar otros submenús
                        document.querySelectorAll('.nav-submenu.show').forEach(menu => {
                            if (menu !== submenu) {
                                menu.classList.remove('show');
                            }
                        });
                    }
                });
            });

            // Marcar enlace activo
            const currentPage = window.location.pathname.split('/').pop();
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPage || href.includes(currentPage)) {
                    link.classList.add('active');
                }
            });
        });

        // ====================================================================
        // Función para mostrar alertas
        // ====================================================================
        function mostrarAlerta(mensaje, tipo = 'info') {
            const tiposMap = {
                'exito': 'success',
                'advertencia': 'warning',
                'error': 'danger',
                'info': 'info'
            };
            
            const tipoBootstrap = tiposMap[tipo] || 'info';
            const iconos = {
                'success': 'fa-check-circle',
                'warning': 'fa-exclamation-circle',
                'danger': 'fa-times-circle',
                'info': 'fa-info-circle'
            };
            
            const icono = iconos[tipoBootstrap];
            
            const alerta = document.createElement('div');
            alerta.className = `alert alert-${tipoBootstrap} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
            alerta.style.zIndex = '9999';
            alerta.innerHTML = `
                <i class="fas ${icono}"></i> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alerta);
            
            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                alerta.remove();
            }, 5000);
        }
        
        // ====================================================================
        // Inicializar componentes de Bootstrap
        // ====================================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
        
        // ====================================================================
        // Accesibilidad - Mejorar navegación con teclado
        // ====================================================================
        document.addEventListener('keydown', function(event) {
            // ESC para cerrar modales y sidebar
            if (event.key === 'Escape') {
                const modal = document.querySelector('.modal.show');
                if (modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                }
                
                // Cerrar sidebar
                const sidebar = document.getElementById('sidebar');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                if (sidebar && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>