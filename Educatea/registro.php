<?php
require_once "funciones.php";

$conexion = conexion();
$error = '';

if (isset($_POST['register'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['contraseña'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    $confirmPassword = $_POST['confirmar_contraseña'];

    if ($password !== $confirmPassword) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 5 || strlen($password) > 10) {
        $error = "La contraseña debe tener entre 5 y 10 caracteres.";
    } else {
        // Cifrar la contraseña con MD5 (no se recomienda por motivos de seguridad)
        $password = md5($password);

        $sql = "INSERT INTO usuarios (usuario, contrasena, nombre, apellido, correo_electronico, rol_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssss", $usuario, $password, $nombre, $apellido, $email, $rol);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
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
    <script type="text/javascript">
        function validatePassword() {
            var password = document.getElementById("contraseña").value;
            var confirmPassword = document.getElementById("confirmar_contraseña").value;
            var errorDiv = document.getElementById("password-error");

            if (password !== confirmPassword) {
                errorDiv.innerHTML = "Las contraseñas no coinciden.";
                return false;
            } else {
                errorDiv.innerHTML = "";
                return true;
            }
        }
    </script>
</head>
<body>
    <h1>Registro en E-Ducatea como alumno</h1>
    <form method="post" onsubmit="return validatePassword();">
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
        <label for="confirmar_contraseña">Confirmar Contraseña:</label>
        <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" required minlength="5" maxlength="10">
       <div>
            <label for="rol">Rol:</label>
            <select id="rol" name="rol">
                <?php
                // Consulta modificada para seleccionar solo el rol "alumno"
                $result = $conexion->query("SELECT rol_id, nombre_rol FROM roles WHERE nombre_rol = 'alumno'");
                
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['rol_id'] . "'>" . $row['nombre_rol'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div id="password-error" style="color: red;"></div>
        <input type="submit" value="Registrarse" name="register">
        <a href="index.php">¿Ya tienes una cuenta? Inicia sesión aquí.</a>
        <?php if (isset($error)) { ?>
            <?php echo $error; ?>
        <?php } ?>
    </form>
</body>
</html>
