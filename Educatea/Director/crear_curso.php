<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar la creación de cursos
    if (isset($_POST['crear_curso'])) {
        $nombre_curso = $_POST['nombre_curso'];
        $descripcion_curso = $_POST['descripcion_curso'];
        $nombre_profesor = $_POST['nombre_profesor'];

        // Validaciones de datos (puedes agregar más según tus necesidades)

        // Obtener el id del profesor seleccionado
        $queryProfesor = "SELECT usuarios.id, usuarios.nombre, usuarios.email
                          FROM usuarios
                          INNER JOIN roles ON usuarios.id_rol = roles.id_rol
                          WHERE usuarios.nombre = '$nombre_profesor' AND roles.nombre_rol = 'profesor'";
        $resultadoProfesor = $conexion->query($queryProfesor);

        if ($resultadoProfesor && $resultadoProfesor->num_rows > 0) {
            $rowProfesor = $resultadoProfesor->fetch_assoc();
            
            $nombre_profesor = $rowProfesor['nombre'];
            $email_profesor = $rowProfesor['email'];

           // Verificar si el profesor ya está asignado a algún curso
            $queryVerificar = "SELECT id_curso FROM cursos WHERE nombre_profesor = '$nombre_profesor'";
            $resultadoVerificar = $conexion->query($queryVerificar);


            if ($resultadoVerificar && $resultadoVerificar->num_rows > 0) {
                echo "Error: El profesor ya está asignado a otro curso.";
            } else {
                // Insertar el curso en la base de datos
                $query = "INSERT INTO cursos (nombre_curso, descripcion_curso,  nombre_profesor, email_profesor) 
                          VALUES ('$nombre_curso', '$descripcion_curso',  '$nombre_profesor', '$email_profesor')";
                $resultado = $conexion->query($query);

                if ($resultado) {
                    echo "Curso creado correctamente.";
                } else {
                    echo "Error al crear el curso: " . mysqli_error($conexion);
                }
            }
        } else {
            echo "Error al obtener el nombre y el correo del profesor: " . mysqli_error($conexion);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Crear Curso</title>
</head>

<body>
    <h2>Crear Curso</h2>

    <!-- Formulario para crear cursos -->
    <form method="post" action="crear_curso.php">
        <label for="nombre_curso">Nombre del Curso:</label>
        <input type="text" name="nombre_curso" required>
        <br/><br/>
        <label for="descripcion_curso">Descripción del Curso:</label>
        <textarea name="descripcion_curso" required></textarea>
        <br/><br/>
        <label for="nombre_profesor">Nombre del Profesor:</label>
        <!-- Obtener la lista de nombres de usuarios con el rol 'profesor' desde la base de datos -->
        <?php
        $queryProfesores = "SELECT usuarios.nombre
                            FROM usuarios
                            INNER JOIN roles ON usuarios.id_rol = roles.id_rol
                            WHERE roles.nombre_rol = 'profesor'";
        $resultadoProfesores = $conexion->query($queryProfesores);
        ?>
        <select name="nombre_profesor" required>
            <?php
            while ($rowProfesor = $resultadoProfesores->fetch_assoc()) {
                echo "<option value='" . $rowProfesor['nombre'] . "'>" . $rowProfesor['nombre'] . "</option>";
            }
            ?>
        </select>
        <br/><br/>
        <input type="submit" name="crear_curso" value="Crear Curso">
    </form>
    
    <!-- Botón para volver a la página principal -->
    <button onclick="goBack()">Volver</button>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
