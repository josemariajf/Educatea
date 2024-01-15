<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'director') {
    header('Location: ../index.php'); // Redirigir si no es un director
    exit;
}

// Verificar si se ha proporcionado un ID para eliminar la asignatura
if (isset($_GET['id'])) {
    $id_asignatura = $_GET['id'];

    // Realizar la eliminación de la asignatura
    $queryEliminar = "DELETE FROM asignaturas WHERE asignatura_id = $id_asignatura";
    $resultadoEliminar = $conexion->query($queryEliminar);

    // Redirigir de nuevo a la página principal después de la eliminación
    header('Location: gestion_asignaturas.php');
    exit;
}

// Obtener la lista de asignaturas sin repetir
$queryAsignaturas = "SELECT DISTINCT a.asignatura_id, a.nombre_asignatura, a.codigo_asignatura
                    FROM asignaturas a
                    LEFT JOIN asignaturas_clases ac ON a.asignatura_id = ac.asignatura_id
                    LEFT JOIN clases c ON ac.clase_id = c.clase_id";
$resultadoAsignaturas = $conexion->query($queryAsignaturas);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asignaturas - Educatea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/bootstrap-4.0.0-dist/js/bootstrap.min.js">
</head>

<body class="bg-light">
<div class="jumbotron bg-primary text-center text-white">
<img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Gestión de Asignaturas</h2>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Asignatura</th>
                    <th>Código Asignatura</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowAsignatura = $resultadoAsignaturas->fetch_assoc()) :
                ?>
                    <tr>
                        <td><?php echo $rowAsignatura['asignatura_id']; ?></td>
                        <td><?php echo $rowAsignatura['nombre_asignatura']; ?></td>
                        <td><?php echo $rowAsignatura['codigo_asignatura']; ?></td>
                        
                        <td>
                            <a href='editar_asignatura.php?id=<?php echo $rowAsignatura['asignatura_id']; ?>' class="btn btn-warning btn-sm mr-2">Editar</a>
                            <a href='eliminar_asignatura.php?id=<?php echo $rowAsignatura['asignatura_id']; ?>' class="btn btn-danger btn-sm mr-2">Eliminar</a>
                            <a href='añadir_asignatura.php?id_asignatura=<?php echo $rowAsignatura['asignatura_id']; ?>' class="btn btn-success btn-sm">Añadir a Clase</a>
                            <a href='asignatura_alumno.php?id_asignatura=<?php echo $rowAsignatura['asignatura_id']; ?>' class="btn btn-info btn-sm">Ver Alumnos</a>
                        </td>
                    </tr>
                <?php
                endwhile;
                ?>
            </tbody>
        </table>

        <div class="mt-3">
            <a href="crear_asignatura.php" class="btn btn-success">Añadir Asignatura</a>
            <a href="../../Roles/inicio_director.php" class="btn btn-secondary">Volver a Inicio</a>
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

