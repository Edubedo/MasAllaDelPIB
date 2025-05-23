<?php session_start();
require '../vendor/autoload.php'; // Ajusta la ruta si es necesario
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
        $codigo = strval(rand(100000, 999999)); // Código de 6 dígitos

        // Guardar el código en la base de datos
        $updateStmt = $conn->prepare("UPDATE users SET codigo_verificacion = ? WHERE email = ?");
        $updateStmt->bind_param("ss", $codigo, $email);
        $updateStmt->execute();
        $updateStmt->close();

        // Enviar código por correo
        $asunto = "Código de recuperación de contraseña";
        $mensaje = "Tu código de verificación es: $codigo";
        $cabeceras = "From: no-responder@masalladelpib.com";

        // Enviar código por correo usando SendGrid
        // Enviar código por correo usando SendGrid
        $emailSendgrid = new \SendGrid\Mail\Mail();
        $emailSendgrid->setFrom("masalladelpib1@gmail.com", "MasAllaDelPib");
        $emailSendgrid->setSubject("MasAlláDelPib - Código de recuperación de contraseña");
        $emailSendgrid->addTo($email);
        $emailSendgrid->addContent(
            "text/plain",
            "Tu código de verificación es: $codigo"
        );
        $emailSendgrid->addContent(
            "text/html",
            "<p><strong>Tu código de verificación es: $codigo</strong></p>
            <img src='https://www.masalladelpib.site/assets/img/logo.png' alt='Logo MasAllaDelPib' style='width:200px;'>"
        );



        //llama a la variable de .env 
        $apiKey = $_ENV['APIKEY_SENDGRID'] ?? null;

        // Verificar si la clave API se cargó correctamente

        if (!$apiKey) {
            die('Error: No se pudo cargar la clave API desde el .env');
        }

        $sendgrid = new \SendGrid($apiKey);

        try {
            $response = $sendgrid->send($emailSendgrid);
            // Puedes registrar el estado en el log en lugar de usar echo
            error_log("Correo enviado. Status: " . $response->statusCode());
        } catch (Exception $e) {
            $error = 'Error al enviar el correo: ' . $e->getMessage();
            error_log($error); // Registra el error en el log
        }

        $_SESSION['email_recuperacion'] = $email;
        header("Location: verificar_codigo.php");
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
            <div class="contenedor__Login-register">
                <form action="olvidaste_tu_contrasena.php" method="POST" class="formulario__login">
                    <h2>Ingresa tu correo</h2>
                    <p class="fa fa-envelope" style="font-size: 20px; margin-right: 5px; color:rgb(55, 72, 155);"></p>

                    <?php if (isset($error)): ?>
                        <p style="color: red;"><?= $error ?></p>
                    <?php endif; ?>

                    <input type="email" name="email" placeholder="Correo Electrónico" required>
                    <div class="botones-contenedor">
                        <button onclick="window.history.back()" class="btn-regresar">
                            <i class="fas fa-arrow-left"></i> Regresar
                        </button>
                        <button class="boton-enviar" type="submit">
                            Enviar <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>

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