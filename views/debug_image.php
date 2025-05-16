<?php
session_start();
include('../config/database.php');

// Verificar si se recibió un ID válido en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de post no válido. Por favor especifique un ID en la URL: debug_image.php?id=X");
}

$postId = $_GET['id'];

// Consultar la base de datos
$stmt = $pdo->prepare("SELECT Id_posts, title, image FROM posts WHERE Id_posts = :id");
$stmt->execute(['id' => $postId]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el post existe
if (!$post) {
    die("Publicación no encontrada.");
}

// Mostrar información sobre la imagen
echo "<h1>Información de la imagen para post ID: {$postId}</h1>";
echo "<p><strong>Título:</strong> " . htmlspecialchars($post['title']) . "</p>";

if (!empty($post['image'])) {
    $imageSize = strlen($post['image']);
    echo "<p><strong>Tamaño de la imagen:</strong> " . $imageSize . " bytes</p>";

    // Para imágenes muy pequeñas, mostrar datos hex para diagnóstico
    if ($imageSize < 100) {
        echo "<p><strong>Datos hexadecimales:</strong> " . bin2hex($post['image']) . "</p>";
        echo "<p style='color:red;'>ADVERTENCIA: La imagen parece muy pequeña para ser un archivo de imagen válido.</p>";
    }

    // Determinar si los datos parecen ser una imagen válida
    $mime = "desconocido";
    $imageInfo = @getimagesizefromstring($post['image']);
    if ($imageInfo !== false) {
        $mime = $imageInfo['mime'];
        echo "<p><strong>Tipo de imagen detectado:</strong> " . $mime . "</p>";
        echo "<p><strong>Dimensiones:</strong> " . $imageInfo[0] . "x" . $imageInfo[1] . " píxeles</p>";
    } else {
        echo "<p style='color:red;'><strong>Error:</strong> No se pudo identificar un formato de imagen válido.</p>";
    }

    // Intentar mostrar la imagen
    echo "<p><strong>Vista previa de la imagen:</strong></p>";
    echo "<img src='data:image/jpeg;base64," . base64_encode($post['image']) . "' alt='Vista previa' style='max-width:500px;'><br>";

    // Sugerencias para solucionar problemas
    if ($imageSize < 1000 || $imageInfo === false) {
        echo "<h2>Posibles problemas:</h2>";
        echo "<ul>";
        echo "<li>La imagen es demasiado pequeña para ser un archivo válido.</li>";
        echo "<li>Es posible que los datos estén corruptos.</li>";
        echo "<li>Podría ser que se esté almacenando la ruta del archivo en lugar del contenido binario.</li>";
        echo "</ul>";

        echo "<h2>Soluciones sugeridas:</h2>";
        echo "<ul>";
        echo "<li>Revise el código que maneja la carga de imágenes.</li>";
        echo "<li>Asegúrese de que está leyendo el contenido del archivo antes de almacenarlo.</li>";
        echo "<li>Verifique que la base de datos esté configurada correctamente para almacenar BLOBs.</li>";
        echo "<li>Intente subir una nueva imagen para este post.</li>";
        echo "</ul>";
    }
} else {
    echo "<p>No hay imagen almacenada para este post.</p>";
}
?>

<p><a href="/views/post.php?id=<?= $postId ?>">Volver al post</a></p>