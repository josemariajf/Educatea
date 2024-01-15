<?php
session_start();
require_once "../funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();

$usuario = $_SESSION["usuario"];
$resultado = $conexion->query("SELECT nombre, apellido, rol_id FROM usuarios WHERE usuario_id = '".$usuario['usuario_id']."'");

if (!$resultado) {
    echo "Error al realizar la consulta: " . mysqli_error($conexion);
    exit();
}

$usu = $resultado->fetch_assoc();
$nombre = $usu['nombre'];
$apellido = $usu['apellido'];

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Bienvenido/a en Moodle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">
<img src="../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
<div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <h1>¡Bienvenido/a profesor/a <?php echo $nombre . " " . $apellido?> en E-ducatea</h1>

        <p>¡Explora todas las opciones que E-ducatea tiene para ofrecerte.</p>

        <!-- Botones para ver alumnos y acceder a los cursos -->
        <div class="row">
            
            
            <div class="col-md-6 mb-3">
                <form action="../Profesor/clase.php" method="get">
                    <button type="submit" class="btn btn-primary btn-block">Ver Clases</button>
                </form>
            </div>
            <div class="col-md-6 mb-3">
                <form action="../Profesor/Asignatura/Asignatura.php" method="get">
                    <button type="submit" class="btn btn-primary btn-block">Ver Asignaturas</button>
                </form>
            </div>
        </div>

        <!-- Botón para cerrar sesión -->
        <form method="post">
            <button type="submit" name="logout" class="btn btn-danger btn-block">Cerrar sesión</button>
        </form>
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
