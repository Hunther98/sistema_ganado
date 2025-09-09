    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?php echo APP_NAME; ?></h5>
                    <p>Plataforma especializada en la compra y venta de ganado de calidad</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 Sistema de Venta de Ganado. Versión <?php echo APP_VERSION; ?></p>
                    <p>Desarrollado con arquitectura de 3 capas</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Funciones generales
        function mostrarAlerta(mensaje, tipo = 'info') {
            const alerta = document.createElement('div');
            alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
            alerta.innerHTML = `
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.prepend(alerta);
            
            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                alerta.remove();
            }, 5000);
        }
        
        // Inicializar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>