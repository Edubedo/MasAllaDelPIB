<?php
session_start();
require '../config/database.php';
// Se hace la consulta y se obtienen los datos de las publicaciones 
$query = "SELECT Id_posts, title, content, post_date, category, image, user_creation, vote_up, vote_down
          FROM posts"; 


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

$idtypeuser = $_SESSION['id_type_user'] ?? null;
// Si no hay valor en $_SESSION['id_type_user'], asignar por defecto "visitante"
$idtypeuser = $_SESSION['id_type_user'] ?? 3; // Por defecto, tipo 3 = visitante

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
        <link rel="stylesheet" href='./css/posts.css'>
        <link rel="stylesheet" href='css/index.css'>

        <!-- Font Awesome para iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    </head>

    <body>
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
                <li><a href="./posts.php">Todas las categorias</a></li>
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
            <?php
                include ('megusta.php');
                $posts = new Posts($pdo); // Instanciar la clase Posts

                foreach ($postsDB as $post) {
                    // Si no hay imagen, usamos la imagen predeterminada
                    $imageSrc = !empty($post['image']) ? "../admin/posts/" . htmlspecialchars($post['image']) : "../admin/posts/uploads/preterminada.jpg";

                    // Estructura base del enlace
                    $postLink = 'post.php?id=' . htmlspecialchars($post['Id_posts']);
                    $title = htmlspecialchars(strlen($post['title']) > 70 ? substr($post['title'], 0, 54) . "..." : $post['title']);
                    $userCreation = htmlspecialchars($post['user_creation']);
                    $postDate = date("F d, Y", strtotime($post['post_date']));
                    $content = htmlspecialchars(strlen($post['title']) > 60 ? substr($post['content'], 0, 225) . "..." : substr($post['content'], 0, 180) . "...");

                    // Estructura común del post
                    $postHTML = '
                        <a href="' . $postLink . '">
                            <div class="p1">
                                <div class="cuerpo_post">
                                    <div class="imagen_post">
                                        <img class="imagen1" src="' . $imageSrc . '" alt="imagen de ' . htmlspecialchars($post['title']) . '">
                                    </div>
                                    <div class="info_post">
                                        <h4 class="titulo1">' . $title . '</h4>
                                        <div class="datos1">
                                            <i class="far fa-user"></i> <span>' . $userCreation . '</span>
                                            <i class="far fa-calendar"></i> <span>' . $postDate . '</span>
                                        </div>
                                        <p class="texto1">' . $content . '</p>
                                    </div>
                                </div>';

                    // Lógica de interacciones según el tipo de usuario
                    if ($idtypeuser == 1 || $idtypeuser == 2) {
                        $postHTML .= '
                                <div class="interaccion">
                                    <div class="likes">
                                        <a class="options" data-vote-type="1" id="post_vote_up_' . htmlspecialchars($post['Id_posts']) . '">
                                            <i class="fas fa-thumbs-up" data-original-title="Like this post"></i>
                                        </a>
                                        <span class="likes_count" id="vote_up_count_' . htmlspecialchars($post['Id_posts']) . '">' . htmlspecialchars($post['vote_up'] ?? 0) . '</span>
                                        <a class="options" data-vote-type="0" id="post_vote_down_' . htmlspecialchars($post['Id_posts']) . '">
                                            <i class="fas fa-thumbs-down" data-original-title="Dislike this post"></i>
                                        </a>
                                        <span class="likes_count" id="vote_down_count_' . htmlspecialchars($post['Id_posts']) . '">' . htmlspecialchars($post['vote_down'] ?? 0) . '</span>
                                    </div>
                            </div>';
                    }

                    $postHTML .= '</div></a>';

                    // Imprimir el HTML de la publicación
                    echo $postHTML;
                }
            ?>
        </div>

        

        <!-- Footer -->
        <?php include './layout/footer.php'; ?>

    </body>

</html>