<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Obtener el ID de la tarea desde la URL
$tarea_id = isset($_GET['tarea_id']) ? $_GET['tarea_id'] : null;

// Verificar si se proporcionó el ID de la tarea
if ($tarea_id === null) {
    echo "<div class='alert alert-danger' role='alert'>Error: No se proporcionó el ID de la tarea.</div>";
    exit();
}

// Consulta preparada para obtener la información del archivo
$sqlArchivo = "SELECT url FROM tareas_usuarios WHERE tarea_id = ? AND usuario_id = ?";
$stmtArchivo = $conexion->prepare($sqlArchivo);
$stmtArchivo->bind_param("ii", $tarea_id, $_SESSION['usuario']);
$stmtArchivo->execute();
$resultadoArchivo = $stmtArchivo->get_result();

if ($resultadoArchivo->num_rows > 0) {
    $filaArchivo = $resultadoArchivo->fetch_assoc();
    $urlArchivo = $filaArchivo['url'];

    // Descargar el archivo
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($urlArchivo) . '"');
    readfile($urlArchivo);
} else {
    echo "<div class='alert alert-warning' role='alert'>No se encontró el archivo para descargar.</div>";
}

$stmtArchivo->close();
$conexion->close();
?>
