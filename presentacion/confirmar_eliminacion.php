<?php
require_once(__DIR__ . "/../negocio/GestorGanado.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $gestor = new GestorGanado();
    $animal = $gestor->obtenerAnimal($id);
    
    if ($animal) {
        if (isset($_POST['confirmar'])) {
            if ($gestor->eliminarAnimal($id)) {
                header("Location: lista_ganado.php?mensaje=eliminado");
                exit();
            } else {
                $error = "Error al eliminar el animal";
            }
        }
    } else {
        header("Location: lista_ganado.php?mensaje=no_existe");
        exit();
    }
} else {
    header("Location: lista_ganado.php");
    exit();
}
?>