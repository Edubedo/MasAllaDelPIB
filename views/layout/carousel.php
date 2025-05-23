<?php

include '././config/database.php';



// consulta a la base de datos y limitamos a 6 el carousel
$query = "SELECT Id_posts, title, content, post_date, category, image, user_creation 
          FROM posts 
          WHERE status = 'ACTIVO'
          ORDER BY RAND()
          LIMIT 6";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Resultados del array
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$posts) {
        die("No se encontraron publicaciones.");
    }

    // Ahora que tenemos los posts, podemos buscar la foto de perfil de cada autor
    foreach ($posts as &$post) {
        $stmtUser = $pdo->prepare("SELECT foto_perfil FROM users WHERE username = :username");
        $stmtUser->execute([':username' => $post['user_creation']]);
        $userProfile = $stmtUser->fetch(PDO::FETCH_ASSOC);
        $foto_perfil = $userProfile['foto_perfil'] ?? null;
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $post['ruta_foto_perfil'] = !empty($foto_perfil) ? $protocol . $host . "/views/uploads/" . htmlspecialchars($foto_perfil) : $protocol . $host . "/views/uploads/user-default2.jpeg";
    }
    unset($post); // Por buenas prácticas al usar foreach por referencia
} catch (PDOException $e) {
    die("Error al consultar los posts: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Incluyendo los estilos -->
    <link rel="stylesheet" href="./views/css/carousel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Estilos para mejorar el carrusel */
        .slick-track {
            display: flex !important;
            align-items: center;
        }

        .slick-slide {
            opacity: 1;
            transform: scale(1);
            transition: all 0.5s ease;
        }

        /* No need for different styles for current/center slides since all slides will look the same */
        .slick-slide.slick-current img,
        .slick-slide.slick-center img {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Mejoras para navegación */
        .prev,
        .next {
            font-size: 24px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
            cursor: pointer;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .post-wrapper {
            position: relative;
            overflow: visible !important;
        }

        /* Ocultar el carrusel hasta que esté listo */
        .post-slider .post-wrapper {
            visibility: hidden;
        }

        .post-slider .post-wrapper.slick-initialized {
            visibility: visible;
        }
    </style>
</head>

<body>
    <main>
        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <!-- Post Slider -->
            <div class="post-slider">
                <i class="fas fa-chevron-left prev"></i>
                <i class="fas fa-chevron-right next"></i>

                <div class="post-wrapper">
                    <!-- El foreach aqui porque trabajamos con array y es mas dinamico -->
                    <?php foreach ($posts as $post):
                        // Si no hay imagen, usamos la imagen predeterminada
                        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
                        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                        if (!empty($post['image'])) {
                            $imageSrc = $protocol . $host . "/admin/posts/" . htmlspecialchars($post['image']);
                        } else {
                            $imageSrc = $protocol . $host . "/admin/posts/uploads/preterminada.jpg";
                        }
                    ?>
                        <a href="/views/post.php?id=<?= htmlspecialchars($post['Id_posts']); ?>" class="post-link">
                            <div class="post">
                                <!-- Usar la imagen predeterminada -->
                                <img src="<?= $imageSrc; ?>" class="slider-image">

                                <div class="post-info">
                                    <h4><?= htmlspecialchars(strlen($post['title']) > 44 ? substr($post['title'], 0, 46) . "..." : $post['title']); ?></h4>
                                    <div class="post-data">
                                        <div class="imagen-user">
                                            <img src="<?= htmlspecialchars($post['ruta_foto_perfil']); ?>" alt="Foto de perfil">
                                            <span><?= htmlspecialchars($post['user_creation']); ?></span>
                                        </div>
                                        <i class="far fa-calendar"></i>
                                        <span><?= date("F d, Y", strtotime($post['post_date'])); ?></span>
                                    </div>
                                    <p><?= htmlspecialchars(strlen($post['content']) > 120 ? substr($post['content'], 0, 120) . "..." :  $post['content']); ?></p>
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

    <!-- Inicialización alternativa del carrusel para evitar problemas de carga -->
    <script>
        $(document).ready(function() {
            // Esperar a que las imágenes estén cargadas
            var $postWrapper = $('.post-wrapper');

            // Inicializar carrusel si aún no está inicializado
            if (!$postWrapper.hasClass('slick-initialized')) {
                $postWrapper.slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 3000,
                    nextArrow: $('.next'),
                    prevArrow: $('.prev'),
                    responsive: [{
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            }
        });
    </script>
</body>

</html>