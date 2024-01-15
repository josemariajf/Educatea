<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Obtener el ID de la asignatura desde la URL
$asignatura_id = isset($_GET['asignatura_id']) ? $_GET['asignatura_id'] : null;

// Verificar si se proporcionó el ID de la asignatura
if ($asignatura_id === null) {
    echo "Error: No se proporcionó el ID de la asignatura.";
    exit();
}

// Consulta preparada para obtener las tareas de la asignatura
$sql = "SELECT * FROM tareas WHERE asignatura_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $asignatura_id);

// Verificar si la consulta preparada se ejecuta correctamente
if ($stmt->execute()) {
    $resultadoTareasAsignatura = $stmt->get_result();

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tareas de Asignatura</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <div class="jumbotron bg-primary text-center text-white">
    <img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
</div>
        <div class="container mt-4">
            <h1>Tareas de Asignatura</h1>
            <?php
            if ($resultadoTareasAsignatura->num_rows > 0) {
                echo "<table class='table'>";
                echo "<thead class='thead-dark'><tr><th>ID</th><th>Descripción de la Tarea</th><th>Fecha de Vencimiento</th><th>Acciones</th></tr></thead><tbody>";

               // ...

            while ($filaTarea = $resultadoTareasAsignatura->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$filaTarea["tarea_id"]."</td>";
                echo "<td>".$filaTarea["descripcion_tarea"]."</td>";
                echo "<td>".$filaTarea["fecha_vencimiento"]."</td>";
            
                echo "<td><a href='entregar_tarea.php?tarea_id=".$filaTarea["tarea_id"]."' class='btn btn-primary'>Entregar</a></td>";

                echo "</tr>";
            }

// ...


                echo "</tbody></table>";
            } else {
                echo "<p>No hay tareas disponibles para mostrar.</p>";
            }
            ?>
            <a href="../Asignatura_alum.php" class="btn btn-secondary">Volver a Asignaturas</a>
        </div>

            <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>


        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>

    <?php
} else {
    echo "Error al ejecutar la consulta: " . $stmt->error;
}

$stmt->close();
?>
