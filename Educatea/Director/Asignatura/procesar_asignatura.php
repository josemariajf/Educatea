<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $clase_id = $_POST['clase_id'];
    $asignatura_id = $_POST['asignatura_id'];
    $clases_seleccionadas = $_POST['clases_seleccionadas'];
    $clase_seleccionada_id = $_POST['clase_seleccionada_id'];

    // Verificar si ya existe la asignatura en la clase seleccionada
    $queryVerificarExistencia = "SELECT * FROM asignaturas_clases WHERE asignatura_id = '$asignatura_id' AND clase_id = '$clase_seleccionada_id'";
    $resultVerificarExistencia = $conexion->query($queryVerificarExistencia);

    if ($resultVerificarExistencia->num_rows > 0) {
        // Si ya existe, redirigir o manejar el caso
        header("Location: gestionar_asignatura.php?error=asignatura_existente");
        exit();
    }

    // Insertar en la tabla asignaturas_clases para cada clase seleccionada
    foreach ($clases_seleccionadas as $clase_seleccionada) {
        $queryInsert = "INSERT INTO asignaturas_clases (asignatura_id, clase_id) VALUES ('$asignatura_id', '$clase_seleccionada')";
        $resultInsert = $conexion->query($queryInsert);

        if (!$resultInsert) {
            echo "Error al asignar clase: " . mysqli_error($conexion);
            exit();
        }
    }

    // Insertar también la clase seleccionada en la lista
    $queryInsertClaseSeleccionada = "INSERT INTO asignaturas_clases (asignatura_id, clase_id) VALUES ('$asignatura_id', '$clase_seleccionada_id')";
    $resultInsertClaseSeleccionada = $conexion->query($queryInsertClaseSeleccionada);

    if (!$resultInsertClaseSeleccionada) {
        echo "Error al asignar clase seleccionada: " . mysqli_error($conexion);
        exit();
    }

    // Redirigir o realizar cualquier otra acción después de la asignación
    header("Location: gestionar_asignatura.php");
    exit();
} else {
    // Si el formulario no ha sido enviado correctamente, redirigir o manejar el caso
    header("Location: gestionar_asignatura.php");
    exit();
}
?>
