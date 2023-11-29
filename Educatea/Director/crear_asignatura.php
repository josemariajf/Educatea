<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la creación de asignaturas
    if (isset($_POST['crear_asignatura'])) {
        $nombre_asignatura = $_POST['nombre_asignatura'];
        $codigo_asignatura = $_POST['codigo_asignatura'];
        $descripcion_asignatura = $_POST['descripcion_asignatura'];
        

        // Validaciones de datos (puedes agregar más según tus necesidades)

        // Insertar la asignatura en la base de datos
        $query = "INSERT INTO asignaturas (nombre_asignatura, codigo_asignatura, descripcion_asignatura) 
                  VALUES ('$nombre_asignatura', '$codigo_asignatura', '$descripcion_asignatura')";
        $resultado = $conexion->query($query);

        if ($resultado) {
            echo "Asignatura creada correctamente.";
        } else {
            echo "Error al crear la asignatura: " . mysqli_error($conexion);
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Crear Asignatura</title>
</head>

<body>
    <h2>Crear Asignatura</h2>

    <!-- Formulario para crear asignaturas -->
    <form method="post" action="crear_asignatura.php">
        <label for="nombre_asignatura">Nombre de la Asignatura:</label>
        <input type="text" name="nombre_asignatura" required>
        <br/><br/>
        <label for="codigo_asignatura">Código de la Asignatura:</label>
        <input type="text" name="codigo_asignatura" required>
        <br/><br/>
        <label for="descripcion_asignatura">Descripción de la Asignatura:</label>
        <textarea name="descripcion_asignatura" required></textarea>
        <br/><br/>
        <input type="submit" name="crear_asignatura" value="Crear Asignatura">
    </form>
    
    <form action="eliminar_asignatura.php" method="get">
    <button type="submit">Eliminar Asignaturas</button>
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
