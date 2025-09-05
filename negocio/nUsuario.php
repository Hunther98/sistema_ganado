<?php
require_once '../datos/dUsuario.php';

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
    public function iniciarSesion($email, $password) {
        if (empty($email) || empty($password)) {
            return array('exito' => false, 'mensaje' => 'Email y contraseña son requeridos');
        }
        
        $dUsuario = new dUsuario();
        $usuario = $dUsuario->iniciarSesion($email, $password);
        
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
    // Función para obtener usuario por ID
    public function obtenerUsuario($id) {
        $dUsuario = new dUsuario();
        return $dUsuario->obtenerPorId($id);
    }
    public function actualizarUsuario($id, $nombre, $apellido, $email, $telefono, $direccion, $tipo, $activo) {
        // Validaciones de negocio
        if (empty($id) || empty($nombre) || empty($apellido) || empty($email) || empty($tipo)) {
            return array('exito' => false, 'mensaje' => 'Todos los campos obligatorios deben ser completados');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array('exito' => false, 'mensaje' => 'El formato de email no es válido');
        }
        
        // Verificar si el email ya existe (excluyendo el usuario actual)
        $dUsuario = new dUsuario();
        if ($dUsuario->emailExiste($email, $id)) {
            return array('exito' => false, 'mensaje' => 'Ya existe un usuario con ese email');
        }
        
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

    // Función para cerrar sesión
    public function cerrarSesion() {
        session_start();
        session_unset();
        session_destroy();
        return array('exito' => true, 'mensaje' => 'Sesión cerrada correctamente');
    }
    
    
}
?>