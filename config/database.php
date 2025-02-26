<?php
// Cargar las variables de entorno desde el archivo .env
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obtener las constantes de las variables de entorno
$DB_HOST = $_ENV['DB_HOST'];
$DB_USER = $_ENV['DB_USER'];
$DB_PASS = $_ENV['DB_PASS'];
$DB_NAME = $_ENV['DB_NAME'];

// Crear una nueva conexión a la base de datos
$connection = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Verificar si hay errores en la conexión
if ($connection->connect_error) {
    die("Error de conexión: " . $connection->connect_error);
} else {
    echo "Conexión exitosa a la base de datos";
}
