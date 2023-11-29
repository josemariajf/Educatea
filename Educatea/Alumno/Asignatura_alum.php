<?php
session_start();
require_once "../funciones.php";
// Datos de conexión a la base de datos
$conexion = conexion();



?>
<!DOCTYPE html>
<html lang="en">
<head>
<link href="../../css/inicio_alumno.css" rel="stylesheet" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaturas</title>
</head>
<body>
<h1>Asignaturas de E-ducatea</h1>
 <?php
 // Hacer una consulta para obtener la información de las asignaturas
$sql = "SELECT * FROM asignaturas";
$resultado = $conexion->query($sql);

// Crear la tabla
echo "<table>";
echo "<tr><th>Nombre de la asignatura</th><th>Código</th><th>Descripción</th><th></th></tr>";

// Mostrar una fila por cada asignatura
while ($fila = $resultado->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$fila["nombre_asignatura"]."</td>";
    echo "<td>".$fila["codigo_asignatura"]."</td>";
    echo "<td>".$fila["descripcion_asignatura"]."</td>";
    echo "<td><a href='Asignaturas/asignatura.php?id=".$fila["id_asignatura"]."'>
    <button>Ver asignatura</button></a></td>";
    echo "</tr>";
}

echo "</table>";

 
 ?>   
 <a href="../Roles/inicio_alumno.php"><button>Volver a inicio</button></a>

</body>
</html>