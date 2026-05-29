<?php
require 'vendor/autoload.php';

$alerta = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $tipo_fallo = $_POST['tipo_fallo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';

    if (!empty($nombre) && !empty($email) && !empty($descripcion)) {
        try {
            $cliente = new MongoDB\Client("mongodb+srv://prueba:FwaAuvVWQbOJiGaq@cluster0.7dy1rur.mongodb.net/?appName=Cluster0");
            $db = $cliente->prueba;
            $coleccionSoporte = $db->soporte; 
            
            $resultado = $coleccionSoporte->insertOne([
                'contacto' => [
                    'nombre' => $nombre,
                    'email' => $email
                ],
                'tipo_fallo' => $tipo_fallo,
                'descripcion' => $descripcion,
                'fecha_registro' => date("Y-m-d H:i:s")
            ]);

            if ($resultado->getInsertedCount() > 0) {
                $alerta = '<div class="alert alert-success shadow-sm">✔️ Reporte de fallo registrado con éxito en tu MongoDB Atlas.</div>';
            }
        } catch (Exception $e) {
            $alerta = '<div class="alert alert-danger shadow-sm">❌ Error al guardar en Atlas: ' . $e->getMessage() . '</div>';
        }
    } else {
        $alerta = '<div class="alert alert-warning shadow-sm">⚠️ Por favor, llena todos los campos obligatorios.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Portal Aprendices - Soporte Técnico</title>
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
                        <a class="nav-link" href="vista.php">📋 Ver Respuestas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="soporte.php">🛠️ Soporte Técnico</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm p-4 mt-4">
            <h2 class="text-center mb-2 text-danger fs-4">Módulo de Soporte Técnico</h2>
            <p class="text-muted text-center mb-4 small">Registra novedades en caso de fallas en el sitio.</p>
            
            <?php echo $alerta; ?>

            <form action="soporte.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" name="nombre" required class="form-control" placeholder="Ej. Carlos Mendoza">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico de Contacto *</label>
                    <input type="email" name="email" required class="form-control" placeholder="carlos@correo.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Fallo</label>
                    <select name="tipo_fallo" class="form-select">
                        <option value="Error de Carga">Error de Conexión / Carga Lenta</option>
                        <option value="Fallo en Formulario">Fallo al registrar datos</option>
                        <option value="Problema Visual">Interfaz / Diseño roto</option>
                        <option value="Otro">Otro problema técnico</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción detallada del fallo *</label>
                    <textarea name="descripcion" required class="form-control" rows="4" placeholder="Escribe qué error sucedió..."></textarea>
                </div>

                <button type="submit" class="btn btn-danger w-100">Enviar Reporte Técnico</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
