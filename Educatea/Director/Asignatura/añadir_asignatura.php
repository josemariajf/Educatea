<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si se ha enviado el ID de la clase
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clase_id'])) {
    $clase_id = $_POST['clase_id'];
}

// Obtener asignatura_id de la sesión
if (isset($_SESSION['asignatura_id'])) {
    $asignatura_id = $_SESSION['asignatura_id'];
}

// Realiza la consulta para obtener todas las clases
$queryTodasLasClases = "SELECT clase_id, nombre_clase, curso FROM clases";
$resultTodasLasClases = $conexion->query($queryTodasLasClases);

// Verificar si se pudo realizar la consulta
if (!$resultTodasLasClases) {
    echo "Error al realizar la consulta de clases: " . mysqli_error($conexion);
    exit();
}
// Obtener asignatura_id de la sesión o desde la URL
if (isset($_SESSION['asignatura_id'])) {
    $asignatura_id = $_SESSION['asignatura_id'];
} elseif (isset($_GET['id_asignatura'])) {
    $asignatura_id = $_GET['id_asignatura'];
    $_SESSION['asignatura_id'] = $asignatura_id;
} else {
    // Redirigir o manejar el caso en el que no se proporciona el ID de la asignatura
    header("Location: gestionar_asignatura.php");
    exit();
}
// Destruir la sesión asignatura_id si existe
if (isset($_SESSION['asignatura_id'])) {
    unset($_SESSION['asignatura_id']);
}
// Verificar si hay un error en la URL
if (isset($_GET['error']) && $_GET['error'] === 'asignatura_existente') {
    echo '<div class="alert alert-danger" role="alert">No se puede añadir la misma asignatura a la misma clase.</div>';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educatea</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/clases.css">
</head>

<body>
<div class="jumbotron bg-primary text-center text-white">
<img src="../../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
        <h1 class="display-4">Educatea</h1>
    </div>

    <div class="container">
        <h2 class="mb-4">Asignar Asignatura a Clase</h2>

        <form method="post" action="procesar_asignatura.php" id="form-asignatura" class="border p-3 rounded">
            <input type="hidden" name="clase_id" value="<?php echo $clase_id; ?>">
            <input type="hidden" name="asignatura_id" value="<?php echo $asignatura_id; ?>">

            <div class="form-group">
                <label for="clase_seleccionada">Selecciona una clase:</label>
                <select name="clase_seleccionada" id="clase_seleccionada" required class="form-control">
                    <?php
                    // Mostrar todas las clases disponibles
                    while ($filaClase = $resultTodasLasClases->fetch_assoc()) {
                        echo "<option value='" . $filaClase['clase_id'] . "'>" . $filaClase['nombre_clase'] . " - " . $filaClase['curso'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="button" onclick="agregarClase()" class="btn btn-success">Añadir Clase</button>
            <button type="button" onclick="enviarFormularioClase()" class="btn btn-primary">Asignar Clase</button>
        </form>

        <div id="lista-visual-clases"></div>

        <a href="gestionar_asignatura.php" class="btn btn-secondary mt-3">Volver a la gestion</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    var clasesSeleccionadas = [];

    function agregarClase() {
        var selectClase = document.getElementById("clase_seleccionada");
        var listaVisual = document.getElementById("lista-visual-clases");

        if (selectClase.value && clasesSeleccionadas.indexOf(selectClase.value) === -1) {
            // Mostrar el ID de la clase seleccionada en la consola
            console.log("ID de la clase seleccionada:", selectClase.value);

            // Crear un campo oculto con el ID de la clase
            var inputClaseId = document.createElement("input");
            inputClaseId.type = "hidden";
            inputClaseId.name = "clases_seleccionadas[]";
            inputClaseId.value = selectClase.value;

            var divClase = document.createElement("div");
            divClase.textContent = selectClase.options[selectClase.selectedIndex].text;

            var botonQuitar = document.createElement("button");
            botonQuitar.textContent = "Quitar";
            botonQuitar.className = "btn btn-danger btn-sm ml-2";
            botonQuitar.onclick = function () {
                divClase.remove();
                inputClaseId.remove();
                selectClase.disabled = false;

                // Remover la clase de la lista de clases seleccionadas
                var index = clasesSeleccionadas.indexOf(selectClase.value);
                if (index !== -1) {
                    clasesSeleccionadas.splice(index, 1);
                }
            };

            divClase.appendChild(inputClaseId);
            divClase.appendChild(botonQuitar);
            listaVisual.appendChild(divClase);

            clasesSeleccionadas.push(selectClase.value);
            selectClase.disabled = true;
        }
    }

    function enviarFormularioClase() {
        if (clasesSeleccionadas.length > 0) {
            // Agregar un campo oculto para enviar la ID de la clase seleccionada en la lista
            var claseSeleccionadaId = clasesSeleccionadas[0]; // En este ejemplo, solo toma la primera clase seleccionada
            var inputClaseSeleccionadaId = document.createElement("input");
            inputClaseSeleccionadaId.type = "hidden";
            inputClaseSeleccionadaId.name = "clase_seleccionada_id";
            inputClaseSeleccionadaId.value = claseSeleccionadaId;

            // Adjuntar el nuevo campo oculto al formulario
            document.getElementById("form-asignatura").appendChild(inputClaseSeleccionadaId);

            // Enviar el formulario
            document.getElementById("form-asignatura").submit();
        } else {
            alert("Debes seleccionar una clase antes de enviar el formulario.");
        }
    }
</script>

        <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>

</body>

</html>
