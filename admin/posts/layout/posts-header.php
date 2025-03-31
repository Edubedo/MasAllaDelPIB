<?php
include '../../config/database.php';
include "posts-eliminar.php";

// Verifica si hay una session activa y mandamos a llamar el nombre del usuario
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
} else {
    // Si no hay usuario logueado lo va a redirigir al login
    header("Location: ../../views/signin.php");
    exit();
}

// Obtener el tipo de usuario del usuario
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($result);
$idtypeuser = $row['id_type_user'];
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
    <!-- ICONSCOUT CDN -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <!-- GOOGLE FONT(MONTSERATE) -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,800;1,700&display=swap" rel="stylesheet">
</head>

<body>

    <nav>
        <div class="container nav__container">
            <a href="<?= "/" ?>index.php" class="nav__logo">
                <span class="nav__logo-name">
                    <img src="../../assets/img/logo.png" alt="imagen logo empresa" width="50" height="40">
                    <h2 style="color:white">MAS ALLÁ DEL PIB</h2>
                </span>
            </a>
            <ul class="nav__items" style="font-size: 1.2rem;">
                <li><a href="/index.php">Inicio</a></li>
                <li><a href="<?= "/views/" ?>posts.php">Publicaciones</a></li>
                <?php
                if ($idtypeuser == 1) { // si es administrador
                    echo '<li><a href="/admin/posts/panel-usuarios.php">Panel de usuarios</a></li>';
                } else if ($idtypeuser == 2) { // si es autor
                    echo '';
                }
                ?>
            </ul>

        </div>
    </nav>