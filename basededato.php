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
// Creación de la tabla "asignaturas"
$sqlasignatura = "CREATE TABLE IF NOT EXISTS asignaturas (
    id_asignatura INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_asignatura VARCHAR(50) NOT NULL,
    codigo_asignatura VARCHAR(50) NOT NULL,
    descripcion_asignatura VARCHAR(255) NOT NULL,
    id_profesor INT(6) UNSIGNED,
    FOREIGN KEY (id_profesor) REFERENCES usuarios(id)
    )";
if ($conexion->query($sqlasignatura)) {
    echo "Tabla 'asignaturas' creada correctamente.<br>";
} else {
    echo "Error al crear la tabla 'asignaturas': " . mysqli_error($conn)."\n";
}

// Creación de la tabla "cursos"
$sqlcursos = "CREATE TABLE IF NOT EXISTS cursos (
    id_curso INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_curso VARCHAR(50) NOT NULL,
    id_profesor INT(6) UNSIGNED,
    FOREIGN KEY (id_profesor) REFERENCES usuarios(id)
    )";
    
    if ($conexion->query($sqlcursos)) {
        echo "Tabla 'cursos' creada correctamente.<br>";
    } else {
        echo "Error al crear la tabla 'cursos': " . mysqli_error($conn)."<br>";
    }

// Creación de la tabla "calificaciones"
$sqlcalifica = "CREATE TABLE calificaciones (
    id_calificacion INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT(6) UNSIGNED,
    id_asignatura INT(6) UNSIGNED,
    id_curso INT(6) UNSIGNED,
    calificacion INT(2) NOT NULL,
    nota_media INT(2) NOT NULL,
    fecha_calificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_asignatura) REFERENCES asignaturas(id_asignatura),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso)
    )";
    
    if ($conexion->query($sqlcalifica)) {
        echo "Tabla 'calificaciones' creada correctamente.<br>";
    } else {
        echo "Error al crear la tabla 'calificaciones': " . mysqli_error($conn)."<br>";
    }



?>
