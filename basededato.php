<?php
// Datos de conexión a la base de datos
$host = 'localhost'; // Cambiar por la dirección del servidor de MySQL
$user = 'root'; // Cambiar por el usuario de MySQL
$pass = ''; 
$bd = 'educatea';// Cambiar por la contraseña de MySQL
$conexion = new mysqli($host,$user,$pass,$bd);





// Crear la base de datos tiendajm
$sql = 'CREATE DATABASE IF NOT EXISTS educatea';
if ($conexion->query($sql)) {
    echo "<h1>Base de datos tiendajm creada exitosamente</h1>\n";
} else {
    echo "Error al crear la base de datos: " . mysqli_error($conn) . "\n";
}

// Seleccionar la base de datos tiendajm


// Crear la tabla de usuarios
$sqlusuario = 'CREATE TABLE IF NOT EXISTS usuarios (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(30) NOT NULL,
    contraseña VARCHAR(50) NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    apellido VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    rol VARCHAR(30) NOT NULL
)';
if ($conexion->query($sqlusuario)) {
    echo "<h1>Tabla de usuarios creada exitosamente</h1>\n";
} else {
    echo "Error al crear la tabla de usuarios: " . mysqli_error($conn) . "\n";
}




?>
