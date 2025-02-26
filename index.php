<?php
require 'config/database.php';

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB</title>

    <!-- Incluyendo los estilos -->
    <link rel="stylesheet" href="./views/css/navbar.css">
    <link rel="stylesheet" href="./views/css/footer.css">
    <link rel="stylesheet" href="./views/css/index.css">

    <!-- carrouse -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link href="css/style.min.css" rel="stylesheet">

    <!-- Incluyendo los scripts -->
</head>

<body>
    <!-- Incluyendo la barra de navegación -->
    <?php include 'views/layout/header.php'; ?>

    <!-- Contenido principal -->
    <main>
        <!-- IMAGE CALL ATENTIONS -->
        <?php include("views/layout/carousel.php"); ?>

        <!-- IMAGE CALL ATENTIONS -->

        <div class="grid">
            <!-- Elementos de la grilla con noticias -->
            <div class="item2">
                <h2 class="titulo">Nueva Estrategia Económica en Colima</h2>
                <img src="./assets/img/estrategia-economica.jpg" alt="Estrategia Económica">
                <p>El gobierno de Colima presenta una estrategia para impulsar la economía local, enfocándose en el emprendimiento y las inversiones extranjeras.</p>
            </div>

            <div class="item3">
                <h2 class="titulo">Apertura de Nuevas Tiendas en Colima</h2>
                <img src="./assets/img/nuevas-tiendas.avif" alt="Nuevas Tiendas">
                <p>Colima expande su oferta comercial con nuevas tiendas y centros comerciales, generando más oportunidades laborales.</p>
            </div>

            <div class="item4">
                <h2 class="titulo">Iniciativa de Sostenibilidad en la Ciudad</h2>
                <img src="./assets/img/sostenibilidad.jpg" alt="Sostenibilidad">
                <p>Colima promueve la sostenibilidad mediante energías renovables y la creación de espacios verdes urbanos.</p>
            </div>

            <div class="item5">
                <h2 class="titulo">Nuevo Plan de Infraestructura para Colima</h2>
                <img src="./assets/img/infraestructura.jpeg" alt="Infraestructura">
                <p>El nuevo plan de infraestructura mejorará las carreteras y creará nuevas autopistas para mejorar la conectividad en Colima.</p>
            </div>

            <div class="item6">
                <h2 class="titulo">Educación y Formación para el Futuro</h2>
                <img src="./assets/img/educacion.webp" alt="Educación">
                <p>Colima promueve programas de educación y formación técnica para mejorar las oportunidades laborales en la región.</p>
            </div>
        </div>


    </main>
    <!-- Incluyendo el pie de página -->
    <?php include './views/layout/footer.php'; ?>
</body>

</html>