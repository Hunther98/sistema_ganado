<?php
/**
 * Página de Contacto
 * Formulario para que usuarios se comuniquen con la plataforma
 */

require_once __DIR__ . '/../config/config.php';
$titulo = 'Contacto - ' . APP_NAME;
?>

<?php require_once __DIR__ . '/template/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Encabezado -->
            <div class="text-center mb-5">
                <h1 class="mb-3">
                    <i class="fas fa-envelope"></i> Contáctanos
                </h1>
                <p class="lead text-muted">
                    ¿Tienes preguntas o sugerencias? Nos encantaría saber de ti.
                </p>
            </div>

            <!-- Alertas -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Error:</strong>
                    <?php
                    $errores = [
                        'campos_requeridos' => 'Por favor completa todos los campos requeridos.',
                        'email_invalido' => 'Por favor ingresa un email válido.',
                        'mensaje_corto' => 'El mensaje debe tener al menos 10 caracteres.',
                        'formulario_invalido' => 'El formulario contiene datos inválidos.',
                        'error_servidor' => 'Hubo un error al procesar tu solicitud. Por favor intenta más tarde.',
                    ];
                    echo $errores[$_GET['error']] ?? 'Ha ocurrido un error.';
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['exito'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <strong>¡Éxito!</strong>
                    Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto pronto.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Formulario de Contacto -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <form method="POST" action="enviarContacto.php" id="formularioContacto" novalidate>
                        
                        <!-- Token CSRF -->
                        <?php require_once __DIR__ . '/../config/seguridad.php'; ?>
                        <?php echo generarCampoCSRF(); ?>
                        
                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label fw-bold">
                                <i class="fas fa-user"></i> Nombre Completo <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg" 
                                id="nombre" 
                                name="nombre" 
                                placeholder="Tu nombre completo"
                                required
                                minlength="3"
                                maxlength="100"
                                value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                            >
                            <small class="form-text text-muted">Mínimo 3 caracteres</small>
                            <div class="invalid-feedback">
                                Por favor ingresa un nombre válido.
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope"></i> Correo Electrónico <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="email" 
                                class="form-control form-control-lg" 
                                id="email" 
                                name="email" 
                                placeholder="tu@email.com"
                                required
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            >
                            <small class="form-text text-muted">Usaremos esto para responder tu mensaje</small>
                            <div class="invalid-feedback">
                                Por favor ingresa un email válido.
                            </div>
                        </div>

                        <!-- Teléfono (Opcional) -->
                        <div class="mb-4">
                            <label for="telefono" class="form-label fw-bold">
                                <i class="fas fa-phone"></i> Teléfono (Opcional)
                            </label>
                            <input 
                                type="tel" 
                                class="form-control form-control-lg" 
                                id="telefono" 
                                name="telefono" 
                                placeholder="+58 (0414) 123-4567"
                                pattern="[0-9\s\-\+\(\)]+"
                                maxlength="20"
                                value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>"
                            >
                            <small class="form-text text-muted">Incluye tu código de país si lo deseas</small>
                        </div>

                        <!-- Asunto -->
                        <div class="mb-4">
                            <label for="asunto" class="form-label fw-bold">
                                <i class="fas fa-tag"></i> Asunto <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg" id="asunto" name="asunto" required>
                                <option value="">-- Selecciona un asunto --</option>
                                <option value="consulta">Consulta General</option>
                                <option value="problema">Reporte de Problema</option>
                                <option value="sugerencia">Sugerencia o Mejora</option>
                                <option value="soporte">Solicitud de Soporte</option>
                                <option value="otro">Otro</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor selecciona un asunto.
                            </div>
                        </div>

                        <!-- Mensaje -->
                        <div class="mb-4">
                            <label for="mensaje" class="form-label fw-bold">
                                <i class="fas fa-comment"></i> Mensaje <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                class="form-control form-control-lg" 
                                id="mensaje" 
                                name="mensaje" 
                                rows="6" 
                                placeholder="Cuéntanos más detalles..."
                                required
                                minlength="10"
                                maxlength="2000"
                            ><?php echo htmlspecialchars($_POST['mensaje'] ?? ''); ?></textarea>
                            <small class="form-text text-muted">
                                Mínimo 10 caracteres, máximo 2000
                                <span class="float-end">
                                    <span id="charCount">0</span>/2000
                                </span>
                            </small>
                            <div class="invalid-feedback">
                                Por favor ingresa un mensaje válido (10-2000 caracteres).
                            </div>
                        </div>

                        <!-- Checkbox de Privacidad -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input 
                                    type="checkbox" 
                                    class="form-check-input" 
                                    id="privacidad" 
                                    name="privacidad" 
                                    required
                                >
                                <label class="form-check-label" for="privacidad">
                                    Acepto la <a href="<?php echo APP_URL; ?>/presentacion/politica_privacidad.php" target="_blank">política de privacidad</a>
                                </label>
                                <div class="invalid-feedback">
                                    Debes aceptar la política de privacidad.
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row gap-2">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane"></i> Enviar Mensaje
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="index.php" class="btn btn-secondary btn-lg w-100">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información de Contacto Adicional -->
            <div class="row mt-5 gap-3">
                <div class="col-md-4">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="display-6 text-primary mb-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h5 class="card-title">Ubicación</h5>
                            <p class="card-text text-muted">Venezuela</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="display-6 text-success mb-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h5 class="card-title">Email</h5>
                            <p class="card-text text-muted">
                                <a href="mailto:info@sistemaganado.com" class="text-decoration-none">
                                    info@sistemaganado.com
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="display-6 text-warning mb-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5 class="card-title">Disponibilidad</h5>
                            <p class="card-text text-muted">Lunes a Viernes<br>8:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts de Validación -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contador de caracteres del mensaje
    const textarea = document.getElementById('mensaje');
    const charCount = document.getElementById('charCount');
    
    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    // Validación del formulario
    const formulario = document.getElementById('formularioContacto');
    
    formulario.addEventListener('submit', function(e) {
        if (!formulario.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        formulario.classList.add('was-validated');
    });
});
</script>

<!-- Estilos adicionales -->
<style>
.form-control:focus,
.form-select:focus,
.form-check-input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
}

.form-label {
    color: #333;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

textarea.form-control {
    resize: vertical;
    min-height: 150px;
}

.text-danger {
    color: #dc3545;
}
</style>

<?php require_once __DIR__ . '/template/footer.php'; ?>
