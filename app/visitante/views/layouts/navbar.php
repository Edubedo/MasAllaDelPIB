<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../layouts/css/navbar.css">
    <title>Navbar></title>
</head>

<body>
    <nav>
        <input type="checkbox" id="toggle">
        <div class="logo">
            <img src="/app/visitante/views/layouts/img/logo.png" alt="imagen logo empresa" width="50" height=40">
            MAS ALLÁ DEL PIB
        </div>
        <ul class="list">
            <li><a href="/index.php">Inicio</a></li>
            <li><a href="/app/visitante/views/layouts/login.php">Inicio de Sesión</a></li>
            <li><a href="/app/visitante/views/layouts/spubli.php">Publicaciones</a></li>
            <li><a href="/app/visitante/views/layouts/snosotros.php">Nosotros</a></li>
            <li><a href="/app/visitante/views/layouts/publi_unica.php">borrar despues</a></li> <!--se añadió para visualizar la noticia unica. Borrar despues-->
        </ul>
        <label for="toggle" class="icon-bars">&#9776;
        </label>
    </nav>
</body>

</html>