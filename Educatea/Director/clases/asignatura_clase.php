<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['nombre_rol'];

// Verificar si el usuario tiene permisos de administrador
if ($rol !== 'director') {
    header('Location: ../../index.php');
    exit;
}

// Verificar si se proporciona el parámetro clase_id
if (!isset($_GET['clase_id'])) {
    header('Location: gestionar_clases.php');
    exit;
}

$clase_id = $_GET['clase_id'];

// Obtener información de la clase
$queryClase = "SELECT * FROM clases WHERE clase_id = $clase_id";
$resultClase = $conexion->query($queryClase);

// Verificar si se pudo realizar la consulta
if (!$resultClase || $resultClase->num_rows === 0) {
    echo "Error al obtener información de la clase.";
    exit;
}

$clase = $resultClase->fetch_assoc();

// Obtener asignaturas asociadas a la clase
$queryAsignaturas = "SELECT a.* FROM asignaturas a
                    INNER JOIN asignaturas_clases ac ON a.asignatura_id = ac.asignatura_id
                    WHERE ac.clase_id = $clase_id";

$resultAsignaturas = $conexion->query($queryAsignaturas);

// Verificar si se pudo realizar la consulta
if (!$resultAsignaturas) {
    echo "Error al obtener asignaturas de la clase.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaturas de la Clase - Educatea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="jumbotron bg-primary text-center text-white">
    <img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>

    <div class="container mt-4">
        <h1 class="mb-4">Asignaturas de la Clase: <?php echo $clase['nombre_clase']; ?></h1>

        <?php if ($resultAsignaturas->num_rows > 0) : ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            
                            <th>Nombre Asignatura</th>
                            <th>Código Asignatura</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($asignatura = $resultAsignaturas->fetch_assoc()) : ?>
                            <tr>
                               
                                <td><?php echo $asignatura['nombre_asignatura']; ?></td>
                                <td><?php echo $asignatura['codigo_asignatura']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p>No hay asignaturas en esta clase.</p>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <a href='gestionar_clases.php' class="btn btn-secondary">Volver a Gestión de Clases</a>
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
