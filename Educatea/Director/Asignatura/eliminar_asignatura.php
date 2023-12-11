<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'director') {
    header('Location: ../index.php'); // Redirigir si no es un director
    exit;
}

// Verificar si se ha proporcionado un ID para eliminar la asignatura
if (isset($_GET['id'])) {
    $id_asignatura = $_GET['id'];

    // Verificar y eliminar las filas relacionadas en asignaturas_clases
    $queryVerificarRelaciones = "SELECT asignatura_id FROM asignaturas_clases WHERE asignatura_id = $id_asignatura";
    $resultadoRelaciones = $conexion->query($queryVerificarRelaciones);

    if ($resultadoRelaciones->num_rows > 0) {
        // Eliminar las filas relacionadas en asignaturas_clases
        $queryEliminarRelaciones = "DELETE FROM asignaturas_clases WHERE asignatura_id = $id_asignatura";
        $conexion->query($queryEliminarRelaciones);
    }

    // Realizar la eliminación de la asignatura
    $queryEliminar = "DELETE FROM asignaturas WHERE asignatura_id = $id_asignatura";
    $resultadoEliminar = $conexion->query($queryEliminar);

    // Redirigir de nuevo a la página principal después de la eliminación
    header('Location: gestionar_asignatura.php');
    exit;
} else {
    // Si no se proporcionó un ID válido, redirigir a la página principal
    header('Location: gestionar_asignatura.php');
    exit;
}
?>
