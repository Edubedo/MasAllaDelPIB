<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB</title>
    <script src="../js/main.js"></script>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href='./css/footer.css'>
    <link rel="stylesheet" href='./css/posts.css'>
    <link rel="stylesheet" href='css/index.css'>
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
        $posts = json_decode(file_get_contents('../data/posts.json'), true); // Obtener los posts
        foreach ($posts as $post) { // Recorrer los posts
            echo '<a href="./post.php?id=' . $post['id'] . '">
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

    <!-- Footer -->
    <?php include './layout/footer.php'; ?>

</body>

</html>
