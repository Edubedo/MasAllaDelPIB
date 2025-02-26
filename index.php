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
    <link rel="stylesheet" href="app/visitante/views/layouts/css/navbar.css">
    <link rel="stylesheet" href="app/visitante/public/css/footer.css">
    <link rel="stylesheet" href="app/visitante/views/layouts/css/index.css">
</head>

<body>
    <!-- Incluyendo la barra de navegación -->
    <?php include 'views/layout/header.php'; ?>

    <!-- Primer contenedor con contenido -->
    <div class="item1">
        <div class="wrapper">
            <h1 class="titulo">Trabajo Colima invita a participar en la expo virtual de Empleo</h1>
            <div class="contenido">
                <img src="app/visitante/views/layouts/img/principal.jpg" alt="PIB">
                <p>¡Trabajo Colima te invita a participar en la Expo Virtual de Empleo! Este evento es una excelente oportunidad para interactuar con empresas de diversos sectores en busca de talento.
                    Durante la expo, podrás explorar vacantes, postularte directamente desde la plataforma y asistir a talleres y charlas sobre desarrollo profesional.
                    Además, tendrás acceso a consejos sobre cómo mejorar tu perfil profesional y enfrentar entrevistas laborales. Lo mejor es que la Expo es completamente virtual, permitiéndote participar desde cualquier lugar con acceso a internet.
                    ¡No pierdas la oportunidad de impulsar tu carrera y acercarte a tu futuro profesional!</p>
            </div>
        </div>
    </div>

    <div class="grid">
        <!-- Elementos de la grilla con noticias -->
        <div class="item2">
            <h2 class="titulo">Nueva Estrategia Económica en Colima</h2>
            <img src="app/visitante/views/layouts/img/estrategia-economica.jpg" alt="Estrategia Económica">
            <p>El gobierno de Colima presenta una estrategia para impulsar la economía local, enfocándose en el emprendimiento y las inversiones extranjeras.</p>
        </div>

        <div class="item3">
            <h2 class="titulo">Apertura de Nuevas Tiendas en Colima</h2>
            <img src="app/visitante/views/layouts/img/nuevas-tiendas.avif" alt="Nuevas Tiendas">
            <p>Colima expande su oferta comercial con nuevas tiendas y centros comerciales, generando más oportunidades laborales.</p>
        </div>

        <div class="item4">
            <h2 class="titulo">Iniciativa de Sostenibilidad en la Ciudad</h2>
            <img src="app/visitante/views/layouts/img/sostenibilidad.jpg" alt="Sostenibilidad">
            <p>Colima promueve la sostenibilidad mediante energías renovables y la creación de espacios verdes urbanos.</p>
        </div>

        <div class="item5">
            <h2 class="titulo">Nuevo Plan de Infraestructura para Colima</h2>
            <img src="app/visitante/views/layouts/img/infraestructura.jpeg" alt="Infraestructura">
            <p>El nuevo plan de infraestructura mejorará las carreteras y creará nuevas autopistas para mejorar la conectividad en Colima.</p>
        </div>

        <div class="item6">
            <h2 class="titulo">Educación y Formación para el Futuro</h2>
            <img src="app/visitante/views/layouts/img/educacion.webp" alt="Educación">
            <p>Colima promueve programas de educación y formación técnica para mejorar las oportunidades laborales en la región.</p>
        </div>
    </div>

    <!-- Incluyendo el pie de página -->
    <?php include 'app/visitante/views/layouts/footer.php'; ?>
</body>

</html>