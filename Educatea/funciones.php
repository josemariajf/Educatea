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
    global $conexion;

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






function obtenerUsuariosPorRol() {
    global $conexion;

    $query = "SELECT * FROM usuarios WHERE rol_id = 2";
    $result = mysqli_query($conexion, $query);

    $tutores = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $tutores[] = $row;
    }

    return $tutores;
}


function  asignarTutorAClase($clase_id, $tutor_id) {
    global $conexion;

    $query = "UPDATE clases SET id_tutor = ? WHERE clase_id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ii", $tutor_id, $clase_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
// En funciones.php

function obtenerTutorDeClase($clase_id) {
    global $conexion;

    $query = "SELECT id_tutor FROM clases WHERE clase_id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $clase_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_tutor);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $id_tutor;
}

function actualizarTutorDeClase($clase_id, $tutor_id) {
    global $conexion;

    $query = "UPDATE clases SET id_tutor = ? WHERE clase_id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ii", $tutor_id, $clase_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
function tutorAsignadoOtraClase($tutor_id, $clase_id) {
    global $conexion;

    $query = "SELECT c.nombre_clase, u.nombre
              FROM clases c
              JOIN usuarios u ON c.id_tutor = u.usuario_id
              WHERE c.id_tutor = ? AND c.clase_id <> ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ii", $tutor_id, $clase_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nombre_clase, $nombre_tutor);
    
    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        return "El tutor '$nombre_tutor' ya está asignado a la clase '$nombre_clase'.";
    }

    mysqli_stmt_close($stmt);
    return false;
}








?>