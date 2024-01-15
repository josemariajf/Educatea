<?php
require_once "../../funciones.php";
$conexion = conexion();

// Inicializar variables
$asignatura_id = $nombre_asignatura = $codigo_asignatura = '';

// Verificar si se proporciona un ID de asignatura
if (isset($_GET['id'])) {
    $asignatura_id = $_GET['id'];

    // Obtener información de la asignatura desde la tabla asignaturas
    $queryAsignatura = "SELECT nombre_asignatura, codigo_asignatura FROM asignaturas WHERE asignatura_id = ?";
    $stmt = $conexion->prepare($queryAsignatura);
    $stmt->bind_param("i", $asignatura_id);
    $stmt->execute();
    $stmt->bind_result($nombre_asignatura, $codigo_asignatura);

    // Verificar si se encontró la asignatura
    if (!$stmt->fetch()) {
        echo "No se encontró la asignatura.";
        exit;
    }

    $stmt->close();
}

// Procesar formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_asignatura'])) {
    $nombre_asignatura_nuevo = $_POST['nombre_asignatura'];
    $codigo_asignatura_nuevo = $_POST['codigo_asignatura'];

    // Realizar la actualización en la tabla asignaturas
    $queryActualizarAsignatura = "UPDATE asignaturas SET nombre_asignatura = ?, codigo_asignatura = ? WHERE asignatura_id = ?";
    $stmtActualizar = $conexion->prepare($queryActualizarAsignatura);
    $stmtActualizar->bind_param("ssi", $nombre_asignatura_nuevo, $codigo_asignatura_nuevo, $asignatura_id);
    $stmtActualizar->execute();
    $stmtActualizar->close();

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
    <title>Editar Asignatura</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">
<img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
<div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4">Editar Asignatura</h2>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $asignatura_id; ?>" class="mb-4">
            <div class="form-group">
                <label for="nombre_asignatura">Nombre de la Asignatura:</label>
                <input type="text" name="nombre_asignatura" value="<?php echo $nombre_asignatura; ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="codigo_asignatura">Código de la Asignatura:</label>
                <input type="text" name="codigo_asignatura" value="<?php echo $codigo_asignatura; ?>" class="form-control" required>
            </div>

            <button type="submit" name="editar_asignatura" class="btn btn-primary">Guardar Cambios</button>
        </form>

        <a href="gestionar_asignatura.php" class="btn btn-secondary">Volver a la Gestión de Asignaturas</a>
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
