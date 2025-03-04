<?php
$conexion = new mysqli("localhost", "root", "", "pi");

if ($conexion->connect_error) {
    die("Error al conectar a la base de datos: " . $conexion->connect_error);
} else {
    echo "Conexión exitosa a la base de datos.";
}

$conexion->set_charset("utf8mb4");
?>