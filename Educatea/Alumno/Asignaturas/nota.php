<?php
session_start();
require_once "../../funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$usuario_id = $usuario['usuario_id'];

// Obtener las asignaturas del usuario
$asignaturas_usuario = obtenerAsignaturasUsuario($usuario_id);

function obtenerAsignaturasUsuario($usuario_id) {
    global $conexion;
    $asignaturas = array(); // Inicializamos el array
    $query = "SELECT asignaturas.asignatura_id, asignaturas.nombre_asignatura, asignaturas.codigo_asignatura
              FROM Usuarios_Asignaturas
              JOIN asignaturas ON Usuarios_Asignaturas.asignatura_id = asignaturas.asignatura_id
              WHERE Usuarios_Asignaturas.usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($asignatura_id, $nombre_asignatura, $codigo_asignatura);

    while ($stmt->fetch()) {
        $asignaturas[] = array(
            'asignatura_id' => $asignatura_id,
            'nombre_asignatura' => $nombre_asignatura,
            'codigo_asignatura' => $codigo_asignatura
        );
    }

    $stmt->close();
    return $asignaturas;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaturas del Usuario</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h1>Asignaturas del Usuario</h1>

    <?php if (!empty($asignaturas_usuario)) : ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Asignatura ID</th>
                    <th>Nombre Asignatura</th>
                    <th>Código Asignatura</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asignaturas_usuario as $asignatura) : ?>
                    <tr>
                        <td><?php echo $asignatura['asignatura_id']; ?></td>
                        <td><?php echo $asignatura['nombre_asignatura']; ?></td>
                        <td><?php echo $asignatura['codigo_asignatura']; ?></td>
                        <td>
                            <form action="ver_tareas.php" method="post">
                                <input type="hidden" name="asignatura_id" value="<?php echo $asignatura['asignatura_id']; ?>">
                                <input type="submit" value="Ver Tareas">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No hay asignaturas asignadas para este usuario.</p>
    <?php endif; ?>

    <a href="../perfil.php">Volver al perfil</a>
</body>

</html>
