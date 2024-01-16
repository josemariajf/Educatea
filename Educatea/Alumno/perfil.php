<?php
session_start();
require_once "../funciones.php";

// Datos de conexión a la base de datos
$conexion = conexion();

// Verificar si el usuario ha iniciado sesión y si tiene la clave 'clase_id' en la información
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$nombre = $usuario['nombre'];
$apellido = $usuario['apellido'];
$correo = $usuario['correo_electronico'];

// Obtener la información de la clase, alumno y tutor
$infoClase = obtenerInformacionClase($usuario['usuario_id'], $conexion);

function obtenerInformacionClase($usuario_id, $conexion) {
    $query = "SELECT c.nombre_clase, c.curso, c.id_tutor
              FROM clases c
              JOIN clases_usuarios cu ON c.clase_id = cu.clase_id
              WHERE cu.usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    return $result;
}

function obtenerNombreClase($clase_id, $conexion) {
    if ($clase_id === null) {
        return 'No asignada';
    }

    $nombre_clase = ''; // Inicializamos la variable
    $query = "SELECT nombre_clase FROM clases WHERE clase_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $clase_id);
    $stmt->execute();
    
    // Variable para almacenar el resultado de bind_result
    $nombre_clase_result = null;
    $stmt->bind_result($nombre_clase_result);
    
    // Verificar si se encontró una clase
    if ($stmt->fetch()) {
        $stmt->close();
        return $nombre_clase_result;
    } else {
        $stmt->close();
        return 'No asignada';
    }
}

function obtenerNombreTutor($tutor_id, $conexion) {
    if ($tutor_id === null) {
        return 'No asignado';
    }

    $nombre_tutor = ''; // Inicializamos la variable
    $query = "SELECT nombre, apellido FROM usuarios WHERE usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $tutor_id);
    $stmt->execute();
    
    // Variables para almacenar el resultado de bind_result
    $nombre_result = null;
    $apellido_result = null;
    $stmt->bind_result($nombre_result, $apellido_result);
    
    // Verificar si se encontró un tutor
    if ($stmt->fetch()) {
        $stmt->close();
        return $nombre_result . " " . $apellido_result;
    } else {
        $stmt->close();
        return 'No asignado';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Perfil del Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/perfil.css">
</head>

<body>
<div class="jumbotron bg-primary text-center text-white">
<img src="../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>

    <div class="container mt-5">
        <div class="text-center">
            <h1>Perfil de <?php echo $nombre . " " . $apellido; ?></h1>
        </div>

        <div>
            <h2 class="mt-4">Información del Usuario</h2>
            <table class="table table-bordered">
                <tr>
                    <th>Nombre y Apellido</th>
                    <td><?php echo $nombre . " " . $apellido; ?></td>
                </tr>
                <tr>
                    <th>Correo Electrónico</th>
                    <td><?php echo $correo; ?></td>
                </tr>
                <tr>
                    <th>Clase</th>
                    <td><?php echo isset($infoClase['nombre_clase']) ? $infoClase['nombre_clase'] : 'No asignada'; ?></td>
                </tr>
                <tr>
                    <th>Tutor</th>
                    <td><?php echo obtenerNombreTutor($infoClase['id_tutor'] ?? null, $conexion); ?></td>
                </tr>
            </table>
          
        </div>

        <a href="../Roles/inicio_alumno.php" class="btn btn-secondary mt-3">Volver a inicio</a>
    </div>


        <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>


    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
