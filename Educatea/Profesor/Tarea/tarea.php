<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['usuario']['usuario_id'];

// Consulta para obtener las tareas del usuario
$queryTareas = "SELECT t.*, a.nombre_asignatura
               FROM tareas t
               JOIN asignaturas a ON t.asignatura_id = a.asignatura_id
               WHERE t.usuario_id = ?";
$stmtTareas = $conexion->prepare($queryTareas);
$stmtTareas->bind_param("i", $usuario_id);
$stmtTareas->execute();
$resultadoTareas = $stmtTareas->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas del Usuario</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-4">
        <h1>Tareas del Usuario</h1>
        <?php
        // Verificar si hay tareas para mostrar
        if ($resultadoTareas->num_rows > 0) {
            // Crear la tabla con estilos de Bootstrap
            echo "<table class='table'>";
            echo "<thead class='thead-dark'><th>Fecha de Vencimiento</th><th>Asignatura</th><th>Acciones</th></tr></thead><tbody>";

            // Mostrar una fila por cada tarea
            while ($fila = $resultadoTareas->fetch_assoc()) {
                echo "<tr>";
               
                echo "<td>".$fila["fecha_vencimiento"]."</td>";
                echo "<td>".$fila["nombre_asignatura"]."</td>";
                echo "<td><a class='btn btn-info' href='ver_tarea.php?id=".$fila["tarea_id"]."'>Ver Tarea</a></td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No hay tareas disponibles para mostrar.</p>";
        }
        ?>
        <a href="../alumnos.php" class="btn btn-secondary">Volver a inicio</a>
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
