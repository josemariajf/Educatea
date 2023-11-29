<?php
// Incluir el archivo de funciones y realizar la conexión a la base de datos
require_once "../funciones.php";
$conexion = conexion();

// Realizar la consulta para obtener la información de los cursos
$query = "SELECT id_curso, nombre_curso, descripcion_curso FROM cursos";
$resultado = $conexion->query($query);

// Verificar si se pudo realizar la consulta
if (!$resultado) {
    echo "Error al realizar la consulta: " . mysqli_error($conexion);
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lista de Cursos</title>
</head>

<body>
    <h2>Lista de Cursos</h2>

    <table border="1">
        <tr>
            <th>ID Curso</th>
            <th>Nombre del Curso</th>
            <th>Descripción del Curso</th>
        </tr>

        <?php
        // Mostrar los resultados de la consulta en una tabla
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $fila['id_curso'] . "</td>";
            echo "<td>" . $fila['nombre_curso'] . "</td>";
            echo "<td>" . $fila['descripcion_curso'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <br>

    <!-- Botón para volver a la página anterior utilizando JavaScript -->
    <button onclick="goBack()">Volver</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
