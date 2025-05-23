<?php
session_start();
require_once '../config/database.php'; // Asegúrate que esta ruta es correcta

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

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/', $password)) {
        $_SESSION['mensaje_error'] = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial";
        header("Location: nueva_contraseña.php");
        exit();
    }

    // Usar password_hash (recomendado) - Elimina la línea de md5()
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    
    global $conexion; // Accede a la conexión ya establecida
    
    $stmt = $conexion->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $passwordHash, $_SESSION['email_recuperacion']);

    if ($stmt->execute()) {
        unset($_SESSION['email_recuperacion']);
        $_SESSION['mensaje_exito'] = "¡Contraseña actualizada correctamente!";
        header("Location: signin.php");
        exit();
    } else {
        $_SESSION['mensaje_error'] = "Error al actualizar la contraseña: " . $conexion->error;
        header("Location: nueva_contraseña.php");
        exit();
    }

    $stmt->close();

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
           
            <!-- Formularios de login y registro -->
            <div class="contenedor__Login-register">
                <form action="nueva_contraseña.php" method="POST" class="formulario__login" onsubmit="return validarPassword()">
                    <h2>Nueva contraseña</h2>
                    <div class="input-con-icono">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="Contraseña" required oninput="validarFortalezaPassword()">
                        <i id="toggle-passwordNueva" class="fa fa-eye"></i>
                    </div>
                    <div id="password-strength">
                        <p style="font-weight: bold;" id="length">✓ Al menos 8 caracteres</p>
                        <p style="font-weight: bold;" id="uppercase">✓ Al menos una mayúscula</p>
                        <p style="font-weight: bold;" id="number">✓ Al menos un número</p>
                        <p style="font-weight: bold;" id="special">✓ Al menos un carácter especial</p>
                    </div>

                    <h2>Confirma tu contraseña</h2>
                    <div class="input-con-icono">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmar Contraseña" required>
                    </div>
                    <?php if (isset($_SESSION['mensaje_error'])): ?>
                        <p style="color: red;"><?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?></p>
                    <?php endif; ?>
                    
                    <button type="submit">Enviar <i class="fas fa-paper-plane"></i></button>
                </form>


            </div>
        </div>
    </main>
    <script src="../js/login.js"></script>
    <script src="../js/nueva_contraseña.js"></script>
    

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->
</body>

</html>