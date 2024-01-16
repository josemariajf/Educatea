<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario tiene acceso
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'director') {
    header('Location: ../index.php'); // Redirigir si no es un director
    exit;
}

// Verificar si se proporciona un ID de profesor
if (isset($_GET['id'])) {
    $idProfesor = $_GET['id'];

    // Verificar si el ID de profesor es válido
    if (!filter_var($idProfesor, FILTER_VALIDATE_INT)) {
        header('Location: gestionar_profesor.php'); // Redirigir si el ID no es válido
        exit;
    }

    try {
        // Eliminar las clases asociadas al profesor
        $queryEliminarClases = "DELETE FROM clases_usuarios WHERE usuario_id = ?";
        $stmtEliminarClases = $conexion->prepare($queryEliminarClases);
        $stmtEliminarClases->bind_param("i", $idProfesor);

        if (!$stmtEliminarClases->execute()) {
            echo "Error al eliminar las clases asociadas al profesor: " . $stmtEliminarClases->error;
            exit;
        }

        // Ahora puedes eliminar al profesor
        $queryEliminar = "DELETE FROM usuarios WHERE usuario_id = ?";
        $stmt = $conexion->prepare($queryEliminar);
        $stmt->bind_param("i", $idProfesor);

        if ($stmt->execute()) {
            $_SESSION['mensaje_exito'] = "Profesor eliminado correctamente.";
            header('Location: gestionar_profesor.php'); // Redirigir a la lista de profesores después de eliminar
            exit;
        } else {
            echo "Error al eliminar al profesor: " . $stmt->error;
        }
    } catch (mysqli_sql_exception $e) {
        // Manejar la excepción de restricción de clave externa
        $_SESSION['mensaje_error'] = "No se puede eliminar al profesor, debido a que pertenece a una clase";
        header('Location: gestionar_profesor.php');
        exit;
    }
} else {
    header('Location: gestionar_profesor.php'); // Redirigir si no se proporciona un ID de profesor
    exit;
}
?>
