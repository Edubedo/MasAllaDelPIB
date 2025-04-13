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

    if (!empty($_POST['password'])) {
        $new_password = md5($_POST['password']);
        $updates[] = "password = '$new_password'";
    }

    // 👇 Lógica para subir imagen
    if (!empty($_FILES['foto']['name'])) {
        $foto_nombre = time() . '_' . basename($_FILES['foto']['name']);
        $ruta_guardado = '../../views/uploads/' . $foto_nombre;
            
        $extensiones_validas = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($foto_nombre, PATHINFO_EXTENSION));

        if (in_array($extension, $extensiones_validas)) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_guardado)) {
                $updates[] = "foto_perfil = '$foto_nombre'";
                if ($id == $iduser) {
                    $_SESSION['foto_perfil'] = $foto_nombre;
                }
            } else {
                echo "❌ Error al subir la imagen.";
            } 
        }else {
            echo "❌ Formato de imagen no permitido.";
        }
      
}

    

    if (count($updates) > 0) {
        // Obtener username anterior
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
    } else {
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
                        <input type="password" id="password" name="password" placeholder="Nueva contraseña">
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
