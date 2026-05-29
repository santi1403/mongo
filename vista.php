<?php
require 'vendor/autoload.php';

try {
    $cliente = new MongoDB\Client("mongodb+srv://prueba:FwaAuvVWQbOJiGaq@cluster0.7dy1rur.mongodb.net/?appName=Cluster0");
    $db = $cliente->prueba;	
    $coleccionGustos = $db->gustos;	
    
    // CONSULTA PARA EL PROFESOR: Filtro vacío [] para listar todos los documentos
    $registros = $coleccionGustos->find([]); 
} catch (Exception $e) {
    echo "Error al consultar los datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Portal Aprendices - Listado</title>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.html">🎓 Portal Aprendices</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Formulario Registro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="vista.php">📋 Ver Respuestas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="soporte.php">🛠️ Soporte Técnico</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow-sm p-4">
            <h3 class="mb-4 text-secondary">Respuestas de la Comunidad (Desde Mongo Atlas)</h3>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Aprendiz</th>
                            <th>Color</th>
                            <th>Comida</th>
                            <th>Cine/Lit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($registros)): ?>
                            <?php foreach ($registros as $row): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars(($row['apellidos'] ?? '') . ' ' . ($row['nombres'] ?? '')); ?></strong></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row['color'] ?? 'N/A'); ?></span></td>
                                <td><?php echo htmlspecialchars($row['comida'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['pelicula'] ?? 'N/A'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
