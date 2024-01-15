<?php
// Incluir el archivo de funciones y realizar la conexión a la base de datos
require_once "../funciones.php";
$conexion = conexion();

// Obtener el ID del usuario profesor con sesión iniciada
session_start();
$id_usuario_profesor = isset($_SESSION['usuario']['usuario_id']) ? $_SESSION['usuario']['usuario_id'] : null;

// Verificar si el ID del profesor está definido
if ($id_usuario_profesor !== null) {
    // Realizar la consulta para obtener la información de los alumnos y tutor en la clase del profesor
    $query = "SELECT u.usuario_id, u.nombre, u.apellido, r.nombre_rol
              FROM usuarios u
              JOIN roles r ON u.rol_id = r.rol_id
              JOIN clases_usuarios cu ON u.usuario_id = cu.usuario_id
              JOIN clases c ON cu.clase_id = c.clase_id
              WHERE (r.nombre_rol = 'alumno' OR r.nombre_rol = 'profesor') AND c.id_tutor = $id_usuario_profesor";

    $resultado = $conexion->query($query);

    // Verificar si se pudo realizar la consulta
    if (!$resultado) {
        echo "Error al realizar la consulta: " . mysqli_error($conexion);
        exit();
    }
} else {
    echo "ID del profesor no definido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Lista de Alumnos y Tutor en la Clase</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">
<img src="../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <h2>Lista de Alumnos y Tutor en la Clase</h2>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los resultados de la consulta en una tabla
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila['nombre'] . "</td>";
                    echo "<td>" . $fila['apellido'] . "</td>";
                    echo "<td>" . $fila['nombre_rol'] . "</td>";
                    echo "<td><a href='Tarea/tarea.php?id_alumno=" . $fila['usuario_id'] . "' class='btn btn-primary'>Ver Tareas</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <br>

        <!-- Botón para volver a la página anterior -->
        <a href="clase.php" class="btn btn-secondary mt-3">Volver a inicio</a>
    </div>


 <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
 <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>


    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
