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
<html>
  <head>
    <title>Bienvenido/a  en Moodle</title>
  </head>
  <body>
    <h1>¡Bienvenido/a profesor/a  <?php echo $nombre ." ". $apellido?>  E-ducatea </h1>
    
 
         <p>¡Explora todas las opciones de E-ducatea tiene para ofrecerte.</p>

    <!-- Botón para ver alumnos -->
    <form action="../Profesor/alumnos.php" method="get">
        <button type="submit">Ver Alumnos</button>
    </form>

    <!-- Botón para acceder a los cursos -->
    <form action="../Profesor/cursos.php" method="get">
        <button type="submit">Ver Cursos</button>
    </form>
<form method="post">
           
                <button type="submit" name="logout">Cerrar sesión</button>
            
        </form>
        
  </body>
</html>
