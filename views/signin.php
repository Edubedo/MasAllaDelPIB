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
    <?php include 'layout/header.php'; ?>
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
                    <?php if (!empty($_SESSION['error_message'])): ?>
                        <?php $tiempo = $_SESSION['bloqueo_restante'] ?? 0; ?>
                        <p id="error-msg" style="font-size:14px;margin-top:2px;color: red;">
                            <?= $_SESSION['error_message'] ?>
                            <?php if ($tiempo > 0): ?>
                                <span id="timer"> (<?= $tiempo ?>s)</span>
                            <?php endif; ?>
                        </p>

                        <?php if ($tiempo > 0): ?>
                        <script>
                            let time = <?= $tiempo ?>;
                            const timerSpan = document.getElementById('timer');
                            const loginBtn = document.querySelector('.formulario__login button[type="submit"]');
                            if (loginBtn) loginBtn.disabled = true;

                            const interval = setInterval(() => {
                                time--;
                                if (time <= 0) {
                                    clearInterval(interval);
                                    location.reload();
                                } else {
                                    timerSpan.innerText = ` (${time}s)`;
                                }
                            }, 1000);
                        </script>
                        <?php endif; ?>

                        <?php
                            unset($_SESSION['error_message']);
                            unset($_SESSION['bloqueo_restante']);
                        ?>
                    <?php endif; ?>

                    <a href="olvidaste_tu_contrasena.php"><p style="color: blue; font-size: 14px;">¿Olvidaste tu contraseña?</p></a>
                    <button type="submit">Inicia  sesión</button>
                </form>

                <!-- Formulario de registro -->
                <form action="registro-usuarios.php" class="formulario__register" method="POST" enctype="multipart/form-data" onsubmit="return validarPassword()">
                    <h2 class="fa fa-user" style="font-size: 40px; margin-bottom: 10px; text-align: center; display: block;"></h2>
                    <input type="text" name="fullname" placeholder="Nombre Completo" required>
                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    <input type="text" name="username" placeholder="Usuario" required>
                    <input type="password" name="password" id="password" placeholder="Contraseña" required oninput="validarFortalezaPassword()">
                    <div id="password-strength">
                        <p style="font-weight: bold;" id="length">✓ Al menos 8 caracteres</p>
                        <p style="font-weight: bold;" id="uppercase">✓ Al menos una mayúscula</p>
                        <p style="font-weight: bold;" id="number">✓ Al menos un número</p>
                        <p style="font-weight: bold;" id="special">✓ Al menos un carácter especial</p>
                    </div>
                    <input type="password" id="confirm_password" placeholder="Confirmar Contraseña" required>
                    <!-- Reemplaza el input de archivo actual por este código -->
                    <div class="custom-file-input">
                        <label for="foto-perfil-input">
                            <i class="fas fa-camera" style="margin-right: 5px;"></i>
                            <span id="file-name">Selecciona foto de perfil</span>
                        </label>
                        <input type="file" id="foto-perfil-input" name="foto_perfil" accept="image/*" required>
                        <!-- Vista previa de la imagen seleccionada -->
                        <div id="preview-container" style="display: none; margin-top: 10px;">
                            <img id="preview-image" style="max-width: 100px; max-height: 100px; border-radius: 50%;">
                        </div>
                    </div>
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