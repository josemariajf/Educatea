<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verifica si se ha enviado el ID de la clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clase_id'])) {
    $clase_id = $_POST['clase_id'];
}

// Realiza la consulta para obtener los usuarios con rol 'alumno' que NO están en la clase y NO están en la tabla clases_usuarios
$query = "SELECT usuario_id, nombre, apellido FROM usuarios 
          WHERE rol_id = (SELECT rol_id FROM roles WHERE nombre_rol = 'alumno') 
          AND usuario_id NOT IN (
              SELECT cu.usuario_id FROM clases_usuarios cu
              WHERE cu.clase_id = $clase_id
          )
          AND usuario_id NOT IN (
              SELECT usuario_id FROM clases_usuarios
          )";

$result = $conexion->query($query);

// Verificar si se pudo realizar la consulta
if (!$result) {
    echo "Error al realizar la consulta: " . mysqli_error($conexion);
    exit();
}

// Consulta para obtener el nombre de la clase
$queryClase = "SELECT nombre_clase FROM clases WHERE clase_id = $clase_id";
$resultClase = $conexion->query($queryClase);

// Verificar si se pudo realizar la consulta de la clase
if (!$resultClase) {
    echo "Error al obtener el nombre de la clase: " . mysqli_error($conexion);
    exit();
}

$nombreClase = $resultClase->fetch_assoc()['nombre_clase'];

// Consulta para obtener los usuarios que ya están en la clase
$queryUsuariosEnClase = "SELECT u.usuario_id, u.nombre, u.apellido FROM usuarios u
                        JOIN clases_usuarios cu ON u.usuario_id = cu.usuario_id
                        WHERE cu.clase_id = $clase_id";
$resultUsuariosEnClase = $conexion->query($queryUsuariosEnClase);

// Verificar si se pudo realizar la consulta de los usuarios en la clase
if (!$resultUsuariosEnClase) {
    echo "Error al obtener los usuarios en la clase: " . mysqli_error($conexion);
    exit();
}

// Función para verificar si un usuario ya está en una clase
function usuarioEnClase($usuarioId, $claseId, $conexion)
{
    $query = "SELECT 1 FROM clases_usuarios WHERE usuario_id = $usuarioId AND clase_id = $claseId";
    $result = $conexion->query($query);

    if ($result && $result->num_rows > 0) {
        return true;
    }

    return false;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educatea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/bootstrap-4.0.0-dist/js/bootstrap.min.js">
</head>
<body>
    <div class="jumbotron bg-primary text-center text-white">
    <img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>

    <div class="container">
        <h2 class="mb-4">Añadir Alumnos a Clase: <?php echo $nombreClase; ?></h2>

        <form method="post" action="procesar_alumno.php" id="form-alumnos" class="border p-3 rounded">
            <input type="hidden" name="clase_id" value="<?php echo $clase_id; ?>">

            <input type="hidden" name="alumnos_seleccionados" id="alumnos_seleccionados_oculto">

            <div class="form-group">
                <label for="alumnos_seleccionados">Selecciona alumnos:</label>
                <select name="alumnos_seleccionados[]" id="alumnos_seleccionados" multiple required class="form-control">
                    <?php
                    while ($fila = $result->fetch_assoc()) {
                        // Verificar si el usuario ya está en la clase
                        $usuarioId = $fila['usuario_id'];
                        $enClase = usuarioEnClase($usuarioId, $clase_id, $conexion);

                        // Agregar la opción y deshabilitar si ya está en la clase
                        echo "<option value='" . $usuarioId . "' " . ($enClase ? "disabled" : "") . ">" . $fila['nombre'] . " " . $fila['apellido'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <p>Alumnos en la clase:</p>
                <ul>
                    <?php
                    while ($usuarioEnClase = $resultUsuariosEnClase->fetch_assoc()) {
                        echo "<li>{$usuarioEnClase['nombre']} {$usuarioEnClase['apellido']}</li>";
                    }
                    ?>
                </ul>
            </div>

            <div id="lista-visual-alumnos"></div>

            <div class="form-group">
                <button type="button" onclick="agregarAlumno()" class="btn btn-success">Agregar Alumno</button>
                <button type="button" onclick="enviarFormulario()" class="btn btn-primary">Añadir Alumnos</button>
            </div>
        </form>

        <a href="gestionar_clases.php" class="btn btn-secondary mt-3">Volver a la gestión</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var alumnosSeleccionados = [];

        function agregarAlumno() {
            var selectAlumnos = document.getElementById("alumnos_seleccionados");
            var listaVisual = document.getElementById("lista-visual-alumnos");

            for (var i = 0; i < selectAlumnos.selectedOptions.length; i++) {
                var alumno = selectAlumnos.selectedOptions[i];

                if (alumnosSeleccionados.indexOf(alumno.value) === -1) {
                    var divAlumno = document.createElement("div");
                    divAlumno.textContent = alumno.text;

                    var botonQuitar = document.createElement("button");
                    botonQuitar.textContent = "Quitar";
                    botonQuitar.className = "btn btn-danger btn-sm ml-2";
                    botonQuitar.onclick = function () {
                        divAlumno.remove();
                        alumnosSeleccionados = alumnosSeleccionados.filter(function (id) {
                            return id !== alumno.value;
                        });
                        alumno.disabled = false;
                    };

                    divAlumno.appendChild(botonQuitar);
                    listaVisual.appendChild(divAlumno);

                    alumnosSeleccionados.push(alumno.value);
                    alumno.disabled = true;
                }
            }
        }

        function enviarFormulario() {
            if (alumnosSeleccionados.length > 0) {
                document.getElementById("alumnos_seleccionados_oculto").value = JSON.stringify(alumnosSeleccionados);
                document.getElementById("form-alumnos").submit();
            } else {
                alert("Debes seleccionar al menos un alumno antes de enviar el formulario.");
            }
        }
    </script>

        <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>
    
</body>
</html>
