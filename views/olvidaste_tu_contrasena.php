<?php session_start(); 
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $conn = new mysqli($host, $user, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION['email_recuperacion'] = $email;
        header("Location: nueva_contraseña.php");
        exit();
    } else {
        $error = "El correo ingresado no está registrado.";
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
    <title>MasAllaDelPIB - Recuperar contraseña</title>
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
            <div class="contenedor__Login-register">
                <form action="olvidaste_tu_contrasena.php" method="POST" class="formulario__login">
                    <h2>Ingresa tu correo</h2>
                    <p class="fa fa-envelope" style="font-size: 20px; margin-right: 5px; color:rgb(55, 72, 155);"></p>

                    <?php if (isset($error)): ?>
                        <p style="color: red;"><?= $error ?></p>
                    <?php endif; ?>

                    <input type="email" name="email" placeholder="Correo Electrónico" required>
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