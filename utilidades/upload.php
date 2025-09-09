<?php
require_once '../config/config.php';

function subirImagen($archivo) {
    // Verificar si no hay error en la subida
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return array('exito' => false, 'mensaje' => 'Error al subir el archivo');
    }
    
    // Verificar tamaño máximo
    if ($archivo['size'] > MAX_FILE_SIZE) {
        return array('exito' => false, 'mensaje' => 'El archivo excede el tamaño máximo permitido (5MB)');
    }
    
    // Verificar tipo de archivo
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_IMAGE_TYPES)) {
        return array('exito' => false, 'mensaje' => 'Tipo de archivo no permitido. Solo se permiten: ' . implode(', ', ALLOWED_IMAGE_TYPES));
    }
    
    // Crear directorio de uploads si no existe
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    // Generar nombre único para el archivo
    $nombre_archivo = uniqid() . '_' . time() . '.' . $extension;
    $ruta_destino = UPLOAD_DIR . $nombre_archivo;
    
    // Mover el archivo subido al directorio de destino
    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        return array('exito' => true, 'nombre_archivo' => $nombre_archivo);
    } else {
        return array('exito' => false, 'mensaje' => 'Error al guardar el archivo');
    }
}

function eliminarImagen($nombre_archivo) {
    $ruta_archivo = UPLOAD_DIR . $nombre_archivo;
    
    if (file_exists($ruta_archivo)) {
        return unlink($ruta_archivo);
    }
    
    return false;
}
?>