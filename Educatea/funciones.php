<?php
function conexion() {
    // Datos de conexión a la base de datos
    $host = 'localhost'; // Cambiar por la dirección del servidor de MySQL
    $user = 'root'; // Cambiar por el usuario de MySQL
    $pass = ''; // Cambiar por la contraseña de MySQL
    $bd = 'educatea'; // Nombre de la base de datos a la que se desea conectar
    
    // Conectarse a la base de datos
    $conexion = new mysqli($host,$user,$pass,$bd);
    return $conexion;
    }
    ?>