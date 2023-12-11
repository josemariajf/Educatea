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

    // Eliminar el alumno de la base de datos
    $queryEliminar = "DELETE FROM usuarios WHERE usuario_id = ?";  // Corregido el nombre de la columna
    $stmt = $conexion->prepare($queryEliminar);
    $stmt->bind_param("i", $idAlumno);

    if ($stmt->execute()) {
        header('Location: gestionar_alumno.php'); // Redirigir a la lista de alumnos después de eliminar
        exit;
    } else {
        echo "Error al eliminar el alumno: " . $stmt->error;
    }
} else {
    header('Location: gestionar_alumno.php'); // Redirigir si no se proporciona un ID de alumno
    exit;
}
?>
