<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'director') {
    header('Location: ../index.php'); // Redirigir si no es un director
    exit;
}

// Verificar si se proporciona un ID de alumno
if (isset($_GET['id'])) {
    $idAlumno = $_GET['id'];

    // Verificar si el ID de alumno es válido
    if (!filter_var($idAlumno, FILTER_VALIDATE_INT)) {
        header('Location: gestionar_alumno.php'); // Redirigir si el ID no es válido
        exit;
    }

    try {
        // Intentar eliminar el alumno
        $queryEliminar = "DELETE FROM usuarios WHERE usuario_id = ?";
        $stmtEliminar = $conexion->prepare($queryEliminar);
        $stmtEliminar->bind_param("i", $idAlumno);

        if ($stmtEliminar->execute()) {
            $_SESSION['mensaje_exito'] = "Alumno eliminado exitosamente.";
        } else {
            $_SESSION['mensaje_error'] = "Error al eliminar el alumno: hay tareas asociadas a este usuario. " ;
        }
    } catch (mysqli_sql_exception $e) {
        // Manejar la excepción de restricción de clave externa
        $_SESSION['mensaje_error'] = "No se puede eliminar el alumno, hay tareas asociadas a este usuario.";
    }

    header('Location: gestionar_alumno.php');
    exit;
} else {
    header('Location: gestionar_alumno.php'); // Redirigir si no se proporciona un ID de alumno
    exit;
}
?>
