<?php
// Datos de la base de datos
$host = 'b5szsho9hlusg9vbkktx-mysql.services.clever-cloud.com';
$usuario = 'uaro5fqdabhzh1tk';
$clave = 'BLq5F4sNK3ubd6iZUvii';
$nombre_base_datos = 'b5szsho9hlusg9vbkktx';
$puerto = 3306;

// Establecer la conexión
$mysqli = new mysqli($host, $usuario, $clave, $nombre_base_datos, $puerto);

// Verificar la conexión
if ($mysqli->connect_error) {
    die('Error de Conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
} else {
    echo "Conexión exitosa a la base de datos.";
}

// Aquí puedes hacer consultas y operaciones con la base de datos...
?>
