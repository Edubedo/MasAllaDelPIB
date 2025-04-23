<?php
// Incluye el archivo de configuración
// DESACTIVAR PARA PRODUCCION
require_once __DIR__ . '/../vendor/autoload.php'; // Usa __DIR__ para rutas absolutas
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// composer require vlucas/phpdotenv
$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];
$port = $_ENV['DB_PORT'];
$conexion = mysqli_connect($host, $user, $password, $dbname, $port);

try {
    // Conexion con la base de datos usando PDO para poder potrar las publicaciones en el inicio
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
