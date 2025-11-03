<!-- [file name]: obtener_estadisticas.php
[file content begin] -->
<?php
require_once '../config/config.php';
require_once '../datos/dConexion.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permite requests desde cualquier origen

try {
    $cone = new dConexion();
    $con = $cone->Conectar();
    
    if (!$con) {
        throw new Exception('Error de conexión a la base de datos');
    }
    
    // Obtener estadísticas
    $estadisticas = [
        'usuarios' => obtenerTotalUsuarios($con),
        'ganado' => obtenerTotalGanado($con),
        'ventas' => obtenerTotalVentas($con),
        'ubicaciones' => obtenerTotalUbicaciones($con),
        'ventas_mes_actual' => obtenerVentasMesActual($con),
        'ganado_disponible' => obtenerGanadoDisponible($con),
        'usuarios_activos' => obtenerUsuariosActivos($con),
        'ingresos_totales' => obtenerIngresosTotales($con)
    ];
    
    echo json_encode([
        'success' => true,
        'data' => $estadisticas,
        'timestamp' => time()
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => time()
    ]);
}

// Función para obtener total de usuarios
function obtenerTotalUsuarios($con) {
    $query = "SELECT COUNT(*) as total FROM usuarios WHERE activo = 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}

// Función para obtener total de ganado
function obtenerTotalGanado($con) {
    $query = "SELECT COUNT(*) as total FROM ganado";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}

// Función para obtener total de ventas
function obtenerTotalVentas($con) {
    $query = "SELECT COUNT(*) as total FROM ventas WHERE estado = 'completada'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}

// Función para obtener total de ubicaciones únicas
function obtenerTotalUbicaciones($con) {
    $query = "SELECT COUNT(DISTINCT CONCAT(latitud, longitud)) as total FROM ganado";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}

// Función para obtener ventas del mes actual
function obtenerVentasMesActual($con) {
    $query = "SELECT COUNT(*) as total FROM ventas 
              WHERE estado = 'completada' 
              AND MONTH(fecha) = MONTH(CURRENT_DATE()) 
              AND YEAR(fecha) = YEAR(CURRENT_DATE())";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}

// Función para obtener ganado disponible
function obtenerGanadoDisponible($con) {
    $query = "SELECT COUNT(*) as total FROM ganado WHERE estado = 'disponible'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}

// Función para obtener usuarios activos
function obtenerUsuariosActivos($con) {
    $query = "SELECT COUNT(*) as total FROM usuarios WHERE activo = 1";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (int)$row['total'];
}

// Función para obtener ingresos totales
function obtenerIngresosTotales($con) {
    $query = "SELECT COALESCE(SUM(precio_venta), 0) as total FROM ventas WHERE estado = 'completada'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (float)$row['total'];
}

// Cerrar conexión
if (isset($con)) {
    mysqli_close($con);
}
?>
<!-- [file content end] -->