<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['nombre_rol'];

// Verificar si el usuario tiene permisos de administrador
if ($rol !== 'director') {
    header('Location: ../../index.php');
    exit;
}

// Mensajes de éxito o error después de procesar el formulario
$mensaje = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_clase = $_POST['nombre_clase'];
    $curso = $_POST['curso'];

    // Validar datos
    if (empty($nombre_clase) || empty($curso)) {
        $mensaje = 'Todos los campos son obligatorios.';
    } elseif (!preg_match('/^\d{4}\/\d{4}$/', $curso)) {
        $mensaje = 'El formato del campo Curso debe ser "año/añosiguiente".';
    } else {
        // Llamar a la función para agregar la clase
        agregarClase($nombre_clase, $curso);
        $mensaje = 'Clase creada exitosamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Clase - Educatea</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/bootstrap-4.0.0-dist/js/bootstrap.min.js">
</head>
<script>
        // JavaScript para llenar automáticamente el campo del año con el año actual y siguiente
        document.addEventListener("DOMContentLoaded", function() {
            const cursoInput = document.getElementById("curso");
            const year = new Date().getFullYear();
            cursoInput.value = `${year}/${year + 1}`;
        });
    </script>
<div class="jumbotron bg-primary text-white text-center">
            <h1 class="display-4">Educatea</h1>
        </div>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mt-4">
            <div class="card-body">
                <h1 class="mb-4">Crear Nueva Clase</h1>

                <!-- Mostrar mensajes de éxito o error -->
                <?php if (!empty($mensaje)) : ?>
                    <div class="alert alert-<?php echo $mensaje == 'Clase creada exitosamente.' ? 'success' : 'danger'; ?>" role="alert">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <!-- Formulario para crear una nueva clase -->
                <form method="post" action="" class="mb-4">
                    <div class="form-group">
                        <label for="nombre_clase">Nombre de la Clase:</label>
                        <input type="text" id="nombre_clase" name="nombre_clase" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="curso">Curso:</label>
                        <input type="text" id="curso" name="curso" pattern="\d{4}/\d{4}"  readonly required title="El formato debe ser año/añosiguiente" class="form-control">
                    </div>

                    <button type="submit" name="crear_clase" class="btn btn-primary">Crear Clase</button>
                </form>

                <a href="gestionar_clases.php" class="btn btn-link">Volver a gestionar clase</a>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
