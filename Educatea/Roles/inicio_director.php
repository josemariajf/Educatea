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
var_dump($usuario);





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
    <h1>¡Bienvenido/a director/a <?php echo $nombre ." ". $apellido?>  E-ducatea </h1>
    
 
    <p>¡Explora todas las opciones de E-ducatea tiene para ofrecerte.</p>

     <!-- Botones para realizar otras acciones -->
    
     <button onclick="location.href='../Director/crear_asignatura.php'">Crear Asignatura</button><br/>
     <button onclick="location.href='../Director/crear_curso.php'">Crear Curso</button><br/>
     <button onclick="location.href='../Director/gestionar_profesor.php'">Gestionar Profesor</button><br/>
     <button onclick="location.href='../Director/gestionar_alumno.php'">Gestionar Alumno</button><br/>
     <button onclick="location.href='../Director/asignar_tutor.php'">Asignar Tutor</button><br/>
     <button onclick="location.href='../Director/clases/gestionar_clases.php'">Gestionar Clase</button><br/><br/>
    


<form method="post">
           
                <button type="submit" name="logout">Cerrar sesión</button>
            
        </form>
  </body>
</html>
