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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href='./css/footer.css'>
    <link rel="stylesheet" href='./css/about.css'>

</head>

<body>
    <div class="fondo-overlay"></div>

    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <?php include './layout/header.php'; ?>

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

                </p>
                <p class="texto">
                    Este blog fue creado por estudiantes de la Universidad de Colima como parte de un proyecto escolar. Nuestro objetivo es contribuir a la sociedad a través de medios digitales, generando contenido valioso que apoye a la comunidad. El proyecto se centra en el diseño e implementación de aplicaciones web mediante la integración de diversas herramientas de desarrollo, permitiéndonos construir soluciones innovadoras y funcionales. Este espacio representa una oportunidad para explorar y experimentar en el desarrollo web, aplicando nuestros conocimientos y fortaleciendo nuestras habilidades profesionales.
                </p>
            </div>
            <div class="foto">
                <img class="img" src="../assets/img/imagen_nosotros.png" alt="imagen de nosotros">
            </div>
        </div>

        <div class="title">
            <h1>COLABORADORES</h1>
        </div>

        <div class="team-section">
            <div class="member-card">
                <img src="/views/uploads/esco.jpeg" alt="Eduardo Escobedo">
                <div class="member-name">Eduardo Escobedo</div>
                <div class="member-phrase">"La tecnología es la herramienta del cambio."</div>
                <div class="member-email">ehernandez71@ucol.mx</div>
            </div>

            <div class="member-card">
                <img src="/views/uploads/nancylaureano.jpeg" alt="Nancy Laureano">
                <div class="member-name">Nancy Laureano</div>
                <div class="member-phrase">"Siempre en busca de soluciones creativas."</div>
                <div class="member-email">nlaureano@ucol.mx</div>
            </div>

            <div class="member-card">
                <img src="/views/uploads/pamela.jpeg" alt="Pamela Rodriguez">
                <div class="member-name">Pamela Rodriguez</div>
                <div class="member-phrase">"Cada línea de código es un paso hacia el futuro."</div>
                <div class="member-email">drodriguez64@ucol.mx</div>
            </div>

            <div class="member-card">
                <img src="/views/uploads/juan.jpeg" alt="Juan Pablo Angelina">
                <div class="member-name">Juan Pablo Angelina</div>
                <div class="member-phrase">"Construyendo ideas que impactan."</div>
                <div class="member-email">jangelina@ucol.mx</div>
            </div>

            <div class="member-card">
                <img src="/views/uploads/pablo.jpeg" alt="Juan Pablo Alcala">
                <div class="member-name">Juan Pablo Alcala</div>
                <div class="member-phrase">"La innovación nace de la perseverancia."</div>
                <div class="member-email">jalcala5@ucol.mx</div>
            </div>

            <div class="member-card">
                <img src="/views/uploads/jesusquintero.jpeg" alt="Jesus Antonio Quintero">
                <div class="member-name">Jesus Antonio Quintero</div>
                <div class="member-phrase">"El desarrollo web es arte con lógica."</div>
                <div class="member-email">jquintero12@ucol.mx</div>
            </div>
        </div>


    </div>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->

</body>

</html>