<?php
session_start();
require_once "../funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();
//$logout = logout();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido/a en Moodle</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body class="bg-light">
<div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>

    
    
        <h1 class="display-4">¡Bienvenido/a Alumno/a <i><?php echo $nombre . " " . $apellido ?></i> a E-ducatea</h1>
        <form method="post">
            <button type="submit" name="logout" class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>

    <div class="container">
        <p class="lead">Aquí podrás explorar todas las opciones que E-ducatea tiene para ofrecerte.</p>

        <div class="row">
            <!-- Botón para ver alumnos -->
            <div class="col-md-6 mb-3">
                <form action="../Alumno/perfil.php" method="get">
                    <button type="submit" class="btn btn-primary btn-block">Perfil</button>
                </form>
            </div>

            <!-- Botón para acceder a los cursos -->
            <div class="col-md-6 mb-3">
                <form action="../Alumno/asignatura_alum.php" method="get">
                    <button type="submit" class="btn btn-info btn-block">Asignaturas</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Agregamos la referencia a Bootstrap JS y Popper.js al final del cuerpo del documento -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>



