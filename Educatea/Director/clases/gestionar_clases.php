<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['nombre_rol'];

// Verificar si el usuario tiene permisos de administrador
if ($rol !== 'director') {
    header('Location: ../../index.php');
    exit;
}

// Mensajes de éxito o error después de procesar el formulario
$mensaje = '';

// Procesar el formulario cuando se envía para eliminar una clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_clase'])) {
    $clase_id = $_POST['clase_id'];

    // Llamar a la función para eliminar la clase desde funciones.php
    $eliminacionExitosa = eliminarClase($clase_id);

    if ($eliminacionExitosa) {
        $mensaje = 'Clase eliminada exitosamente.';
    } else {
        $mensaje = 'Error al eliminar la clase.';
    }
}

// Obtener las clases existentes después de la posible eliminación
$clases = obtenerClases();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clases</title>
    <link rel="stylesheet" href="../css/gestion.css">
</head>

<body>
    <h1>Gestión de Clases</h1>

    <h2>Clases Existentes</h2>
    <div>
        <table border="1">
            <thead>
                <tr>
                    <th>Clase ID</th>
                    <th>Nombre Clase</th>
                    <th>Curso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clases as $clase) : ?>
                    <tr>
                        <td><?php echo $clase['clase_id']; ?></td>
                        <td><?php echo $clase['nombre_clase']; ?></td>
                        <td><?php echo $clase['curso']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="clase_id" value="<?php echo $clase['clase_id']; ?>">
                                <button type="submit" name="eliminar_clase">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href='crear_clase.php'><button>Crear Clase</button></a>
    </div>
    <a href="../../Roles/inicio_director.php">Volver al Inicio</a>
    <!-- Mostrar mensajes de éxito o error -->
    <?php if (!empty($mensaje)) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>
</body>

</html>
