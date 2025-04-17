<?php
session_start();
require 'config/database.php';

$query = "SELECT 
        p.Id_posts, 
        p.title, 
        p.content, 
        p.post_date, 
        p.category, 
        p.image, 
        p.user_creation, 
        p.vote_up, 
        p.vote_down,
        COUNT(l.id_post) AS total_likes
        FROM posts p
        LEFT JOIN likes l ON p.Id_posts = l.id_post
        GROUP BY p.Id_posts
        ORDER BY p.post_date DESC 
          LIMIT 4";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $postsDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar publicaciones: " . $e->getMessage());
}
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
    <link rel="stylesheet" href="./views/css/cookies.css">
</head>

<body class="pagina-index">
    <div class="fondo-overlay"></div>
    <!-- Incluyendo la barra de navegación -->
    <?php include 'views/layout/header.php'; ?>

    <div class="container-carousel">
        <div class="header">
            <?php include("views/layout/carousel.php"); ?>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="container-principal">
        <div class="div-central">
            <!-- Sección de Publicaciones -->
            <div class="encabezado">
                <h1>Mas Recientes</h1>
            </div>

            <div class="cuerpo">
                <?php $isIndex = true; ?> <!-- Variable para indicar que estamos en el index -->
                <?php include('views/layout/posts.php'); ?>
            </div>
        </div>

        <div class="div-derecho">
            <div class="populares">
                <h3>Lo mas popular</h3>
            </div>
            <div class="barra">texto aqui</div>
            <div class="formulario">
                <p>Aqui ira la parte del formulario</p>
            </div>
            <div class="barra">texto aqui</div>
            <div class="div-inferior-derecho">
                <p>Otra cosa aqui</p>
            </div>

        </div>

    </div>

    <!-- <div id="cookiesContainer"></div>
    <script src="./js/cookies.js"></script> -->
    <!-- Incluyendo el pie de página -->
    <?php include './views/layout/footer.php'; ?>
</body>

</html>