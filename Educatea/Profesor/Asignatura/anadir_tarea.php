<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

if (isset($_GET["asignatura_id"])&& $_GET["asignatura_id"] != null){ 
$_SESSION["asignatura_id"] = $_GET["asignatura_id"];
}

if (isset($_GET["clase_id"])&& $_GET["clase_id"] != null){ 
    $_SESSION["clase_id"] = $_GET["clase_id"];
    }

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'profesor') {
    header('Location: ../index.php'); // Redirigir si no es un profesor
    exit;
}

// Obtener la lista de asignaturas para el profesor
$profesor_id = $_SESSION['usuario']['usuario_id'];

// Verificar si se ha enviado el formulario de crear tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion_tarea = $_POST['descripcion_tarea'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $asignatura_id = intval($_SESSION["asignatura_id"]) ;
    $clase_id = intval($_SESSION["clase_id"]);

    // Realizar validaciones adicionales según sea necesario
    if (empty($descripcion_tarea) || empty($fecha_vencimiento) || $asignatura_id === null) {
        $error_message = "Todos los campos son obligatorios.";
    } elseif (strtotime($fecha_vencimiento) < time()) {
        $error_message = "La fecha de vencimiento debe ser en el futuro.";
    } else {
        // Verificar la existencia de la asignatura
        $queryVerificarAsignatura = "SELECT COUNT(*) AS total FROM asignaturas WHERE asignatura_id = ?";
        $stmtVerificarAsignatura = $conexion->prepare($queryVerificarAsignatura);
        $stmtVerificarAsignatura->bind_param("i", $asignatura_id);
        $stmtVerificarAsignatura->execute();
        $resultVerificarAsignatura = $stmtVerificarAsignatura->get_result();
        $rowVerificarAsignatura = $resultVerificarAsignatura->fetch_assoc();

        if ($rowVerificarAsignatura['total'] == 0) {
            // La asignatura no existe, mostrar un mensaje de error
            $error_message = "La asignatura no existe.";
        } else {
            // Insertar la tarea en la tabla de tareas utilizando una sentencia preparada
            $queryInsertTarea = "INSERT INTO tareas (descripcion_tarea, fecha_vencimiento, usuario_id, clase_id ,asignatura_id) VALUES (?, ?, ?, ?, ?)";
            $stmtInsertTarea = $conexion->prepare($queryInsertTarea);
            $stmtInsertTarea->bind_param("ssiii", $descripcion_tarea, $fecha_vencimiento, $profesor_id, $clase_id ,$asignatura_id);
            $stmtInsertTarea->execute();

            // Verificar si se pudo realizar la inserción
            if ($stmtInsertTarea->affected_rows > 0) {
                // Redirigir con mensaje de éxito
                header("Location: anadir_tarea.php?success=1");
                exit();
            } else {
                // Mostrar mensaje de error si la inserción falló
                $error_message = "Error al crear la tarea: " . $stmtInsertTarea->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Tarea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Agregar tu estilo CSS aquí -->
    <style>
        /* Estilo CSS personalizado */
        .error-message {
            color: red;
        }
    </style>
</head>

<body>
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>

    <div class="container">
        <h2 class="mb-4">Crear Tarea</h2>

        <?php
        // Mostrar mensaje de éxito si se creó la tarea
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo "<div class='alert alert-success' role='alert'>
                    Tarea creada exitosamente.
                  </div>";
        }

        // Mostrar mensaje de error si hubo un problema
        if (isset($error_message)) {
            echo "<div class='alert alert-danger' role='alert'>
                    $error_message
                  </div>";
        }
        ?>

        <form method="post" action="anadir_tarea.php">
            <div class="form-group">
                <label for="descripcion_tarea">Descripción de la tarea:</label>
                <textarea class="form-control" id="descripcion_tarea" name="descripcion_tarea" required></textarea>
            </div>
            <div class="form-group">
                <label for="fecha_vencimiento">Fecha de vencimiento:</label>
                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
            </div>
           
         

            <button type="submit" class="btn btn-primary">Crear Tarea</button>
        </form>

        <a href="Asignatura.php" class="btn btn-link mt-3">Volver a inicio</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Obtener la fecha actual en formato ISO (YYYY-MM-DD)
            var fechaActual = new Date().toISOString().split('T')[0];

            // Establecer la fecha actual como el valor predeterminado de fecha de vencimiento
            document.getElementById("fecha_vencimiento").value = fechaActual;

            // Configurar el atributo 'min' para evitar fechas anteriores a la actual
            document.getElementById("fecha_vencimiento").setAttribute("min", fechaActual);
        });
    </script>
</body>

</html>
