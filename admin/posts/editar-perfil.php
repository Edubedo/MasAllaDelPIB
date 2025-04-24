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
function comprimir_imagen($origen, $destino, $max_width, $max_height, $quality = 75) {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updates = [];

    if (!empty($_POST['username'])) {
        $new_username = $_POST['username'];
        $updates[] = "username = '$new_username'";
    }

    if (!empty($_POST['email'])) {
        $new_email = $_POST['email'];
        $updates[] = "email = '$new_email'";
    }

    // 👉 Verificar contraseña
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
            $error_password = "❌ La contraseña actual es incorrecta.";
            
        }
    }

    // 👇 Subir imagen
    if (!empty($_FILES['foto']['name'])) {
        $foto_nombre = time() . '_' . basename($_FILES['foto']['name']);
        $ruta_guardado = '../../views/uploads/' . $foto_nombre;
        $extensiones_validas = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($foto_nombre, PATHINFO_EXTENSION));

        if (in_array($extension, $extensiones_validas)) {
            // Primero, obtener la foto actual para eliminarla
            $sql_foto_actual = "SELECT foto_perfil FROM users WHERE iduser = $id";
            $result_foto = $conexion->query($sql_foto_actual);
            $foto_actual = $result_foto->fetch_assoc()['foto_perfil'];
            
            // Eliminar la foto anterior si existe
            if (!empty($foto_actual)) {
                $ruta_foto_anterior = '../../views/uploads/' . $foto_actual;
                if (file_exists($ruta_foto_anterior)) {
                    unlink($ruta_foto_anterior); // Elimina el archivo
                }
            }
            // Comprimir la imagen antes de moverla
            $ruta_comprimida = '../../views/uploads/' . time() . '_comprimida.' . $extension;
            if (comprimir_imagen($_FILES['foto']['tmp_name'], $ruta_comprimida, 300, 300)) {
                $foto_nombre_comprimida = basename($ruta_comprimida); // Usamos solo el nombre de la imagen comprimida
                $updates[] = "foto_perfil = '$foto_nombre_comprimida'";
                if ($id == $iduser) {
                    $_SESSION['foto_perfil'] = $foto_nombre_comprimida;
                }
            } else {
                echo "❌ Error al subir la imagen.";
            }
        } else {
            echo "❌ Formato de imagen no permitido.";
        }
    }

    // 👇 Ejecutar actualizaciones solo si NO hubo error de contraseña
    if (empty($error_password) && count($updates) > 0) {
        $sql_old_username = "SELECT username FROM users WHERE iduser = $id";
        $result = $conexion->query($sql_old_username);
        $row = $result->fetch_assoc();
        $old_username = $row['username'];

        $sql_update = "UPDATE users SET " . implode(", ", $updates) . " WHERE iduser = $id";

        if (!$conexion->query($sql_update)) {
            die("❌ Error al actualizar usuario: " . $conexion->error);
        }

        if (!empty($_POST['username'])) {
            $sql_update_posts = "UPDATE posts SET user_creation = '$new_username' WHERE user_creation = '$old_username'";
            if (!$conexion->query($sql_update_posts)) {
                die("❌ Error en la actualización de posts: " . mysqli_error($conexion));
            }

            if ($id == $iduser) {
                $_SESSION['username'] = $new_username;
            }
        }

        $_SESSION['success_message'] = "✅ Perfil actualizado con éxito";
        header("Location: editar-perfil.php?id=" . $id);
        exit();
    } elseif (empty($error_password)) {
        echo "⚠️ No se realizó ningún cambio.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar perfil</title>
    <link rel="stylesheet" href="css/crear.css">
</head>
<body>
    <div class="container">
        <div class="encabezado">
            <h1>Modificación de usuario</h1>
        </div>

        <!-- Mostrar mensaje de éxito -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Mostrar error de contraseña -->
        <?php if (!empty($error_password)) : ?>
            <div class="error-message" style="color: red; margin-bottom: 10px;">
                <?= $error_password ?>
            </div>
        <?php endif; ?>


        <?php while ($datos = $sql_usuario->fetch_object()) { ?>
            <form action="" name="editar_perfil" method="post" enctype="multipart/form-data">
                <div class="moduser-div">

                    <div class="username-div">
                        <label for="username">Nombre de Usuario:</label>
                        <input type="text" id="username" name="username" value="<?= $datos->username ?>">
                    </div>

                    <div class="email-div">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" value="<?= $datos->email ?>">
                    </div>

                    <div class="password-div">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="Contraseña Actual">
                    </div>

                    <!-- Mostrar foto actual -->
                    <?php if (!empty($datos->foto_perfil)) : ?>
                        <div class="foto-actual">
                            <label>Foto actual:</label><br>
                            <img src="../../views/uploads/<?= $datos->foto_perfil ?>" alt="Foto actual" style="width:100px; height:auto; border-radius:10px;">
                        </div>
                    <?php endif; ?>

                    <div class="foto-div">
                        <label for="foto">Foto de perfil:</label>
                        <input type="file" id="foto" name="foto">
                    </div>

                    <div class="botones-div">
                        <?php
                        if ($idtypeuser == 1) {
                            echo '<a href="panel-usuarios.php" class="btn-editar-perfil">Regresar</a>';
                        } elseif ($idtypeuser == 2) {
                            echo '<a href="posts-consulta.php?id=' . $iduser . '" class="btn-editar-perfil">Regresar</a>';
                        }
                        ?>
                        <button type="submit" name="editar-perfil" style="width: 96%;">Modificar perfil</button>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>
</body>
</html>
