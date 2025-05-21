<?php
    session_start();
    require 'config/database.php';
    // Obtener publicaciones del usuario con ID 1
    $queryUsuario = "SELECT 
        p.Id_posts, 
        p.title, 
        p.content, 
        p.post_date, 
        p.image,
        p.user_creation,
        p.vote_up, 
        p.vote_down,
        COUNT(l.id_post) AS total_likes
        FROM posts p 
        INNER JOIN users u ON p.user_creation = u.username 
        LEFT JOIN likes l ON p.Id_posts = l.id_post
        WHERE u.id_type_user = 1 
        GROUP BY p.Id_posts
        ORDER BY p.post_date DESC 
        LIMIT 3";

    // puedes ajustar el límite si quieres más o menos

    try {
        $stmtUsuario = $pdo->prepare($queryUsuario);
        $stmtUsuario->execute();
        $postsUsuario = $stmtUsuario->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al consultar publicaciones del usuario: " . $e->getMessage());
    }

    // Obtener las publicaciones más recientes
    $query = "SELECT 
        p.Id_posts, 
        p.title, 
        p.content, 
        p.post_date, 
        p.image,
        p.user_creation,
        p.vote_up, 
        p.vote_down,
        COUNT(l.id_post) AS total_likes
        FROM posts p 
        INNER JOIN users u ON p.user_creation = u.username 
        LEFT JOIN likes l ON p.Id_posts = l.id_post
        WHERE u.id_type_user = 2
        GROUP BY p.Id_posts
        ORDER BY p.post_date DESC 
        LIMIT 4";

    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $postsDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al consultar publicaciones: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB</title>
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon">
    <!-- Incluyendo los estilos -->
    <link rel="stylesheet" href="./views/css/navbar.css">
    <link rel="stylesheet" href="./views/css/footer.css">
    <link rel="stylesheet" href="./views/css/index.css">
    <link rel="stylesheet" href="./views/css/cookies.css">
</head>

<body class="pagina-index">
    <div class="fondo-overlay"></div>
    <!-- Incluyendo la barra de navegación -->
    <?php include 'views/layout/header.php'; ?>

    <div class="encabezadoPrincipal">
        <h1>Nuestro Blog les da la bienvenida</h1>
        <p>Descubre artículos, consejos y recursos para mantenerte siempre informado.</p>
    </div>

    <div class="container-carousel">
        <div class="header">
            <?php include("views/layout/carousel.php"); ?>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="container-principal">
        <div class="div-izquierdo">
            <!-- Sección de Publicaciones -->
            <div class="encabezado">
                <h1>Mas Recientes</h1>
            </div>

            <div class="cuerpo">
                <?php $isIndex = true; ?> <!-- Variable para indicar que estamos en el index -->
                <?php include('views/layout/posts.php'); ?>
            </div>
        </div>

        <div class="div-derecho">
            <div class="barra">
                <h4>Publicaciones Del Autor</h4>
            </div>

            <div class="posts-admin">
                <?php if (!empty($postsUsuario)): ?>
                    <?php
                    $postsDB = $postsUsuario;
                    $isIndex = false; // o true, según necesites
                    include('views/layout/posts-admin.php');
                    ?>
                <?php else: ?>
                    <p>No hay publicaciones del autor.</p>
                <?php endif; ?>
            </div>

            <div class="barra"></div>
        </div>



    </div>

    <div id="cookiesContainer"></div>
    <script src="./js/cookies.js"></script>
    <!-- Incluyendo el pie de página -->
    <?php include './views/layout/footer.php'; ?>
</body>

</html>


<script>
    $(document).on('click', '.options', function(e) {
        e.preventDefault();
        const postId = $(this).attr('id').split('_').pop(); // Extraer el ID del post
        const button = $(this);
        const countElement = $(`#vote_up_count_${postId}`);
        let currentCount = parseInt(countElement.text());

        // Cambiar el estado visual inmediatamente
        if (button.hasClass('liked')) {
            button.removeClass('liked').addClass('not-liked');
            countElement.text(currentCount - 1); // Reducir contador visualmente
        } else {
            button.removeClass('not-liked').addClass('liked');
            countElement.text(currentCount + 1); // Incrementar contador visualmente
        }

        // Enviar la solicitud AJAX al backend
        $.ajax({
            url: '/views/layout/like_handler.php',
            type: 'POST',
            data: {
                id_post: postId,
                vote_type: 1 // Siempre enviamos 1 para "like"
            },
            success: function(response) {
                const res = JSON.parse(response);

                if (!res.success) {
                    // Si hubo un error, revertir el cambio visual
                    if (res.action === 'unliked') {
                        button.removeClass('not-liked').addClass('liked');
                        countElement.text(currentCount + 1); // Revertir contador
                    } else if (res.action === 'liked') {
                        button.removeClass('liked').addClass('not-liked');
                        countElement.text(currentCount - 1); // Revertir contador
                    }
                    alert(res.message); // Mostrar mensaje de error
                }
            },
            error: function() {
                // Si hubo un error en la solicitud, revertir el cambio visual
                if (button.hasClass('liked')) {
                    button.removeClass('liked').addClass('not-liked');
                    countElement.text(currentCount - 1); // Revertir contador
                } else {
                    button.removeClass('not-liked').addClass('liked');
                    countElement.text(currentCount + 1); // Revertir contador
                }
                alert('Error al procesar la solicitud.');
            }
        });
    });
</script>