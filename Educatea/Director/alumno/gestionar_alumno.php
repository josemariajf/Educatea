<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'director') {
    header('Location: ../index.php'); // Redirigir si no es un director
    exit;
}

// Obtener la lista de alumnos desde la base de datos
$queryAlumnos = "SELECT usuario_id, nombre, apellido, correo_electronico FROM usuarios WHERE rol_id = (SELECT rol_id FROM roles WHERE nombre_rol = 'alumno')";
$resultadoAlumnos = $conexion->query($queryAlumnos);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
<div class="jumbotron bg-primary text-center text-white">
<img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <h2>Gestión de Alumnos</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowAlumno = $resultadoAlumnos->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowAlumno['usuario_id'] . "</td>";
                    echo "<td>" . $rowAlumno['nombre'] . "</td>";
                    echo "<td>" . $rowAlumno['apellido'] . "</td>";
                    echo "<td>" . $rowAlumno['correo_electronico'] . "</td>";
                    echo "<td><a href='editar_alumno.php?id=" . $rowAlumno['usuario_id'] . "' class='btn btn-primary btn-sm'>Editar</a> | <a href='eliminar_alumno.php?id=" . $rowAlumno['usuario_id'] . "' class='btn btn-danger btn-sm'>Eliminar</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="mt-3">
            <a href="crear_alumno.php" class="btn btn-success">Añadir Alumno</a>
            <a href="../../Roles/inicio_director.php" class="btn btn-secondary">Volver a inicio</a>
        </div>
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

