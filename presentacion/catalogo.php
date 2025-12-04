<?php
require_once '../config/config.php';
require_once '../negocio/nGanado.php';
require_once '../utilidades/api.php'; // ← AÑADIR ESTA LÍNEA
include 'template/header.php'; 

$nGanado = new nGanado();
$filtros = [];

// Aplicar filtros si se enviaron
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['raza'])) $filtros['raza'] = $_GET['raza'];
    if (!empty($_GET['edad_min'])) $filtros['edad_min'] = intval($_GET['edad_min']);
    if (!empty($_GET['edad_max'])) $filtros['edad_max'] = intval($_GET['edad_max']);
    if (!empty($_GET['precio_min'])) $filtros['precio_min'] = floatval($_GET['precio_min']);
    if (!empty($_GET['precio_max'])) $filtros['precio_max'] = floatval($_GET['precio_max']);
}

$ganado = $nGanado->obtenerTodos($filtros);
$razas = $nGanado->obtenerRazas();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Ganado - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .map-container {
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    
    
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold">Catálogo de Ganado</h1>
                <p class="lead">Encuentra el mejor ganado para tu finca o negocio</p>
            </div>
            <div class="col-md-4 text-end">
                <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_tipo'] == 'vendedor'): ?>
                    <a href="pGanado.php?accion=agregar" class="btn btn-success">
                        <i class="fas fa-plus"></i> Publicar Ganado
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros de Búsqueda</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="raza" class="form-label">Raza</label>
                            <select class="form-select" id="raza" name="raza">
                                <option value="">Todas las razas</option>
                                <?php foreach ($razas as $raza): ?>
                                    <option value="<?php echo $raza; ?>" <?php echo (!empty($_GET['raza']) && $_GET['raza'] == $raza) ? 'selected' : ''; ?>>
                                        <?php echo $raza; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="edad_min" class="form-label">Edad Mínima</label>
                            <input type="number" class="form-control" id="edad_min" name="edad_min" min="0" 
                                   value="<?php echo !empty($_GET['edad_min']) ? $_GET['edad_min'] : ''; ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="edad_max" class="form-label">Edad Máxima</label>
                            <input type="number" class="form-control" id="edad_max" name="edad_max" min="0" 
                                   value="<?php echo !empty($_GET['edad_max']) ? $_GET['edad_max'] : ''; ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="precio_min" class="form-label">Precio Mínimo ($)</label>
                            <input type="number" class="form-control" id="precio_min" name="precio_min" min="0" step="0.01" 
                                   value="<?php echo !empty($_GET['precio_min']) ? $_GET['precio_min'] : ''; ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="precio_max" class="form-label">Precio Máximo ($)</label>
                            <input type="number" class="form-control" id="precio_max" name="precio_max" min="0" step="0.01" 
                                   value="<?php echo !empty($_GET['precio_max']) ? $_GET['precio_max'] : ''; ?>">
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Resultados -->
        <div class="row">
            <?php if (count($ganado) > 0): ?>
                <?php foreach ($ganado as $animal): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <!-- <img src="../uploads/<?php echo $animal['imagen']; ?>" class="card-img-top" ...> -->
                            <img src="uploads/<?php echo $animal['imagen']; ?>" class="card-img-top" alt="<?php echo $animal['nombre']; ?>">>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $animal['nombre']; ?></h5>
                                <h6 class="text-primary">$<?php echo number_format($animal['precio'], 2); ?></h6>
                                <p class="card-text"><?php echo substr($animal['descripcion'], 0, 100); ?>...</p>
                                
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <small class="text-muted"><i class="fas fa-cow"></i> Raza: <?php echo $animal['raza']; ?></small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted"><i class="fas fa-birthday-cake"></i> Edad: <?php echo $animal['edad']; ?> años</small>
                                    </div>
                                </div>
                                
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <small class="text-muted"><i class="fas fa-weight"></i> Peso: <?php echo $animal['peso']; ?> kg</small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo substr($animal['ubicacion'], 0, 20); ?>...</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3 map-container">
                                    <div id="map-<?php echo $animal['id']; ?>" class="h-100 w-100"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="detalle_ganado.php?id=<?php echo $animal['id']; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Ver Detalles
                                    </a>
                                    <?php if (isset($_SESSION['usuario_id'])): ?>
                                        <a href="pVenta.php?ganado_id=<?php echo $animal['id']; ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-shopping-cart"></i> Comprar
                                        </a>
                                    <?php else: ?>
                                        <a href="pLogin.php" class="btn btn-success btn-sm">
                                            <i class="fas fa-shopping-cart"></i> Inicia sesión para comprar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <small class="text-muted">
                                    Publicado por: <?php echo $animal['vendedor_nombre'] . ' ' . $animal['vendedor_apellido']; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <?php echo ApiManager::getGoogleMapsScript(); ?>
    
                    <script>
                        // Inicializar todos los mapas
                        function initMaps() {
                            <?php foreach ($ganado as $animal): ?>
                                initMap<?php echo $animal['id']; ?>();
                            <?php endforeach; ?>
                        }
                        
                        // Función para inicializar mapa individual
                        <?php foreach ($ganado as $animal): ?>
                        function initMap<?php echo $animal['id']; ?>() {
                            if (typeof google !== 'undefined') {
                                var location = {lat: <?php echo $animal['latitud']; ?>, lng: <?php echo $animal['longitud']; ?>};
                                var map = new google.maps.Map(document.getElementById('map-<?php echo $animal['id']; ?>'), {
                                    zoom: 12,
                                    center: location,
                                    mapTypeId: google.maps.MapTypeId.HYBRID
                                });
                                var marker = new google.maps.Marker({
                                    position: location,
                                    map: map
                                });
                            }
                        }
                        <?php endforeach; ?>
                    </script>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4>No se encontró ganado con los filtros aplicados</h4>
                        <p>Intenta ajustar los criterios de búsqueda o <a href="catalogo.php">ver todos los animales disponibles</a></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'template/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=&callback=initMaps"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=artNGg&callback=initPano&v=weekly"></script>
    <script>
        // Inicializar todos los mapas
        function initMaps() {
            <?php foreach ($ganado as $animal): ?>
                initMap<?php echo $animal['id']; ?>();
            <?php endforeach; ?>
        }
    </script>
</body>
</html>