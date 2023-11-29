<?php
session_start();
require_once "../../funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();

// Verificar si se ha proporcionado un ID válido
if (isset($_GET['id'])) {
    $id_asignatura = $_GET['id'];

    // Realizar una consulta SQL para obtener los detalles de la asignatura con el ID proporcionado
    $sql = "SELECT * FROM asignaturas WHERE id_asignatura = $id_asignatura";
    $resultado = $conexion->query($sql);

    if ($fila = $resultado->fetch_assoc()) {
        // Aquí puedes mostrar los detalles de la asignatura
        $nombre_asignatura = $fila["nombre_asignatura"];
        $descripcion_asignatura = $fila["descripcion_asignatura"];

        // Puedes mostrar estos datos en tu página HTML
        echo "<h2>Asignatura: $nombre_asignatura</h2>";
        echo "<p>Descripción: $descripcion_asignatura</p>";

        // Agregar el formulario para enviar archivos
        echo "<form action='procesar_tarea.php' method='post' enctype='multipart/form-data'>";
        echo "<label for='archivo'>Seleccionar archivo:</label>";
        echo "<input type='file' name='archivo' id='archivo' required>";
        echo "<input type='hidden' name='id_asignatura' value='$id_asignatura'>";
        echo "<input type='submit' name='enviar_tarea' value='Enviar Tarea'>";
        echo "</form>";
    } else {
        // Manejar el caso en el que no se encontraron detalles de la asignatura
        echo "No se encontraron detalles de la asignatura.";
    }
} else {
    // Manejar el caso en el que no se proporciona un ID válido.
    echo "ID de asignatura no válido.";
}
?>

<a href="../Asignatura_alum.php">Volver a la lista de asignaturas</a>
<!-- Aquí puedes mostrar más información de la asignatura si es necesario -->
