<?php
//Variables de conexion
$host = 'b5szsho9hlusg9vbkktx-mysql.services.clever-cloud.com';
$user = 'uaro5fqdabhzh1tk';
$password = 'BLq5F4sNK3ubd6iZUvii';
$dbname = 'b5szsho9hlusg9vbkktx';
$port = 3306;
$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

try {
    // Conexion con la base de datos usando PDO para poder potrar las publicaciones en el inicio
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

?>
