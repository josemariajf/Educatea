<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si se ha enviado el ID de la clase y los alumnos seleccionados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clase_id']) && isset($_POST['alumnos_seleccionados'])) {
    $clase_id = $_POST['clase_id'];
    $alumnos_seleccionados = json_decode($_POST['alumnos_seleccionados'], true);

    // Verificar si los alumnos ya están asociados a la clase
    $queryVerificar = "SELECT usuario_id FROM clases_usuarios WHERE clase_id = $clase_id AND usuario_id IN (" . implode(',', $alumnos_seleccionados) . ")";
    $resultVerificar = $conexion->query($queryVerificar);

    // Verificar si se pudo realizar la consulta
    if (!$resultVerificar) {
        echo "Error al verificar usuarios: " . mysqli_error($conexion);
        exit();
    }

    $usuariosYaAsociados = [];
    while ($fila = $resultVerificar->fetch_assoc()) {
        $usuariosYaAsociados[] = $fila['usuario_id'];
    }

    // Insertar alumnos en la tabla clases_usuarios solo si no están ya asociados
    foreach ($alumnos_seleccionados as $alumno_id) {
        if (!in_array($alumno_id, $usuariosYaAsociados)) {
            $queryInsert = "INSERT INTO clases_usuarios (clase_id, usuario_id) VALUES ($clase_id, $alumno_id)";
            $resultInsert = $conexion->query($queryInsert);

            // Verificar si se pudo realizar la inserción
            if (!$resultInsert) {
                echo "Error al insertar alumnos: " . mysqli_error($conexion);
                exit();
            }
        }
    }

    // Redirigir a la página de inicio del director o a donde sea necesario
    header('Location: gestionar_clases.php');
    exit;
} else {
    // Si no se proporciona el ID de la clase o los alumnos seleccionados, redirige a alguna página de manejo de errores.
    header('Location: error.php');
    exit;
}

?>
