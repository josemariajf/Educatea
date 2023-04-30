<?php
session_start();
require_once "../funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();


$usuario = $_SESSION["usuario"]; // Suponiendo que el nombre de la variable de sesión que contiene el usuario es "usuario"
$query = "SELECT nombre, apellido, rol FROM usuarios where usuario= '$usuario'";
$resultado = mysqli_query($conexion, $query);

// Verificar si se pudo realizar la consulta
if (!$resultado) {
    echo "Error al realizar la consulta: " . mysqli_error($conexion);
    exit();
}

// Obtener el nombre real del usuario a partir del resultado de la consulta
$registro = mysqli_fetch_assoc($resultado);
$nombre = $registro['nombre'];
$apellido = $registro['apellido'];
$rol = $registro['rol'];


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
    <h1>¡Bienvenido/a a <?php echo $nombre ." ". $apellido?>  E-ducatea </h1>
    <p> Eres un <?php echo $rol?></p>
 
         <p>¡Explora todas las opciones de E-ducatea tiene para ofrecerte.</p>

<form method="post">
           
                <button type="submit" name="logout">Cerrar sesión</button>
            
        </form>
  </body>
</html>
