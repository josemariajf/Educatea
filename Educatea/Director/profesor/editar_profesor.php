<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario tiene el rol de director
if ($_SESSION['rol'] !== 'director') {
    echo "Acceso no autorizado. Debes ser Director.";
    exit;
}

// Inicializar las variables
$id_profesor = $usuario_profesor = $nombre_profesor = $apellido_profesor = $email_profesor = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la actualización del profesor
    if (isset($_POST['editar_profesor'])) {
        $id_profesor = $_POST['id_profesor'];
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
            contrasena = ?,
            correo_electronico = ?
            WHERE usuario_id = ? AND rol_id = (SELECT rol_id FROM roles WHERE nombre_rol = 'profesor')";  
   
        // Preparar la consulta
        $stmt = $conexion->prepare($queryActualizar);
   
        // Vincular los parámetros
        $stmt->bind_param("sssssi", $nuevo_usuario, $nuevo_nombre, $nuevo_apellido, $hashed_password, $nuevo_email, $id_profesor);
   
        // Ejecutar la consulta
        $stmt->execute();
   
        // Verificar el resultado
        $resultadoActualizar = $stmt->affected_rows > 0;

        // Verificar el resultado
        if ($resultadoActualizar) {
            // Redirigir a gestionar_profesores.php
            header("Location: gestionar_profesor.php");
            exit;
        } else {
            echo "Error al actualizar el profesor: " . $stmt->error;
        }
    }
} else {
    // Obtener el ID del profesor desde la URL
    $id_profesor = $_GET['id'];

    // Obtener la información del profesor
    $queryProfesor = "SELECT usuario_id, usuario, nombre, apellido, correo_electronico FROM usuarios WHERE usuario_id = '$id_profesor' AND rol_id = (SELECT rol_id FROM roles WHERE nombre_rol = 'profesor')";
    $resultadoProfesor = $conexion->query($queryProfesor);

    if ($resultadoProfesor && $resultadoProfesor->num_rows > 0) {
        $rowProfesor = $resultadoProfesor->fetch_assoc();
        $usuario_profesor = $rowProfesor['usuario'];
        $nombre_profesor = $rowProfesor['nombre'];
        $apellido_profesor = $rowProfesor['apellido'];
        $email_profesor = $rowProfesor['correo_electronico'];
    } else {
        echo "No se encontró el profesor.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profesor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('form').submit(function (event) {
                var nuevaContraseña = $('#nueva_contraseña').val();
                var confirmarContraseña = $('#confirmar_contraseña').val();

                if (nuevaContraseña !== confirmarContraseña) {
                    alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');
                    event.preventDefault(); // Evitar que el formulario se envíe
                }
            });
        });
    </script>
</head>

<body>
<div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4">Editar Profesor</h2>

        <form method="post" action="editar_profesor.php">
            <input type="hidden" name="id_profesor" value="<?php echo $id_profesor; ?>">

            <div class="form-group">
                <label for="nuevo_usuario">Nuevo Usuario:</label>
                <input type="text" class="form-control" name="nuevo_usuario" value="<?php echo $usuario_profesor; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevo_nombre">Nuevo Nombre:</label>
                <input type="text" class="form-control" name="nuevo_nombre" value="<?php echo $nombre_profesor; ?>" required>
            </div>

            <div class="form-group">
                <label for="nuevo_apellido">Nuevo Apellido:</label>
                <input type="text" class="form-control" name="nuevo_apellido" value="<?php echo $apellido_profesor; ?>" required>
            </div>

            <div class="form-group">
                <label for="nueva_contraseña">Nueva Contraseña:</label>
                <input type="password" class="form-control" id="nueva_contraseña" name="nueva_contraseña" required minlength="5" maxlength="10">
            </div>

            <div class="form-group">
                <label for="confirmar_contraseña">Confirmar Contraseña:</label>
                <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" required minlength="5" maxlength="10">
            </div>

            <div class="form-group">
                <label for="nuevo_email">Nuevo Email:</label>
                <input type="email" class="form-control" name="nuevo_email" value="<?php echo $email_profesor; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary" name="editar_profesor">Guardar Cambios</button>
        </form>

        <div class="mt-3">
            <a href="gestionar_profesor.php" class="btn btn-secondary">Volver a la Gestión de Profesores</a>
        </div>
    </div>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

