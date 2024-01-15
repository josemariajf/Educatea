<?php
session_start();
require_once "../funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['usuario']['usuario_id'];

// Consulta para obtener las clases del alumno
$queryClasesAlumno = "SELECT clase_id FROM clases_usuarios WHERE usuario_id = ?";
$stmtClasesAlumno = $conexion->prepare($queryClasesAlumno);
$stmtClasesAlumno->bind_param("i", $usuario_id);

// Verificar si se pudo preparar la consulta
if (!$stmtClasesAlumno->execute()) {
    echo "Error al realizar la consulta de clases del alumno: " . $stmtClasesAlumno->error;
    exit();
}

// Obtener los ID de las clases a las que pertenece el alumno
$resultadoClasesAlumno = $stmtClasesAlumno->get_result();
$clasesAlumno = [];

while ($filaClase = $resultadoClasesAlumno->fetch_assoc()) {
    $clasesAlumno[] = $filaClase['clase_id'];
}

$stmtClasesAlumno->close();

// Verificar si el alumno tiene clases
if (empty($clasesAlumno)) {
    echo "<div class='container mt-4'>";
    echo "<div class='alert alert-warning' role='alert'>";
    echo "<p>No hay clases asignadas al alumno.</p>";
    echo "</div>";
    echo "<a href='../Roles/inicio_alumno.php' class='btn btn-secondary mt-3'>Volver a la lista de asignaturas</a>";
    echo "</div>";
    exit();
}

// Crear la cláusula IN con marcadores de posición
$placeholders = implode(',', array_fill(0, count($clasesAlumno), '?'));

// Consulta para obtener la información de las asignaturas que están en las clases del alumno
$sql = "SELECT a.*
        FROM asignaturas a
        JOIN asignaturas_clases ac ON a.asignatura_id = ac.asignatura_id
        WHERE ac.clase_id IN ($placeholders)";
$stmtAsignaturas = $conexion->prepare($sql);

// Agregar los valores de los marcadores de posición
$stmtAsignaturas->bind_param(str_repeat('i', count($clasesAlumno)), ...$clasesAlumno);

// Verificar si se pudo preparar la consulta
if (!$stmtAsignaturas->execute()) {
    echo "Error al realizar la consulta de asignaturas: " . $stmtAsignaturas->error;
    exit();
}

$resultadoAsignaturas = $stmtAsignaturas->get_result();
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
        <img src="../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-4">
        <h1>Asignaturas de E-ducatea</h1>
        <?php
        // Verificar si hay asignaturas para mostrar
        if ($resultadoAsignaturas->num_rows > 0) {
            // Crear la tabla con estilos de Bootstrap
            echo "<table class='table'>";
            echo "<thead class='thead-dark'><tr><th>ID</th><th>Nombre de la asignatura</th><th>Código</th><th></th></tr></thead><tbody>";

            // Mostrar una fila por cada asignatura
            while ($fila = $resultadoAsignaturas->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$fila["asignatura_id"]."</td>";
                echo "<td>".$fila["nombre_asignatura"]."</td>";
                echo "<td>".$fila["codigo_asignatura"]."</td>";
                echo "<td><a class='btn btn-primary' href='tarea/ver_tarea.php?asignatura_id=".$fila["asignatura_id"]."'>Ver Tarea</a></td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            // Mostrar un mensaje con estilos de Bootstrap
            echo "<div class='alert alert-warning' role='alert'>";
            echo "<p>No hay asignaturas disponibles para mostrar.</p>";
            echo "</div>";

            // Botón de volver con estilos de Bootstrap
            echo "<a href='../Roles/inicio_alumno.php' class='btn btn-secondary mt-3'>Volver a la lista de asignaturas</a>";
        }
        ?>
        <a href='../Roles/inicio_alumno.php' class='btn btn-secondary mt-3'>Volver a la lista de asignaturas</a>
    </div>


        <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>


    <!-- Agregar el enlace al script de Bootstrap al final del cuerpo -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
