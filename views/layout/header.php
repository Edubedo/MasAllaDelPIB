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
    <script src="/js/buscar.js"></script>

    <!-- CUSTOM STYLESHEET -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    echo '<li><a class="texto_a" href="/index.php"><i class="fas fa-home"></i></a></li>';
                    echo '<li><a class="texto_a" href="/admin/posts/posts-consulta.php"><i class="fas fa-pen"></i></a></li>'; // Cambié el icono de publicaciones
                    echo '<li><a class="texto_a" href="/admin/posts/panel-usuarios.php"><i class="fas fa-users"></i></a></li>';
                } elseif ($idtypeuser == 2) { // Autor
                    echo '<li><a class="texto_a" href="/index.php"><i class="fas fa-home"></i></a></li>';
                    echo '<li><a class="texto_a" href="/views/posts.php"><i class="fas fa-clipboard-list"></i></a></li>';  // Cambié el icono de publicaciones -->
                    echo '<li><a class="texto_a" href="/views/about.php"><i class="fas fa-users-cog"></i></a></li>';  // Cambié el icono de Nosotros -->
                    echo '<li><a class="texto_a" href="/admin/posts/posts-consulta.php"><i class="fas fa-cogs"></i></a></li>';
                } elseif($idtypeuser == 3) { // Visitante
                    echo '<li><a class="texto_a" href="/index.php"><i class="fas fa-home"></i></a></li>';
                    echo '<li><a class="texto_a" href="/views/posts.php"><i class="fas fa-clipboard-list"></i></a></li>';  // Cambié el icono de publicaciones -->
                    echo '<li><a class="texto_a" href="/views/about.php"><i class="fas fa-users-cog"></i></a></li>';  // Cambié el icono de Nosotros -->
                    echo '<li><a class="texto_a" href="/views/signin.php"><i class="fas fa-sign-in-alt"></i></a></li>';
                }
                ?>
            </ul>

            <!-- Agregar el buscador aquí -->
            <div class="search-container">
                <i class="fas fa-search" id="search-icon" style="font-size: 22px; color:white; cursor:pointer;"></i>
                <input type="text" id="search-input" placeholder="Buscar..." style="display:none;">
                <div id="search-results" class="search-results"></div>
            </div>

            <button id="open__nav-btn"><i class="fas fa-bars"></i></button>
            <button id="close__nav-btn"><i class="fas fa-times"></i></button>
        </div>
    </nav>

</body>

</html>
