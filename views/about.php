<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB - Sobre nostros</title>
    <script src="../js/main.js"></script>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href='./css/footer.css'>
    <link rel="stylesheet" href='./css/about.css'>

</head>

<body>
    <div class="fondo-overlay"></div>
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- <?php include './layout/header.php'; ?> -->
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->

    <div class="cuadrosuperior">
        <div class="titulo">
            <h3 class="empresa">INNOMINDS</h3>
            <h1>QUIENES SOMOS</h1>
        </div>
    </div>

    <div class="linea_contorno">
        <div class="linea"></div>
    </div>

    <div class="cuerpo">
        <div class="container-nosotros">
            <div class="info">
                <p class="texto">
                    Este blog fue creado por estudiantes de la Universidad de Colima como parte de un
                    proyecto escolar cuyo objetivo es contribuir a la sociedad a través de medios
                    digitales. A través de esta iniciativa, buscamos generar contenido de valor que pueda
                    servir de apoyo a la comunidad, aprovechando las ventajas de la tecnología y el desarrollo web.
                    El proyecto se enfoca en el diseño e implementación de aplicaciones web mediante la
                    integración de diversas herramientas de desarrollo. Estas tecnologías nos permiten
                    construir soluciones innovadoras y funcionales que respondan a necesidades específicas,
                    fomentando el aprendizaje práctico y el trabajo en equipo entre los estudiantes.

                </p>
                <p class="texto">
                    Además, este blog representa un espacio de exploración y experimentación en el ámbito
                    del desarrollo web, donde podemos aplicar nuestros conocimientos, compartir experiencias
                    y fortalecer nuestras habilidades para futuros proyectos profesionales.
                </p>
            </div>
            <div class="foto">
                <img class="img" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
            </div>
        </div>

        <div class="title">
            <h1>COLABORADORES</h1>
        </div>


        <div class="contacts">
            <div class="foto2">
                <img class="img2" src="../assets/img/team.jpg" alt="imagen del equipo">
            </div>
            <div class="info-contacts">
                <p class="texto-contacts">
                    Este blog fue realizado por parte de estuadiantes de la universidad de Colima como
                    parte de un proyecto escolar en donde el objetivo es contribuir a la sociedad
                    por medios digitales y Diseñar e implementar aplicaciones web mediante la integración de
                    herramientas de desarrollo tales como IDEs, navegadores, frameworks, librerías, lenguajes
                    de programación, servidores y gestores de bases de datos.
                </p>

                <ul class="team-list">
                    <li><span>Nancy Laureano</span> - nlaureano@ucol.mx</li>
                    <li><span>Eduardo Escobedo</span> - ehernandez@ucol.mx</li>
                    <li><span>Pamela Rodriguez</span> - drodriguez@ucol.mx</li>
                    <li><span>Juan Pablo Angelina</span> - jangelina@ucol.mx</li>
                    <li><span>Juan Pablo Alcala</span> - jalcala@ucol.mx</li>
                    <li><span>Jesus Antonio Quintero</span> - jquintero@ucol.mx</li>
                </ul>
            </div>

        </div>



    </div>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->

</body>