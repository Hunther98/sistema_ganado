<?php
require_once __DIR__ . '/../../config/config.php';
$rootPath = '../';
verificarAutenticacion();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes Parametrizados de Muebles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            background: linear-gradient(to right, #127da0, #B721FF, #0837af);
        }
        
        .container-main {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .search-container {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .results-container {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: #B721FF;
            box-shadow: 0 0 0 0.2rem rgba(183, 33, 255, 0.25);
        }

        .btn-custom {
            background: linear-gradient(to right, #B721FF, #C066FE);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(183, 33, 255, 0.3);
            color: white;
        }

        .btn-download {
            background: linear-gradient(to right, #dc3545, #c82333);
        }

        .table thead {
            background: linear-gradient(to right, #B721FF, #C066FE);
            color: white;
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/../template/header.php'; ?>
    <div class="container-main">
        <!-- Sección de búsqueda -->
        <div class="search-container">
            <h2 class="section-title">Búsqueda Avanzada de Muebles</h2>
            <form id="searchForm" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" id="buscarId" placeholder="Buscar por ID">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="buscarNombre" placeholder="Buscar por nombre">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="buscarEmail" placeholder="Buscar por email">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="buscarTelefono" placeholder="Buscar por teléfono">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="buscarDireccion" placeholder="Buscar por dirección">
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="buscarEstado">
                        <option value="">Todos los estados</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-custom me-2" onclick="realizarBusqueda()">
                        Buscar
                    </button>
                    <button type="button" class="btn btn-custom" onclick="limpiarBusqueda()">
                        Limpiar Filtros
                    </button>
                    <button type="button" class="btn btn-custom btn-download" onclick="descargarPDFFiltrado()">
                        Descargar PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Sección de resultados -->
        <div class="results-container">
            <h3 class="section-title">Resultados de la Búsqueda</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="resultadosTabla">
                        <?php
                        // Cargar la clase de negocio correctamente (carpeta 'negocio')
                        include_once __DIR__ . '/../../negocio/nUsuario.php';
                        $usuario = new nUsuario();
                        $rows = $usuario->listarUsuarios(); // devuelve un array de usuarios
                        
                        // if (!empty($rows) && is_array($rows)) {
                            while ($dataRow = mysqli_fetch_array($rows)) {
                                echo "<tr class='fila-usuario'>";
                                echo "<td>" . htmlspecialchars($dataRow['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($dataRow['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($dataRow['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($dataRow['telefono']) . "</td>";
                                echo "<td>" . htmlspecialchars($dataRow['direccion']) . "</td>";
                                $activo = !empty($dataRow['activo']) && $dataRow['activo'] ? true : false;
                                echo "<td><span class='badge " . ($activo ? 'bg-success' : 'bg-danger') . "'>" 
                                     . ($activo ? 'Activo' : 'Inactivo') . "</span></td>";
                                echo "</tr>";
                            }
                        
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function realizarBusqueda() {
            const id = document.getElementById('buscarId').value.toLowerCase();
            const nombre = document.getElementById('buscarNombre').value.toLowerCase();
            const email = document.getElementById('buscarEmail').value.toLowerCase();
            const telefono = document.getElementById('buscarTelefono').value.toLowerCase();
            const direccion = document.getElementById('buscarDireccion').value.toLowerCase();
            const estado = document.getElementById('buscarEstado').value;

            const filas = document.querySelectorAll('.fila-usuario');
            let encontrados = false;

            filas.forEach(fila => {
                const celdas = fila.getElementsByTagName('td');
                const coincide = (
                    (!id || celdas[0].textContent.toLowerCase().includes(id)) &&
                    (!nombre || celdas[1].textContent.toLowerCase().includes(nombre)) &&
                    (!email || celdas[2].textContent.toLowerCase().includes(email)) &&
                    (!telefono || celdas[3].textContent.toLowerCase().includes(telefono)) &&
                    (!direccion || celdas[4].textContent.toLowerCase().includes(direccion)) &&
                    (!estado || (estado === '1' && celdas[5].textContent.includes('Activo')) ||
                              (estado === '0' && celdas[5].textContent.includes('Inactivo')))
                );

                fila.style.display = coincide ? '' : 'none';
                if (coincide) encontrados = true;
            });

            if (!encontrados) {
                const tbody = document.getElementById('resultadosTabla');
                const filaNoResultados = document.createElement('tr');
                filaNoResultados.innerHTML = '<td colspan="6" class="text-center">No se encontraron resultados</td>';
                tbody.appendChild(filaNoResultados);
            }
        }

        function limpiarBusqueda() {
            document.getElementById('searchForm').reset();
            const filas = document.querySelectorAll('.fila-usuario');
            filas.forEach(fila => fila.style.display = '');
        }

        function descargarPDFFiltrado() {
            // Recoger todos los criterios de búsqueda
            const criterios = {
                id: document.getElementById('buscarId').value,
                nombre: document.getElementById('buscarNombre').value,
                email: document.getElementById('buscarEmail').value,
                telefono: document.getElementById('buscarTelefono').value,
                direccion: document.getElementById('buscarDireccion').value,
                estado: document.getElementById('buscarEstado').value
            };

            // Crear un form temporal para enviar los criterios
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'descargarReportesPDF.php';

            // Añadir los criterios como campos ocultos
            for (const [key, value] of Object.entries(criterios)) {
                if (value) { // solo enviar criterios con valor
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }
            }

            // Añadir un campo para indicar que es una búsqueda filtrada
            const filtroInput = document.createElement('input');
            filtroInput.type = 'hidden';
            filtroInput.name = 'filtrado';
            filtroInput.value = '1';
            form.appendChild(filtroInput);

            // Enviar el form
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</body>
</html>