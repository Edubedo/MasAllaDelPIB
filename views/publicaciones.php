<?php
session_start();
require '../config/database.php';

$query = "SELECT Id_posts, title, content, post_date, category, image, user_creation, vote_up, vote_down 
          FROM posts 
          ORDER BY post_date DESC";

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
        <title>MasAllaDelPIB - Publicaciones</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../js/main.js"></script> 
        <script src="../js/buscar.js"></script> 
        <script src="../js/options.js"></script>
        <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="./css/navbar.css">
        <link rel="stylesheet" href='./css/footer.css'>
        <link rel="stylesheet" href='./css/publicaciones.css'>

        <!-- Font Awesome para iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    </head>

    <body>
        <div class="fondo-overlay"></div>
        <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
        <!-- <?php include './layout/header.php'; ?> -->

        <nav class="nav_public">
            <input type="checkbox" id="toggle" style="display: none;">
            <label class="icon-bars" for="toggle">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </label>

            <!-- Lista de enlaces -->
            <ul class="list">
                <li><a href="./publicaciones.php">Todas las categorias</a></li>
                <li><a href="./categories.php?category=crecimiento-economico">Crecimiento Economico</a></li>
                <li><a href="./categories.php?category=emprendimiento-negocios">Emprendimiento y Negocios</a></li>
                <li><a href="./categories.php?category=mundo-laboral">Mundo Laboral</a></li>
            </ul>
        </nav>

        <!-- Encabezado -->
        <div class="encabezado">
            <h1>PUBLICACIONES GENERALES</h1>
        </div>

        <!-- Cuerpo de las publicaciones -->

        <div class="cuerpo">
            <?php include 'megusta.php'; ?>
            <?php include 'layout/posts.php'; ?>
        </div>

        

        <!-- Footer -->
        <?php include './layout/footer.php'; ?>

    </body>

</html>