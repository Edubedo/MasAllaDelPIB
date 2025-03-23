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

// verificacion si hay usuarios
if ($stmt->rowCount() > 0) {

    // obtenemos el resultado de usuario desde un array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $_SESSION["email"] = $email;
    $_SESSION['username'] = $user['username']; 
    
    // Redireccion al panel si tiene acceso
    header('Location: ../admin/posts/posts-consulta.php');
    exit();
} else {
    $_SESSION['error_message'] = 'Correo electrónico o contraseña incorrectos.';
    header('Location: ../views/signin.php');
    exit();
}
?>
