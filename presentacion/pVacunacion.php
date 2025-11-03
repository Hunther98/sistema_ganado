<?php
require_once '../config/config.php';
require_once '../negocio/nVacunacion.php';
require_once '../negocio/nGanado.php';

verificarAutenticacion('vendedor');

$nVacunacion = new nVacunacion();
$nGanado = new nGanado();

$ganado_id = $_GET['ganado_id'] ?? 0;
$accion = $_GET['accion'] ?? '';
$id = $_GET['id'] ?? 0;
$mensaje = '';

// Verificar que el ganado pertenece al usuario
    if ($ganado_id > 0) {
    $ganado = $nGanado->obtenerPorId($ganado_id);
    if (!$ganado || $ganado['usuario_id'] != $_SESSION['usuario_id']) {
        header('Location: admin/listar_ganado.php?error=No tienes permisos para gestionar este animal');
        exit;
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vacuna = $_POST['vacuna'] ?? '';
    $fecha_vacunacion = $_POST['fecha_vacunacion'] ?? '';
    $proxima_vacunacion = $_POST['proxima_vacunacion'] ?? '';
    $observaciones = $_POST['observaciones'] ?? '';
    $ganado_id = $_POST['ganado_id'] ?? 0;
    
    if ($accion == 'agregar') {
        $resultado = $nVacunacion->registrarVacunacion(
            $ganado_id, $vacuna, $fecha_vacunacion, $proxima_vacunacion, $observaciones
        );
        
        if ($resultado['exito']) {
            header('Location: detalle_ganado.php?id=' . $ganado_id . '&exito=' . urlencode($resultado['mensaje']));
            exit;
        } else {
            $mensaje = $resultado['mensaje'];
        }
    } elseif ($accion == 'editar') {
        $resultado = $nVacunacion->actualizarVacunacion(
            $id, $vacuna, $fecha_vacunacion, $proxima_vacunacion, $observaciones
        );
        
        if ($resultado['exito']) {
            header('Location: detalle_ganado.php?id=' . $ganado_id . '&exito=' . urlencode($resultado['mensaje']));
            exit;
        } else {
            $mensaje = $resultado['mensaje'];
        }
    }
}

// Obtener datos para edición
$vacunacion = null;
if ($accion == 'editar' && $id > 0) {
    $vacunacion = $nVacunacion->obtenerVacunacionPorId($id);
    
    if (!$vacunacion) {
        header('Location: detalle_ganado.php?id=' . $ganado_id . '&error=Registro de vacunación no encontrado');
        exit;
    }
    
    $ganado_id = $vacunacion['ganado_id'];
}

$vacunas = $nVacunacion->obtenerVacunasComunes();
$ganado = $nGanado->obtenerPorId($ganado_id);

$titulo = $accion == 'agregar' ? 'Registrar Vacunación' : 'Editar Vacunación';
?>
<?php include 'template/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php echo $titulo; ?>
                        <small class="text-muted">- <?php echo $ganado['nombre']; ?></small>
                    </h3>
                </div>
                <div class="card-body srollable-card">
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="ganado_id" value="<?php echo $ganado_id; ?>">
                        
                        <div class="mb-3">
                            <label for="vacuna" class="form-label">Vacuna *</label>
                            <select class="form-select" id="vacuna" name="vacuna" required>
                                <option value="">Seleccione una vacuna</option>
                                <?php foreach ($vacunas as $vacunaOption): ?>
                                    <option value="<?php echo $vacunaOption; ?>" 
                                        <?php echo (isset($vacunacion['vacuna']) && $vacunacion['vacuna'] == $vacunaOption) ? 'selected' : ''; ?>>
                                        <?php echo $vacunaOption; ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="Otra">Otra (especificar en observaciones)</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_vacunacion" class="form-label">Fecha de Vacunación *</label>
                                <input type="date" class="form-control" id="fecha_vacunacion" name="fecha_vacunacion" 
                                       value="<?php echo $vacunacion['fecha_vacunacion'] ?? date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="proxima_vacunacion" class="form-label">Próxima Vacunación</label>
                                <input type="date" class="form-control" id="proxima_vacunacion" name="proxima_vacunacion" 
                                       value="<?php echo $vacunacion['proxima_vacunacion'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo $vacunacion['observaciones'] ?? ''; ?></textarea>
                            <small class="text-muted">Puede incluir lote de vacuna, dosis, veterinario, etc.</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <?php echo $accion == 'agregar' ? 'Registrar Vacunación' : 'Actualizar'; ?>
                            </button>
                            <a href="detalle_ganado.php?id=<?php echo $ganado_id; ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calcular próxima vacunación (6 meses por defecto)
        document.getElementById('fecha_vacunacion').addEventListener('change', function() {
            const fechaVacunacion = new Date(this.value);
            if (!isNaN(fechaVacunacion.getTime())) {
                const proximaVacunacion = new Date(fechaVacunacion);
                proximaVacunacion.setMonth(proximaVacunacion.getMonth() + 6);
                
                const proximaInput = document.getElementById('proxima_vacunacion');
                if (!proximaInput.value) {
                    proximaInput.value = proximaVacunacion.toISOString().split('T')[0];
                }
            }
        });
    });
</script>

<?php include 'template/footer.php'; ?>