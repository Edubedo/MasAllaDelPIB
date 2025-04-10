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
                    
                    // Común a ambos tipos de usuario
                    $postLink = 'views/post.php?id=' . htmlspecialchars($post['Id_posts']);
                    $title = htmlspecialchars(strlen($post['title']) > 70 ? substr($post['title'], 0, 70) . "..." : $post['title']);
                    $userCreation = htmlspecialchars($post['user_creation']);
                    $postDate = date("F d, Y", strtotime($post['post_date']));
                    $content = htmlspecialchars(strlen($post['title']) > 60 ? substr($post['content'],0,125) . "..." :  substr($post['content'],0,180) . "...");

                    // Estructura común del post
                    $postHTML = '
                        <a href="' . $postLink . '">
                            <div class="p1">
                                <div class="cuerpo_post">
                                    <div class="imagen_post">
                                        <img class="imagen1" src="' . $imageSrc . '" alt="imagen de ' . htmlspecialchars($post['title']) . '">
                                    </div>
                                    <div class="info_post">
                                        <h4 class="titulo1">' . $title . '</h4>
                                        <div class="datos1">
                                            <i class="far fa-user"></i> <span>' . $userCreation . '</span>
                                            <i class="far fa-calendar"></i> <span>' . $postDate . '</span>
                                        </div>
                                        <p class="texto1">' . $content . '</p>
                                    </div>
                                </div>';
                    
                    // Lógica de interacciones según el tipo de usuario
                    if ($idtypeuser == 1 || $idtypeuser == 2) {
                        $postHTML .= '
                            <div class="interaccion">
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
                            </div>';
                    }

                    $postHTML .= '</div></a>';

                    // Imprimir el HTML de la publicación
                    echo $postHTML;
                }
            ?>
        </div>

    </main>
    <div id="cookiesContainer"></div>
    <script src="./js/cookies.js"></script>
    <!-- Incluyendo el pie de página -->
    <?php include './views/layout/footer.php'; ?>
</body>
</html>