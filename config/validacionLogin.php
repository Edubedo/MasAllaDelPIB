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

// Realiza la consulta para obtener al usuario con el correo y la contraseña
$sql = "SELECT * FROM users WHERE email = :email AND password = :password";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':email' => $email,
    ':password' => $password
]);

// Verificación si hay usuarios
if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION["email"] = $email;
    $_SESSION['username'] = $user['username'];
    $_SESSION['id_type_user'] = $user['id_type_user']; // Corrección aquí

    // Redirección al panel si tiene acceso
    header('Location: ../admin/posts/posts-consulta.php?id=' . $user['iduser']);
    exit();
} else {
    $_SESSION['error_message'] = 'Correo electrónico o contraseña incorrectos.';
    // No redirigimos, volvemos al formulario
    header('Location: ../views/signin.php');
    exit();
}
