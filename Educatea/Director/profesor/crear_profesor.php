<?php
session_start();
require_once "../../funciones.php";

// Datos de conexión a la base de datos
$conexion = conexion();

// Inicializar la variable de error
$error = '';

// Procesar el formulario de registro si se ha enviado
if (isset($_POST['register'])) {
    $usuario = $_POST['usuario'];
    $password = $_POST['contraseña'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    // Verificar que ambas contraseñas coincidan en el lado del servidor
    $confirmPassword = $_POST['confirmar_contraseña'];

    if ($password !== $confirmPassword) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 5 || strlen($password) > 10) {
        $error = "La contraseña debe tener entre 5 y 10 caracteres.";
    } else {
        // Si no hay desajuste de contraseñas y la longitud de la contraseña es válida, procede con el registro
        $password = md5($password);
        // Utilizar sentencia preparada para evitar problemas de SQL injection
        $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena, nombre, apellido, correo_electronico, rol_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $usuario, $password, $nombre, $apellido, $email, $rol);

        if ($stmt->execute()) {
            // Si se inserta correctamente, redirige al usuario a la página de gestión de profesores
            header('Location: gestionar_profesor.php');
            exit;
        } else {
            $error = "Ha habido un error al registrar al usuario: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Registro en E-Ducatea</title>
    <link href="../../../css/registrocss.css" rel="stylesheet" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript">
        // Función para validar la coincidencia de contraseñas
        function validatePassword() {
            // Obtener el valor de la contraseña y confirmar contraseña
            var password = document.getElementById("contraseña").value;
            var confirmPassword = document.getElementById("confirmar_contraseña").value;
            var errorDiv = document.getElementById("password-error");

            // Comprobar si las contraseñas no coinciden
            if (password !== confirmPassword) {
                errorDiv.innerHTML = "Las contraseñas no coinciden.";
                return false; // Impedir la presentación del formulario
            } else {
                errorDiv.innerHTML = ""; // Limpiar cualquier mensaje de error anterior
                return true; // Permitir la presentación del formulario
            }
        }
    </script>
</head>

<body>
    <h1>Registro en E-Ducatea como Profesor</h1>
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
                // Consulta modificada para seleccionar solo el rol "profesor"
                $result = $conexion->query("SELECT rol_id, nombre_rol FROM roles WHERE nombre_rol = 'profesor'");

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['rol_id'] . "'>" . $row['nombre_rol'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div id="password-error" style="color: red;"></div>
        <input type="submit" value="Registrarse" name="register">
        <?php if (isset($error)) { ?>
            <?php echo $error; ?>
        <?php } ?>
        <a href="gestionar_profesor.php">Volver a la gestión de profesor.</a>
    </form>
</body>

</html>
