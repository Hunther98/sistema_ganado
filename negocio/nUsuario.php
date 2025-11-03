<?php
require_once __DIR__ . '/../datos/dUsuario.php';

class nUsuario {
    // Función para registrar usuario
    public function registrarUsuario($nombre, $apellido, $email, $password, $confirmPassword, $telefono, $direccion, $tipo = 'comprador') {
        // Validaciones de negocio
        if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
            return array('exito' => false, 'mensaje' => 'Todos los campos obligatorios deben ser completados');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array('exito' => false, 'mensaje' => 'El formato de email no es válido');
        }
        
        if (strlen($password) < 6) {
            return array('exito' => false, 'mensaje' => 'La contraseña debe tener al menos 6 caracteres');
        }
        
        if ($password !== $confirmPassword) {
            return array('exito' => false, 'mensaje' => 'Las contraseñas no coinciden');
        }
        
        // Verificar si ya existe usuario con ese email
        $dUsuario = new dUsuario();
        if ($dUsuario->emailExiste($email)) {
            return array('exito' => false, 'mensaje' => 'Ya existe un usuario con ese email');
        }
        
        $usuario = new dUsuario(null, $nombre, $apellido, $email, $password, $telefono, $direccion, $tipo, true);
        $resultado = $usuario->registrar();
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Usuario registrado exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al registrar el usuario');
        }
    }
    // Función para iniciar sesión
    public function iniciarSesion($identificador, $password) {
        if (empty($identificador) || empty($password)) {
            return array('exito' => false, 'mensaje' => 'Identificador y contraseña son requeridos');
        }
        
        $dUsuario = new dUsuario();
        $usuario = $dUsuario->iniciarSesion($identificador, $password);
        
        if ($usuario) {
            // Iniciar sesión
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            
            return array('exito' => true, 'mensaje' => 'Inicio de sesión exitoso');
        } else {
            return array('exito' => false, 'mensaje' => 'Email o contraseña incorrectos');
        }
    }
    // Función para obtener todos los usuarios
    public function obtenerTodosLosUsuarios() {
        $dUsuario = new dUsuario();
        return $dUsuario->obtenerTodos();
    }
    function listarUsuarios() {
        $dUsuario = new dUsuario();
        $resultado = $dUsuario->mostrarUsuarios();
        return $resultado;
    }
    // Función para obtener usuario por ID
    public function obtenerUsuario($id) {
        $dUsuario = new dUsuario();
        return $dUsuario->obtenerPorId($id);
    }
    public function actualizarUsuario($id, $nombre, $apellido, $email, $telefono, $direccion, $tipo, $activo) {
        // Validaciones de negocio
        if ( empty($nombre) || empty($apellido) || empty($email) || empty($tipo)) {
            return array('exito' => false, 'mensaje' => 'Todos los campos obligatorios deben ser completados');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array('exito' => false, 'mensaje' => 'El formato de email no es válido');
        }
        
        // Verificar si el email ya existe (excluyendo el usuario actual)
        $dUsuario = new dUsuario();
        // if ($dUsuario->emailExiste($email, $id)) {
        //     return array('exito' => false, 'mensaje' => 'Ya existe un usuario con ese email');
        // }
        
        $resultado = $dUsuario->actualizar($id, $nombre, $apellido, $email, $telefono, $direccion, $tipo, $activo);
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Usuario actualizado exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al actualizar el usuario');
        }
    }
    public function eliminarUsuario($id) {
    // Validaciones de negocio
        if (empty($id)) {
            return array('exito' => false, 'mensaje' => 'ID de usuario es requerido');
        }
        
        $dUsuario = new dUsuario();
        $resultado = $dUsuario->eliminar($id);
        
        if ($resultado) {
            return array('exito' => true, 'mensaje' => 'Usuario eliminado exitosamente');
        } else {
            return array('exito' => false, 'mensaje' => 'Error al eliminar el usuario');
        }
    }
    public function eliminarCuenta($usuario_id, $password_confirm) {
    // Validaciones de negocio
    if (empty($usuario_id)) {
        return array('exito' => false, 'mensaje' => 'ID de usuario es requerido');
    }
    
    if (empty($password_confirm)) {
        return array('exito' => false, 'mensaje' => 'Debes confirmar tu contraseña para eliminar la cuenta');
    }
    
    // Verificar que el usuario existe
    $dUsuario = new dUsuario();
    $usuario = $dUsuario->obtenerPorId($usuario_id);
    
    if (!$usuario) {
        return array('exito' => false, 'mensaje' => 'Usuario no encontrado');
    }
    
    // Verificar contraseña
    $usuario_completo = $dUsuario->obtenerCompletoPorId($usuario_id);
    if (!password_verify($password_confirm, $usuario_completo['password'])) {
        return array('exito' => false, 'mensaje' => 'Contraseña incorrecta');
    }
    
    // Verificar si el usuario tiene ganado registrado
    require_once __DIR__ . '/../datos/dGanado.php';
    $dGanado = new dGanado();
    $ganado_usuario = $dGanado->obtenerPorUsuario($usuario_id);
    
    if (count($ganado_usuario) > 0) {
        return array('exito' => false, 'mensaje' => 'No puedes eliminar tu cuenta porque tienes ganado registrado. Primero elimina o transfiere tus animales.');
    }
    
    // Verificar si el usuario tiene ventas pendientes
    require_once __DIR__ . '/../datos/dVenta.php';
    $dVenta = new dVenta();
    $ventas_pendientes = $dVenta->obtenerVentasPendientesPorUsuario($usuario_id);
    
    if (count($ventas_pendientes) > 0) {
        return array('exito' => false, 'mensaje' => 'No puedes eliminar tu cuenta porque tienes ventas pendientes. Primero completa o cancela tus transacciones.');
    }
    
    // Eliminar el usuario
    $resultado = $dUsuario->eliminar($usuario_id);
    
    if ($resultado) {
        return array('exito' => true, 'mensaje' => 'Cuenta eliminada exitosamente');
    } else {
        return array('exito' => false, 'mensaje' => 'Error al eliminar la cuenta');
    }
}
public function obtenerUsuarioPorId($id){
    $dUsuario = new dUsuario();
    return $dUsuario->obtenerPorId($id);
}

// Función para cerrar sesión
public function cerrarSesion() {
    session_start();
    session_unset();
    session_destroy();
        return array('exito' => true, 'mensaje' => 'Sesión cerrada correctamente');
    }
    
    
}
?>