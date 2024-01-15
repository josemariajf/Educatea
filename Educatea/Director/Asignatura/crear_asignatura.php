<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario tiene acceso

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_asignatura = $_POST['nombre_asignatura'];
    $codigo_asignatura = $_POST['codigo_asignatura'];

    // Realizar la inserción en la base de datos utilizando consultas preparadas
    $queryInsertar = "INSERT INTO asignaturas (nombre_asignatura, codigo_asignatura) VALUES (?, ?)";
    $stmt = $conexion->prepare($queryInsertar);
    $stmt->bind_param("ss", $nombre_asignatura, $codigo_asignatura);
    $stmt->execute();
    $stmt->close();

    // Redirigir a la página de gestión de asignaturas
    header("Location: gestionar_asignatura.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Asignatura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/bootstrap-4.0.0-dist/js/bootstrap.min.js">
</head>

<body class="bg-light">
<div class="jumbotron bg-primary text-center text-white">
<img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4">Crear Nueva Asignatura</h2>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="mb-4">
            <div class="form-group">
                <label for="nombre_asignatura">Nombre Asignatura:</label>
                <input type="text" name="nombre_asignatura" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="codigo_asignatura">Código Asignatura:</label>
                <input type="text" name="codigo_asignatura" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Crear Asignatura</button>
        </form>

        <a href="gestionar_asignatura.php" class="btn btn-link">Volver a la Gestión de Asignaturas</a>
    </div>
    

        <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>


    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
