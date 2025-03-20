<?php
include('database.php'); 

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
} else {
    echo "Correo o contraseña no enviados correctamente.";
    exit;
}

session_start();
$_SESSION["email"] = $email;

$sql = "SELECT * FROM users WHERE email = :email AND password = :password";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':email' => $email,
    ':password' => $password
]);

if ($stmt->rowCount() > 0) {
    header('Location: ../admin/posts/posts-consulta.php');
    exit();
} else {
    session_start();
    $_SESSION['error_message'] = 'La contraseña o el correo electrónico son incorrectos.';
    header('Location: ../views/signin.php');
    exit();
}
?>
