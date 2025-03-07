<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB</title>
    <script src="../js/main.js"></script>

    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href='./css/footer.css'>
    <link rel="stylesheet" href='./css/posts.css'>
    <link rel="stylesheet" href='css/index.css'>

</head>

<body>

    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- <?php include './layout/header.php'; ?> -->
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->

    <nav class="nav_public">
        <input type="checkbox" id="toggle">
        <ul class="list">
            <li><a href="./posts.php">Todas las categorias</a></li>
            <li><a href="./categories.php?category=crecimiento-economico">Crecimiento Economico</a></li>
            <li><a href="./categories.php?category=emprendimiento-negocios">Emprendimiento y Negocios</a></li>
            <li><a href="./categories.php?category=mundo-laboral">Mundo Laboral</a></li>
        </ul>
    </nav class="nav_public">

    <div class="encabezado">
        <h1>Publicaciones</h1>
    </div>
    <div class="cuerpo">
<<<<<<< HEAD
        <a href="./post.php?=1">
            <div class="p1">
                <div>
                    <img class="imagen1" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
                </div>
                <div class="texto1">
                    <h4>Titulo de la publicacion</h4>
                    <p>Pequeña informacion sobre la publicacion para dar interes</p>
                </div>
            </div>
        </a>
        <a href="/views/layouts/post.php">
            <div class="p1">
                <div>
                    <img class="imagen1" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
                </div>
                <div class="texto1">
                    <h4>Titulo de la publicacion</h4>
                    <p>Pequeña informacion sobre la publicacion para dar interes</p>
                </div>
            </div>
        </a>
        <a href="/views/layouts/post.php">
            <div class="p1">
                <div>
                    <img class="imagen1" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
                </div>
                <div class="texto1">
                    <h4>Titulo de la publicacion</h4>
                    <p>Pequeña informacion sobre la publicacion para dar interes</p>
                </div>
            </div>
        </a>
        <a href="/views/layouts/post.php">
            <div class="p1">
                <div>
                    <img class="imagen1" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
                </div>
                <div class="texto1">
                    <h4>Titulo de la publicacion</h4>
                    <p>Pequeña informacion sobre la publicacion para dar interes</p>
                </div>
            </div>
        </a>
        <a href="/views/layouts/post.php">
            <div class="p1">
                <div>
                    <img class="imagen1" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
                </div>
                <div class="texto1">
                    <h4>Titulo de la publicacion</h4>
                    <p>Pequeña informacion sobre la publicacion para dar interes</p>
                </div>
            </div>
        </a>
        <a href="/views/layouts/post.php">
            <div class="p1">
                <div>
                    <img class="imagen1" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
                </div>
                <div class="texto1">
                    <h4>Titulo de la publicacion</h4>
                    <p>Pequeña informacion sobre la publicacion para dar interes</p>
                </div>
            </div>
        </a>
        <a href="/views/layouts/post.php">
            <div class="p1">
                <div>
                    <img class="imagen1" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
                </div>
                <div class="texto1">
                    <h4>Titulo de la publicacion</h4>
                    <p>Pequeña informacion sobre la publicacion para dar interes</p>
                </div>
            </div>
        </a>
        <a href="/views/layouts/post.php">
            <div class="p1">
                <div>
                    <img class="imagen1" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
                </div>
                <div class="texto1">
                    <h4>Titulo de la publicacion</h4>
                    <p>Pequeña informacion sobre la publicacion para dar interes</p>
                </div>
            </div>
        </a>



=======
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
>>>>>>> 1504c762522f6f9e15ce3afb02ced21638a047a6
    </div>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->

</body>

</html>