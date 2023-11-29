<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

// Obtener la lista de asignaturas para mostrar los códigos
$query_lista_asignaturas = "SELECT id_asignatura, codigo_asignatura FROM asignaturas";
$resultado_lista_asignaturas = $conexion->query($query_lista_asignaturas);

// Procesar la eliminación de asignaturas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_asignatura'])) {
    $id_asignatura = $_POST['id_asignatura'];

    // Validaciones de datos (puedes agregar más según tus necesidades)

    // Eliminar la asignatura de la base de datos
    $query_eliminar_asignatura = "DELETE FROM asignaturas WHERE id_asignatura = $id_asignatura";
    $resultado_eliminar_asignatura = $conexion->query($query_eliminar_asignatura);

    if ($resultado_eliminar_asignatura) {
        echo "Asignatura eliminada correctamente.";
    } else {
        echo "Error al eliminar la asignatura: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Eliminar Asignatura</title>
</head>

<body>
    <h2>Eliminar Asignatura</h2>

    <!-- Formulario para eliminar asignaturas -->
    <form method="post" action="eliminar_asignatura.php">
        <!-- Desplegable para seleccionar la asignatura a eliminar -->
        <label for="id_asignatura">Selecciona una asignatura:</label>
        <select name="id_asignatura" required>
            <?php
            while ($fila = $resultado_lista_asignaturas->fetch_assoc()) {
                echo "<option value='" . $fila['id_asignatura'] . "'>" . $fila['codigo_asignatura'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" name="eliminar_asignatura" value="Eliminar Asignatura">
    </form>

    <!-- Botón para volver a la página principal -->
    <button onclick="goBack()">Volver</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
