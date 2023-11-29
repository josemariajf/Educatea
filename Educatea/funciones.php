<?php
function conexion() {
    // Datos de conexión a la base de datos
    $host = 'localhost'; // Cambiar por la dirección del servidor de MySQL
    $user = 'root'; // Cambiar por el usuario de MySQL
    $pass = ''; // Cambiar por la contraseña de MySQL
    $bd = 'educatea2'; // Nombre de la base de datos a la que se desea conectar
    
    // Conectarse a la base de datos
    $conexion = new mysqli($host,$user,$pass,$bd);
    return $conexion;
    }
   
function logout(){
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: ../index.php');
        exit;
      }
}

function agregarClase($nombre_clase, $curso) {
    global $conexion; // Asegúrate de que $conexion esté definido y disponible en este contexto

    // Consulta SQL para insertar una nueva clase
    $query = "INSERT INTO Clases (nombre_clase, curso) VALUES (?, ?)";
    
    // Preparar la consulta
    $stmt = $conexion->prepare($query);

    // Vincular los parámetros
    $stmt->bind_param("ss", $nombre_clase, $curso);

    // Ejecutar la consulta
    $stmt->execute();

    // Cerrar la consulta
    $stmt->close();
}
function obtenerClases() {
    global $conexion;
    $clases = array();

    // Escapar los datos para evitar inyección de SQL
    $query = "SELECT clase_id, nombre_clase, curso FROM Clases";
    $result = $conexion->query($query);

    while ($row = $result->fetch_assoc()) {
        $clases[] = $row;
    }

    return $clases;
}
function eliminarClase($clase_id) {
    global $conexion;
    
    // Escapar el ID para evitar inyección de SQL
    $clase_id = mysqli_real_escape_string($conexion, $clase_id);
    
    // Query para eliminar la clase
    $query = "DELETE FROM Clases WHERE clase_id = '$clase_id'";
    
    // Ejecutar la consulta
    $resultado = $conexion->query($query);
    
    // Verificar si la eliminación fue exitosa
    if ($resultado) {
        return true;
    } else {
        return false;
    }
}

?>