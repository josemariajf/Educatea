<?php
session_start();
require_once "../funciones.php";
$conexion = conexion();

// Manejar la búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $busqueda = $_POST['busqueda'];
    // Consulta con filtro por nombre o rol
    $query = "SELECT usuarios.*, roles.nombre_rol FROM usuarios 
              JOIN roles ON usuarios.rol_id = roles.rol_id 
              WHERE usuario_id != '0' AND (usuario LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%')";
    
    // %$busqueda% Me busca las palabras que contenga los caracteres escritos en el filtro.
} else {
    // Consulta sin filtro
    $query = "SELECT usuarios.*, roles.nombre_rol FROM usuarios 
              JOIN roles ON usuarios.rol_id = roles.rol_id 
              WHERE usuario_id != '0'";
}

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
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<img src="../img/Logo_educatea.png" alt="Logo de Educatea" style="position: absolute; top: 10px; left: 10px; max-width: 100px; max-height: 100px;">
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>
    
    <div class="container mt-5">
        <h2 class="mb-4">Lista de Usuarios Registrados</h2>

        <!-- Formulario de búsqueda -->
        <form method="post" action="">
            <div class="form-group">
                <label for="busqueda">Buscar por Nombre o Rol:</label>
                <input type="text" name="busqueda" id="busqueda" class="form-control" placeholder="Ingrese nombre o rol">
            </div>
            <button type="submit" class="btn btn-primary" name="buscar">Buscar</button>
            <a href="tutor.php" class="btn btn-primary">Ir a Tutor</a>
            <button class="btn btn-secondary" onclick="location.reload()">Refrescar</button>
        </form>
       
        <table class="table mt-3">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Usuario</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Correo Electrónico</th>
                    <th scope="col">Rol</th> 
                </tr>
            </thead>
            <tbody>
                <?php
                while ($usuario = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$usuario['usuario']}</td>
                            <td>{$usuario['nombre']}</td>
                            <td>{$usuario['apellido']}</td>
                            <td>{$usuario['correo_electronico']}</td>
                            <td>{$usuario['nombre_rol']}</td> 
                        </tr>";
                }
                ?>
            </tbody>
        </table>
        <br>
        <a href="../Roles/inicio_director.php" class="btn btn-secondary">Volver al Inicio de Director </a>
    </div>
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
