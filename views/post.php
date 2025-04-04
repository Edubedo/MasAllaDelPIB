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
            
            if($idtypeuser == 1 || $idtypeuser == 2){
                echo '
                <div class="noticia">
                    <div class="titulo">
                        <h1>' . htmlspecialchars($post['title']) . '</h1>
                        <div class="datos">
                            <i class="far fa-user"></i>' . htmlspecialchars($post['user_creation']).' |
                            <i class="far fa-calendar"></i> ' . date("F d, Y", strtotime($post['post_date'])) . '
                        </div>
                    </div>

                    <div class="imagen-noticia">
                        <img class="img" src="' . htmlspecialchars($imageSrc) .'" alt="Imagen de la noticia">
                    </div>
                    
                    <div class="info">
                        <div class="texto">
                            <p class="texto-noticia">' . nl2br(htmlspecialchars($post['content'])) . '</p>
                        </div>
                    </div>
                </div>
                <div class="caja-comentarios">
                    <div class="titulo-com">Comentarios</div>
                </div>
                
                ';
            }elseif($idtypeuser==3){
                echo '
                <div class="noticia">
                    <div class="titulo">
                        <h1>' . htmlspecialchars($post['title']) . '</h1>
                        <div class="datos">
                            <i class="far fa-user"></i>' . htmlspecialchars($post['user_creation']).' |
                            <i class="far fa-calendar"></i> ' . date("F d, Y", strtotime($post['post_date'])) . '
                        </div>
                    </div>

                    <div class="imagen-noticia">
                        <img class="img" src="' . htmlspecialchars($imageSrc) .'" alt="Imagen de la noticia">
                    </div>
                    
                    <div class="info">
                        <div class="texto">
                            <p class="texto-noticia">' . nl2br(htmlspecialchars($post['content'])) . '</p>
                        </div>
                    </div>
                </div>
                ';
            }
        
        ?>

        

        <!-- IMPORTAR EL FOOTER -->
        <?php include './layout/footer.php'; ?>
        <!-- IMPORTAR EL FOOTER -->
    </body>

</html>
