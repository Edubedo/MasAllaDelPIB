<?php
    session_start();
    include('../config/database.php');

    // Verificar si se recibió un ID válido en la URL
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        die("ID de post no válido.");
    }

    $postId = $_GET['id'];

    // Consultar la base de datos
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE Id_posts = :id");
    $stmt->execute(['id' => $postId]);

    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el post existe
    if (!$post) {
        die("Publicación no encontrada.");
    }

    // Definir imagen predeterminada si no hay imagen en la base de datos
    $imageSrc = !empty($post['image']) ? "../admin/posts/" . htmlspecialchars($post['image']) : "../admin/posts/uploads/preterminada.jpg";

    $idtypeuser = $_SESSION['id_type_user'] ?? null;
    // Si no hay valor en $_SESSION['id_type_user'], asignar por defecto "visitante"
    $idtypeuser = $_SESSION['id_type_user'] ?? 3; // Por defecto, tipo 3 = visitante

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

        <?php
            // Verificar si el tipo de usuario es 1 o 2
            if ($idtypeuser == 1 || $idtypeuser == 2) {
                ?>
                <div class="noticia">
                    <div class="titulo">
                        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                        <div class="datos">
                            <i class="far fa-user"></i> <?php echo htmlspecialchars($post['user_creation']); ?> |
                            <i class="far fa-calendar"></i> <?php echo date("F d, Y", strtotime($post['post_date'])); ?>
                        </div>
                    </div>

                    <div class="imagen-noticia">
                        <img class="img" src="<?php echo htmlspecialchars($imageSrc); ?>" alt="Imagen de la noticia">
                    </div>
                    
                    <div class="info">
                        <div class="texto">
                            <p class="texto-noticia"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        </div>
                    </div>
                </div>
                <div class="caja-comentarios">
                    <div class="titulo-com">Comentarios</div>
                </div>
                <?php
            } elseif ($idtypeuser == 3) {
                ?>
                <div class="noticia">
                    <div class="titulo">
                        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                        <div class="datos">
                            <i class="far fa-user"></i> <?php echo htmlspecialchars($post['user_creation']); ?> |
                            <i class="far fa-calendar"></i> <?php echo date("F d, Y", strtotime($post['post_date'])); ?>
                        </div>
                    </div>

                    <div class="imagen-noticia">
                        <img class="img" src="<?php echo htmlspecialchars($imageSrc); ?>" alt="Imagen de la noticia">
                    </div>
                    
                    <div class="info">
                        <div class="texto">
                            <p class="texto-noticia"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                        </div>
                    </div>

                    <div class="referencias">
                        <h2 class="titulo-ref">Referencias</h2>
                        <?php
                            // Obtener las referencias de la base de datos
                            $referencias = explode("\n", trim($post['referencia_posts'])); // Cambié $mostrar a $post
                            if (!empty($referencias)) {
                                foreach ($referencias as $ref) {
                                    $ref = trim($ref);  // Eliminar espacios en blanco
                                    if (!empty($ref)) {
                                        if (filter_var($ref, FILTER_VALIDATE_URL)) {
                                            echo '<a class="referencias-noticia" href="' . htmlspecialchars($ref) . '" target="_blank">' . htmlspecialchars($ref) . '</a><br>';
                                        } else {
                                            echo htmlspecialchars($ref) . '<br>';
                                        }
                                    }
                                }
                            } else {
                                echo '—'; // Si no hay referencias, muestra un guion
                            }
                        ?>
                    </div>
                </div>
                <div class="caja-comentarios">
                    <div class="titulo-com">Comentarios</div>
                </div>
                <?php
            }
        ?>

        

        <!-- IMPORTAR EL FOOTER -->
        <?php include './layout/footer.php'; ?>
        <!-- IMPORTAR EL FOOTER -->
    </body>

</html>
