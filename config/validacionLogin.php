<?php
// Incluir la conexión a la base de datos
include('database.php'); // Asegúrate de que el archivo 'database.php' esté correctamente incluido

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = md5($_POST["password"]);
} else {
    echo "Correo o contraseña no enviados correctamente.";
    exit;
}

session_start();

// Realiza la consulta para obtener al usuario con el correo
$sql = "SELECT * FROM users WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([ ':email' => $email ]);

// Verificación si existe el usuario
if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Comparar la contraseña ingresada con la almacenada en la base de datos
    if ($password == $user['password']) { // Compara texto plano
        // Si la contraseña es válida, iniciamos la sesión
        $_SESSION["email"] = $email;
        $_SESSION['username'] = $user['username'];
        $_SESSION['id_type_user'] = $user['id_type_user']; // Aquí se almacena el tipo de usuario

        // Redirección al panel de administración si tiene acceso
        header('Location: ../admin/posts/posts-consulta.php?id=' . $user['iduser']);
        exit();
    } else {
        // Si la contraseña no es válida
        $_SESSION['error_message'] = 'Correo electrónico o contraseña incorrectos.';
        header('Location: ../views/signin.php');
        exit();
    }
} else {
    // Si no existe el usuario
    $_SESSION['error_message'] = 'Correo electrónico o contraseña incorrectos.';
    header('Location: ../views/signin.php');
    exit();
}
?>
