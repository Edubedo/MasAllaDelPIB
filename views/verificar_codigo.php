<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['email_recuperacion'])) {
    header("Location: olvidaste_tu_constrasena.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigoIngresado = $_POST['codigo'];
    $email = $_SESSION['email_recuperacion'];

    $conn = new mysqli($host, $user, $password, $dbname, $port);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND codigo_verificacion = ?");
    $stmt->bind_param("ss", $email, $codigoIngresado);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Código correcto, limpiamos el código
        $limpiarStmt = $conn->prepare("UPDATE users SET codigo_verificacion = NULL WHERE email = ?");
        $limpiarStmt->bind_param("s", $email);
        $limpiarStmt->execute();
        $limpiarStmt->close();

        header("Location: nueva_contraseña.php");
        exit();
    } else {
        $error = "Código incorrecto.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/bg-animation.css">

    <title>Verificar Código</title>
</head>
<body>

    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <!-- <?php include 'layout/header.php'; ?> -->
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->

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

    <<div class="contenedor__todo">
            <div class="contenedor__Login-register">
                <form action="verificar_codigo.php" method="POST" class="formulario__login">
                    <h2>Ingresa el código de verificación enviado a tu correo</h2>
                    <p class="fa fa-key" style="font-size: 20px; margin-right: 5px; color:rgb(55, 72, 155);"></p>

                    <?php if (isset($error)): ?>
                        <p style="color: red;"><?= $error ?></p>
                    <?php endif; ?>

                    <input type="text" name="codigo" placeholder="Código de verificación" required>
                    <button type="submit">Verificar</button>
                </form>
            </div>
        </div>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->
</body>
</html>
