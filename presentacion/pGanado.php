<?php
require_once '../config/config.php';
require_once '../negocio/nGanado.php';
require_once '../utilidades/upload.php';
require_once '../utilidades/api.php';
echo ApiManager::getGoogleMapsScript(); 

verificarAutenticacion('vendedor');

$nGanado = new nGanado();
$accion = $_GET['accion'] ?? '';
$id = $_GET['id'] ?? 0;
$mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $raza = $_POST['raza'] ?? '';
    $edad = $_POST['edad'] ?? '';
    $peso = $_POST['peso'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $latitud = $_POST['latitud'] ?? '';
    $longitud = $_POST['longitud'] ?? '';
    
    // Subir imagen
    $imagen = '';
    if (!empty($_FILES['imagen']['name'])) {
       $upload = subirImagen($_FILES['imagen']);
        if ($upload['exito']) {
            $imagen = $upload['nombre_archivo'];
        } else {
            $mensaje = $upload['mensaje'];
        }
    } elseif (!empty($_POST['imagen_actual'])) {
        $imagen = $_POST['imagen_actual'];
    }
    
    if ($accion == 'agregar') {
        $resultado = $nGanado->registrarGanado(
            $_SESSION['usuario_id'], 
            $nombre, $descripcion, $raza, $edad, $peso, $precio, 
            $imagen, $ubicacion, $latitud, $longitud
        );
        
        if ($resultado['exito']) {
            header('Location: listar_ganado.php?exito=' . urlencode($resultado['mensaje']));
            exit;
        } else {
            $mensaje = $resultado['mensaje'];
        }
    } elseif ($accion == 'editar') {
        $resultado = $nGanado->actualizarGanado(
            $id, $_SESSION['usuario_id'], 
            $nombre, $descripcion, $raza, $edad, $peso, $precio, 
            $imagen, $ubicacion, $latitud, $longitud
        );
        
        if ($resultado['exito']) {
            header('Location: listar_ganado.php?exito=' . urlencode($resultado['mensaje']));
            exit;
        } else {
            $mensaje = $resultado['mensaje'];
        }
    }
}

// Obtener datos para edición
$ganado = null;
if ($accion == 'editar' && $id > 0) {
    $ganado = $nGanado->obtenerPorId($id);
    
    // Verificar que el ganado pertenece al usuario
    if (!$ganado || $ganado['usuario_id'] != $_SESSION['usuario_id']) {
        header('Location: listar_ganado.php?error=No tienes permisos para editar este animal');
        exit;
    }
}

$razas = $nGanado->obtenerRazas();
$titulo = $accion == 'agregar' ? 'Publicar Ganado' : 'Editar Ganado';
?>
<?php include 'template/header.php'; ?>

<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $titulo; ?></h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <?php if ($accion == 'editar'): ?>
                            <input type="hidden" name="imagen_actual" value="<?php echo $ganado['imagen']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Animal *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo $ganado['nombre'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $ganado['descripcion'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="raza" class="form-label">Raza *</label>
                                <select class="form-select" id="raza" name="raza" required>
                                    <option value="">Seleccione una raza</option>
                                    <?php foreach ($razas as $razaOption): ?>
                                        <option value="<?php echo $razaOption; ?>" 
                                            <?php echo (isset($ganado['raza']) && $ganado['raza'] == $razaOption) ? 'selected' : ''; ?>>
                                            <?php echo $razaOption; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edad" class="form-label">Edad (años) *</label>
                                <input type="number" class="form-control" id="edad" name="edad" 
                                       value="<?php echo $ganado['edad'] ?? ''; ?>" min="0" step="0.1" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="peso" class="form-label">Peso (kg) *</label>
                                <input type="number" class="form-control" id="peso" name="peso" 
                                       value="<?php echo $ganado['peso'] ?? ''; ?>" min="0" step="0.1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="precio" class="form-label">Precio ($) *</label>
                                <input type="number" class="form-control" id="precio" name="precio" 
                                       value="<?php echo $ganado['precio'] ?? ''; ?>" min="0" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen del Animal *</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" 
                                   <?php echo ($accion == 'agregar') ? 'required' : ''; ?>>
                            <?php if ($accion == 'editar' && !empty($ganado['imagen'])): ?>
                                <div class="mt-2">
                                    <img src="../uploads/<?php echo $ganado['imagen']; ?>" alt="Imagen actual" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="ubicacion" class="form-label">Ubicación de la Hacienda/Finca *</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" 
                                   value="<?php echo $ganado['ubicacion'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitud" class="form-label">Latitud *</label>
                                <input type="text" class="form-control" id="latitud" name="latitud" 
                                       value="<?php echo $ganado['latitud'] ?? '14.634915'; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitud" class="form-label">Longitud *</label>
                                <input type="text" class="form-control" id="longitud" name="longitud" 
                                       value="<?php echo $ganado['longitud'] ?? '-90.506882'; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div id="map" style="height: 300px; border-radius: 8px;"></div>
                            <small class="text-muted">Arrastre el marcador para ajustar la ubicación exacta</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <?php echo $accion == 'agregar' ? 'Publicar Ganado' : 'Actualizar'; ?>
                            </button>
                            <a href="listar_ganado.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap"></script>
<script>
    let map;
    let marker;
    
    function initMap() {
        const defaultLocation = { 
            lat: parseFloat(document.getElementById('latitud').value), 
            lng: parseFloat(document.getElementById('longitud').value) 
        };
        
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: defaultLocation,
            mapTypeId: google.maps.MapTypeId.HYBRID
        });
        
        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true
        });
        
        // Actualizar campos de latitud y longitud cuando se mueve el marcador
        google.maps.event.addListener(marker, 'dragend', function() {
            document.getElementById('latitud').value = marker.getPosition().lat();
            document.getElementById('longitud').value = marker.getPosition().lng();
        });
        
        // Permitir hacer clic en el mapa para mover el marcador
        google.maps.event.addListener(map, 'click', function(event) {
            marker.setPosition(event.latLng);
            document.getElementById('latitud').value = event.latLng.lat();
            document.getElementById('longitud').value = event.latLng.lng();
        });
        initMap();
    }
    
    // Geocodificación inversa para obtener coordenadas desde la dirección
    document.getElementById('ubicacion').addEventListener('blur', function() {
        const address = this.value;
        if (address) {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: address }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    const location = results[0].geometry.location;
                    document.getElementById('latitud').value = location.lat();
                    document.getElementById('longitud').value = location.lng();
                    
                    if (marker) {
                        marker.setPosition(location);
                        map.setCenter(location);
                    }
                }
            });
        }
    });
</script>

<?php include 'template/footer.php'; ?>