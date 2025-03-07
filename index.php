<?php
require 'config/database.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB</title>

    <!-- Incluyendo los estilos -->
    <link rel="stylesheet" href="./views/css/navbar.css">
    <link rel="stylesheet" href="./views/css/footer.css">
    <link rel="stylesheet" href="./views/css/index.css">
    <link rel="stylesheet" href="./views/css/posts.css">

    <!-- carrouse -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">

    <!-- Incluyendo los scripts -->
</head>

<body>
    <!-- Incluyendo la barra de navegación -->
    <?php include 'views/layout/header.php'; ?>

    <!-- Contenido principal -->
    <main>
        <!-- IMAGE CALL ATENTIONS -->
        <?php include("views/layout/carousel.php"); ?>

        <!-- POSTS -->
        <div class="encabezado">
            <h1>Publicaciones</h1>
        </div>
        <div class="cuerpo">
            <?php
            $posts = json_decode(file_get_contents('./data/posts.json'), true); // Obtener los posts
            foreach ($posts as $post) { // Recorrer los posts
                echo '<a href="/views/post.php?id=' . $post['id'] . '">
                        <div class="p1">
                            <div>
                                <img class="imagen" src="' . $post['image'] . '" alt="imagen de nosotros">
                            </div>
                            <div class="texto1">
                                <h4>' . $post['title'] . '</h4>
                                <p>' . $post['description'] . '</p>
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