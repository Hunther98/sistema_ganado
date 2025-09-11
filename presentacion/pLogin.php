<?php
require_once '../config/config.php';
require_once '../negocio/nUsuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $nUsuario = new nUsuario();
    $resultado = $nUsuario->iniciarSesion($email, $password);
    
    if ($resultado['exito']) {
        header('Location: index.php');
        exit;
    } else {
        $error = $resultado['mensaje'];
    }
}

// Si ya está autenticado, redirigir al inicio
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            background-image: url('https://www.shutterstock.com/shutterstock/photos/2308105043/display_1500/stock-photo-empty-wooden-table-top-with-grass-field-and-cows-background-2308105043.jpg');
            background-size: cover;
            background-position: center;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);

        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-container p-4 mt-5">
                    <div class="text-center mb-4">
                        <img src="..\presentacion\uploads\68aeb175dbb43_1756279157.png" alt="Logo" height="80" class="mb-3">
                        <h2>Iniciar Sesión</h2>
                        <p class="text-muted">Accede a tu cuenta</p>
                    </div>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
                        </div>
                    </form>
                    
                    <hr>
                    <div class="text-center">
                        <p>¿No tienes cuenta? <a href="pRegistro.php">Regístrate aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>