<?php
session_start();
require_once "../../funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['usuario']['usuario_id'];

// Consulta para obtener las clases del alumno
$queryClasesAlumno = "SELECT clase_id FROM clases_usuarios WHERE usuario_id = ?";
$stmtClasesAlumno = $conexion->prepare($queryClasesAlumno);
$stmtClasesAlumno->bind_param("i", $usuario_id);
$stmtClasesAlumno->execute();
$resultadoClasesAlumno = $stmtClasesAlumno->get_result();

// Verificar si se pudo realizar la consulta
if (!$resultadoClasesAlumno) {
    echo "Error al realizar la consulta de clases del alumno: " . mysqli_error($conexion);
    exit();
}

// Obtener los ID de las clases a las que pertenece el alumno
$clasesAlumno = [];
while ($filaClase = $resultadoClasesAlumno->fetch_assoc()) {
    $clasesAlumno[] = $filaClase['clase_id'];
}

// Verificar si hay clases antes de hacer la consulta de asignaturas
if (!empty($clasesAlumno)) {
    // Construir la lista de marcadores de posición para IN
    $inList = implode(',', array_fill(0, count($clasesAlumno), '?'));

    // Consulta para obtener la información de las asignaturas que están en las clases del alumno
    $queryAsignaturas = "SELECT a.*
                        FROM asignaturas a
                        JOIN asignaturas_clases ac ON a.asignatura_id = ac.asignatura_id
                        WHERE ac.clase_id IN ($inList)";
    
    $stmtAsignaturas = $conexion->prepare($queryAsignaturas);

    // Bind parameters
    $types = str_repeat('i', count($clasesAlumno));
    $stmtAsignaturas->bind_param($types, ...$clasesAlumno);
    
    $stmtAsignaturas->execute();
    $resultAsignaturas = $stmtAsignaturas->get_result();
} else {
    $resultAsignaturas = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaturas</title>
     <!-- Agregar los enlaces a los estilos de Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="jumbotron bg-primary text-center text-white">
    <img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-4">
        <h1>Asignaturas de E-ducatea</h1>
        <?php
        // Verificar si hay asignaturas para mostrar
        if ($resultAsignaturas && $resultAsignaturas->num_rows > 0) {
            // Crear la tabla con estilos de Bootstrap
            echo "<table class='table'>";
            echo "<thead class='thead-dark'><tr><th>ID</th><th>Nombre de la asignatura</th><th>Código</th><th></th></tr></thead><tbody>";

            // Mostrar una fila por cada asignatura
            while ($fila = $resultAsignaturas->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$fila["asignatura_id"]."</td>";
                echo "<td>".$fila["nombre_asignatura"]."</td>";
                echo "<td>".$fila["codigo_asignatura"]."</td>";
                echo "<td><a class='btn btn-primary' href='Tarea/tare_alumno.php?id=".$fila["asignatura_id"]."'>Ver Tarea</a></td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No hay asignaturas disponibles para mostrar.</p>";
        }
        ?>
        <a href="../alumnos.php" class="btn btn-secondary">Volver a inicio</a>
    </div>

<!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
<footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
