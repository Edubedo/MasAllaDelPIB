<?php
session_start();
require '../config/database.php';

$query = "SELECT Id_posts, title, content, post_date, category, image, user_creation, vote_up, vote_down 
          FROM posts ";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $postsDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar publicaciones: " . $e->getMessage());
}

$category = $_GET['category'] ?? null; 

// Filtrar las publicaciones si hay una categoría seleccionada
if ($category) {
    $postsDB = array_filter($postsDB, function ($postDB) use ($category) {
        return $postDB['category'] === $category; 
    });
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB - Categorías</title>
    <script src="../js/main.js"></script>
    <script src="../js/buscar.js"></script>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href='./css/footer.css'>
    <link rel="stylesheet" href='./css/publicaciones.css'>
    <link rel="stylesheet" href='css/index.css'>

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- <?php include './layout/header.php'; ?> -->
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->

    <nav class="nav_public">
        <ul class="list">
            <li><a href="./publicaciones.php">Todas las categorias</a></li>
            <li><a href="./categories.php?category=crecimiento-economico">Crecimiento Economico</a></li>
            <li><a href="./categories.php?category=emprendimiento-negocios">Emprendimiento y Negocios</a></li>
            <li><a href="./categories.php?category=mundo-laboral">Mundo Laboral</a></li>
        </ul>
        <input type="checkbox" id="toggle" style="display: none;">
        <label for="toggle" class="icon-bar">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </label>
    </nav>

    <div class="encabezado">
        <h1><?php echo strtoupper(str_replace('-', ' ', $category)); ?></h1>
    </div>

    <div class="cuerpo">
        <?php include 'megusta.php'; ?>
        <?php include 'layout/posts.php'; ?>
    </div>
    
    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->
</body>

</html>