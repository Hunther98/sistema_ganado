<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../datos/dConexion.php';
// Verificar autenticación
verificarAutenticacion('admin');
if ($_SESSION['usuario_tipo'] != 'admin') {
    $_COOKIE['error'] = 'permisos';
    setcookie('error', 'permisos', time() + 3600, '/');
    echo '<script>window.location.href = "../index.php";</script>';
}
$cone = new dConexion();
$con = $cone->Conectar();

// Obtener todas las tablas
$tablas = [];
$result = mysqli_query($con, "SHOW TABLES");
while ($row = mysqli_fetch_array($result)) {
    $tablas[] = $row[0];
}

// --- Paginación para la lista de tablas (10 por página) ---
// $perPage = 10;
// $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
// $totalTablas = count($tablas);
// $totalPages = $totalTablas > 0 ? (int)ceil($totalTablas / $perPage) : 1;

// // Si se indicó una tabla seleccionada, asegurarnos de que su página sea la actual
// $tabla_seleccionada = $_GET['tabla'] ?? '';
// if (!empty($tabla_seleccionada) && in_array($tabla_seleccionada, $tablas)) {
//     $indexSel = array_search($tabla_seleccionada, $tablas);
//     $pageOfSelected = (int)floor($indexSel / $perPage) + 1;
//     if ($page !== $pageOfSelected) {
//         $page = $pageOfSelected;
//     }
// }

// $start = ($page - 1) * $perPage;
// $tablas_page = array_slice($tablas, $start, $perPage);

// Obtener datos de la tabla seleccionada
$tabla_seleccionada = $_GET['tabla'] ?? '';
$datos_tabla = [];
$estructura_tabla = [];

if (!empty($tabla_seleccionada) && in_array($tabla_seleccionada, $tablas)) {
    // Obtener estructura de la tabla
    $result = mysqli_query($con, "DESCRIBE $tabla_seleccionada");
    while ($row = mysqli_fetch_assoc($result)) {
        $estructura_tabla[] = $row;
    }
    
    // Obtener datos de la tabla
    $result = mysqli_query($con, "SELECT * FROM $tabla_seleccionada");
    while ($row = mysqli_fetch_assoc($result)) {
        $datos_tabla[] = $row;
    }
}

$titulo = 'Visualizador de Tablas';
$rootPath = '../';
?>
<?php include __DIR__ . '/../template/header.php'; ?>

<div class="container-fluid col-md-12 py-4 pt-4">
    <h2 class="mb-1">Visualizador de Tablas de la Base de Datos</h2>

    <div class="row mt-2 py-4">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-4 ">Tablas Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($tablas as $tabla): ?>
                            <a href="?tabla=<?php echo $tabla; ?>" 
                               class="list-group-item list-group-item-action <?php echo $tabla == $tabla_seleccionada ? 'active' : ''; ?>">
                                <i class="fas fa-table me-2"></i><?php echo $tabla; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($tabla_seleccionada)): ?>
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">Información de la Tabla</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Tabla:</strong> <?php echo $tabla_seleccionada; ?></p>
                        <p><strong>Registros:</strong> <?php echo count($datos_tabla); ?></p>
                        <p><strong>Campos:</strong> <?php echo count($estructura_tabla); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-10">
            <?php if (!empty($tabla_seleccionada)): ?>
                <div class="card mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Estructura: <?php echo $tabla_seleccionada; ?></h5>
                        <span class="badge bg-light text-dark"><?php echo count($estructura_tabla); ?> campos</span>
                    </div>
                    
                </div>
                
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Datos: <?php echo $tabla_seleccionada; ?></h5>
                        <span class="badge bg-light text-dark"><?php echo count($datos_tabla); ?> registros</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (count($datos_tabla) > 0): ?>
                            <div class="table-responsive" style="overflow-y: auto;">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead class="table-dark sticky-top">
                                        <tr>
                                            <?php foreach (array_keys($datos_tabla[0]) as $columna): ?>
                                                <th><?php echo $columna; ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($datos_tabla as $fila): ?>
                                            <tr>
                                                <?php foreach ($fila as $valor): ?>
                                                    <td>
                                                        <?php 
                                                        if (is_null($valor)) {
                                                            echo '<span class="text-muted">NULL</span>';
                                                        } elseif (empty($valor)) {
                                                            echo '<span class="text-muted">Vacío</span>';
                                                        } elseif (strlen($valor) > 50) {
                                                            echo substr($valor, 0, 50) . '...';
                                                        } else {
                                                            echo htmlspecialchars($valor);
                                                        }
                                                        ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> No hay registros en esta tabla.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-database fa-4x text-muted mb-3"></i>
                        <h4>Selecciona una tabla</h4>
                        <p class="text-muted">Elige una tabla de la lista para ver su estructura y datos.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../template/footer.php'; ?>
