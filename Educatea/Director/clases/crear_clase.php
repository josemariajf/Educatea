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
    <title>Crear Clase</title>
    <link rel="stylesheet" href="../css/gestion.css">
    <script>
        // JavaScript para llenar automáticamente el campo del año con el año actual y siguiente
        document.addEventListener("DOMContentLoaded", function() {
            const cursoInput = document.getElementById("curso");
            const year = new Date().getFullYear();
            cursoInput.value = `${year}/${year + 1}`;
        });
    </script>
</head>

<body>
    <h1>Crear Nueva Clase</h1>

    <!-- Mostrar mensajes de éxito o error -->
    <?php if (!empty($mensaje)) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <!-- Formulario para crear una nueva clase -->
    <form method="post" action="">
        <label for="nombre_clase">Nombre de la Clase:</label>
        <input type="text" id="nombre_clase" name="nombre_clase" required>

        <label for="curso">Curso:</label>
        <input type="text" id="curso" name="curso" pattern="\d{4}/\d{4}" readonly required title="El formato debe ser año/añosiguiente">

        <button type="submit" name="crear_clase">Crear Clase</button>
    </form>

    <a href="gestionar_clases.php">Volver a gestionar clase</a>
</body>

</html>
