<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../negocio/nUsuario.php';
// Cargar TCPDF
require_once __DIR__ . '/../../tcpdf/tcpdf.php';
verificarAutenticacion();
$rootPath = '../';

// Establecer zona horaria y limpiar cualquier salida previa
date_default_timezone_set('America/Bogota');
ob_end_clean(); // Usar ob_end_clean en lugar de ob_get_clean
class MYPDF extends TCPDF {
    public function Header() {
        // cabecera personalizada
        $bMargin = $this->getBreakMargin();
        $autoPageBreak = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false);
        $img_file = 'Captura.png';
        $this->Image($img_file, 85, 8, 30, 25, '', 'PNG', '', '', false, 30, '', false, false, 0, false, false, false);
        $this->SetAutoPageBreak($autoPageBreak, $bMargin);
        $this->setPageMark();
    }
}

    // Crear PDF con TCPDF (usando clase personalizada)
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetTitle('Reportes Parametrizados - Usuarios');
    // Usar DejaVu Sans para mejor soporte UTF-8

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetMargins(15, 30, 15);
    $pdf->SetHeaderMargin(10);
    $pdf->setPrintFooter(false);
    $pdf->setPrintHeader(true);
    $pdf->SetAutoPageBreak(TRUE, 20);
    $pdf->AddPage();

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetXY(15, 20);
    $pdf->Write(0, 'Reporte de Muebles');
    $pdf->SetXY(150, 20);
    $pdf->Write(0, 'Fecha: '.date('d/m/Y') .' Hora: '.date('H:i:s'));

    $pdf->Ln(18);

    // Título antes de la tabla
    $pdf->SetFont('helvetica', 'B', 15);
    $pdf->SetTextColor(34,68,136);
    $pdf->Cell(0, 8, 'Lista de muebles', 0, 1, 'C');
    $pdf->Ln(4);
    $pdf->SetTextColor(0,0,0);

    // Armado de la tabla (encabezados)
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetFillColor(232,232,232);
    $pdf->Cell(20, 8, 'Código', 1, 0,'C',1);
    $pdf->Cell(40, 8, 'Nombre', 1, 0,'C',1);
    $pdf->Cell(50, 8, 'email', 1, 0,'C',1);
    $pdf->Cell(35, 8, 'telefono', 1, 0,'C',1);
    $pdf->Cell(35, 8, 'direccion', 1, 1,'C',1);

    $pdf->SetFont('helvetica', '', 10);
    // Preparar filtros
    $filtros = array();
    if (isset($_POST['filtrado']) && $_POST['filtrado'] == '1') {
        if (!empty($_POST['id'])) $filtros['id'] = $_POST['id'];
        if (!empty($_POST['nombre'])) $filtros['nombre'] = $_POST['nombre'];
        if (!empty($_POST['email'])) $filtros['email'] = $_POST['email'];
        if (!empty($_POST['telefono'])) $filtros['telefono'] = $_POST['telefono'];
        if (!empty($_POST['direccion'])) $filtros['direccion'] = $_POST['direccion'];
        if (!empty($_POST['estado'])) $filtros['estado'] = $_POST['estado'];
    }
    // Obtener datos filtrados
    $usu = new nUsuario();
    // Obtener lista de usuarios
    $listarUsuarios = $usu->listarUsuarios();
// Filtrar usuarios según criterios
    $usuariosFiltrados = array();  
    
    foreach ($listarUsuarios as $row) {
        $incluir = true;
        
        // Aplicar cada filtro
        if (!empty($filtros['id']) && stripos($row['idUsuario'], $filtros['id']) === false) {
            $incluir = false;
        }
        if (!empty($filtros['nombre']) && stripos($row['nombre'], $filtros['nombre']) === false) {
            $incluir = false;
        }
        if (!empty($filtros['email']) && stripos($row['email'], $filtros['email']) === false) {
            $incluir = false;
        }
        if (!empty($filtros['telefono']) && stripos($row['telefono'], $filtros['telefono']) === false) {
            $incluir = false;
        }
        if (!empty($filtros['direccion']) && stripos($row['direccion'], $filtros['direccion']) === false) {
            $incluir = false;
        }
        if (isset($filtros['estado'])) {
            if ($filtros['estado'] == '1' && !$row['activo']) {
                $incluir = false;
            } elseif ($filtros['estado'] == '0' && $row['activo']) {
                $incluir = false;
            }
        }
        
        if ($incluir) {
            $usuariosFiltrados[] = $row;
        }
    }
    
    // Usar los resultados filtrados
    $listarUsuarios = $usuariosFiltrados;

    if (!empty($listarUsuarios)) {
        foreach ($listarUsuarios as $dataRows) {
            $pdf->Cell(20, 6, $dataRows['id'], 1, 0, 'C');
            $pdf->Cell(40, 6, $dataRows['nombre'], 1, 0, 'L');
            $pdf->Cell(50, 6, $dataRows['email'], 1, 0, 'C');
            $pdf->Cell(35, 6, $dataRows['telefono'], 1, 0, 'C');
            $pdf->Cell(35, 6, $dataRows['direccion'], 1, 1, 'C');
        }
    } else {
        // Si no hay datos, mostrar un mensaje en el PDF
        $pdf->Cell(0, 8, 'No se encontraron usuarios para mostrar.', 1, 1, 'C');
    }
    

    $pdf->Output('reporteUsuarios.pdf', 'I');
?>