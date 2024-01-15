<?php
session_start();
require_once "funciones.php";

$conexion = conexion();
$error = '';

if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['contrasena'];

    // Consulta preparada para evitar la inyección SQL y obtener información de ambas tablas
    $sql = "SELECT usuarios.usuario_id, usuarios.usuario, usuarios.contrasena, usuarios.nombre, usuarios.apellido, usuarios.correo_electronico, roles.nombre_rol
            FROM usuarios
            INNER JOIN roles ON usuarios.rol_id = roles.rol_id
            WHERE usuarios.usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Verificar la contraseña utilizando password_verify
        if (md5($password) === $usuario['contrasena']) {
          // Contraseña correcta, almacenar información en la sesión
          $_SESSION['usuario'] = $usuario;
          $_SESSION['rol'] = $usuario['nombre_rol'];

            // Redirigir al usuario a una página de inicio específica dependiendo de su rol
            switch ($_SESSION['rol']) {
                case 'alumno':
                    header('Location: roles/inicio_alumno.php');
                    break;
                case 'profesor':
                    header('Location: roles/inicio_profesor.php');
                    break;
                case 'director':
                    header('Location: roles/inicio_director.php');
                    break;
                default:
                    // Manejar cualquier otro rol según sea necesario
                    header('Location: roles/inicio_general.php');
            }
            exit;
        } else {
            // Contraseña incorrecta
            $error = "Nombre de usuario o contraseña incorrectos.";
        }
    } else {
        // Usuario no encontrado
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
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required minlength="5" maxlength="255">
        <input type="submit" value="Iniciar sesión" name="login">
        <a href="registro.php" >Registrarse</a>
        <?php if(isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
    </form>
</body>
</html>
