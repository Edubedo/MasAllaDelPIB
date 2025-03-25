<?php
require '../config/database.php';

// Definir la ruta de la imagen predeterminada
$imagenPredeterminada = '../admin/posts/uploads/preterminada.jpg'; // Cambia esto por la ruta correcta

// Se hace la consulta y se obtienen los datos de las publicaciones 
$query = "SELECT Id_posts, title, content, post_date, category, image, user_creation
          FROM posts 
          "; 

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
    <title>MasAllaDelPIB - Publicaciones</title>
    <script src="../js/main.js"></script>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href='./css/footer.css'>
    <link rel="stylesheet" href='./css/posts.css'>
    <link rel="stylesheet" href='css/index.css'>

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- <?php include './layout/header.php'; ?> -->
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- BARRA DE NAVEGACIÓN -->
    <nav class="nav_public">
        <input type="checkbox" id="toggle" style="display: none;">
        <label class="icon-bars" for="toggle">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>

        <!-- Lista de enlaces -->
        <ul class="list">
            <li><a href="./posts.php">Todas las categorias</a></li>
            <li><a href="./categories.php?category=crecimiento-economico">Crecimiento Economico</a></li>
            <li><a href="./categories.php?category=emprendimiento-negocios">Emprendimiento y Negocios</a></li>
            <li><a href="./categories.php?category=mundo-laboral">Mundo Laboral</a></li>
        </ul>
    </nav>

    <!-- Encabezado -->
    <div class="encabezado">
        <h1>Publicaciones</h1>
    </div>

    <!-- Cuerpo de las publicaciones -->

    <div class="cuerpo">
        <?php
            foreach ($postsDB as $post) { 

                echo '<a href="./post.php?id=' . htmlspecialchars($post['Id_posts']) . '">
                        <div class="p1">
                            <div class="imagen_post">
                                <img class="imagen1" src="' . htmlspecialchars($post['image']) . '" alt="imagen de ' . htmlspecialchars($post['title']) . '">
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

    <!-- Footer -->
    <?php include './layout/footer.php'; ?>

</body>

</html>