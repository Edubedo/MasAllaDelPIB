<?php
// Este script convierte imágenes almacenadas como rutas en la base de datos a BLOBs
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION['id_type_user']) || $_SESSION['id_type_user'] != 1) {
    die("Acceso restringido. Solo los administradores pueden ejecutar esta herramienta.");
}

include '../config/database.php';

// Mostrar formulario de confirmación
if (!isset($_POST['confirm'])) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Convertir imágenes a BLOB</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .warning { color: red; font-weight: bold; }
            .button { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
            .button.cancel { background-color: #f44336; }
        </style>
    </head>
    <body>
        <h1>Conversión de imágenes a BLOB</h1>
        <p class="warning">ADVERTENCIA: Este proceso convertirá todas las imágenes almacenadas como rutas a formato BLOB en la base de datos.</p>
        <p>Este proceso:</p>
        <ul>
            <li>Buscará todos los posts que tienen imágenes almacenadas como rutas</li>
            <li>Intentará leer los archivos de imagen</li>
            <li>Convertirá esas imágenes a formato BLOB y las almacenará en la base de datos</li>
        </ul>
        <p class="warning">Asegúrese de hacer una copia de seguridad de la base de datos antes de continuar.</p>
        
        <form method="post">
            <input type="hidden" name="confirm" value="1">
            <button type="submit" class="button">Iniciar conversión</button>
            <a href="/admin/posts/posts-consulta.php" class="button cancel" style="display: inline-block; text-decoration: none; margin-left: 10px;">Cancelar</a>
        </form>
    </body>
    </html>';
    exit;
}

// Obtener todos los posts
$stmt = $pdo->query("SELECT Id_posts, image FROM posts");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($posts);
$converted = 0;
$errors = 0;
$skipped = 0;

echo '<h1>Proceso de conversión</h1>';
echo '<ul>';

foreach ($posts as $post) {
    $id = $post['Id_posts'];
    $image = $post['image'];

    // Si la imagen es NULL o ya es un BLOB con tamaño considerable
    if (empty($image) || strlen($image) > 1000) {
        echo "<li>Post ID {$id}: Ya está en formato BLOB o no tiene imagen.</li>";
        $skipped++;
        continue;
    }

    // Si parece ser una ruta de archivo
    if (is_string($image) && strlen($image) < 255) {
        // Primero verificar si el valor contiene una ruta
        if (strpos($image, '/') !== false || strpos($image, '\\') !== false) {
            // Verificar primero la ruta relativa desde la raíz del proyecto
            $relativePath = $_SERVER['DOCUMENT_ROOT'] . "/" . ltrim($image, '/');
            $directPath = dirname(__DIR__) . "/admin/posts/" . ltrim($image, '/');

            $filePath = "";
            if (file_exists($relativePath)) {
                $filePath = $relativePath;
            } elseif (file_exists($directPath)) {
                $filePath = $directPath;
            } else {
                echo "<li style='color:red'>Post ID {$id}: No se encontró el archivo en '{$relativePath}' ni en '{$directPath}'.</li>";
                $errors++;
                continue;
            }

            // Leer el archivo
            $imageData = file_get_contents($filePath);
            if ($imageData === false) {
                echo "<li style='color:red'>Post ID {$id}: Error al leer el archivo '{$filePath}'.</li>";
                $errors++;
                continue;
            }

            // Actualizar en la base de datos
            try {
                $updateStmt = $pdo->prepare("UPDATE posts SET image = :image WHERE Id_posts = :id");
                $updateStmt->bindParam(':image', $imageData, PDO::PARAM_LOB);
                $updateStmt->bindParam(':id', $id);

                if ($updateStmt->execute()) {
                    echo "<li style='color:green'>Post ID {$id}: Imagen convertida correctamente - " .
                        strlen($imageData) . " bytes.</li>";
                    $converted++;
                } else {
                    echo "<li style='color:red'>Post ID {$id}: Error al actualizar la base de datos.</li>";
                    $errors++;
                }
            } catch (PDOException $e) {
                echo "<li style='color:red'>Post ID {$id}: Error de base de datos: " . $e->getMessage() . "</li>";
                $errors++;
            }
        } else {
            echo "<li style='color:orange'>Post ID {$id}: El valor no parece ser una ruta de archivo: '{$image}'.</li>";
            $skipped++;
        }
    } else {
        echo "<li>Post ID {$id}: Formato de datos no reconocido.</li>";
        $skipped++;
    }
}

echo '</ul>';
echo '<h2>Resumen</h2>';
echo "<p>Total de posts: {$total}<br>";
echo "Posts convertidos: {$converted}<br>";
echo "Posts ignorados: {$skipped}<br>";
echo "Errores: {$errors}</p>";

echo '<p><a href="/admin/posts/posts-consulta.php" style="padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none;">Volver a Posts</a></p>';
