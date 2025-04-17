<?php
include('database.php');
session_start();

$max_intentos = 5;
$tiempo_bloqueo = 60; // segundos

if (!isset($_SESSION['intentos'])) $_SESSION['intentos'] = 0;
if (!isset($_SESSION['bloqueado_hasta'])) $_SESSION['bloqueado_hasta'] = 0;

if (time() < $_SESSION['bloqueado_hasta']) {
    $espera = $_SESSION['bloqueado_hasta'] - time();
    $_SESSION['bloqueo_restante'] = $espera;
    $_SESSION['error_message'] = "Demasiados intentos. Cuenta bloqueada temporalmente.";
    header('Location: ../views/signin.php');
    exit;
}

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = md5($_POST["password"]);
} else {
    $_SESSION['error_message'] = "Correo o contraseña no enviados correctamente.";
    header('Location: ../views/signin.php');
    exit;
}

$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([ ':email' => $email ]);

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($password === $user['password']) {
        $_SESSION['intentos'] = 0;
        $_SESSION['bloqueado_hasta'] = 0;
        $_SESSION["email"] = $email;
        $_SESSION['username'] = $user['username'];
        $_SESSION['id_type_user'] = $user['id_type_user'];
        header('Location: ../admin/posts/posts-consulta.php?id=' . $user['iduser']);
        exit;
    } else {
        $_SESSION['intentos']++;
        if ($_SESSION['intentos'] >= $max_intentos) {
            $_SESSION['bloqueado_hasta'] = time() + $tiempo_bloqueo;
            $_SESSION['bloqueo_restante'] = $tiempo_bloqueo;
            $_SESSION['error_message'] = "Demasiados intentos fallidos. Cuenta bloqueada temporalmente.";
        } else {
            $_SESSION['error_message'] = "Correo o contraseña incorrectos.";
        }
        header('Location: ../views/signin.php');
        exit;
    }
} else {
    $_SESSION['intentos']++;
    if ($_SESSION['intentos'] >= $max_intentos) {
        $_SESSION['bloqueado_hasta'] = time() + $tiempo_bloqueo;
        $_SESSION['bloqueo_restante'] = $tiempo_bloqueo;
        $_SESSION['error_message'] = "Demasiados intentos fallidos. Cuenta bloqueada temporalmente.";
    } else {
        $_SESSION['error_message'] = "Correo o contraseña incorrectos.";
    }
    header('Location: ../views/signin.php');
    exit;
}
