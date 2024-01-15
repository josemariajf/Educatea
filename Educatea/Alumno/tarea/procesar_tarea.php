<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['id'])) {
        $id_asignatura = $_GET['id'];

        // Procesamiento de la tarea
        if (isset($_FILES['archivo'])) {
            $archivo_nombre = $_FILES['archivo']['name'];
            $archivo_temporal = $_FILES['archivo']['tmp_name'];
            $ruta_destino = "../../Tareas/"; // Cambia esto por la ruta adecuada en tu servidor
            $ruta_final = $ruta_destino . $archivo_nombre;

            if (move_uploaded_file($archivo_temporal, $ruta_final)) {
                // Archivo cargado exitosamente, ahora puedes guardar la información en la base de datos
                $usuario_id = $_SESSION['usuario']['usuario_id'];
                $calificacion = null; // Puedes establecer un valor predeterminado según tu lógica

                // Obtener el ID de la tarea recién creada
                $tarea_id = obtenerIdTarea();

                // Verificar que el tarea_id exista en la tabla tareas
                $sql_verificar_tarea = "SELECT tarea_id FROM tareas WHERE tarea_id = ? AND asignatura_id = ?";
                $stmt_verificar_tarea = $conexion->prepare($sql_verificar_tarea);
                $stmt_verificar_tarea->bind_param("ii", $tarea_id, $id_asignatura);
                $stmt_verificar_tarea->execute();
                $stmt_verificar_tarea->store_result();

                if ($stmt_verificar_tarea->num_rows > 0) {
                    // El tarea_id existe en la tabla tareas, proceder con la inserción en tareas_usuarios
                    $sql_insertar_tarea = "INSERT INTO tareas_usuarios (tarea_id, usuario_id, calificacion, url, fecha_entrega)
                                           VALUES (?, ?, ?, ?, NOW())";
                    $stmt_insertar_tarea = $conexion->prepare($sql_insertar_tarea);
                    $stmt_insertar_tarea->bind_param("iiss", $tarea_id, $usuario_id, $calificacion, $ruta_final);

                    $stmt_insertar_tarea->execute();

                    if ($stmt_insertar_tarea->affected_rows > 0) {
                        echo "Tarea guardada correctamente.";
                    } else {
                        echo "Error al guardar la tarea en la base de datos.";
                    }

                    $stmt_insertar_tarea->close();
                } else {
                    echo "El tarea_id no existe en la asignatura actual.";
                }

                $stmt_verificar_tarea->close();
            } else {
                echo "Error al cargar el archivo en el servidor.";
            }
        } else {
            echo "No se ha enviado ningún archivo.";
        }

        // ... (código existente)
    } else {
        echo "ID de asignatura no válido.";
    }
} else {
    echo "Método de solicitud no válido.";
}

function obtenerIdTarea() {
    global $conexion;

    $sql_ultimo_id = "SELECT MAX(tarea_id) AS ultimo_id FROM tareas_usuarios";
    $resultado = $conexion->query($sql_ultimo_id);

    if ($fila = $resultado->fetch_assoc()) {
        return $fila['ultimo_id'] + 1;
    } else {
        return 1; // Si no hay ninguna tarea registrada, devuelve 1 como ID inicial
    }
}
?>
