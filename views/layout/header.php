<?php
$idtypeuser = $_SESSION['id_type_user'] ?? null;
// Si no hay valor en $_SESSION['id_type_user'], asignar por defecto "visitante"
$idtypeuser = $_SESSION['id_type_user'] ?? 3; // Por defecto, tipo 3 = visitante
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="max-age=3600">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Website</title>

    <script src="js/main.js"></script>
    <!-- CUSTOM STYLESHEET -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,800;1,700&display=swap" rel="stylesheet">
</head>

<body>

    <nav>
        <div class="container nav__container">
            <a href="<?= "/" ?>index.php" class="nav__logo">
                <span class="nav__logo-name">
                    <img src="../../assets/img/logo.png" alt="imagen logo empresa" width="50" height="40">
                    <h2 style="color:white;">Mas Allá Del PIB</h2>
                </span>
            </a>
            <ul class="nav__items" style="font-size: 1.5rem;">
                
                <?php

                if ($idtypeuser == 1) { // Administrador
                    echo '<li><a class="texto_a" href="/index.php">Inicio</a></li>';
                    echo '<li><a class="texto_a" href="/admin/posts/posts-consulta.php">Panel de publicaciones</a></li>';
                    echo '<li><a class="texto_a" href="/admin/posts/panel-usuarios.php">Panel de usuarios</a></li>';
                }elseif ($idtypeuser == 2) { // Autor
                    echo '<li><a class="texto_a" href="/index.php">Inicio</a></li>';
                    echo '<li><a class="texto_a" href="/views/posts.php">Publicaciones</a></li>';
                    echo '<li><a class="texto_a" href="/views/about.php">Nosotros</a></li>';
                    echo '<li><a class="texto_a" href="/admin/posts/posts-consulta.php">Panel de control</a></li>';

                }elseif($idtypeuser == 3){ //visitante
                    echo '<li><a class="texto_a" href="/index.php">Inicio</a></li>';
                    echo '<li><a class="texto_a" href="/views/posts.php">Publicaciones</a></li>';
                    echo '<li><a class="texto_a" href="/views/about.php">Nosotros</a></li>';
                    echo '<li><a class="texto_a" href="/views/signin.php">Iniciar Sesión</a></li>';
                }
                
                
                ?>
            </ul>

            <button id="open__nav-btn"><i class="uil uil-bars"></i></button>
            <button id="close__nav-btn"><i class="uil uil-multiply"></i></button>
        </div>
    </nav>

</body>

</html>
