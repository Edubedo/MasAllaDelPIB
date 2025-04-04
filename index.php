<?php
session_start();
require 'config/database.php';

// Definir la ruta de la imagen predeterminada
$imagenPredeterminada = 'admin/posts/uploads/preterminada.jpg'; // Cambia esto por la ruta correcta

// Se hace la consulta y se obtienen los datos de las publicaciones 
$query = "SELECT Id_posts, title, content, post_date, category, image, user_creation 
          FROM posts 
          ORDER BY post_date DESC
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
    <link rel="stylesheet" href="./views/css/cookies.css">
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
            <h1>Mas Recientes</h1>
        </div>

        <div class="cuerpo">
            <?php
                foreach ($postsDB as $post) { 
                    // Si no hay imagen, usamos la imagen predeterminada
                    $imageSrc = !empty($post['image']) ? "../admin/posts/" . htmlspecialchars($post['image']) : "../admin/posts/uploads/preterminada.jpg";

                    echo '<a href="views/post.php?id=' . htmlspecialchars($post['Id_posts']) . '">
                            <div class="p1">
                                <div class="cuerpo_post">
                                    <div class="imagen_post">
                                        <img class="imagen1" src="' . $imageSrc . '" alt="imagen de ' . htmlspecialchars($post['title']) . '">
                                    </div>
                                    <div class="info_post">
                                        <h4 class="titulo1">' . htmlspecialchars($post['title']) . '</h4>
                                        <div class="datos1">
                                            <i class="far fa-user"></i> <span>' . htmlspecialchars($post['user_creation']) . '</span>
                                            <i class="far fa-calendar"></i> <span>' . date("F d, Y", strtotime($post['post_date'])) . '</span>
                                        </div>
                                        <p class="texto1">' . htmlspecialchars(strlen($post['title']) > 60 ? substr($post['content'],0,125) . "..." :  substr($post['content'],0,180) . "...")  . '</p>
                                    </div>
                                </div>

                                <div class="interaccion">
                                    <div class="comentarios">
                                        <p>Comentar</p>
                                    </div>
                                    <div class="likes">
                                        <a class="options" data-vote-type="1" id="post_vote_up_' . htmlspecialchars($post['Id_posts']) . '">
                                            <i class="fas fa-thumbs-up" data-original-title="Like this post"></i>
                                        </a>
                                        <span class="likes_count" id="vote_up_count_' . htmlspecialchars($post['Id_posts']) . '">' . htmlspecialchars($post['vote_up'] ?? 0) . '</span>
                                        <a class="options" data-vote-type="0" id="post_vote_down_' . htmlspecialchars($post['Id_posts']) . '">
                                            <i class="fas fa-thumbs-down" data-original-title="Dislike this post"></i>
                                        </a>
                                        <span class="likes_count" id="vote_down_count_' . htmlspecialchars($post['Id_posts']) . '">' . htmlspecialchars($post['vote_down'] ?? 0) . '</span>
                                    </div>
                                </div>
                            </div>
                        </a>';
                }
            ?>
        </div>

    </main>
    <script src="./js/cookies.js"></script>
    <div id="cookiesContainer"></div>
    <!-- Incluyendo el pie de página -->
    <?php include './views/layout/footer.php'; ?>
</body>
</html>