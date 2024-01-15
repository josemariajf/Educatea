<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'director') {
    header('Location: ../index.php'); // Redirigir si no es un director
    exit;
}

// Verificar si se ha proporcionado un ID para la asignatura
if (!isset($_GET['id_asignatura'])) {
    header('Location: gestion_asignaturas.php');
    exit;
}

$id_asignatura = $_GET['id_asignatura'];

// Obtener información de la asignatura
$queryAsignatura = "SELECT * FROM asignaturas WHERE asignatura_id = $id_asignatura";
$resultadoAsignatura = $conexion->query($queryAsignatura);

// Verificar si se pudo realizar la consulta
if (!$resultadoAsignatura || $resultadoAsignatura->num_rows === 0) {
    echo "Error al obtener información de la asignatura.";
    exit;
}

$asignatura = $resultadoAsignatura->fetch_assoc();

// Obtener alumnos asociados a la asignatura
$queryAlumnos = "SELECT u.* FROM usuarios u
                 INNER JOIN clases_usuarios cu ON u.usuario_id = cu.usuario_id
                 INNER JOIN clases c ON cu.clase_id = c.clase_id
                 INNER JOIN asignaturas_clases ac ON c.clase_id = ac.clase_id
                 WHERE ac.asignatura_id = $id_asignatura";

$resultadoAlumnos = $conexion->query($queryAlumnos);

// Verificar si se pudo realizar la consulta
if (!$resultadoAlumnos) {
    echo "Error al obtener alumnos de la asignatura.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos de la Asignatura - Educatea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="jumbotron bg-primary text-center text-white">
    <img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>

    <div class="container mt-4">
        <h1 class="mb-4">Alumnos de la Asignatura: <?php echo $asignatura['nombre_asignatura']; ?></h1>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo Electrónico</th>
                        <!-- Puedes agregar más columnas según tus necesidades -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($alumno = $resultadoAlumnos->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $alumno['usuario_id']; ?></td>
                            <td><?php echo $alumno['usuario']; ?></td>
                            <td><?php echo $alumno['nombre']; ?></td>
                            <td><?php echo $alumno['apellido']; ?></td>
                            <td><?php echo $alumno['correo_electronico']; ?></td>
                            <!-- Puedes agregar más columnas según tus necesidades -->
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-6">
                <a href='gestionar_asignatura.php' class="btn btn-secondary">Volver a Gestión de Asignaturas</a>
            </div>
        </div>
    </div>


        <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
