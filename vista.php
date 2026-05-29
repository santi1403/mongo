<?php
require 'vendor/autoload.php'; // Cargar Composer

try {
    // Usamos tu misma cadena de conexión exacta
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
    <title>Portal Aprendices - Inicio</title>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">🎓 Portal Aprendices</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Inicio / Registro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="soporte.php">🛠️ Soporte Técnico</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm p-4">
                    <h3 class="text-center mb-4 text-primary">REGISTRO DE GUSTOS</h3>
                    <form action="logica.php" method="post">
                        <div class="mb-3">
                            <label class="form-label">Apellidos:</label>
                            <input type="text" required maxlength="200" name="apellidos" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombres:</label>
                            <input type="text" required maxlength="200" name="nombres" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color favorito:</label>
                            <input type="text" required maxlength="200" name="color" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comida favorita:</label>
                            <input type="text" required maxlength="200" name="comida" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de literatura y cine:</label>
                            <input type="text" required maxlength="200" name="pelicula" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registrar mi respuesta</button>
                    </form>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm p-4">
                    <h3 class="mb-4 text-secondary">Respuestas de la Comunidad</h3>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
