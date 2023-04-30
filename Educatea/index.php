<?php
    session_start();
require_once "funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();
// Procesar el formulario de inicio de sesión si se ha enviado
if (isset($_POST['login'])) {
  // Obtener los valores del formulario
  $usuario = $_POST['usuario'];
  $password = $_POST['contraseña']; // Obtener la contraseña sin encriptar
  
  // Encriptar la contraseña introducida por el usuario
  $password = md5($password);

  // Verificar que el usuario y la contraseña sean correctos
  $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contraseña='$password'";
  $result = $conexion->query($sql);

  if ($result->num_rows > 0) {
    // Obtener el rol del usuario
    $usuario = $result->fetch_assoc();
    $rol = $usuario['rol'];

    // Almacenar el rol del usuario en una variable de sesión

    $_SESSION['usuario'] = $usuario;
    $_SESSION['rol'] = $rol;

    // Redirigir al usuario a una página de inicio específica dependiendo de su rol
    if ($rol == 'alumno') {
      header('Location: roles/inicio_alumno.php');
      exit;
    } elseif ($rol == 'profesor') {
      header('Location: roles/inicio_profesor.php');
      exit;
    } elseif ($rol == 'director') {
      header('Location: roles/inicio_director.php');
      exit;
    } else {
      // Si el rol del usuario no es válido, mostrar un mensaje de error
      $error = "Rol de usuario no válido.";
    }
  } else {
    // Si el usuario y la contraseña no son correctos, mostrar un mensaje de error
    $error = "Nombre de usuario o contraseña incorrectos.";
  }
}

?>
<!DOCTYPE html>
<html>
<head>
  <title>Iniciar sesión en E-Ducatea</title>
  <link href="../css/indexcss.css" rel="stylesheet" />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <h1>Iniciar sesión en E-Ducatea</h1>
  <form method="post">
    <label for="usuario">Nombre de usuario:</label>
    <input type="text" id="usuario" name="usuario" required>
    <label for="password">Contraseña:</label>
    <input type="password" id="contraseña" name="contraseña"  required minlength="5" maxlength="10">
    <input type="submit" value="Iniciar sesión" name="login">
    <a href="registro.php" >Registrarse</a>
    <?php if(isset($error)) { ?>
      <p class="error"><?php echo $error; ?></p>
    <?php } ?>
  </form>
</body>
</html>
