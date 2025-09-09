<?php
session_start();
require_once '../config/config.php';
require_once '../datos/dConexion.php';
// Verificar autenticación
verificarAutenticacion('admin');

$cone = new dConexion();
$con = $cone->Conectar();

// Obtener todas las tablas
$tablas = [];
$result = mysqli_query($con, "SHOW TABLES");
while ($row = mysqli_fetch_array($result)) {
    $tablas[] = $row[0];
}

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
?>
<?php include 'template/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4">Visualizador de Tablas de la Base de Datos</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tablas Disponibles</h5>
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
                <div class="card mt-3">
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
        
        <div class="col-md-9">
            <?php if (!empty($tabla_seleccionada)): ?>
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Estructura: <?php echo $tabla_seleccionada; ?></h5>
                        <span class="badge bg-light text-dark"><?php echo count($estructura_tabla); ?> campos</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Campo</th>
                                        <th>Tipo</th>
                                        <th>Nulo</th>
                                        <th>Llave</th>
                                        <th>Por Defecto</th>
                                        <th>Extra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estructura_tabla as $campo): ?>
                                        <tr>
                                            <td><strong><?php echo $campo['Field']; ?></strong></td>
                                            <td><code><?php echo $campo['Type']; ?></code></td>
                                            <td><?php echo $campo['Null']; ?></td>
                                            <td>
                                                <?php if (!empty($campo['Key'])): ?>
                                                    <span class="badge bg-warning text-dark"><?php echo $campo['Key']; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $campo['Default'] ?? 'NULL'; ?></td>
                                            <td><?php echo $campo['Extra']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Datos: <?php echo $tabla_seleccionada; ?></h5>
                        <span class="badge bg-light text-dark"><?php echo count($datos_tabla); ?> registros</span>
                    </div>
                    <div class="card-body">
                        <?php if (count($datos_tabla) > 0): ?>
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
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

<?php include 'template/footer.php'; ?>