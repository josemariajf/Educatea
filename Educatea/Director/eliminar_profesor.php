<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

// Inicializar la variable $nombre_profesor
$nombre_profesor = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la eliminación del profesor
    if (isset($_POST['eliminar_profesor'])) {
        $id_profesor = $_POST['id_profesor'];

        // Realizar la eliminación en la base de datos
        $queryEliminar = "DELETE FROM usuarios WHERE id = '$id_profesor'";
        $resultadoEliminar = $conexion->query($queryEliminar);

        if ($resultadoEliminar) {
            // Redirigir a gestionar_profesor.php
            header('Location: gestionar_profesor.php');
            exit;
        } else {
            echo "Error al eliminar el profesor: " . mysqli_error($conexion);
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
    <title>Eliminar Profesor</title>
</head>

<body>
    <h2>Eliminar Profesor</h2>

    <p>¿Estás seguro de que deseas eliminar al profesor "<?php echo $nombre_profesor; ?>"?</p>

    <form method="post" action="eliminar_profesor.php">
        <input type="hidden" name="id_profesor" value="<?php echo $id_profesor; ?>">
        <input type="submit" name="eliminar_profesor" value="Eliminar">
    </form>

    <br>
   
    <!-- Botón para volver a la página principal -->
    <a href="gestionar_profesor.php">Volver a la Gestión de Profesores</a>
</script>
</body>

</html>
