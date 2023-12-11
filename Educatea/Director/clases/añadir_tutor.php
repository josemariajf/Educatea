<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario ha iniciado sesión y tiene permisos de director
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nombre_rol'] !== 'director') {
    header('Location: ../../index.php');
    exit;
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clase_id = $_POST['clase_id'];
    if(isset($_POST['tutor_id'])){
    $tutor_id = $_POST['tutor_id'];
    }else{
    $tutor_id = 0;
}
    if (empty($clase_id) || empty($tutor_id)) {
        $mensaje = 'Todos los campos son obligatorios.';
    } else {
        // Verificar si ya existe un tutor asignado a la clase
        $tutorExistente = obtenerTutorDeClase($clase_id);

        if ($tutorExistente) {
            // Si ya hay un tutor asignado, actualizar el tutor
            actualizarTutorDeClase($clase_id, $tutor_id);
            $mensaje = 'Tutor actualizado exitosamente en la clase.';
        } else {
            // Si no hay un tutor asignado, asignar el tutor
            asignarTutorAClase($clase_id, $tutor_id);
            $mensaje = 'Tutor asignado exitosamente a la clase.';
        }
    }
}

// Obtener la lista de clases y tutores
$clases = obtenerClases();
$tutores = obtenerUsuariosPorRol(2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Tutor a Clase</title>
    <!-- Agrega la referencia a Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body class="container mt-5">

    <h2 class="mb-4">Asignar Tutor a Clase</h2>

    <form action="" method="post">
        <div class="form-group">
            <label for="clase_id">Seleccione la Clase:</label>
            <select class="form-control" id="clase_id" name="clase_id" required>
                <?php
                    foreach ($clases as $clase) {
                        echo "<option value=\"{$clase['clase_id']}\">{$clase['nombre_clase']}</option>";
                    }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tutor_id">Seleccione un Tutor:</label>
            <select class="form-control" id="tutor_id" name="tutor_id" required>
                <?php
                    foreach ($tutores as $tutor) {
                        echo "<option value=\"{$tutor['usuario_id']}\">{$tutor['nombre']} {$tutor['apellido']}</option>";
                    }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Asignar Tutor</button>
    </form>

    <a href="gestionar_clases.php" class="btn btn-secondary mt-3">Volver a la gestión</a>

    <?php
    if (!empty($mensaje)) {
        echo "<p class='mt-3 alert alert-info'>{$mensaje}</p>";
    }
    ?>

    <!-- Agrega la referencia a Bootstrap JS y Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

