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
                    <h2 class="fa fa-user" style="font-size: 40px; margin-bottom: 30px; text-align: center; display: block;"></h2>
                    <p class="fa fa-envelope" style="font-size: 20px; margin-right: 5px; color:rgb(55, 72, 155);"></p>
                    <input type="text" name="email" placeholder="Correo Electrónico" required>
                    <p class="fa fa-lock" style="font-size: 20px; margin-right: 10px; color:rgb(55, 72, 155);"></p>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <p style="font-size:14px;margin-top:2px;color: red;"><?= $_SESSION['error_message']; ?></p>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                    <a href="../views/olvidaste_tu_contraseña.php"><p style="color: blue; font-size: 14px;">¿Olvidaste tu contraseña?</p></a>
                    <button type="submit">Inicia  sesión</button>
                </form>

                <!-- Formulario de registro -->
                <form action="registro-usuarios.php" class="formulario__register" method="POST">
                    <h2 class="fa fa-user" style="font-size: 40px; margin-bottom: 10px; text-align: center; display: block;"></h2>
                    <input type="text" name="fullname" placeholder="Nombre Completo" required>
                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    <input type="text" name="username" placeholder="Usuario" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit">Registrate</button>
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