<?php
session_start();
require_once "../../funciones.php";

// Datos de conexión a la base de datos
$conexion = conexion();

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['usuario']['usuario_id'];

// Consulta para obtener las clases del profesor
$queryClasesProfesor = "SELECT clase_id FROM clases WHERE id_tutor = $usuario_id";
$resultadoClasesProfesor = $conexion->query($queryClasesProfesor);

// Verificar si se pudo realizar la consulta
if (!$resultadoClasesProfesor) {
    echo "Error al realizar la consulta de clases del profesor: " . mysqli_error($conexion);
    exit();
}

// Obtener los ID de las clases a las que pertenece el profesor
$clasesProfesor = [];
while ($filaClase = $resultadoClasesProfesor->fetch_assoc()) {
    $clasesProfesor[] = $filaClase['clase_id'];
}

// Preparar la consulta para obtener la información de las asignaturas que están en las clases del profesor
$sql = "SELECT a.*
        FROM asignaturas a
        JOIN asignaturas_clases ac ON a.asignatura_id = ac.asignatura_id
        WHERE ac.clase_id IN (";

// Agregar los marcadores de posición para la lista de clases
$sql .= implode(",", array_fill(0, count($clasesProfesor), "?"));
$sql .= ")";

// Preparar la sentencia
$stmt = $conexion->prepare($sql);

// Verificar si la preparación fue exitosa
if (!$stmt) {
    echo "Error al preparar la consulta: " . $conexion->error;
    exit();
}

// Bind los parámetros para los marcadores de posición
foreach ($clasesProfesor as $key => $value) {
    $stmt->bind_param("i", $value);
}

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$resultadoAsignaturas = $stmt->get_result();

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
                echo "<td><a class='btn btn-primary' href='anadir_tarea.php?asignatura_id=".$fila["asignatura_id"]."&clase_id=".$clasesProfesor[0]."'>Añadir Tarea</a></td>";
                echo "</tr>";
            }
            

            echo "</tbody></table>";
        } else {
            echo "<p>No hay asignaturas disponibles para mostrar.</p>";
        }
        ?>
        <a href="../../Roles/inicio_profesor.php" class="btn btn-secondary">Volver a inicio</a>
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
