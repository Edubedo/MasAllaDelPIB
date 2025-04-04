<?php
session_start();
include('../../config/database.php');
$id = $_GET['id'];

// Iniciar sesión para usar mensajes de éxito


// Obtener los datos del usuario para mostrarlos en el formulario
$sql = $conexion->query("SELECT * FROM users WHERE iduser = $id");

// Si el formulario fue enviado, procesamos los datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updates = [];

    // Verificar qué campo se ha modificado
    if (!empty($_POST['username'])) {
        $new_username = $_POST['username'];
        $updates[] = "username = '$new_username'";
    }

    if (!empty($_POST['email'])) {
        $new_email = $_POST['email'];
        $updates[] = "email = '$new_email'";
    }

    if (!empty($_POST['password'])) {
        $new_password = $_POST['password'];
        // Asegúrate de encriptar la contraseña antes de guardarla
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
        $updates[] = "password = '$new_password'";
    }

    if (count($updates) > 0) {
        // Unir las actualizaciones y generar la consulta UPDATE
        $sql_update = "UPDATE users SET " . implode(", ", $updates) . " WHERE iduser = $id";
        $conexion->query($sql_update);

        // Establecer un mensaje de éxito
        $_SESSION['success_message'] = "Perfil actualizado con éxito";

        // Redirigir para que los cambios se reflejen de inmediato
        header("Location: editar-perfil.php?id=" . $id);
        exit(); // Detener el script después de la redirección
    } else {
        echo "No se realizó ningún cambio.";
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
            <h1>Modificacion de usuario</h1>
        </div>

        <!-- Mostrar el mensaje de éxito si está disponible -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']); // Eliminar el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de edición de perfil -->
        <?php while ($datos = $sql->fetch_object()) {  ?>

            <form action="" name="editar_perfil" method="post" enctype="multipart/form-data">
                <div class="moduser-div">
                    <!-- Campo para el Username -->
                    <div class="username-div">
                        <label for="username">Nombre de Usuario:</label>
                        <input type="text" id="username" name="username" value="<?= $datos->username ?>">
                    </div>

                    <!-- Campo para el Email -->
                    <div class="email-div">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" value="<?= $datos->email ?>">
                    </div>

                    <div class="botones-div">
                        <a href="panel-usuarios.php" class="btn-editar-perfil">Regresar</a>
                        <button type="submit" name="crear_post">Modificar perfil</button>
                    </div>

                </div>
            </form>
            
        <?php } ?>
    </div>
</body>

</html>