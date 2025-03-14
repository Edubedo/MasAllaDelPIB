<?php
require 'config/database.php';

//cargar los datos desde el json
$jsonFile = __DIR__ . '/../../data/posts.json';
if (!file_exists($jsonFile)) {
    die("Error: No se encontró el archivo JSON en " . $jsonFile);
}

// Intentar leer el contenido del archivo
$data = file_get_contents($jsonFile);
if ($data === false) {
    die("Error: No se pudo leer el archivo JSON.");
}

$posts = json_decode($data, true);

if (!is_array($posts)) {
    die("Error: El JSON no es válido o está vacío.");
}

usort($posts, function ($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

$filteredPosts = array_slice($posts, 0, 5);

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
                    <div class="post-slider">
                        <h1 class="slider-title">MAS ALLÁ DEL PIB</h1>
                        <i class="fas fa-chevron-left prev"></i>
                        <i class="fas fa-chevron-right next"></i>

                        <div class="post-wrapper">
                            
                            <?php foreach ($filteredPosts as $post): ?> 
                                <a href="<?= $post['link']; ?>">
                                    <div class="post">
                                        <img src="<?= $post['image']; ?>" class="slider-image">                                        
                                        <div class="post-info">
                                            <h4><?= $post['title']; ?></h4>
                                            <div class="post-data">
                                                <i class="far fa-user"></i> <span><?php echo $post['user']; ?></span>
                                                <i class="far fa-calendar"></i> <span><?php echo date("F d, Y", strtotime($post['date'])); ?></span>
                                            </div>
                                            <p><?= $post['description']; ?></p>
                                            
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                                
                            
                        </div>
                    </div>
                    
                </div>
                <!-- //Post Slider -->

            </div>
            <!-- //Page Wrapper -->
        </main>
        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Custom Script -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

        <script src="/js/scripts.js"></script>
        
    </body>
</html>

