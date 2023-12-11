<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Obtener el ID de la tarea desde la URL
$tarea_id = isset($_GET['id']) ? $_GET['id'] : null;

// Verificar si se proporcionó el ID de la tarea
if ($tarea_id === null) {
    echo "<div class='alert alert-danger' role='alert'>Error: No se proporcionó el ID de la tarea.</div>";
    exit();
}

// Consulta preparada para obtener los detalles de la tarea
$sqlTarea = "SELECT * FROM tareas WHERE tarea_id = ?";
$stmtTarea = $conexion->prepare($sqlTarea);
$stmtTarea->bind_param("i", $tarea_id);

// Verificar si la consulta preparada se ejecuta correctamente
if ($stmtTarea->execute()) {
    $resultadoTarea = $stmtTarea->get_result();

    // Verificar si se encontró la tarea
    if ($resultadoTarea->num_rows > 0) {
        $filaTarea = $resultadoTarea->fetch_assoc();

        // Obtener el ID de la asignatura asociada a la tarea
        $asignatura_id = $filaTarea["asignatura_id"];

        // Consulta preparada para obtener la calificación del usuario para esa tarea
        $sqlCalificacion = "SELECT calificacion FROM tareas_usuarios WHERE tarea_id = ? AND usuario_id = ?";
        $stmtCalificacion = $conexion->prepare($sqlCalificacion);
        $stmtCalificacion->bind_param("ii", $tarea_id, $_SESSION['usuario']);
        $stmtCalificacion->execute();
        $resultadoCalificacion = $stmtCalificacion->get_result();

        // Verificar si se encontró la calificación
        $calificacion = $resultadoCalificacion->num_rows > 0 ? $resultadoCalificacion->fetch_assoc()['calificacion'] : null;
    } else {
        echo "<div class='alert alert-warning' role='alert'>No se encontró la tarea.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Error al ejecutar la consulta: " . $stmtTarea->error . "</div>";
    exit();
}

$stmtTarea->close();
$stmtCalificacion->close();
$conexion->close();
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
        <div class='card'>
            <div class='card-body'>
                <h1 class='card-title'>Detalles de la Tarea</h1>
               
                <p class='card-text'><strong>Descripción:</strong> <?php echo $filaTarea["descripcion_tarea"]; ?></p>
                <p class='card-text'><strong>Fecha de Vencimiento:</strong> <?php echo $filaTarea["fecha_vencimiento"]; ?></p>
                <?php if ($calificacion !== null): ?>
                    <p class="card-text"><strong>Calificación:</strong> <?php echo $calificacion; ?></p>
                <?php endif; ?>
                <!-- Descargar el archivo y calificar la nota -->
                <a href='descarga.php?tarea_id=<?php echo $tarea_id; ?>' class='btn btn-primary'>Descargar Archivo</a>
                <button class='btn btn-success' data-toggle='modal' data-target='#calificarModal'>Calificar</button>

               
            </div>
            <a href="tarea.php" class="btn btn-secondary">Volver a Asignaturas</a>
        </div>
    
    </div>
  
    <!-- Modal para calificar la nota -->
    <div class='modal fade' id='calificarModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLabel'>Calificar Tarea</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
               
                <div class='modal-body'>
                    <form method='post' action='calificar.php'>
                        <input type='hidden' name='tarea_id' value='<?php echo $tarea_id; ?>'>
                        <div class='form-group'>
                            <label for='nota'>Nota:</label>
                            <input type='number' class='form-control' min=0 max=10   name='nota' id='nota' required>
                        </div>
                        <button type='submit' class='btn btn-primary'>Calificar</button>
                    </form>
                    
                </div>
               
            </div>
           
        </div>
      
    </div> 

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   
</body>
</html>
