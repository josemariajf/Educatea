<?php
session_start();
require_once "../../funciones.php";
$conexion = conexion();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$rol = $usuario['nombre_rol'];

// Verificar si el usuario tiene permisos de administrador
if ($rol !== 'director') {
    header('Location: ../../index.php');
    exit;
}

// Mensajes de éxito o error después de procesar el formulario
$mensaje = '';

// Obtener las clases existentes después de la posible eliminación
$clases = obtenerClases();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clases - Educatea</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/bootstrap-4.0.0-dist/js/bootstrap.min.js">
</head>

<body>
    <div class="jumbotron bg-primary text-center text-white">
        <h1 class="display-4">Educatea</h1>
    </div>

    <div class="container mt-4">
        <h1 class="mb-4">Gestión de Clases</h1>

        <h2>Clases Existentes</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Clase ID</th>
                        <th>Nombre Clase</th>
                        <th>Curso</th>
                        <th colspan="3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clases as $clase) : ?>
                        <tr>
                            <td><?php echo $clase['clase_id']; ?></td>
                            <td><?php echo $clase['nombre_clase']; ?></td>
                            <td><?php echo $clase['curso']; ?></td>
                            <td>
                                <form method="post" action="eliminar_clase.php">
                                    <input type="hidden" name="clase_id" value="<?php echo $clase['clase_id']; ?>">
                                    <button type="submit" name="eliminar_clase" class="btn btn-danger">Eliminar</button>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="anadir_alumno.php">
                                    <input type="hidden" name="clase_id" value="<?php echo $clase['clase_id']; ?>">
                                    <button type="submit" name="anadir_alumno" class="btn btn-primary">Añadir Alumno</button>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="añadir_tutor.php">
                                    <input type="hidden" name="clase_id" value="<?php echo $clase['clase_id']; ?>">
                                    <button type="submit" name="anadir_alumno" class="btn btn-primary">Añadir tutor</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-6">
                <a href='crear_clase.php' class="btn btn-success">Crear Clase</a>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="../../Roles/inicio_director.php" class="btn btn-link">Volver al Inicio</a>
            </div>
        </div>

        <!-- Mostrar mensajes de éxito o error -->
        <?php if (!empty($mensaje)) : ?>
            <p class="mt-3 alert alert-info"><?php echo $mensaje; ?></p>
        <?php endif; ?>
    </div>

    <!-- Agrega la referencia a Bootstrap JS y Popper.js al final del cuerpo del documento -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
