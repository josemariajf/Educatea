<?php
require_once "../../funciones.php";

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

    // Verificar si el correo electrónico ya existe en la base de datos
    $correoExistente = correoExistente($conexion, $email);

    if ($correoExistente) {
        $error = "El correo electrónico ya está registrado.";
    } elseif ($password !== $confirmPassword) {
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
            // Redirigir a gestionar_alumno.php
            header('Location: gestionar_alumno.php');
            exit;
        } else {
            $error = "Ha habido un error al registrar al usuario.";
        }
    }
}

// Función para verificar si el correo electrónico ya existe en la base de datos
function correoExistente($conexion, $email)
{
    $sql = "SELECT correo_electronico FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Registro en E-Ducatea</title>
    <link href="../../../css/registrocss.css" rel="stylesheet" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript">
        function validateForm() {
            // Validar coincidencia de contraseñas
            var password = document.getElementById("contraseña").value;
            var confirmPassword = document.getElementById("confirmar_contraseña").value;
            var errorDiv = document.getElementById("password-error");

            if (password !== confirmPassword) {
                errorDiv.innerHTML = "Las contraseñas no coinciden.";
                return false;
            } else {
                errorDiv.innerHTML = "";
            }

            // Validar nombre (debe contener solo letras)
            var nombre = document.getElementById("nombre").value;
            var nombreErrorDiv = document.getElementById("nombre-error");

            if (!/^[a-zA-Z]+$/.test(nombre)) {
                nombreErrorDiv.innerHTML = "El nombre solo puede contener letras.";
                return false;
            } else {
                nombreErrorDiv.innerHTML = "";
            }

            // Validar apellido (debe contener solo letras)
            var apellido = document.getElementById("apellido").value;
            var apellidoErrorDiv = document.getElementById("apellido-error");

            if (!/^[a-zA-Z]+$/.test(apellido)) {
                apellidoErrorDiv.innerHTML = "El apellido solo puede contener letras.";
                return false;
            } else {
                apellidoErrorDiv.innerHTML = "";
            }

            // Validar usuario (debe contener solo letras)
            var usuario = document.getElementById("usuario").value;
            var usuarioErrorDiv = document.getElementById("usuario-error");

            if (!/^[a-zA-Z]+$/.test(usuario)) {
                usuarioErrorDiv.innerHTML = "El nombre de usuario solo puede contener letras.";
                return false;
            } else {
                usuarioErrorDiv.innerHTML = "";
            }

            // Validar correo electrónico
            var email = document.getElementById("email").value;
            var emailErrorDiv = document.getElementById("email-error");

            if (!validateEmail(email)) {
                emailErrorDiv.innerHTML = "Ingrese un correo electrónico válido.";
                return false;
            } else {
                emailErrorDiv.innerHTML = "";
            }

            return true;
        }

        function validateEmail(email) {
            // Puedes agregar una lógica más avanzada aquí si es necesario
            // Esto es solo un ejemplo básico
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</head>
<body>
<img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 0px; left: 10px; max-width: 100px; max-height: 100px;">
    <h1>Registro en E-Ducatea como alumno</h1>
    <form method="post" onsubmit="return validateForm();">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <div id="nombre-error" style="color: red;"></div>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>
        <div id="apellido-error" style="color: red;"></div>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>
        <div id="email-error" style="color: red;"></div>

        <label for="usuario">Nombre de usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
        <div id="usuario-error" style="color: red;"></div>

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
        <a href="gestionar_alumno.php">Volver a la gestión de alumno.</a>

        <?php if (isset($error)) { ?>
            <div style="color: red;">
                <?php echo $error; ?>
            </div>
        <?php } ?>
    </form>

    
            
</body>
</html>
