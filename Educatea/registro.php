<?php
require_once "funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();


// Inicializar la variable de error
$error = '';

// Procesar el formulario de registro si se ha enviado
if (isset($_POST['register'])) {
 $usuario = $_POST['usuario'];
  $password = $_POST['contraseña']; // Obtener la contraseña sin encriptar
  $nombre = $_POST['nombre'];
  $apellido = $_POST['apellido'];
  $email = $_POST['email'];
  $rol = $_POST['rol'];  // Obtener los valores del formulario
 

  // Verificar que no haya otro usuario con el mismo nombre de usuario
  $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
  $result = $conexion->query($sql);

  if (mysqli_num_rows($result) > 0) {
    // Si ya existe un usuario con el mismo nombre de usuario, mostrar un mensaje de error
    $error = "Ya existe un usuario con el mismo nombre de usuario.";
  } elseif (strlen($password) < 5 || strlen($password) > 10) {
    // Si la contraseña no cumple con los requisitos de longitud, mostrar un mensaje de error
    $error = "La contraseña debe tener entre 5 y 10 caracteres.";
  } else {
    // Si no existe otro usuario con el mismo nombre de usuario y la contraseña cumple con los requisitos de longitud, encriptar la contraseña con md5() y insertar los datos en la base de datos
    $password = md5($password);
    $sql = "INSERT INTO usuarios (usuario, contraseña, nombre, apellido, email, rol) VALUES ('$usuario', '$password', '$nombre', '$apellido', '$email','$rol')";
    if ($conexion->query($sql)) {
      // Si se ha insertado correctamente, redirigir al usuario a la página de inicio de sesión
      header('Location: index.php');
      exit;
    } else {
      // Si ha habido un error al insertar los datos, mostrar un mensaje de error
      $error = "Ha habido un error al registrar al usuario.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Registro en E-Ducatea</title>
  <link href="../css/registrocss.css" rel="stylesheet" />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <h1>Registro en E-Ducatea como alumno</h1>
  <form method="post">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>
    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" required>
    <label for="email">Correo electrónico:</label>
    <input type="email" id="email" name="email" required>
    <label for="usuario">Nombre de usuario:</label>
    <input type="text" id="usuario" name="usuario" required>
    <label for="password">Contraseña:</label>
    <input type="password" id="contraseña" name="contraseña" required minlength="5" maxlength="10">
    <div>    
      <label for="rol">Rol:</label>
    <select id="rol" name="rol">
     <option value="alumno">Alumno</option>
     <option value="profesor">Profesor</option>
    </select>
    </div>
    <input type="submit" value="Registrarse" name="register">
    
    <a href="index.php">¿Ya tienes una cuenta? Inicia sesión aquí.</a>
    <?php if (isset($error)) { ?>
             
                <?php echo $error; ?>
              </div>
            <?php } ?>
  </form>
</body>
</html>
