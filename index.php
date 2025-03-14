<?php
require 'config/database.php';

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
        <!-- IMAGE CALL ATENTIONS -->
        <?php include("views/layout/carousel.php"); ?>

        <!-- POSTS -->
        <div class="encabezado">
            <h1>Publicaciones</h1>
        </div>

        <div class="cuerpo">
        <?php
            $file = __DIR__ . '/data/posts.json';

            if (!file_exists($file)) {
                echo "<p>Error: No se encontró el archivo de publicaciones.</p>";
            } else {
                $jsonContent = file_get_contents($file);
                $posts = json_decode($jsonContent, true);

                if (!$posts) {
                    echo "<p>Error: El archivo JSON está vacío o mal formado.</p>";
                } else {
                    foreach ($posts as $post) {
                        echo '<a href="./post.php?id=' . htmlspecialchars($post['id']) . '">
                                <div class="p1">
                                    <div class="imagen_post">
                                        <img class="imagen1" src="' . htmlspecialchars($post['image']) . '" alt="imagen de ' . htmlspecialchars($post['title']) . '">
                                    </div>
                                    <div class="info_post">
                                        <h4 class="titulo1">' . htmlspecialchars($post['title']) . '</h4>
                                        <div class="datos1">
                                            <i class="far fa-user"></i> <span>' . htmlspecialchars($post['user']) . '</span>
                                            <i class="far fa-calendar"></i> <span>' . date("F d, Y", strtotime($post['date'])) . '</span>
                                        </div>
                                        <p class="texto1">' . htmlspecialchars($post['description']) . '</p>
                                    </div>
                                </div>
                            </a>';
                    }
                }
            }
        ?>
    </div>
    </main>
    <!-- Incluyendo el pie de página -->
    <?php include './views/layout/footer.php'; ?>
</body>

</html>