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

// Verificar si se ha proporcionado un ID para eliminar la clase
if (isset($_POST['eliminar_clase'])) {
    $clase_id = $_POST['clase_id'];

    // Verificar si hay usuarios asociados a la clase
    $queryUsuariosAsociados = "SELECT COUNT(*) AS total FROM clases_usuarios WHERE clase_id = $clase_id";
    $resultUsuariosAsociados = $conexion->query($queryUsuariosAsociados);
    $rowUsuariosAsociados = $resultUsuariosAsociados->fetch_assoc();

    if ($rowUsuariosAsociados['total'] > 0) {
        $_SESSION['mensaje_error'] = "No se puede eliminar la clase, hay usuarios asociados.";
        header('Location: gestionar_clases.php');
        exit;
    }

    // Eliminar relaciones en clases_usuarios
    $queryEliminarRelaciones = "DELETE FROM clases_usuarios WHERE clase_id = $clase_id";
    $conexion->query($queryEliminarRelaciones);

    // Eliminar las asignaturas asociadas a la clase (cascada)
    $queryEliminarAsignaturas = "DELETE FROM asignaturas_clases WHERE clase_id = $clase_id";
    $conexion->query($queryEliminarAsignaturas);

    // Realizar la eliminación de la clase
    $queryEliminarClase = "DELETE FROM clases WHERE clase_id = $clase_id";
    $resultadoEliminarClase = $conexion->query($queryEliminarClase);

    if ($resultadoEliminarClase) {
        $_SESSION['mensaje_exito'] = 'Clase eliminada exitosamente.';
    } else {
        $_SESSION['mensaje_error'] = 'Error al eliminar la clase: ' . $conexion->error;
    }
} else {
    // Si no se proporcionó un ID válido, redirigir a la página principal
    header('Location: gestionar_clases.php');
    exit;
}

// Redirigir de nuevo a la página principal después de la eliminación
header('Location: gestionar_clases.php');
exit;
?>
