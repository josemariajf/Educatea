<?php
// Datos de conexión a la base de datos
$host = 'localhost'; // Cambiar por la dirección del servidor de MySQL
$user = 'root'; // Cambiar por el usuario de MySQL
$pass = ''; 
$bd = 'educatea';// Cambiar por la contraseña de MySQL
$conexion = new mysqli($host,$user,$pass,$bd);





/*INSERTS */
$insertar_asignaturas = "INSERT INTO asignaturas (nombre_asignatura, codigo_asignatura, descripcion_asignatura) 
VALUES 
('Desarrollo Web En Entorno Cliente', 'DWEC', 'Introducción al desarrollo web en el entorno cliente utilizando HTML, CSS y JavaScript.'),
('Despliegue De Aplicaciones Web', 'DAW', 'Aprendizaje del despliegue de aplicaciones web en diferentes entornos y tecnologías.'),
('Diseño De Interfaces Web', 'DIW', 'Diseño y maquetación de interfaces web utilizando tecnologías como HTML, CSS y JavaScript.'),
('Hora Libre de Configuración', 'HLC', 'Hora dedicada a la configuración y personalización de las herramientas y tecnologías utilizadas en el ciclo formativo.'),
('Desarrollo Web en Entorno Servidor 2022/2023', 'DWES', 'Desarrollo de aplicaciones web en el entorno servidor.'),
('Empresa e Iniciativa Emprendedora', 'EIE', 'Conocimiento sobre la creación y gestión de una empresa, así como habilidades y actitudes emprendedoras.')";

if ($conexion->query($insertar_asignaturas)) {
    echo "Asignaturas insertadas correctamente.<br>";
} else {
    echo "Error al insertar las asignaturas: " . mysqli_error($conn)."\n";
}


?>