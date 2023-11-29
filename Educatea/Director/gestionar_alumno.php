<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'director') {
    header('Location: ../index.php'); // Redirigir si no es un director
    exit;
}

// Obtener la lista de alumnos desde la base de datos
$queryAlumnos = "SELECT id, nombre, apellido, email FROM usuarios WHERE id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'alumno')";
$resultadoAlumnos = $conexion->query($queryAlumnos);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos</title>
</head>

<body>
    <h2>Gestión de Alumnos</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>

        <?php
        while ($rowAlumno = $resultadoAlumnos->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rowAlumno['id'] . "</td>";
            echo "<td>" . $rowAlumno['nombre'] . "</td>";
            echo "<td>" . $rowAlumno['apellido'] . "</td>";
            echo "<td>" . $rowAlumno['email'] . "</td>";
            echo "<td><a href='editar_alumno.php?id=" . $rowAlumno['id'] . "'>Editar</a> | <a href='eliminar_alumno.php?id=" . $rowAlumno['id'] . "'>Eliminar</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <br>
    <a href="crear_alumno.php">Añadir Alumno</a>
    <a href="../Roles/inicio_director.php">Volver a inicio</a>
</body>

</html>
