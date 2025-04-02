<?php
session_start();
// Mostrar errores en pantalla
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/database.php';


// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
        $email = trim($_POST["email"]);
        $username = trim($_POST["username"]);
        $password = $_POST["password"];
        $id_type_user = 2; // Todos los usuarios nuevos serán tipo 2

        try {
            // Verificar si el usuario o email ya existen
            $checkStmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
            $checkStmt->bindParam(":email", $email);
            $checkStmt->bindParam(":username", $username);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $_SESSION['error_message'] = "El correo o usuario ya están registrados.";
                header("Location: signin.php");
                exit();
            }

            // Insertar datos en la tabla users
            $stmt = $conn->prepare("INSERT INTO users (email, username, password, id_type_user) 
                                    VALUES (:email, :username, :password, :id_type_user)");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":id_type_user", $id_type_user);
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
