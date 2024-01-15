<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

// Consulta para obtener todos los tutores y sus clases
$query = "SELECT usuarios.usuario_id, usuarios.nombre, usuarios.apellido, GROUP_CONCAT(clases.nombre_clase SEPARATOR ', ') AS clases_asignadas
          FROM usuarios
          LEFT JOIN clases ON usuarios.usuario_id = clases.id_tutor
          WHERE usuarios.rol_id = 2
          GROUP BY usuarios.usuario_id, usuarios.nombre, usuarios.apellido";

$result = $conexion->query($query);

// Verificar si se pudo realizar la consulta
if (!$result) {
    echo "Error al realizar la consulta: " . mysqli_error($conexion);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tutores y Clases Asignadas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<img src="../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    
    <div class="container mt-5">
        <h2 class="mb-4">Lista de Tutores y Clases Asignadas</h2>
        
        <table class="table mt-3">
            <thead class="thead-dark">
                <tr>                 
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Clases Asignadas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($tutor = $result->fetch_assoc()) {
                    echo "<tr>
                          
                            <td>{$tutor['nombre']}</td>
                            <td>{$tutor['apellido']}</td>
                            <td>{$tutor['clases_asignadas']}</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
        <br>
        <a href="listado.php" class="btn btn-secondary">Volver a los usuarios</a>
    </div>

    <!--fixed-bottom de Bootstrap para fijar el footer en la parte inferior de la página. -->
    <footer class="fixed-bottom bg-dark text-white text-center p-2">
            <p>&copy; 2024 Educatea. Todos los derechos reservados.</p>
        </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
