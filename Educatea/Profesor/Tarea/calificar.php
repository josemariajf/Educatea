<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si se recibió el formulario de calificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tarea_id'], $_POST['nota'])) {
    $tarea_id = $_POST['tarea_id'];
    $nota = $_POST['nota'];

    // Verificar si el usuario tiene permisos para calificar
    // Puedes agregar la lógica de verificación del rol de usuario aquí

    // Consulta preparada para actualizar la nota
    $sqlCalificar = "UPDATE tareas_usuarios SET calificacion = ? WHERE tarea_id = ? AND usuario_id = ?";
    $stmtCalificar = $conexion->prepare($sqlCalificar);
    $stmtCalificar->bind_param("dii", $nota, $tarea_id, $_SESSION['usuario']);

    if ($stmtCalificar->execute()) {
        // Calificación actualizada con éxito, redirigir a ver_tarea.php
        header("Location: ver_tarea.php?id=$tarea_id");
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error al actualizar la calificación: " . $stmtCalificar->error . "</div>";
    }

    $stmtCalificar->close();
} else {
    echo "<div class='alert alert-danger' role='alert'>Error: No se recibieron datos válidos para calificar.</div>";
}

$conexion->close();
?>
