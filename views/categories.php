<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB - Categorias</title>
    <script src="../js/main.js"></script>
<<<<<<< HEAD

    <link rel="stylesheet" href="./css/navbar.css">
=======
>>>>>>> 1504c762522f6f9e15ce3afb02ced21638a047a6
    <link rel="stylesheet" href='./css/footer.css'>
    <link rel="stylesheet" href='./css/posts.css'>
</head>

<body>
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- <?php include './layout/header.php'; ?> -->
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->

    <?php
    $category = $_GET['category'];
    $posts = json_decode(file_get_contents('../data/posts.json'), true);
    $filteredPosts = array_filter($posts, function ($post) use ($category) {
        return $post['category'] == $category;
    });
    ?>

    <nav class="nav_public">
        <ul class="list">
            <li><a href="./posts.php">Todas las categorias</a></li>
            <li><a href="./categories.php?category=crecimiento-economico">Crecimiento Economico</a></li>
            <li><a href="./categories.php?category=emprendimiento-negocios">Emprendimiento y Negocios</a></li>
            <li><a href="./categories.php?category=mundo-laboral">Mundo Laboral</a></li>
        </ul>
    </nav>

    <div class="encabezado">
        <h1><?php echo strtoupper(str_replace('-', ' ', $category)); ?></h1>
    </div>
    <div class="cuerpo">
        <?php foreach ($filteredPosts as $post): ?>
            <div class="p1">
                <div>
                    <img class="imagen1" src="<?php echo $post['image']; ?>" alt="imagen de <?php echo $post['title']; ?>">
                </div>
                <div class="texto1">
                    <h4><?php echo $post['title']; ?></h4>
                    <p><?php echo $post['description']; ?></p>
                </div>
            </div>
<<<<<<< HEAD
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen mundo laboral">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
        <div class="p1">
            <div>
                <img class="imagen1" src="../assets/img/laboral.jpg" alt="imagen de nosotros">
            </div>
            <div class="texto1">
                <h4>Titulo de la publicacion</h4>
                <p>Pequeña informacion sobre la publicacion para dar interes</p>
            </div>
        </div>
=======
        <?php endforeach; ?>
>>>>>>> 1504c762522f6f9e15ce3afb02ced21638a047a6
    </div>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->
</body>

</html>