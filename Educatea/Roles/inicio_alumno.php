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
<html>
  <head>
    <title>Bienvenido/a E-ducatea</title>
  
    <link href="../../css/inicio_alumno.css" rel="stylesheet" />
  </head>
  <body>
 
      <div id=header>
  <h1>¡Bienvenido/a alumno/a <?php echo $nombre ." ". $apellido?> a E-ducatea</h1>
  <form method="post">  
           <button type="submit" name="logout" id="cerrarses">Cerrar sesión</button>
         </form>      
</div>
    
    
      <div id=cuerpo>
      
        <p>Aquí podrás acceder a tus cursos, ver tus calificaciones y participar en actividades en línea con tus compañeros de clase.
         Estamos encantados de tenerte como parte de nuestra comunidad educativa.</p>
         <p>¡Explora todas las opciones de E-ducatea tiene para ofrecerte.</p>
         <button onclick="location.href='../Alumno/perfil.php'">Perfil</button>
         <button onclick="location.href='../Alumno/tarea_alum.php'">Tarea</button>
       
        
      </div>
    
  </body>
</html>
</html>
