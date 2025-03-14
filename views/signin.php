<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB - Iniciar Sesión</title>
    <script src="../js/main.js"></script>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/footer.css">
</head>

<body>
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- <?php include './layout/header.php'; ?> -->
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->

    <main>
        <div class="loginn">
            <div class="contenedor__todo">
                <!-- Formularios de login y registro -->
                <div class="contenedor__Login-register">
                    <!-- Formulario con action para redirigir al login -->
                    <form action="../admin/posts/posts-consulta.php" method="POST" class="formulario__login">
                        <h2><i class="fas fa-user-circle fa-3x"></i></h2>

                        <!-- Contenedor para los iconos y los inputs -->
                        <div class="input-container">
                            <i class="fas fa-envelope icon fa-2x"></i>
                            <input type="text" placeholder="Correo Electrónico" name="email">
                        </div>

                        <div class="input-container">
                            <i class="fas fa-lock icon fa-2x"></i>
                            <input type="password" placeholder="Contraseña" name="password">
                        </div>

                        <!-- Enlace para recordar contraseña -->
                        <p><a href="#">¿Olvidaste tu contraseña?</a></p>

                        <!-- Botón de enviar -->
                        <button type="submit">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
        
    </main>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->
</body>

</html>