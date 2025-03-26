<?php
require 'config/database.php';

// Definir la ruta de la imagen predeterminada
$imagenPredeterminada = 'admin/posts/uploads/preterminada.jpg'; // Cambia esto por la ruta correcta

// Se hace la consulta y se obtienen los datos de las publicaciones 
$query = "SELECT Id_posts, title, content, post_date, category, image, user_creation 
          FROM posts 
          ORDER BY RAND() 
          LIMIT 4"; // Aquí ajustamos el límite de las publicaciones a mostrar debajo del carrusel

try {
    // Preparar y ejecutar la consulta
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Se obtienen los datos en un array
    $postsDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificación
    if (!$postsDB) {
        die("No se encontraron publicaciones en la base de datos.");
    }
} catch (PDOException $e) {
    die("Error al consultar los posts en la base de datos: " . $e->getMessage());
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB</title>
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon">
    <!-- Incluyendo los estilos -->
    <link rel="stylesheet" href="./views/css/navbar.css">
    <link rel="stylesheet" href="./views/css/footer.css">
    <link rel="stylesheet" href="./views/css/index.css">
    <link rel="stylesheet" href="./views/css/posts.css">
</head>

<body>
    <!-- Incluyendo la barra de navegación -->
    <?php include 'views/layout/header.php'; ?>

    <!-- Contenido principal -->
    <main>
        <!-- Carrusel de imágenes -->
        <?php include("views/layout/carousel.php"); ?>

        <!-- Sección de Publicaciones -->
        <div class="encabezado">
            <h1>Publicaciones</h1>
        </div>

        <div class="cuerpo">
            <?php
                // Mostrar las publicaciones obtenidas de la base de datos
                foreach ($postsDB as $post) {
                    // Si no hay imagen, usamos la imagen predeterminada
                    $imageSrc = !empty($post['image']) ? "../admin/posts/" . htmlspecialchars($post['image']) : "../admin/posts/uploads/preterminada.jpg";


                    echo '<a href="./post.php?id=' . htmlspecialchars($post['Id_posts']) . '">
                            <div class="p1">
                                <div class="imagen_post">
                                    <!-- Imagen predeterminada -->
                                    <img class="imagen1" src="' . $imageSrc . '" alt="imagen de ' . htmlspecialchars($post['title']) . '">
                                </div>
                                <div class="info_post">
                                    <h4 class="titulo1">' . htmlspecialchars($post['title']) . '</h4>
                                    <div class="datos1">
                                        <i class="far fa-user"></i> <span>' . htmlspecialchars($post['user_creation']) . '</span>
                                        <i class="far fa-calendar"></i> <span>' . date("F d, Y", strtotime($post['post_date'])) . '</span>
                                    </div>
                                    <p class="texto1">' . htmlspecialchars(substr($post['content'],0,180) . "...") . '</p>
                                </div>
                            </div>
                        </a>';
                }
            ?>
        </div>

    </main>

    <!-- Incluyendo el pie de página -->
    <?php include './views/layout/footer.php'; ?>
</body>

</html>