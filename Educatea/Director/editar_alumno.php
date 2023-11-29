<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

// Inicializar las variables
$id_alumno = $usuario_alumno = $nombre_alumno = $apellido_alumno = $email_alumno = '';

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
            contraseña = ?,
            email = ?
            WHERE id = ?";
   
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
    $queryAlumno = "SELECT id, usuario, nombre, apellido, email FROM usuarios WHERE id = '$id_alumno' AND id_rol = (SELECT id_rol FROM roles WHERE nombre_rol = 'alumno')";
    $resultadoAlumno = $conexion->query($queryAlumno);

    if ($resultadoAlumno && $resultadoAlumno->num_rows > 0) {
        $rowAlumno = $resultadoAlumno->fetch_assoc();
        $usuario_alumno = $rowAlumno['usuario'];
        $nombre_alumno = $rowAlumno['nombre'];
        $apellido_alumno = $rowAlumno['apellido'];
        $email_alumno = $rowAlumno['email'];
    } else {
        echo "No se encontró el alumno.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Alumno</title>
</head>

<body>
    <h2>Editar Alumno</h2>

    <form method="post" action="editar_alumno.php">
        <input type="hidden" name="id_alumno" value="<?php echo $id_alumno; ?>">
        
        <label for="nuevo_usuario">Nuevo Usuario:</label>
        <input type="text" name="nuevo_usuario" value="<?php echo $usuario_alumno; ?>" required>
        <br/><br/>
        
        <label for="nuevo_nombre">Nuevo Nombre:</label>
        <input type="text" name="nuevo_nombre" value="<?php echo $nombre_alumno; ?>" required>
        <br/><br/>

        <label for="nuevo_apellido">Nuevo Apellido:</label>
        <input type="text" name="nuevo_apellido" value="<?php echo $apellido_alumno; ?>" required>
        <br/><br/>
        
        <label for="nueva_contraseña">Nueva Contraseña:</label>
        <input type="password" name="nueva_contraseña" required minlength="5" maxlength="10">
        <br/><br/>
        
        <label for="confirmar_contraseña">Confirmar Contraseña:</label>
        <input type="password" name="confirmar_contraseña" required minlength="5" maxlength="10">
        <br/><br/>
        
        <label for="nuevo_email">Nuevo Email:</label>
        <input type="email" name="nuevo_email" value="<?php echo $email_alumno; ?>" required>
        <br/><br/>
        
        <input type="submit" name="editar_alumno" value="Guardar Cambios">
    </form>

    <br>
    <a href="gestionar_alumno.php">Volver a la Gestión de Alumnos</a>
</body>

</html>
