<?php
// Iniciar sesión si aún no se ha hecho
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../config/database.php';

// Asignar tipo de usuario y email desde la sesión
$idtypeuser = $_SESSION['id_type_user'] ?? 3;
$email = $_SESSION['email'] ?? null;
$foto_perfil = '';
$username = $_SESSION['username'] ?? null;


// Obtener ID del usuario y foto de perfil si hay email
$iduser = null;
if ($email) {
    $sql = "SELECT iduser, foto_perfil FROM users WHERE email = '$email'";
    $result = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_assoc($result);
    $iduser = $row['iduser'];
    $foto_perfil = $row['foto_perfil'] ?? '';
}

// Ruta a la imagen o imagen por defecto
$rutaImagen = !empty($foto_perfil) ? "/views/uploads/" . $foto_perfil : "/views/uploads/user-default2.jpeg";
?>

<!DOCTYPE HTML>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="public, max-age=86400">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Website</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/views/css/navbar.css">
    <link rel="stylesheet" href="/admin/posts/css/userpop.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,800;1,700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="fondo-overlay"></div>
    <nav>
        <div class="container nav__container">
            <a href="/index.php" class="nav__logo">
                <span class="nav__logo-name">
                    <img src="/assets/img/logo.png" alt="imagen logo empresa" width="50" height="40">
                    <h2 style="color:white;">Mas Allá Del PIB</h2>
                </span>
            </a>

            <ul class="nav__items" style="font-size: 1.5rem;">
                <?php
                if ($idtypeuser == 1) {
                    echo '
                        <li><a class="texto_a" href="/index.php"><i class="fas fa-home"></i><span class="hover-text">Inicio</span></a></li>
                        <li><a class="texto_a" href="/admin/posts/posts-consulta.php"><i class="fas fa-pen"></i><span class="hover-text">Posts</span></a></li>
                        <li><a class="texto_a" href="/admin/posts/panel-usuarios.php"><i class="fas fa-users"></i><span class="hover-text">Usuarios</span></a></li>
                        <li><a class="texto_a" id="settings-icon"><i class="fas fa-cog"></i><span class="hover-text">Ajustes</span></a></li>
                        <li><a class="texto_a" href="#" id="language-icon" data-lang="es"><img src="../../views/uploads/Bandera_de_España.svg.png" alt="Español" class="flag-icon"><span class="hover-text">Idioma</span></a></li>';
                } elseif ($idtypeuser == 2) {
                    echo '
                        <li><a class="texto_a" href="/index.php"><i class="fas fa-home"></i><span class="hover-text">Inicio</span></a></li>
                        <li><a class="texto_a" href="/views/publicaciones.php"><i class="fas fa-clipboard-list"></i><span class="hover-text">Posts</span></a></li>
                        <li><a class="texto_a" href="/views/about.php"><i class="fas fa-users-cog"></i><span class="hover-text">Nosotros</span></a></li>
                        <li style="display: flex; align-items: center; justify-content: center;">
                            <a class="texto_a" href="/admin/posts/posts-consulta.php" style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                <img src="' . $rutaImagen . '" alt="Foto de perfil" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                                <span class="hover-text">Perfil</span>
                            </a>
                        </li>
                        <li><a class="texto_a" id="settings-icon"><i class="fas fa-cog"></i><span class="hover-text">Ajustes</span></a></li>
                        <li><a class="texto_a" href="#" id="language-icon" data-lang="es"><img src="../../views/uploads/Bandera_de_España.svg.png" alt="Español" class="flag-icon"><span class="hover-text">Idioma</span></a></li>';
                } elseif ($idtypeuser == 3) {
                    echo '
                        <li><a class="texto_a" href="/index.php"><i class="fas fa-home"></i><span class="hover-text">Inicio</span></a></li>
                        <li><a class="texto_a" href="/views/publicaciones.php"><i class="fas fa-clipboard-list"></i><span class="hover-text">Posts</span></a></li>
                        <li><a class="texto_a" href="/views/about.php"><i class="fas fa-users-cog"></i><span class="hover-text">Nosotros</span></a></li>
                        <li><a class="texto_a" href="/views/signin.php"><i class="fas fa-sign-in-alt"></i><span class="hover-text">Ingresar</span></a></li>
                        <li><a class="texto_a" href="#" id="language-icon" data-lang="es"><img src="../../views/uploads/Bandera_de_España.svg.png" alt="Español" class="flag-icon"><span class="hover-text">Idioma</span></a></li>';
                }
                ?>
            </ul>

            <?php if ($idtypeuser == 1 || $idtypeuser == 2): ?>
                <div id="userPopup">
                    <div class="imagen-pop">
                        <div class="imagen-user">
                            <img id="img_user" src="<?= $rutaImagen ?>" alt="Foto de perfil">
                        </div>
                    </div>
                    <div class="nombre-pop">
                        <p><span id="hola-text">¡Hola,</span> <span id="username"><?= htmlspecialchars($username) ?></span>!</p>
                    </div>
                    <div class="info-pop">
                        <div class="nombredeluser">
                            <p><strong>Nombre de usuario</strong></p>
                            <p><span><?= htmlspecialchars($username) ?></span></p>
                        </div>
                        <div class="emaildeluser">
                            <p><strong>Email</strong></p>
                            <p><span><?= htmlspecialchars($email) ?></span></p>
                        </div>
                    </div>
                    <div class="botones-pop">
                        <a href="/admin/posts/editar-perfil.php?id=<?= $iduser ?>">
                            <button type="button"><i class="fas fa-user-edit"></i> Editar perfil</button>
                        </a>
                        <a href="/config/logout.php">
                            <button type="button"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="nav__icon-group" style="display: flex; align-items: center; justify-content: flex-end; min-width: 100px;">
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                $hidden_pages = ['posts-consulta.php', 'about.php', 'signin.php', 'nueva-publicacion.php', 'editar-publicacion.php', 
                'panel-usuarios.php', 'editar-perfil.php', 'posts-consulta.php', 'olvidaste_tu_contrasena.php', 'verificar_codigo.php', 'cambiar_contrasena.php'];
                if (!in_array($current_page, $hidden_pages)):
                ?>
                    <div class="search-container">
                        <i class="fas fa-search" id="search-icon" style="font-size: 22px; color:white; cursor:pointer;"></i>
                        <input type="text" id="search-input" placeholder="Buscar...">
                        <div id="search-results" class="search-results"></div>
                    </div>
                <?php endif; ?>

                <button id="open__nav-btn" style="background-color: transparent; border: none; cursor: pointer; margin-left: 15px;">
                    <i class="fas fa-bars" style="font-size: 24px; color: white;"></i>
                </button>
                <button id="close__nav-btn" style="background-color: transparent; border: none; cursor: pointer; margin-left: 15px; display: none;">
                    <i class="fas fa-times" style="font-size: 24px; color: white;"></i>
                </button>
            </div>

        </div>
    </nav>

    <script src="/js/main.js"></script>
    <script src="/js/buscar.js"></script>
    <script src="/js/profile.js"></script>
    <script src="/js/language.js"></script>
    <script src="/js/translations.js"></script>
</body>

</html>