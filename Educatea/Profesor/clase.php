<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

// Verificar si el usuario ha iniciado sesión y si tiene la clave 'usuario' en la información
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$usuario_id = $usuario['usuario_id'];
$nombre = $usuario['nombre'];
$apellido = $usuario['apellido'];
$correo = $usuario['correo_electronico'];

// Realizar la consulta para obtener las clases del profesor
$query = "SELECT c.clase_id, c.nombre_clase, c.curso
          FROM clases c
          WHERE c.id_tutor = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si se pudo realizar la consulta
if (!$resultado) {
    echo "Error al realizar la consulta: " . mysqli_error($conexion);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Lista de Clases</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
<img src="../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    <div class="container mt-5">
        <div class="text-center">
            <h1>Clases de <?php echo $nombre . " " . $apellido; ?></h1>
        </div>

        <div>
            <h2 class="mt-4">Lista de Clases</h2>
            <table class="table table-bordered">
                <tr>
                    <th>ID Clase</th>
                    <th>Nombre de la Clase</th>
                    <th>Curso</th>
                    <th>Acciones</th>
                </tr>

                <?php
                // Mostrar los resultados de la consulta en una tabla
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila['clase_id'] . "</td>";
                    echo "<td>" . $fila['nombre_clase'] . "</td>";
                    echo "<td>" . $fila['curso'] . "</td>";
                    // Agrega el botón con un enlace que redirige a la página de alumnos pasando el ID de la clase
                    echo "<td><a href='alumnos.php?clase_id=" . $fila['clase_id'] . "' class='btn btn-info'>Ver Alumnos</a></td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

        <a href="../Roles/inicio_profesor.php" class="btn btn-secondary mt-3">Volver a inicio</a>
    </div>
    
     <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
     <footer class="fixed-bottom bg-dark text-white text-center p-2">
        <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
    </footer>
    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

