<?php
require 'config/database.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Incluyendo los estilos -->
     <link rel="stylesheet" href="./views/css/carousel.css">
    
</head>

<body>
    <main>
        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <!-- Post Slider -->
            <div class="post-slider">
                <div class="slider-title">
                    <h1>Nuevas Noticias</h1>
                </div>

                <div class="post-wrapper">
                    <div class ="post">1</div>
                    <div class ="post">2</div>
                    <div class ="post">3</div>
                    <div class ="post">4</div>
                    <div class ="post">5</div>
                </div>

            </div>
            <!-- Post Slider -->

        </div>
        <!-- //Page Wrapper -->


        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Slick Carousel -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

        <!-- Custom Script -->
         <script src="js/scripts.js"></script>
    </main>
</body>
