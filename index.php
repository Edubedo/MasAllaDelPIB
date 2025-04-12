<?php
session_start();
require 'config/database.php';

$query = "SELECT Id_posts, title, content, post_date, category, image, user_creation, vote_up, vote_down 
          FROM posts 
          ORDER BY post_date DESC 
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

        <div class="div-izquierdo"></div>

        <div class="div-central">
            <!-- Sección de Publicaciones -->
            <div class="encabezado">
                <h1>Mas Recientes</h1>
            </div>

            <div class="cuerpo">
                <?php include ('views/layout/posts.php'); ?>
            </div>
        </div>

        <div class="div-derecho"></div>

    </main>

    <!-- <div id="cookiesContainer"></div>
    <script src="./js/cookies.js"></script> -->
    <!-- Incluyendo el pie de página -->
    <?php include './views/layout/footer.php'; ?>
</body>
</html>