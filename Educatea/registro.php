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

    // Verificar si el correo electrónico ya está en uso
    $queryVerificarCorreo = "SELECT usuario_id FROM usuarios WHERE correo_electronico = ?";
    $stmtVerificarCorreo = $conexion->prepare($queryVerificarCorreo);
    $stmtVerificarCorreo->bind_param("s", $email);
    $stmtVerificarCorreo->execute();
    $resultVerificarCorreo = $stmtVerificarCorreo->get_result();

    if ($resultVerificarCorreo->num_rows > 0) {
        $error = "El correo electrónico ya está en uso.";
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
        function validateForm() {
            var nombre = document.getElementById("nombre").value;
            var apellido = document.getElementById("apellido").value;
            var usuario = document.getElementById("usuario").value;
            var errorNombre = document.getElementById("error-nombre");
            var errorApellido = document.getElementById("error-apellido");
            var errorUsuario = document.getElementById("error-usuario");

            // Expresión regular para permitir solo letras
            var regex = /^[A-Za-z]+$/;

            if (!regex.test(nombre)) {
                errorNombre.innerHTML = "Solo se permiten letras en el nombre.";
                return false;
            } else {
                errorNombre.innerHTML = "";
            }

            if (!regex.test(apellido)) {
                errorApellido.innerHTML = "Solo se permiten letras en el apellido.";
                return false;
            } else {
                errorApellido.innerHTML = "";
            }

            if (!regex.test(usuario)) {
                errorUsuario.innerHTML = "Solo se permiten letras en el usuario.";
                return false;
            } else {
                errorUsuario.innerHTML = "";
            }

            return true;
        }

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
    <form method="post" onsubmit="return validateForm() && validatePassword();">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <div id="error-nombre" style="color: red;"></div>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>
        <div id="error-apellido" style="color: red;"></div>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="usuario">Nombre de usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
        <div id="error-usuario" style="color: red;"></div>

        <label for="password">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required minlength="5" maxlength="10">

        <label for="confirmar_contraseña">Confirmar Contraseña:</label>
        <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" required minlength="5" maxlength="10">

        <div>
            <label for="rol">Rol:</label>
            <select id="rol" name="rol">
                <?php
        
                $result = $conexion->query("SELECT rol_id, nombre_rol FROM roles WHERE nombre_rol = 'alumno'");
                
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['rol_id'] . "'>" . $row['nombre_rol'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div id="password-error" style="color: red;"></div>
        
        <?php if (isset($error)) { ?>
            <div style="color: red;">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <input type="submit" value="Registrarse" name="register">
        <a href="index.php">¿Ya tienes una cuenta? Inicia sesión aquí.</a>

       
        
    </form>
</body>
</html>

