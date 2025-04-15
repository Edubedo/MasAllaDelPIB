<?php
session_start();
require_once '../config/database.php';
if (!isset($_SESSION['cambio_pass_autorizado']) || !$_SESSION['cambio_pass_autorizado']) {
    header("Location: ../../views/signin.php");
    exit();
}
if (!isset($_SESSION['email_recuperacion'])) {
    header("Location: olvidaste_tu_contrasena.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        $_SESSION['mensaje_error'] = "Las contraseñas no coinciden.";
        header("Location: nueva_contraseña.php");
        exit();
    }

    // Usando md5 para el hash (aunque no recomendado para contraseñas)
    $passwordHash = md5($password);

    // Conectar a base de datos
    $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_PORT']);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $passwordHash, $_SESSION['email_recuperacion']);

    if ($stmt->execute()) {
        unset($_SESSION['email_recuperacion']);
        $_SESSION['mensaje_exito'] = "¡Contraseña actualizada correctamente!";
        header("Location: signin.php");
        exit();
    } else {
        $_SESSION['mensaje_error'] = "Error al actualizar la contraseña.";
        header("Location: nueva_contraseña.php");
        exit();
    }

    $stmt->close();
    $conn->close();

}
?>


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
    <link rel="stylesheet" href="css/recordar_contraseña.css">
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
           
            <!-- Formularios de login y registro -->
            <div class="contenedor__Login-register">
                <form action="nueva_contraseña.php" method="POST" class="formulario__login">
                    <h3>Nueva contraseña</h3>
                    <p class="fa fa-lock" style="font-size: 20px; margin-right: 10px; color:rgb(55, 72, 155);"></p>
                    <input type="password" name="password" placeholder="Contraseña" required>

                    <h3>Confirma tu contraseña</h3>
                    <p class="fa fa-lock" style="font-size: 20px; margin-right: 10px; color:rgb(55, 72, 155);"></p>
                    <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>

                    <?php if (isset($_SESSION['mensaje_error'])): ?>
                        <p style="color: red;"><?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?></p>
                    <?php endif; ?>
                    
                    <button type="submit">Enviar</button>
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