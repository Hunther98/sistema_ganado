<?php
require_once __DIR__ . '/../../config/config.php';

// Verificar autenticación solo si no está en páginas públicas
$pagina_actual = basename($_SERVER['PHP_SELF']);
$paginas_publicas = array('pLogin.php', 'pRegistro.php', 'index.php', 'catalogo.php');

if (!isset($_SESSION['usuario_id']) && !in_array($pagina_actual, $paginas_publicas)) {
    header('Location: pLogin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <?php $rootPath = $rootPath ?? ''; ?>
    <link rel="stylesheet" href="<?php echo $rootPath; ?>css/styles.css">

    <style>
        body{
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(to right, #127da0, #B721FF, #0837af);
            height: 100vh;
        }
    .navbar{
            background: linear-gradient(135deg, #064a13ff 0%, #0ba227ff, #25ff04ff 100%);
            padding: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            z-index: 1000;
        }
        h1{
            font-size: 64px;
            font-weight: bold;
            text-transform: uppercase;
            width: fit-content;
            margin: 0 auto;
            background: linear-gradient(to right, #55b7e5ff,#0837af, #09adeeff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        h2,h3{
            font-size: 48px;
            font-weight: bold;
            text-transform: uppercase;
            width: fit-content;
            margin: 0 auto;
            background: linear-gradient(to right, #55b7e5ff,#0837af, #09adeeff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .hero-section {
            color: black;
            margin: 0px;
            text-align: center;
            /* background: linear-gradient(to right top, #a2ede5ff,  #21ffc4ff, #6854dbff); */

        }
        .imagen-hero {
            /* background-image: url('<?php echo $rootPath; ?>uploads/68b91dbf30aac_1756962239.jpeg'); */
            background-size: cover;
            background-position: center;
            height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(8, 64, 100, 0.7);
        }
        .nav-item:hover{
            background-color: #343a40;
            border-radius: 10px;
        }
        .feature-card {
            border: none;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.79);
            transition: transform 0.3s;
            height: 100%;
            background: linear-gradient(to right, #127da0, #21deffff, #54abdbff);
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .footer {
            position: relative;
            background-color: #343a40;
            color: white;
            width: 100%;
            z-index: 1000;
            padding-top: 30px;
            bottom: 0%;
            left: 0;
            right: 0;
            top: 30%;
        }
        .scrollable-card {
            max-height: 100%;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
        <div class="container-fluid mt-2 mb-2">
            <a class="navbar-brand" href="<?php echo $rootPath; ?>index.php">
                <i class="fas fa-cow"></i>
                <?php echo APP_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $rootPath; ?>index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $rootPath; ?>catalogo.php">Catálogo</a>
                    </li>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <?php if ($_SESSION['usuario_tipo'] == 'vendedor'): ?>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $rootPath; ?>pGanado.php?accion=agregar">Publicar Ganado</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $rootPath; ?>admin/listar_ganado.php">Mis Animales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $rootPath; ?>mis_ventas.php">Mis Ventas</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['usuario_tipo'] == 'comprador'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo $rootPath; ?>mis_compras.php">Mis Compras</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $rootPath; ?>pContacto.php">Contacto</a>
                    </li>
                    <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_tipo'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $rootPath; ?>admin/admin.php">Panel Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $rootPath; ?>admin/ver_tablas.php">Ver Tablas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $rootPath; ?>admin/listarReportesParametrizados.php">Reportes</a>
                        </li>

                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['usuario_nombre']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo $rootPath; ?>perfil.php">Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo $rootPath; ?>../utilidades/logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $rootPath; ?>pLogin.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $rootPath; ?>pRegistro.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- aside -->

    <!-- Contenido principal -->
    <main class="scrollable-main  container-fluid" style="padding-top: 80px; padding-bottom: 80px;">