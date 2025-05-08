<?php
// Verificar si estamos en un entorno local (puedes usar diferentes métodos para detectar esto)
$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost' ||
    $_SERVER['SERVER_NAME'] === '127.0.0.1' ||
    strpos($_SERVER['SERVER_NAME'], '.local') !== false);

// Cargar Dotenv sólo en entorno local
if ($isLocal) {
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

// Configuración de la base de datos
$host = $_ENV['DB_HOST'] ?? 'valor_por_defecto_produccion';
$user = $_ENV['DB_USER'] ?? 'valor_por_defecto_produccion';
$password = $_ENV['DB_PASSWORD'] ?? 'valor_por_defecto_produccion';
$dbname = $_ENV['DB_NAME'] ?? 'valor_por_defecto_produccion';
$port = $_ENV['DB_PORT'] ?? 'valor_por_defecto_produccion';

try {
    // Conexión con la base de datos usando PDO para poder mostrar las publicaciones en el inicio
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Conexión con MySQLi (manteniendo tu conexión original)
    $conexion = mysqli_connect($host, $user, $password, $dbname, $port);
    if (!$conexion) {
        throw new Exception("Error de conexión MySQLi: " . mysqli_connect_error());
    }
} catch (PDOException $e) {
    die("Error de conexión PDO: " . $e->getMessage());
} catch (Exception $e) {
    die($e->getMessage());
}
