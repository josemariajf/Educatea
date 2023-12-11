<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Obtener la lista de profesores
$queryProfesores = "SELECT usuario_id, nombre, correo_electronico FROM usuarios WHERE rol_id = (SELECT rol_id FROM roles WHERE nombre_rol = 'profesor')";
$stmtProfesores = $conexion->prepare($queryProfesores);

if ($stmtProfesores->execute()) {
    $resultadoProfesores = $stmtProfesores->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Profesores - Educatea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/bootstrap-4.0.0-dist/js/bootstrap.min.js">
</head>

<body class="bg-light">
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <h2>Gestión de Profesores</h2>

        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowProfesor = $resultadoProfesores->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowProfesor['usuario_id'] . "</td>";
                    echo "<td>" . $rowProfesor['nombre'] . "</td>";
                    echo "<td>" . $rowProfesor['correo_electronico'] . "</td>";
                    echo "<td>
                            <a href='editar_profesor.php?id=" . $rowProfesor['usuario_id'] . "' class='btn btn-primary btn-sm'>Editar</a>
                            <a href='eliminar_profesor.php?id=" . $rowProfesor['usuario_id'] . "' class='btn btn-danger btn-sm'>Eliminar</a>
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="mt-3">
            <a href="crear_profesor.php" class="btn btn-success">Añadir Profesor</a>
            <a href="../../Roles/inicio_director.php" class="btn btn-link">Volver a inicio</a>
        </div>
    </div>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

