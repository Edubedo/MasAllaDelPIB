<?php
session_start();
// Mostrar errores en pantalla
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir la conexión a la base de datos
include('../config/database.php'); // Asegúrate de que esta ruta sea correcta

// Inicializar la variable para la foto de perfil
$foto_perfil = null;

// Verificar si se subió una imagen
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/'; // Directorio donde se guardarán las imágenes
    $fileName = basename($_FILES['foto_perfil']['name']);
    $targetFilePath = $uploadDir . $fileName;

    // Mover el archivo subido al directorio de destino
    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $targetFilePath)) {
        $foto_perfil = $fileName; // Guardar solo el nombre del archivo
    } else {
        $_SESSION['error_message'] = "Error al subir la imagen.";
        header("Location: signin.php");
        exit();
    }
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
        $email = trim($_POST["email"]);
        $username = trim($_POST["username"]);
        $password = $_POST["password"];
        $id_type_user = 2; // Todos los usuarios nuevos serán tipo 2

        try {
            // Verificar si el usuario o email ya existen
            $checkStmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
            $checkStmt->bindParam(":email", $email);
            $checkStmt->bindParam(":username", $username);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $_SESSION['error_message'] = "El correo o usuario ya están registrados.";
                header("Location: signin.php");
                exit();
            }

            // Encriptar la contraseña (aunque se recomienda usar password_hash en vez de md5)
            $hashedPassword = md5($password);

            // Insertar datos en la tabla users incluyendo la foto
            $stmt = $pdo->prepare("INSERT INTO users (email, username, password, id_type_user, foto_perfil) 
                                   VALUES (:email, :username, :password, :id_type_user, :foto_perfil)");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $hashedPassword);
            $stmt->bindParam(":id_type_user", $id_type_user);
            $stmt->bindParam(":foto_perfil", $foto_perfil);
            $stmt->execute();

            $_SESSION['success_message'] = "Registro exitoso. Ahora puedes iniciar sesión.";
            header("Location: signin.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error al registrar usuario: " . $e->getMessage();
            header("Location: signin.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Todos los campos son obligatorios.";
        header("Location: signin.php");
        exit();
    }
}
?>
