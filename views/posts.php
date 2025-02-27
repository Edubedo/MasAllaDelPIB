<!-- AQUI ESTARÍA BUENO HACERLO REUTILIZABLE, NO TENER QUE DUPLICAR LAS CATEGORÍAS -->
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

    <!-- <nav class="nav_public">
        <input type="checkbox" id="toggle">
        <label for="toggle" class="icon-bars">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>
        <ul class="list">
            <li><a href="../assets/img/posts.php">Todas las categorias</a></li>
            <li><a href="../assets/img/catCE.php">Crecimiento Economico</a></li>
            <li><a href="../assets/img/catEYN.php">Emprendimiento y Negocios</a></li>
            <li><a href="../assets/img/catML.php">Mundo Laboral</a></li>
        </ul>
    </nav> -->
    <nav class="nav_public">
        <input type="checkbox" id="toggle">
        <ul class="list">
            <li><a href="./posts.php">Todas las categorias</a></li>
            <li><a href="./categories.php?=crecimiento-economico">Crecimiento Economico</a></li>
            <li><a href="./categories.php?=emprendimiento-negocios">Emprendimiento y Negocios</a></li>
            <li><a href="./categories.php?=mundo-laboral">Mundo Laboral</a></li>
        </ul>
    </nav class="nav_public">

    <div class="encabezado">
        <h1>TODOS LOS POSTS</h1>
    </div>
    <div class="cuerpo">
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



    </div>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->

</body>