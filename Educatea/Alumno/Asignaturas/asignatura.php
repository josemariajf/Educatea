<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

if (isset($_GET['id'])) {
    $id_asignatura = $_GET['id'];

    $sql_asignatura = "SELECT * FROM asignaturas WHERE asignatura_id = $id_asignatura";
    $resultado_asignatura = $conexion->query($sql_asignatura);

    if ($fila_asignatura = $resultado_asignatura->fetch_assoc()) {
        $nombre_asignatura = $fila_asignatura["nombre_asignatura"];
        $descripcion_asignatura = $fila_asignatura["codigo_asignatura"];

        $usuario_id = $_SESSION['usuario']['usuario_id'];
        $sql_tareas_alumno = "SELECT t.*, tu.fecha_entrega, tu.calificacion
                              FROM tareas t
                              LEFT JOIN tareas_usuarios tu ON t.tarea_id = tu.tarea_id AND tu.usuario_id = $usuario_id
                              WHERE t.asignatura_id = $id_asignatura";
        $resultado_tareas_alumno = $conexion->query($sql_tareas_alumno);
    
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Detalles de la Asignatura</title>
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="jumbotron bg-primary text-center text-white">
                <h1 class="display-4">Educatea</h1>
            </div>
            <div class="container mt-4">
                <?php
                if ($resultado_tareas_alumno->num_rows > 0) {
                    echo "<table class='table'>";
                    echo "<thead class='thead-dark'><tr><th>Descripción</th><th>Fecha de Vencimiento</th><th>Fecha de Entrega</th><th>Calificación</th></tr></thead><tbody>";

                    while ($fila_tarea = $resultado_tareas_alumno->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$fila_tarea["descripcion_tarea"]."</td>";
                        echo "<td>".$fila_tarea["fecha_vencimiento"]."</td>";
                        echo "<td>".($fila_tarea["fecha_entrega"] ?: "No entregado")."</td>";
                        echo "<td>".($fila_tarea["calificacion"] ?: "No calificado")."</td>";
                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<p>No hay tareas disponibles para mostrar.</p>";
                }
                ?>

                <!-- Formulario para agregar una nueva tarea -->
                <form action="../tarea/procesar_tarea.php?id=<?php echo $id_asignatura; ?>" method="post" enctype="multipart/form-data">
                    <h2>Entregar Tarea</h2>
                    <div class="form-group">
                        <label for="archivo">Archivo:</label>
                        <input type="file" class="form-control" name="archivo" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                </form>

                <a href="../Asignatura_alum.php " class="btn btn-secondary mt-3">Volver a la lista de asignaturas</a>
            </div>

            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>
        </html>

        <?php
    } else {
        echo "No se encontraron detalles de la asignatura.";
    }
} else {
    echo "ID de asignatura no válido.";
}
?>
