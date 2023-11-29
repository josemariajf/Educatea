<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

// Obtener la lista de profesores
$queryProfesores = "SELECT id, nombre, email FROM usuarios WHERE id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'profesor')";
$resultadoProfesores = $conexion->query($queryProfesores);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Gestión de Profesores</title>
</head>

<body>
    <h2>Gestión de Profesores</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>

        <?php
        while ($rowProfesor = $resultadoProfesores->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $rowProfesor['id'] . "</td>";
            echo "<td>" . $rowProfesor['nombre'] . "</td>";
            echo "<td>" . $rowProfesor['email'] . "</td>";
            echo "<td><a href='editar_profesor.php?id=" . $rowProfesor['id'] . "'>Editar</a> | <a href='eliminar_profesor.php?id=" . $rowProfesor['id'] . "'>Eliminar</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <br>
    <a href="crear_profesor.php">Añadir Profesor</a>
    <a href="../Roles/inicio_director.php">Volver a inicio</a>
</body>

</html>
