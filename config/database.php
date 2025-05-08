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

// Leer el tipo de base de datos
$dbtype = $_ENV['DB_TYPE'] ?? 'dev';

if ($dbtype === 'prod') {
    $host = $_ENV['DB_HOST_PROD'] ?? 'valor_por_defecto_produccion';
    $user = $_ENV['DB_USER_PROD'] ?? 'valor_por_defecto_produccion';
    $password = $_ENV['DB_PASSWORD_PROD'] ?? 'valor_por_defecto_produccion';
    $dbname = $_ENV['DB_NAME_PROD'] ?? 'valor_por_defecto_produccion';
    $port = $_ENV['DB_PORT_PROD'] ?? '3306';
} else {
    $host = $_ENV['DB_HOST_DEV'] ?? 'valor_por_defecto_dev';
    $user = $_ENV['DB_USER_DEV'] ?? 'valor_por_defecto_dev';
    $password = $_ENV['DB_PASSWORD_DEV'] ?? 'valor_por_defecto_dev';
    $dbname = $_ENV['DB_NAME_DEV'] ?? 'valor_por_defecto_dev';
    $port = $_ENV['DB_PORT_DEV'] ?? '3306';
}

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
