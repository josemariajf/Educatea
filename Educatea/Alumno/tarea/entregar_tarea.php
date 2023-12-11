<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();
$usuario = $_SESSION["usuario"];

// Obtener el ID de la tarea desde la URL
$tarea_id = isset($_GET['tarea_id']) ? $_GET['tarea_id'] : null;

// Verificar si se proporcionó el ID de la tarea
if ($tarea_id === null) {
    echo "<div class='alert alert-danger' role='alert'>Error: No se proporcionó el ID de la tarea.</div>";
    exit();
}

// Verificar si el usuario ya entregó la tarea
$sqlVerificarEntrega = "SELECT * FROM tareas_usuarios WHERE tarea_id = ? AND usuario_id = ?";
$stmtVerificarEntrega = $conexion->prepare($sqlVerificarEntrega);
$stmtVerificarEntrega->bind_param("ii", $tarea_id, $_SESSION['usuario']);
$stmtVerificarEntrega->execute();
$resultadoVerificarEntrega = $stmtVerificarEntrega->get_result();

if ($resultadoVerificarEntrega->num_rows > 0) {
    // El usuario ya entregó la tarea, permitir actualizarla
    $actualizarTarea = true;
    $filaEntrega = $resultadoVerificarEntrega->fetch_assoc();
    $urlTareaAnterior = $filaEntrega["url"];
    $calificacionTarea = $filaEntrega["calificacion"];
} else {
    // El usuario no ha entregado la tarea, proceder normalmente
    $actualizarTarea = false;
    $calificacionTarea = null;
}

$stmtVerificarEntrega->close();

// Procesar el formulario de carga de archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    // Procesar el archivo
    $nombreArchivo = $_FILES['archivo']['name'];
    $rutaTemporal = $_FILES['archivo']['tmp_name'];
    $rutaDestino = "../../Tarea/" . $nombreArchivo;

    // Mover el archivo a la ruta de destino
    move_uploaded_file($rutaTemporal, $rutaDestino);

    // Obtener la fecha actual
    $fechaEntrega = date("Y-m-d");

    // Guardar la información del archivo en la base de datos
    $calificacion = 0;

    if ($actualizarTarea) {
        // Actualizar la tarea si ya ha sido entregada anteriormente
        $sqlUpdate = "UPDATE tareas_usuarios SET url = ?, fecha_entrega = ? WHERE tarea_id = ? AND usuario_id = ?";
        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ssii", $rutaDestino, $fechaEntrega, $tarea_id, $_SESSION['usuario']);

        if ($stmtUpdate->execute()) {
            echo "<div class='alert alert-success' role='alert'>Archivo actualizado con éxito.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error al actualizar el archivo: " . $stmtUpdate->error . "</div>";
        }

        $stmtUpdate->close();
    } else {
        // Insertar en la tabla tareas_usuarios si es la primera entrega
        $sqlInsert = "INSERT INTO tareas_usuarios (tarea_id, usuario_id, calificacion, url, fecha_entrega) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $conexion->prepare($sqlInsert);
        $stmtInsert->bind_param("iiiss", $tarea_id, $_SESSION['usuario'], $calificacion, $rutaDestino, $fechaEntrega);

        if ($stmtInsert->execute()) {
            echo "<div class='alert alert-success' role='alert'>Archivo cargado y registrado con éxito.</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error al registrar el archivo en la base de datos: " . $stmtInsert->error . "</div>";
        }

        $stmtInsert->close();
    }
}

// Consulta preparada para obtener los detalles de la tarea
$sqlTarea = "SELECT * FROM tareas WHERE tarea_id = ?";
$stmtTarea = $conexion->prepare($sqlTarea);
$stmtTarea->bind_param("i", $tarea_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Tarea</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-4">
        <?php

        // Verificar si la consulta preparada se ejecuta correctamente
        if ($stmtTarea->execute()) {
            $resultadoTarea = $stmtTarea->get_result();

            // Verificar si se encontró la tarea
            if ($resultadoTarea->num_rows > 0) {
                $filaTarea = $resultadoTarea->fetch_assoc();

                // Obtener el ID de la asignatura asociada a la tarea
                $asignatura_id = $filaTarea["asignatura_id"];

                // Obtener la fecha de entrega
                $fechaEntrega = $filaTarea["fecha_vencimiento"];

                // Mostrar los detalles de la tarea utilizando Bootstrap
                echo "<div class='card'>";
                echo "<div class='card-body'>";
                echo "<h1 class='card-title'>Detalles de la Tarea</h1>";
                echo "<p class='card-text'><strong>Descripción:</strong> " . $filaTarea["descripcion_tarea"] . "</p>";
                echo "<p class='card-text'><strong>Fecha de Vencimiento:</strong> " . $filaTarea["fecha_vencimiento"] . "</p>";

                // Formulario de carga de archivos
                echo "<form method='post' enctype='multipart/form-data'>";
                echo "<div class='form-group'>";
                echo "<label for='archivo'>Seleccione un archivo:</label>";
                echo "<input type='file' class='form-control-file' name='archivo' id='archivo' required>";
                echo "</div>";

                echo "<div class='form-group'>";
                echo "<label for='fecha_entrega'>Fecha de Entrega:</label>";
                echo "<input type='text' class='form-control' name='fecha_entrega' id='fecha_entrega' value='$fechaEntrega' readonly>";
                echo "</div>";

                // Mostrar la calificación si existe
                if ($calificacionTarea !== null) {
                    echo "<p class='card-text'><strong>Calificación:</strong> $calificacionTarea</p>";
                }

                echo "<button type='submit' class='btn btn-primary'>Entregar</button>";
                echo "</form>";

                if ($actualizarTarea) {
                    echo "<div class='alert alert-info mt-3' role='alert'>Ya has entregado esta tarea. Puedes actualizarla.</div>";
                }

                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning' role='alert'>No se encontró la tarea.</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error al ejecutar la consulta: " . $stmtTarea->error . "</div>";
        }

        $stmtTarea->close();
        $conexion->close();
        ?>
         <a href="../Asignatura_alum.php" class="btn btn-secondary">Volver a asignaturas</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   
</body>
</html>
