
<?php
    include('../config/database.php');
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MasAllaDelPIB - Publicación</title>

        <script src="../js/main.js"></script>
        <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="./css/navbar.css">
        <link rel="stylesheet" href='./css/footer.css'>
        <link rel="stylesheet" href='./css/post.css'>

    </head>

    <body>
        <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
        <!-- <?php include './layout/header.php'; ?> -->
        <!-- IMPORTAR BARRA DE NAVEGACIÓN -->

        <div class="noticia">
            <?php
        

            $postId = $_GET['id'];

            $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
            $stmt->execute(['id' => $postId]);

            $post = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($post) {
            ?>
            <div class="titulo">
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            </div>

            <img class="img" src="<?php echo htmlspecialchars($post['image']); ?>" alt="Imagen de la noticia">

            <div class="info">
                <div class="texto">
                    <p class="texto-noticia"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                </div>

                <div class="autor">
                    <p class="autor-noticia"><?php echo htmlspecialchars($post['user_cration']); ?></p>
                    <p class="fecha-noticia"><?php echo htmlspecialchars($post['post_date']); ?></p>
                </div>
            </div>

            <?php
            }
            ?>
        </div>

        <!-- IMPORTAR EL FOOTER -->
        <?php include './layout/footer.php'; ?>
        <!-- IMPORTAR EL FOOTER -->
    </body>

    </html>
