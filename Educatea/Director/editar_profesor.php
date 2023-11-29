<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la actualización del profesor
    if (isset($_POST['editar_profesor'])) {
        $id_profesor = $_POST['id_profesor'];
        $nuevo_nombre = $_POST['nuevo_nombre'];

        // Realizar la actualización en la base de datos
        $queryActualizar = "UPDATE usuarios SET nombre = '$nuevo_nombre' WHERE id = '$id_profesor'";
        $resultadoActualizar = $conexion->query($queryActualizar);

        if ($resultadoActualizar) {
            echo "Profesor actualizado correctamente.";
        } else {
            echo "Error al actualizar el profesor: " . mysqli_error($conexion);
        }
    }
} else {
    // Obtener el ID del profesor desde la URL
    $id_profesor = $_GET['id'];

    // Obtener la información del profesor
    $queryProfesor = "SELECT id, nombre FROM usuarios WHERE id = '$id_profesor' AND id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'profesor')";
    $resultadoProfesor = $conexion->query($queryProfesor);

    if ($resultadoProfesor && $resultadoProfesor->num_rows > 0) {
        $rowProfesor = $resultadoProfesor->fetch_assoc();
        $nombre_profesor = $rowProfesor['nombre'];
    } else {
        echo "No se encontró el profesor.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Profesor</title>
</head>

<body>
    <h2>Editar Profesor</h2>

    <form method="post" action="editar_profesor.php">
        <input type="hidden" name="id_profesor" value="<?php echo $id_profesor; ?>">
        <label for="nuevo_nombre">Nuevo Nombre:</label>
        <input type="text" name="nuevo_nombre" value="<?php echo $nombre_profesor; ?>" required>
        <br/><br/>
        <input type="submit" name="editar_profesor" value="Guardar Cambios">
    </form>

    <br>
    <a href="gestionar_profesor.php">Volver a la Gestión de Profesores</a>
</body>

</html>
