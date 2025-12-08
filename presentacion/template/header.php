<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/seguridad.php';

// Verificar autenticación solo si no está en páginas públicas
$pagina_actual = basename($_SERVER['PHP_SELF']);
$paginas_publicas = array('pLogin.php', 'pRegistro.php', 'index.php', 'catalogo.php', 'contacto.php', 'enviarContacto.php');

if (!estaAutenticado() && !in_array($pagina_actual, $paginas_publicas)) {
    redirigirALogin();
}

// Obtener datos del usuario si está autenticado
$usuario_nombre = obtenerUsuarioNombre();
$usuario_rol = obtenerRolActual();
$usuario_id = obtenerUsuarioId();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo APP_NAME; ?> - Plataforma de venta de ganado">
    <title><?php echo $titulo ?? APP_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tema Profesional -->
    <?php $rootPath = $rootPath ?? ''; ?>
    <link rel="stylesheet" href="<?php echo $rootPath; ?>./css/theme.css">
    <link rel="stylesheet" href="<?php echo $rootPath; ?>./css/styles.css">
    
    <style>
        /* Sidebar Layout */
        :root {
            --sidebar-width: 280px;
            --sidebar-width-mobile: 250px;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 1030;
            transition: transform 0.3s ease;
        }

        /* Contenedor Principal */
        body > main {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            padding-top: 0;
            display: flex;
            flex-direction: column;
        }

        /* Footer Adjustment: when footer is inside main, let it stretch full width of main */
        .footer {
            margin-left: 0;
            width: 100%;
            background-color: var(--dark);
            color: var(--secondary);
            border-top: 4px solid var(--primary);
            position: relative;
        }

        /* Header Top Bar (móvil) */
        .top-bar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            z-index: 1031;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar Logo Section */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header .brand {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .sidebar-header .brand:hover {
            opacity: 0.8;
            color: white;
        }

        .sidebar-header .brand i {
            font-size: 2rem;
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
        }

        .sidebar-nav .nav-item {
            margin: 0.25rem 0;
        }

        .sidebar-nav .nav-link {
            color: rgba(64, 44, 44, 0.8);
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--secondary);
            padding-left: 1.35rem;
        }

        .sidebar-nav .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.2);
            border-left-color: var(--secondary);
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Dropdown Menu */
        .sidebar-nav .dropdown-toggle::after {
            display: none;
        }

        .sidebar-nav .nav-submenu {
            display: none;
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .sidebar-nav .nav-submenu.show {
            display: block;
        }

        .sidebar-nav .nav-submenu .nav-link {
            padding-left: 3rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .sidebar-nav .nav-submenu .nav-link:hover {
            color: white;
            padding-left: 2.85rem;
        }

        /* Separator */
        .sidebar-divider {
            margin: 1rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* User Section (Bottom) */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(0, 0, 0, 0.1);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .sidebar-user-info {
            flex: 1;
            color: white;
            font-size: 0.9rem;
        }

        .sidebar-user-info .user-name {
            font-weight: 600;
            display: block;
        }

        .sidebar-user-info .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .sidebar-logout {
            width: 100%;
            color: rgba(255, 255, 255, 0.8);
            background-color: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-logout:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            :root {
                --sidebar-width: 0;
            }

            body > main {
                margin-left: 0;
                width: 100%;
                margin-top: 60px;
            }

            .footer {
                margin-left: 0;
                width: 100%;
            }

            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width-mobile);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .top-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 1rem;
            }

            .sidebar-toggle {
                background: none;
                border: none;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1029;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .sidebar-footer {
                position: relative;
                padding: 1rem;
            }

            body > main {
                padding-top: 0;
            }
        }

        /* Mejora de contraste y legibilidad */
        main {
            min-height: calc(100vh - 250px);
        }
    </style>
</head>
<body>
    <!-- Overlay para móvil -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Top Bar (Móvil) -->
    <div class="top-bar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="text-white" style="flex: 1; text-align: center;">
            <i class="fas fa-cow"></i> <?php echo APP_NAME; ?>
        </div>
        <div style="width: 40px;"></div>
    </div>

    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <a href="<?php echo $rootPath; ?>index.php" class="brand">
                <i class="fas fa-cow"></i>
                <span><?php echo APP_NAME; ?></span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav" id="sidebarNav">
            <!-- Inicio -->
            <li class="nav-item">
                <a href="<?php echo $rootPath; ?>index.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>

            <!-- Catálogo -->
            <li class="nav-item">
                <a href="<?php echo $rootPath; ?>catalogo.php" class="nav-link">
                    <i class="fas fa-store"></i>
                    <span>Catálogo</span>
                </a>
            </li>

            <!-- Opciones para Vendedores -->
            <?php if (estaAutenticado() && tieneRol(ROLE_VENDEDOR)): ?>
                <li class="nav-item">
                    <a href="#vendedorMenu" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-cow"></i>
                        <span>Mi Ganado</span>
                        <i class="fas fa-chevron-right ms-auto" style="font-size: 0.75rem;"></i>
                    </a>
                    <ul class="nav-submenu" id="vendedorMenu">
                        <li><a href="<?php echo $rootPath; ?>pGanado.php?accion=agregar" class="nav-link">
                            <i class="fas fa-plus"></i> Publicar Ganado
                        </a></li>
                        <li><a href="<?php echo $rootPath; ?>listar_ganado.php" class="nav-link">
                            <i class="fas fa-list"></i> Mis Animales
                        </a></li>
                        <li><a href="<?php echo $rootPath; ?>mis_ventas.php" class="nav-link">
                            <i class="fas fa-chart-line"></i> Mis Ventas
                        </a></li>
                        <li><a href="<?php echo $rootPath; ?>pVacunacion.php?ganado_id=<?php echo $ganado['id']; ?>"class="nav-link">
                            <i class="fas fa-syringe"></i> Agregar Vacunación
                        </a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- Opciones para Compradores -->
            <?php if (estaAutenticado() && tieneRol(ROLE_COMPRADOR)): ?>
                <li class="nav-item">
                    <a href="<?php echo $rootPath; ?>mis_compras.php" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Mis Compras</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Panel Administrativo -->
            <?php if (estaAutenticado() && tieneRol(ROLE_ADMIN)): ?>
                <li class="nav-item">
                    <a href="#adminMenu" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-shield-alt"></i>
                        <span>Acceso y seguridad</span>
                        <i class="fas fa-chevron-right ms-auto" style="font-size: 0.75rem;"></i>
                    </a>
                    <ul class="nav-submenu" id="adminMenu">
                        <li><a href="<?php echo $rootPath; ?>admin/admin.php" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i> Panel Admin
                        </a></li>
                        <li><a href="<?php echo $rootPath; ?>admin/listar_usuario.php" class="nav-link">
                            <i class="fas fa-users"></i> Administrar Usuarios
                        </a></li>
                        <li><a href="<?php echo $rootPath; ?>admin/aListarRol.php" class="nav-link">
                            <i class="fas fa-key"></i>Administrar Roles
                        </a></li>
                        <li><a href="<?php echo $rootPath; ?>admin/listarReportesParametrizados.php" class="nav-link">
                            <i class="fas fa-file-pdf"></i> Reportes
                        </a></li>
                        <li><a href="<?php echo $rootPath; ?>admin/ver_tablas.php" class="nav-link">
                            <i class="fas fa-database"></i> Ver Tablas
                        </a></li>
                        <li><a href="#" class="nav-link"><i class="fas fa-shield-alt"></i>Acceso y seguridad</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- Contacto -->
            <li class="nav-item">
                <a href="<?php echo $rootPath; ?>contacto.php" class="nav-link">
                    <i class="fas fa-envelope"></i>
                    <span>Contacto</span>
                </a>
            </li>
        </nav>

        <!-- User Section -->
        <?php if (estaAutenticado()): ?>
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        <?php echo strtoupper(substr($usuario_nombre, 0, 1)); ?>
                    </div>
                    <div class="sidebar-user-info">
                        <span class="user-name"><?php echo htmlspecialchars($usuario_nombre); ?></span>
                        <span class="user-role"><?php echo obtenerDescripcionRol($usuario_rol); ?></span>
                    </div>
                </div>
                <a href="<?php echo $rootPath; ?>perfil.php" class="btn btn-sm btn-outline-light w-100 mb-2">
                    <i class="fas fa-cog"></i> Perfil
                </a>
                <a href="<?php echo $rootPath; ?>../utilidades/logout.php" class="btn btn-sm btn-outline-light w-100">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        <?php else: ?>
            <div class="sidebar-footer">
                <a href="<?php echo $rootPath; ?>pLogin.php" class="btn btn-light w-100 mb-2">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
                <a href="<?php echo $rootPath; ?>pRegistro.php" class="btn btn-outline-light w-100">
                    <i class="fas fa-user-plus"></i> Registrarse
                </a>
            </div>
        <?php endif; ?>
    </aside>

    <!-- Contenido Principal -->
    <main class="">