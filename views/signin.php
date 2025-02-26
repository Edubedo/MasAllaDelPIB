<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB</title>
    <script src="../js/main.js"></script>

    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href='./css/footer.css'>
    <link rel="stylesheet" href='./css/posts.css'>
    <link rel="stylesheet" href='css/index.css'>
    <link rel="stylesheet" href='./css/login.css'>

</head>

<body>
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- <?php include './layout/header.php'; ?> -->
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->

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
                <form action="" class="formulario__login">
                    <h2>Iniciar Sesión</h2>
                    <input type="text" placeholder="Correo Electrónico">
                    <input type="password" placeholder="Contraseña">
                    <button>Entrar</button>
                </form>

                <!-- Formulario de registro -->
                <form action="" class="formulario__register">
                    <h2>Regístrate</h2>
                    <input type="text" placeholder="Nombre Completo">
                    <input type="text" placeholder="Correo Electrónico">
                    <input type="text" placeholder="Usuario">
                    <input type="password" placeholder="Contraseña">
                    <button>Registrarse</button>
                </form>
            </div>
        </div>
    </main>
    <script src="js/login.js"></script>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->
</body>