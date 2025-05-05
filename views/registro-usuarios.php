<?php
session_start();
// Desactivar la visualización de errores en producción
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Incluir la conexión a la base de datos
include('../config/database.php'); // Asegúrate de que esta ruta sea correcta

//funcion para comprimir la imagen
function comprimir_imagen($origen, $destino, $max_width, $max_height, $quality = 75)
{
    list($width, $height, $type) = getimagesize($origen);

    // Calculamos las nuevas dimensiones respetando la relación de aspecto
    $new_width = $width;
    $new_height = $height;

    if ($width > $max_width || $height > $max_height) {
        $ratio = $width / $height;
        if ($width > $height) {
            $new_width = $max_width;
            $new_height = $max_width / $ratio;
        } else {
            $new_height = $max_height;
            $new_width = $max_height * $ratio;
        }
    }

    // Creamos la imagen de destino a partir de la original
    switch ($type) {
        case IMAGETYPE_JPEG:
            $src = imagecreatefromjpeg($origen);
            break;
        case IMAGETYPE_PNG:
            $src = imagecreatefrompng($origen);
            break;
        case IMAGETYPE_GIF:
            $src = imagecreatefromgif($origen);
            break;
        default:
            return false;
    }

    // Creamos la nueva imagen con las nuevas dimensiones
    $new_width = (int)$new_width; // Convertir a entero
    $new_height = (int)$new_height; // Convertir a entero
    $dst = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Guardamos la imagen comprimida
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($dst, $destino, $quality);
            break;
        case IMAGETYPE_PNG:
            imagepng($dst, $destino, round($quality / 10)); // La calidad es de 0 a 9
            break;
        case IMAGETYPE_GIF:
            imagegif($dst, $destino);
            break;
    }

    // Liberar memoria
    imagedestroy($src);
    imagedestroy($dst);

    return true;
}


// Inicializar la variable para la foto de perfil
$foto_perfil = null;

// Verificar si se subió una imagen
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/'; // Directorio donde se guardarán las imágenes
    $fileName = basename($_FILES['foto_perfil']['name']);
    $ext = pathinfo($fileName, PATHINFO_EXTENSION); // Obtener la extensión del archivo
    $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_comprimida.' . $ext;
    $targetFilePath = $uploadDir . $fileName;

    // Definir las dimensiones máximas para la imagen (por ejemplo, 300x300 píxeles)
    $max_width = 300;
    $max_height = 300;

    // Comprimir la imagen antes de guardarla
    if (comprimir_imagen($_FILES['foto_perfil']['tmp_name'], $targetFilePath, $max_width, $max_height)) {
        $foto_perfil = $fileName; // Guardar solo el nombre del archivo
    } else {
        $_SESSION['error_message'] = "Error al comprimir la imagen.";
        header("Location: signin.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "No se subió ninguna imagen.";
    header("Location: signin.php");
    exit();
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
