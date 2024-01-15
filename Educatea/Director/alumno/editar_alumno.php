<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Inicializar las variables
$id_alumno = $usuario_alumno = $nombre_alumno = $apellido_alumno = $email_alumno = '';
$error_correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la actualización del alumno
    if (isset($_POST['editar_alumno'])) {
        $id_alumno = $_POST['id_alumno'];
        $nuevo_usuario = $_POST['nuevo_usuario'];
        $nuevo_nombre = $_POST['nuevo_nombre'];
        $nuevo_apellido = $_POST['nuevo_apellido'];
        $nueva_contraseña = $_POST['nueva_contraseña'];
        $confirmar_contraseña = $_POST['confirmar_contraseña'];
        $nuevo_email = $_POST['nuevo_email'];

        // Verificar que ambas contraseñas coincidan
        if ($nueva_contraseña !== $confirmar_contraseña) {
            echo "Las contraseñas no coinciden.";
            exit;
        }

        // Encriptar la nueva contraseña con MD5
        $hashed_password = md5($nueva_contraseña);

      

        // Realizar la actualización en la base de datos utilizando consultas preparadas
        $queryActualizar = "UPDATE usuarios SET 
            usuario = ?,
            nombre = ?,
            apellido = ?,
            contrasena = ?,  -- Corregido el nombre de la columna
            correo_electronico = ?
            WHERE usuario_id = ?";  
   
        // Preparar la consulta
        $stmt = $conexion->prepare($queryActualizar);
   
        // Vincular los parámetros
        $stmt->bind_param("sssssi", $nuevo_usuario, $nuevo_nombre, $nuevo_apellido, $hashed_password, $nuevo_email, $id_alumno);
   
        // Ejecutar la consulta
        $stmt->execute();
   
        // Verificar el resultado
        $resultadoActualizar = $stmt->affected_rows > 0;

        // Verificar el resultado
        if ($resultadoActualizar) {
            // Redirigir a gestionar_alumno.php
            header("Location: gestionar_alumno.php");
            exit;
        } else {
            echo "Error al actualizar el alumno: " . $stmt->error;
        }
    }
} else {
    // Obtener el ID del alumno desde la URL
    $id_alumno = $_GET['id'];

    // Obtener la información del alumno
    $queryAlumno = "SELECT usuario_id, usuario, nombre, apellido, correo_electronico FROM usuarios WHERE usuario_id = '$id_alumno' AND rol_id = (SELECT rol_id FROM roles WHERE nombre_rol = 'alumno')";
    $resultadoAlumno = $conexion->query($queryAlumno);

    if ($resultadoAlumno && $resultadoAlumno->num_rows > 0) {
        $rowAlumno = $resultadoAlumno->fetch_assoc();
        $usuario_alumno = $rowAlumno['usuario'];
        $nombre_alumno = $rowAlumno['nombre'];
        $apellido_alumno = $rowAlumno['apellido'];
        $email_alumno = $rowAlumno['correo_electronico'];
    } else {
        echo "No se encontró el alumno.";
        exit;
    }
}

// Función para verificar si el correo electrónico ya existe en la base de datos, excluyendo el usuario actual
function correoExistente($conexion, $email, $usuario_id)
{
    $sql = "SELECT correo_electronico FROM usuarios WHERE correo_electronico = ? AND usuario_id != ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $email, $usuario_id);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script>
        function validarFormulario() {
            var usuario = document.getElementById("nuevo_usuario").value;
            var nombre = document.getElementById("nuevo_nombre").value;
            var apellido = document.getElementById("nuevo_apellido").value;
            var correo = document.getElementById("nuevo_email").value;

            // Expresión regular para verificar que solo contiene letras
            var regex = /^[a-zA-Z]+$/;

            var usuarioError = document.getElementById("usuario-error");
            var nombreError = document.getElementById("nombre-error");
            var apellidoError = document.getElementById("apellido-error");
            var correoError = document.getElementById("correo-error");

            // Restablecer mensajes de error
            usuarioError.innerHTML = "";
            nombreError.innerHTML = "";
            apellidoError.innerHTML = "";
            correoError.innerHTML = "";

            if (!regex.test(usuario)) {
                usuarioError.innerHTML = "El usuario solo puede contener letras.";
                return false;
            }

            if (!regex.test(nombre)) {
                nombreError.innerHTML = "El nombre solo puede contener letras.";
                return false;
            }

            if (!regex.test(apellido)) {
                apellidoError.innerHTML = "El apellido solo puede contener letras.";
                return false;
            }

            if (correo.trim() === "") {
                correoError.innerHTML = "El correo electrónico es obligatorio.";
                return false;
            }

            return true;
        }
    </script>
</head>

<body >
<div class="jumbotron bg-primary text-center text-white">
<img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
    <h2 class="mb-4">Editar Alumno</h2>

    <form method="post" action="editar_alumno.php" onsubmit="return validarFormulario();">
        <input type="hidden" name="id_alumno" value="<?php echo $id_alumno; ?>">

        <div class="form-group">
            <label for="nuevo_usuario">Nuevo Usuario:</label>
            <input type="text" class="form-control" name="nuevo_usuario" id="nuevo_usuario" value="<?php echo $usuario_alumno; ?>" required>
            <div id="usuario-error" style="color: red;"></div>
        </div>

        <div class="form-group">
            <label for="nuevo_nombre">Nuevo Nombre:</label>
            <input type="text" class="form-control" name="nuevo_nombre" id="nuevo_nombre" value="<?php echo $nombre_alumno; ?>" required>
            <div id="nombre-error" style="color: red;"></div>
        </div>

        <div class="form-group">
            <label for="nuevo_apellido">Nuevo Apellido:</label>
            <input type="text" class="form-control" name="nuevo_apellido" id="nuevo_apellido" value="<?php echo $apellido_alumno; ?>" required>
            <div id="apellido-error" style="color: red;"></div>
        </div>

        <div class="form-group">
            <label for="nueva_contraseña">Nueva Contraseña:</label>
            <input type="password" class="form-control" name="nueva_contraseña" required minlength="5" maxlength="10">
        </div>

        <div class="form-group">
            <label for="confirmar_contraseña">Confirmar Contraseña:</label>
            <input type="password" class="form-control" name="confirmar_contraseña" required minlength="5" maxlength="10">
        </div>

        <div class="form-group">
            <label for="nuevo_email">Nuevo Email:</label>
            <input type="email" class="form-control" name="nuevo_email" id="nuevo_email" value="<?php echo $email_alumno; ?>" required>
            <div id="correo-error" style="color: red;"><?php echo $error_correo; ?></div>
        </div>

        <button type="submit" class="btn btn-primary" name="editar_alumno">Guardar Cambios</button>
        <a href="gestionar_alumno.php" class="btn btn-secondary">Volver a la Gestión de Alumnos</a>
    </form>

   
   
    </div>


        <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>

    <!-- Agregamos la referencia a Bootstrap JS y Popper.js al final del cuerpo del documento -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
