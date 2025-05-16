<?php
session_start();
// Desactivar la visualización de errores en producción
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Incluir la conexión a la base de datos
include('../config/database.php'); // Asegúrate de que esta ruta sea correcta


// Validar contraseña
$password = $_POST['password'];
$regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/';

if (!preg_match($regex, $password)) {
    $_SESSION['error_message'] = 'La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial';
    header('Location: signin.php'); 
    exit();
}

function comprimirImagen($rutaOriginal, $rutaDestino, $maxAncho = 900, $calidad = 85) {
    if (!extension_loaded('gd')) {
        error_log("La extensión GD no está habilitada.");
        return false;
    }

    $info = getimagesize($rutaOriginal);
    if (!$info) {
        error_log("No se pudo obtener información de la imagen: $rutaOriginal");
        return false;
    }

    $tipo = $info['mime'];
    $ancho = $info[0];
    $alto = $info[1];

    if ($ancho <= 0 || $alto <= 0) {
        error_log("Dimensiones inválidas para la imagen: $rutaOriginal");
        return false;
    }

    // Crear la imagen desde el archivo original
    switch ($tipo) {
        case 'image/jpeg':
        case 'image/jpg':
            $imagen = @imagecreatefromjpeg($rutaOriginal);
            break;
        case 'image/png':
            $imagen = @imagecreatefrompng($rutaOriginal);
            break;
        case 'image/webp':
            $imagen = @imagecreatefromwebp($rutaOriginal);
            break;
        default:
            error_log("Formato de imagen no soportado: $tipo");
            return false;
    }

    if (!$imagen) {
        error_log("No se pudo crear la imagen desde el archivo: $rutaOriginal");
        return false;
    }

    // Redimensionar si es necesario
    $nuevaImagen = $imagen;
    if ($ancho > $maxAncho) {
        $nuevoAncho = $maxAncho;
        $nuevoAlto = max(1, (int)(($maxAncho / $ancho) * $alto));
        $nuevaImagen = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

        // Mantener transparencia para PNG y WebP
        if ($tipo == 'image/png' || $tipo == 'image/webp') {
            imagealphablending($nuevaImagen, false);
            imagesavealpha($nuevaImagen, true);
        }

        imagecopyresampled($nuevaImagen, $imagen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
        imagedestroy($imagen); // Liberar la imagen original

    } 

    // Asegurarse de que el archivo de destino tenga la extensión .webp
    $rutaDestino = preg_replace('/\.[a-zA-Z]+$/', '.webp', $rutaDestino);

    // Guardar la imagen en formato WebP
    $resultado = imagewebp($nuevaImagen, $rutaDestino, $calidad); // calidad 0-100

    // Liberar recursos
    imagedestroy($nuevaImagen);

    if (!$resultado) {
        error_log("No se pudo guardar la imagen comprimida en: $rutaDestino");
        return false;
    }

    return true;
}


// Inicializar la variable para la foto de perfil
$foto_perfil = null;

// Verificar si se subió una imagen
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/'; // Directorio donde se guardarán las imágenes
    $fileName = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_FILENAME) . '_comprimida.webp';
    $targetFilePath = $uploadDir . $fileName;

    // Definir las dimensiones máximas para la imagen (por ejemplo, 300x300 píxeles)
    $max_width = 300;

    // Comprimir la imagen antes de guardarla
    if (comprimirImagen($_FILES['foto_perfil']['tmp_name'], $targetFilePath, $max_width, 85)) {
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
