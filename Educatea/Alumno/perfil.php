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

// Obtener el nombre de la clase del usuario si existe clase_id
$clase_id = isset($usuario['clase_id']) ? $usuario['clase_id'] : null;
$nombre_clase_result = obtenerNombreClase($clase_id, $conexion);

function obtenerNombreClase($clase_id, $conexion) {
    if ($clase_id === null) {
        return 'No asignada';
    }

    $nombre_clase = ''; // Inicializamos la variable
    $query = "SELECT nombre_clase FROM Clases WHERE clase_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $clase_id);
    $stmt->execute();
    $stmt->bind_result($nombre_clase);
    
    // Verificar si se encontró una clase
    if ($stmt->fetch()) {
        $stmt->close();
        return $nombre_clase;
    } else {
        $stmt->close();
        return 'No asignada';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Perfil del Usuario</title>
    <link rel="stylesheet" href="../../css/perfil.css">
</head>

<body>
    <div id="header">
        <h1>Perfil de <?php echo $nombre . " " . $apellido; ?></h1>
    </div>

    <div id="cuerpo">
        <h2>Información del Usuario</h2>
        <table border="1">
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
                <td><?php echo $nombre_clase_result; ?></td>
            </tr>
        </table>
        <button onclick="location.href='Asignaturas/nota.php'">Notas</button>
    </div>
    <a href="../Roles/inicio_alumno.php"><button>Volver a inicio</button></a>
</body>

</html>
