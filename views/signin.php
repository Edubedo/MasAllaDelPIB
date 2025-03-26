<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB - Iniciar Sesión</title>
    <script src="../js/main.js"></script>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/bg-animation.css">

</head>

<body>
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- <?php include 'layout/header.php'; ?> -->
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->


    <!-- Session start -->
    <!-- <?php include './config/session_start.php'; ?> -->
    <!-- Session start -->
    <div class="area">
            <ul class="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
    <main>
        
        <div class="contenedor__todo">
            <div class="caja__tracera">
                <div class="caja__tracera-login">
                    <h3>¿Ya tienes una cuenta aquí?</h3>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__Iniciar-Sesión">Iniciar Sesión</button>
                </div>
                <div class="caja__tracera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn__registrarse">Regístrate</button>
                </div>
            </div>
            
            <!-- Formularios de login y registro -->
            <div class="contenedor__Login-register">
                <form action="../config/validacionLogin.php" method="POST" class="formulario__login">
                    <h2>Iniciar Sesión</h2>
                    <input type="text" name="email" placeholder="Correo Electrónico" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit">Entrar</button>
                </form>


                <!-- Formulario de registro -->
                <form action="" class="formulario__register">
                    <h2>Regístrate</h2>
                    <input type="text" name="fullname" placeholder="Nombre Completo" required>
                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    <input type="text" name="username" placeholder="Usuario" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit">Registrarse</button>
                </form>
            </div>
        </div>
    </main>
    <script src="../js/login.js"></script>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->
</body>

</html>