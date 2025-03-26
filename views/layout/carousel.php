<?php

include '././config/database.php';



// consulta a la base de datos y limitamos a 6 el carousel
$query = "SELECT Id_posts, title, content, post_date, category, image, user_creation 
          FROM posts 
          ORDER BY post_date DESC 
          LIMIT 6";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Resultados del array
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$posts) {
        die("No se encontraron publicaciones.");
    }
} catch (PDOException $e) {
    die("Error al consultar los posts: " . $e->getMessage());
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Incluyendo los estilos -->
    <link rel="stylesheet" href="./views/css/carousel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <main>
        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <!-- Post Slider -->
            <div class="post-slider">
                <h1 class="slider-title">MAS ALLÁ DEL PIB</h1>
                <i class="fas fa-chevron-left prev"></i>
                <i class="fas fa-chevron-right next"></i>

                <div class="post-wrapper">
                    <!-- El foreach aqui porque trabajamos con array y es mas dinamico -->
                    <?php foreach ($posts as $post): 
                        // Si no hay imagen, usamos la imagen predeterminada
                        $imageSrc = !empty($post['image']) ? "../admin/posts/" . htmlspecialchars($post['image']) : "../admin/posts/uploads/preterminada.jpg";
                    ?>
                        <a href="" target="_blank"> 
                            <div class="post">
                                <!-- Usar la imagen predeterminada -->
                                <img src="<?= $imageSrc; ?>" class="slider-image">
                                
                                <div class="post-info">
                                    <h4><?= htmlspecialchars($post['title']); ?></h4>
                                    <div class="post-data">
                                        <i class="far fa-user"></i> <span><?= htmlspecialchars($post['user_creation']); ?></span>
                                        <i class="far fa-calendar"></i> <span><?= date("F d, Y", strtotime($post['post_date'])); ?></span>
                                    </div>
                                    <p><?= htmlspecialchars(substr($post['content'],0,120) . "..."); ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom Script -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script src="/js/scripts.js"></script>
</body>
</html>
