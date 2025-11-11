<?php
// Cargar la conexión (dConexion está en la misma carpeta 'datos')
require_once __DIR__ . '/dConexion.php';

class dEstadisticas {
    public function obtenerEstadisticasGenerales() {
        // Usar la conexión mysqli definida en dConexion
        $db = new dConexion();
        $conn = $db->Conectar();

        $total_usuarios = 0;
        $total_ganado = 0;
        $total_ventas = 0;

        if ($conn) {
            // Total usuarios
            $query = "SELECT COUNT(*) as total_usuarios FROM usuarios";
            if ($res = mysqli_query($conn, $query)) {
                $row = mysqli_fetch_assoc($res);
                $total_usuarios = $row ? (int)$row['total_usuarios'] : 0;
                mysqli_free_result($res);
            }

            // Total ganado
            $query = "SELECT COUNT(*) as total_ganado FROM ganado";
            if ($res = mysqli_query($conn, $query)) {
                $row = mysqli_fetch_assoc($res);
                $total_ganado = $row ? (int)$row['total_ganado'] : 0;
                mysqli_free_result($res);
            }

            // Total ventas
            $query = "SELECT COUNT(*) as total_ventas FROM ventas";
            if ($res = mysqli_query($conn, $query)) {
                $row = mysqli_fetch_assoc($res);
                $total_ventas = $row ? (int)$row['total_ventas'] : 0;
                mysqli_free_result($res);
            }

            // No cerramos la conexión aquí (dConexion puede manejarlo), pero si se desea:
            // mysqli_close($conn);
        }

        return [
            'total_usuarios' => $total_usuarios,
            'total_ganado' => $total_ganado,
            'total_ventas' => $total_ventas,
        ];
    }
}