<?php
session_start();
require_once "../funciones.php";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido/a Educatea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body class="bg-light">
<img src="../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <h1>¡Bienvenido/a director/a <?php echo $nombre ." ". $apellido?> en E-ducatea</h1>
    
        <p>¡Explora todas las opciones que E-ducatea tiene para ofrecerte.</p>

        <!-- Botones para realizar otras acciones -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <button class="btn btn-primary btn-block" onclick="location.href='../Director/Asignatura/gestionar_asignatura.php'">Gestionar Asignatura</button>
            </div>
           
            <div class="col-md-6 mb-3">
                <button class="btn btn-primary btn-block" onclick="location.href='../Director/profesor/gestionar_profesor.php'">Gestionar Profesor</button>
            </div>
            <div class="col-md-6 mb-3">
                <button class="btn btn-primary btn-block" onclick="location.href='../Director/alumno/gestionar_alumno.php'">Gestionar Alumno</button>
            </div>
           
            <div class="col-md-6 mb-3">
                <button class="btn btn-primary btn-block" onclick="location.href='../Director/clases/gestionar_clases.php'">Gestionar Clase</button>
            </div>
            <div class="col-md-12 mb-4">
                <button class="btn btn-primary btn-block" onclick="location.href='../Director/listado.php'">Ver Usuarios</button>
            </div>
        </div>
        <br/>

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
