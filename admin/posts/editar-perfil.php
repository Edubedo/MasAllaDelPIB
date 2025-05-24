<?php
session_start();
include('../../config/database.php');

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
} else {
    header("Location: ../../views/signin.php");
    exit();
}

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($result);
$iduser = $row['iduser'];

$id = $_GET['id'];

$sql_usuario = $conexion->query("SELECT * FROM users WHERE iduser = $id");
$idtypeuser = $_SESSION['id_type_user'];

$error_password = ""; // 👈 Declarada fuera del POST

// Función para comprimir imágen
function comprimir_imagen($rutaOriginal, $rutaDestino, $maxAncho = 900, $calidad = 85)
{
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

    // Procesar dependiendo del tipo de imagen
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updates = [];
    $changes_made = false;

    $sql_current = "SELECT username, email, foto_perfil FROM users WHERE iduser = $id";
    $result_current = $conexion->query($sql_current);
    $current_data = $result_current->fetch_assoc();

    $old_username = $current_data['username'];

    // Username
    if (!empty($_POST['username']) && $_POST['username'] !== $current_data['username']) {
        $new_username = $conexion->real_escape_string($_POST['username']);
        $updates[] = "username = '$new_username'";
        $changes_made = true;
    }

    // Email
    if (!empty($_POST['email']) && $_POST['email'] !== $current_data['email']) {
        $new_email = $conexion->real_escape_string($_POST['email']);
        $updates[] = "email = '$new_email'";
        $changes_made = true;
    }

    // Foto
    if (!empty($_FILES['foto']['name'])) {
        $foto_nombre = time() . '_' . pathinfo($_FILES['foto']['name'], PATHINFO_FILENAME) . '.webp';
        $ruta_destino = "../../views/uploads/" . $foto_nombre;
        $foto_tmp = $_FILES['foto']['tmp_name'];

        if (comprimir_imagen($foto_tmp, $ruta_destino)) {
            $updates[] = "foto_perfil = '$foto_nombre'";
            $changes_made = true;
        } else {
            $_SESSION['error_message'] = "❌ Error al procesar la imagen.";
            header("Location: editar-perfil.php?id=$id");
            exit();
        }
    }

    // Validar campos vacíos
    if (empty($_POST['username']) || empty($_POST['email'])) {
        $_SESSION['error_message'] = "❌ No puede dejar campos vacíos.";
        header("Location: editar-perfil.php?id=$id");
        exit();
    }

    // Contraseña
    if (!empty($_POST['password'])) {
        $password_actual = md5($_POST['password']);
        $sql_verifica = "SELECT * FROM users WHERE iduser = $id AND password = '$password_actual'";
        $verifica_resultado = $conexion->query($sql_verifica);

        if ($verifica_resultado->num_rows > 0) {
            $_SESSION['cambio_pass_autorizado'] = true;
            $_SESSION['iduser_cambio'] = $id;
            header("Location: ../../views/nueva_contraseña.php");
            exit();
        } else {
            $_SESSION['error_message'] = "❌ La contraseña actual es incorrecta.";
            header("Location: editar-perfil.php?id=$id");
            exit();
        }
    }

    // Aplicar actualizaciones si las hay
    if (!empty($updates)) {
        $sql_update = "UPDATE users SET " . implode(", ", $updates) . " WHERE iduser = $id";

        if (!$conexion->query($sql_update)) {
            $_SESSION['error_message'] = "❌ Error al actualizar usuario: " . $conexion->error;
            header("Location: editar-perfil.php?id=$id");
            exit();
        }

        // Actualizar nombre en posts si cambió el username
        if (isset($new_username) && $old_username !== $new_username) {
            $sql_update_posts = "UPDATE posts SET user_creation = '$new_username' WHERE user_creation = '$old_username'";
            $conexion->query($sql_update_posts);
            if ($id == $iduser) {
                $_SESSION['username'] = $new_username;
            }
        }

        if (isset($new_email) && $id == $iduser) {
            $_SESSION['email'] = $new_email;
        }

        $_SESSION['success_message'] = "✅ Perfil actualizado con éxito";
        header("Location: editar-perfil.php?id=$id");
        exit();
    } else {
        $_SESSION['error_message'] = "⚠️ No se realizó ningún cambio.";
        header("Location: editar-perfil.php?id=$id");
        exit();
    }
}


?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar perfil</title>

    <link rel="icon" href="../../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/crear.css">
    <script src="/js/language.js"></script>
    <script src="/js/translations.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <?php
    /*include(__DIR__ . '/../../views/layout/header.php');*/
    ?>
    <div class="container">
        <div class="encabezado">
            <h1>Modificación de usuario</h1>
        </div>

        <?php
        // Display error message if set
        if (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?= $_SESSION['error_message'];
                unset($_SESSION['error_message']); ?>
            </div>
        <?php endif;
        ?>

        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?= $_SESSION['success_message'];
                unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar error de contraseña -->
        <?php if (!empty($error_password)) : ?>
            <div class="error-message">
                <?= $error_password ?>
            </div>
        <?php endif; ?>


        <?php while ($datos = $sql_usuario->fetch_object()) { ?>
            <form action="" name="editar_perfil" method="post" enctype="multipart/form-data">
                <div class="moduser-div">

                    <div class="username-div">
                        <label for="username"><i class="fas fa-user"></i> Nombre de Usuario:</label>
                        <input type="text" id="username" name="username" value="<?= $datos->username ?>">
                    </div>

                    <div class="email-div">
                        <label for="email"><i class="fas fa-envelope"></i> Correo Electrónico:</label>
                        <input type="email" id="email" name="email" value="<?= $datos->email ?>">
                    </div>


                    <!-- Mostrar foto actual -->
                    <?php if (!empty($datos->foto_perfil)) : ?>
                        <div class="foto-actual">
                            <label><i class="fas fa-image"></i> Foto actual:</label><br>
                            <img src="../../views/uploads/<?= $datos->foto_perfil ?>" alt="Foto actual" style="width:100px; height:auto; border-radius:10px; margin-left:1rem;">
                        </div>
                    <?php endif; ?>

                    <div class="foto-div">
                        <label for="foto"><i class="fas fa-upload"></i> Nueva Foto de perfil:</label>
                        <input type="file" id="foto" name="foto">
                    </div>

                    <div class="cambiarContrasena-div">
                        <label><i class="fas fa-lock"></i> Contraseña:</label>
                        <button type="button" class="btn-cambiar-contrasena" onclick="window.location.href='../../views/olvidaste_tu_contrasena.php'">
                            <i class="fas fa-key"></i> Cambiar contraseña
                        </button>
                    </div>

                    <div class="botones-div">
                        <?php
                        if ($idtypeuser == 1) {
                            echo '<a href="panel-usuarios.php" class="btn-editar-perfil">Regresar</a>';
                        } elseif ($idtypeuser == 2) {
                            echo '<a href="posts-consulta.php?id=' . $iduser . '" class="btn-editar-perfil"><i class="fas fa-arrow-left"></i> Regresar</a>';
                        }
                        ?>
                        <button type="submit" name="editar-perfil" style="width: 75%;"><i class="fas fa-pen" style="margin-right: 0.5rem;"></i>Modificar perfil</button>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
</body>

</html>