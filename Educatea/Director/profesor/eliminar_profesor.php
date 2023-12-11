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
        header('Location: gestionar_profesor.php'); // Redirigir si el ID no es válido
        exit;
    }

    // Eliminar las clases asociadas al usuario
    $queryEliminarClases = "DELETE FROM clases_usuarios WHERE usuario_id = ?";
    $stmtEliminarClases = $conexion->prepare($queryEliminarClases);
    $stmtEliminarClases->bind_param("i", $idAlumno);

    if (!$stmtEliminarClases->execute()) {
        echo "Error al eliminar las clases asociadas al alumno: " . $stmtEliminarClases->error;
        exit;
    }

    // Ahora puedes eliminar al usuario
    $queryEliminar = "DELETE FROM usuarios WHERE usuario_id = ?";
    $stmt = $conexion->prepare($queryEliminar);
    $stmt->bind_param("i", $idAlumno);

    if ($stmt->execute()) {
        header('Location: gestionar_profesor.php'); // Redirigir a la lista de profesor después de eliminar
        exit;
    } else {
        echo "Error al eliminar el alumno: " . $stmt->error;
    }
} else {
    header('Location: gestionar_profesor.php'); // Redirigir si no se proporciona un ID de profesor
    exit;
}
?>
