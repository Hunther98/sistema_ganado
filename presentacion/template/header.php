<?php
require_once '../config/config.php';

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
    <link rel="stylesheet" href="css/styles.css">

    <style>
        main {
            min-height: 75vh;
            padding: 20px 0;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .hero-section {
            
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        .imagen-hero {
            background-image: url('../uploads/descarga1.jpeg');
            background-size: cover;
            background-position: center;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .feature-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .footer {
            background-color: #343a40;
            color: white;
            margin-top: 50px;
            padding: 30px 0;
        }
        /* Estilos generales para la página de reportes */
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    border: none;
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    font-weight: 600;
    padding: 15px 20px;
    border-radius: 10px 10px 0 0 !important;
}

.card-body {
    padding: 20px;
}

/* Estilos para las métricas */
.card.metric-card {
    transition: transform 0.3s ease;
}

.card.metric-card:hover {
    transform: translateY(-5px);
}

.card.bg-primary, 
.card.bg-success, 
.card.bg-info, 
.card.bg-warning {
    color: white;
    border: none;
}

.card.bg-primary {
    background-color: #4e73df !important;
}

.card.bg-success {
    background-color: #1cc88a !important;
}

.card.bg-info {
    background-color: #36b9cc !important;
}

.card.bg-warning {
    background-color: #f6c23e !important;
}

/* Estilos para las tablas */
.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    border-collapse: collapse;
}

.table th {
    background-color: #f8f9fc;
    color: #5a5c69;
    font-weight: 600;
    padding: 12px 15px;
    text-align: left;
}

.table td {
    padding: 12px 15px;
    vertical-align: middle;
    border-top: 1px solid #e3e6f0;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Badges */
.badge {
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 4px;
}

.badge-success {
    background-color: #1cc88a;
}

.badge-primary {
    background-color: #4e73df;
}

.badge-info {
    background-color: #36b9cc;
}

.badge-warning {
    background-color: #f6c23e;
    color: #1a1a1a;
}

.badge-danger {
    background-color: #e74a3b;
}

/* Botones */
.btn {
    border-radius: 6px;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #3a5ec0;
    border-color: #3a5ec0;
}

.btn-secondary {
    background-color: #858796;
    border-color: #858796;
}

.btn-secondary:hover {
    background-color: #717384;
    border-color: #717384;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

/* Formularios */
.form-control {
    border-radius: 6px;
    padding: 10px 15px;
    border: 1px solid #d1d3e2;
}

.form-label {
    font-weight: 600;
    color: #5a5c69;
    margin-bottom: 8px;
}

/* Estilos para la impresión */
@media print {
    body * {
        visibility: hidden;
    }
    .card, .card * {
        visibility: visible;
    }
    .card {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
        border: 1px solid #ddd;
        page-break-after: always;
    }
    .btn {
        display: none;
    }
}

/* Animaciones */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.table tbody tr {
    animation: fadeIn 0.3s ease-in-out;
}

/* Estilos para tablas con estado */
.table-danger {
    background-color: rgba(231, 74, 59, 0.1) !important;
}

.table-warning {
    background-color: rgba(246, 194, 62, 0.1) !important;
}

/* Estilos para el encabezado */
h2 {
    color: #4e73df;
    font-weight: 700;
    margin-bottom: 0;
}

/* Estilos para los iconos */
.fas {
    margin-right: 5px;
}
h1{
    background-color: linear-gradient(tp right, #0b871fff 0%, #73f55fff 100%);
    background-clip: text;
    color: transparent;
    text-transform: uppercase;
    font-weight: bold;
}
    </style>
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-cow"></i>
                <?php echo APP_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="catalogo.php">Catálogo</a>
                    </li>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <?php if ($_SESSION['usuario_tipo'] == 'vendedor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="pGanado.php?accion=agregar">Publicar Ganado</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="listar_ganado.php">Mis Animales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="mis_ventas.php">Mis Ventas</a>
                            </li>
                        <?php endif; ?>
                        <?php if ($_SESSION['usuario_tipo'] == 'comprador'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="mis_compras.php">Mis Compras</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="pContacto.php">Contacto</a>
                    </li>
                    <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_tipo'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Panel Admin</a>
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
                                <li><a class="dropdown-item" href="perfil.php">Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../utilidades/logout.php">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="pLogin.php">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pRegistro.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main>