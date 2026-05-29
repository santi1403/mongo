<?php
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require 'vendor/autoload.php';

$alerta = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tipo_fallo = $_POST['tipo_fallo'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');

    if (!empty($nombre) && !empty($email) && !empty($descripcion)) {
        try {
            // Mapeamos los tipos para evitar colisiones con el driver de Render
            $cliente = new MongoDB\Client(
                "mongodb+srv://prueba:FwaAuvVWQbOJiGaq@cluster0.7dy1rur.mongodb.net/?appName=Cluster0",
                [],
                ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
            );
            $db = $cliente->prueba;
            
            // Creación dinámica de la colección requerida por el docente
            $coleccionSoporte = $db->soporte; 
            
            // ESTRUCTURA EMBEBIDA DE MONGO: Guardamos el contacto dentro de un objeto interno
            $resultado = $coleccionSoporte->insertOne([
                'contacto' => [
                    'nombre' => $nombre,
                    'email' => $email
                ],
                'tipo_fallo' => $tipo_fallo,
                'descripcion' => $descripcion,
                'estado_ticket' => 'Abierto',
                'fecha_registro' => date("Y-m-d H:i:s")
            ]);

            if ($resultado->getInsertedCount() > 0) {
                $alerta = '
                <div class="alert alert-success alert-dismissible fade show shadow border-0" role="alert">
                    <i class="fa-solid fa-circle-check me-2 fa-lg"></i>
                    <strong>¡Reporte Guardado!</strong> El incidente técnico se ha inyectado con el ID: ' . $resultado->getInsertedId() . ' directamente en el clúster Atlas.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
            }
        } catch (Exception $e) {
            $alerta = '
            <div class="alert alert-danger alert-dismissible fade show shadow border-0" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2 fa-lg"></i>
                <strong>Error en la persistencia:</strong> ' . htmlspecialchars($e->getMessage()) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
    } else {
        $alerta = '
        <div class="alert alert-warning alert-dismissible fade show shadow border-0" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2 fa-lg"></i>
            <strong>Campos Incompletos:</strong> Por favor rellene toda la información requerida.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Soporte - Tickets Técnicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); }
        .card-custom { border-radius: 15px; border: none; }
        .btn-danger-custom { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: white; border: none; }
        .btn-danger-custom:hover { background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%); color: white; }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.html">
                <i class="fa-solid fa-graduation-cap me-2 text-info"></i>PORTAL APRENDICES
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="topNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item me-2">
                        <a class="nav-link fw-semibold text-light px-3" href="index.html">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Registrar Gustos
                        </a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="nav-link fw-semibold text-light px-3" href="vista.php">
                            <i class="fa-solid fa-table-list me-1"></i> Panel de Respuestas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold btn btn-outline-light btn-sm text-white px-3" href="soporte.php">
                            <i class="fa-solid fa-screwdriver-wrench me-1"></i> Soporte Técnico
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4" style="max-width: 750px;">
        
        <?php echo $alerta; ?>

        <div class="card card-custom bg-white shadow p-4 p-md-5 mt-3">
            <div class="text-center mb-4">
                <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle d-inline-block mb-3">
                    <i class="fa-solid fa-headset fa-2x"></i>
                </div>
                <h3 class="fw-bold text-dark">MÓDULO DE SOPORTE TÉCNICO</h3>
                <p class="text-muted small">Registra novedades de fallas encontradas en el sitio directo a la base de datos.</p>
            </div>

            <form action="soporte.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-secondary">Nombre de Contacto *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-user-gear"></i></span>
                            <input type="text" name="nombre" required class="form-control" placeholder="Ej. Carlos Mendoza">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-secondary">Correo Electrónico *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" required class="form-control" placeholder="nombre@correo.com">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary">Categoría del Incidente Técnico *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-layer-group"></i></span>
                        <select name="tipo_fallo" class="form-select">
                            <option value="Error de Carga">Latencia de Red / Carga Intermitente de Datos</option>
                            <option value="Fallo en Formulario">Fallo Funcional al procesar peticiones POST</option>
                            <option value="Problema Visual">Defectos Visuales / Maquetación de Interfaz</option>
                            <option value="Otro">Otro problema no catalogado</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary">Descripción Detallada del Fallo *</label>
                    <textarea name="descripcion" required class="form-control" rows="5" placeholder="Describa el paso a paso del error visualizado en el despliegue..."></textarea>
                </div>

                <button type="submit" class="btn btn-danger-custom btn-lg w-100 fw-bold shadow-sm">
                    <i class="fa-solid fa-ticket me-2"></i>Emitir Ticket e Inyectar en Atlas
                </button>
            </form>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0 small text-white-50">© 2026 Portal de Aprendices - Práctica Avanzada de Despliegue de Aplicaciones NoSQL.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
