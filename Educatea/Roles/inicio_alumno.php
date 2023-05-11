<?php
session_start();
require_once "../funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();


$usuario = $_SESSION["usuario"]; // Suponiendo que el nombre de la variable de sesión que contiene el usuario es "usuario"
$resultado = $conexion-> query("SELECT nombre, apellido, rol FROM usuarios WHERE id = '".$usuario['id']."'");

// Verificar si se pudo realizar la consulta
if (!$resultado) {
    echo "Error al realizar la consulta: " . mysqli_error($conexion);
    exit();
}

// Obtener el nombre real del usuario a partir del resultado de la consulta

$usu = $resultado->fetch_assoc();
$nombre = $usu['nombre'];
$apellido = $usu['apellido'];
$rol = $usu['rol'];


if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../index.php');
    exit;
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Bienvenido/a E-ducatea</title>
  
    <link href="../../css/inicio_alumno.css" rel="stylesheet" />
  </head>
  <body>
 
      <div id=header>
  <h1>¡Bienvenido/a <?php echo $nombre ." ". $apellido?> a E-ducatea</h1>
  <form method="post">
           <button type="submit" name="logout" id="cerrarses">Cerrar sesión</button>
         </form>      
</div>
    
    
      <div id=cuerpo>
        <p> Eres un <?php echo $rol?></p>
        <p>Aquí podrás acceder a tus cursos, ver tus calificaciones y participar en actividades en línea con tus compañeros de clase.
         Estamos encantados de tenerte como parte de nuestra comunidad educativa.</p>
         <p>¡Explora todas las opciones de E-ducatea tiene para ofrecerte.</p>
         <button onclick="location.href='../Alumno/Asignatura_alum.php'">Asignatura</button>
         <button onclick="location.href='../Alumno/califica_alum.php'">Calificaciones</button>
        
      </div>
    
  </body>
</html>
</html>
