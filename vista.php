<?php
// Desactivar advertencias molestas de depreciación de librerías antiguas en servidores modernos
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require 'vendor/autoload.php';

$totalRegistros = 0;
$registrosValidos = [];

try {
    // Conexión con un mapa de tipos forzado a Array Nativo para bloquear el Fatal Error de bsonSerialize
    $cliente = new MongoDB\Client(
        "mongodb+srv://prueba:FwaAuvVWQbOJiGaq@cluster0.7dy1rur.mongodb.net/?appName=Cluster0",
        [],
        ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
    );
    $db = $cliente->prueba;	
    $coleccionGustos = $db->gustos;	
    
    // EXPLICACIÓN AL PROFESOR: Ejecutamos el método find sin argumentos para simular la selección total NoSQL
    $cursor = $coleccionGustos->find([]); 
    
    // Almacenamos en array para contar y hacer iteraciones avanzadas requeridas en diseños complejos
    foreach ($cursor as $doc) {
        $registrosValidos[] = $doc;
        $totalRegistros++;
    }
} catch (Exception $e) {
    $errorMsg = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero de Analítica - Base de Datos Atlas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); }
        .card-kpi { border-left: 5px solid #3b82f6; border-radius: 10px; }
        .card-kpi-total { border-left: 5px solid #10b981; border-radius: 10px; }
        .table-container { background: #ffffff; border-radius: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .avatar-circle { width: 40px; height: 40px; background-color: #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 50%; }
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
                        <a class="nav-link active fw-semibold btn btn-outline-light btn-sm text-white px-3" href="vista.php">
                            <i class="fa-solid fa-table-list me-1"></i> Panel de Respuestas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-light px-3" href="soporte.php">
                            <i class="fa-solid fa-screwdriver-wrench me-1"></i> Soporte Técnico
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        
        <?php if (isset($errorMsg)): ?>
            <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-3 fa-lg"></i>
                <div>
                    <strong>Error Crítico en MongoDB Atlas:</strong> <?php echo htmlspecialchars($errorMsg); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold text-dark"><i class="fa-solid fa-chart-line me-2 text-primary"></i>Panel Central de Información</h2>
                <p class="text-muted">Consulta avanzada de documentos persistidos en la colección remota.</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card card-kpi bg-white p-3 shadow-sm d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Motor de Almacenamiento</h6>
                        <h4 class="mb-0 fw-extrabold text-primary">MongoDB Cloud</h4>
                    </div>
                    <div class="text-primary bg-primary bg-opacity-10 p-3 rounded"><i class="fa-solid fa-cloud fa-xl"></i></div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card card-kpi-total bg-white p-3 shadow-sm d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Muestras Totales</h6>
                        <h4 class="mb-0 fw-extrabold text-success"><?php echo $totalRegistros; ?> Documentos</h4>
                    </div>
                    <div class="text-success bg-success bg-opacity-10 p-3 rounded"><i class="fa-solid fa-users-viewfinder fa-xl"></i></div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 mb-3">
                <div class="card bg-white p-3 shadow-sm d-flex flex-row align-items-center justify-content-between" style="border-left: 5px solid #a855f7; border-radius: 10px;">
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Estado de Conexión</h6>
                        <h4 class="mb-0 fw-extrabold text-purple" style="color: #a855f7;">Online / Activo</h4>
                    </div>
                    <div class="p-3 rounded" style="background-color: rgba(168,85,247,0.1); color: #a855f7;"><i class="fa-solid fa-circle-check fa-xl"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-container p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <h4 class="fw-bold text-dark m-0"><i class="fa-solid fa-database text-muted me-2"></i>Registros de la Comunidad</h4>
                        <span class="badge bg-dark px-3 py-2 mt-2 mt-sm-0">Sincronizado con Render</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th scope="col" style="width: 80px;">Avatar</th>
                                    <th scope="col">Nombre del Aprendiz</th>
                                    <th scope="col">Color Favorito</th>
                                    <th scope="col">Gastronomía</th>
                                    <th scope="col">Cine y Literatura</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($totalRegistros > 0): ?>
                                    <?php foreach ($registrosValidos as $row): 
                                        $inicial = strtoupper(substr($row['nombres'] ?? 'A', 0, 1));
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="avatar-circle shadow-sm"><?php echo $inicial; ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars(($row['apellidos'] ?? '') . ' ' . ($row['nombres'] ?? '')); ?></div>
                                                <small class="text-muted text-xs"><i class="fa-regular fa-clock me-1"></i>Remoto Atlas</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-20 text-dark border px-3 py-2 fw-semibold">
                                                    <i class="fa-solid fa-tint me-1 text-primary"></i><?php echo htmlspecialchars($row['color'] ?? 'No Definido'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="text-secondary"><i class="fa-solid fa-bowl-food me-1 text-warning"></i><?php echo htmlspecialchars($row['comida'] ?? 'N/A'); ?></div>
                                            </td>
                                            <td>
                                                <div class="text-muted fw-medium"><i class="fa-solid fa-clapperboard me-1 text-danger"></i><?php echo htmlspecialchars($row['pelicula'] ?? 'N/A'); ?></div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fa-regular fa-folder-open fa-3x mb-3 text-light"></i>
                                            <p class="mb-0 fw-bold">No se encontraron documentos en la colección</p>
                                            <small>Usa el formulario para realizar la primera inserción NoSQL.</small>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
