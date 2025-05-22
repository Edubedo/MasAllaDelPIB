<?php
session_start();

include '../../config/database.php';
include "usuario-eliminar.php";

// Verifica si hay una sesión activa y mandamos a llamar el nombre del usuario
if (isset($_SESSION['username'])) {

    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $idtypeuser = $_SESSION['id_type_user'];
} else {
    // Si no hay usuario logueado lo va a redirigir al login
    header("Location: ../../views/signin.php");
    exit();
}

// Obtener el tipo de usuario del usuario
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($result);
$idtypeuser = $row['id_type_user'];
$iduser = $row['iduser'];
$foto_perfil = $row['foto_perfil'];
$ruta = isset($foto_perfil) && !empty($foto_perfil) ? "../../views/uploads/" . $foto_perfil : "../../views/uploads/user-default2.jpeg";
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de control Usuarios</title>

    <link rel="icon" href="../../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/consulta.css">
    <link rel="stylesheet" href="../../views/css/navbar.css">
    <link rel="stylesheet" href="css/userpop.css">

</head>

<body>

    <?php 
    include ('../../views/layout/header.php')
    ?>

    <!-- Panel de Administración -->
    <main id="admin-panel">
        <!-- Header de la página -->
        <header>
            <div class="container-header">
                <!-- Obtener el tipo de usuario -->
                <?php
                $sql = "SELECT * FROM users WHERE iduser = '$iduser'";
                ?>
                <!-- Mostrar imagen de perfil o imagen predeterminada -->
                <div class="imagen-user">
                    <?php 
                        echo '<img id="logo_admin" src="' . $ruta . '" alt="Foto de perfil">';
                    ?>
                </div>
                <h1><?php echo htmlspecialchars($username); ?></h1>
            </div>

            <div id="userPopup">
                <div class="imagen-pop">
                    <div class="imagen-user">
                        <?php 
                            echo '<img id="img_user" src="' . $ruta . '" alt="Foto de perfil">';
                        ?>
                    </div>
                </div>
                <div class="nombre-pop">
                    <p>¡Hola, <span id="username"><?php echo htmlspecialchars($username); ?></span>!</p>
                </div>
                <div class="info-pop">
                    <div class="nombredeluser">
                        <p><strong>Nombre de usuario</strong></p>
                        <p><span id="username"><?php echo htmlspecialchars($username); ?></span></p>
                    </div>
                    <div class="emaildeluser">
                        <p><strong>Email</strong></p>
                        <p><span id="email"><?php echo htmlspecialchars($email); ?></span></p>
                    </div>
                </div>
                <div class="botones-pop">
                    <a href="editar-perfil.php?id=<?php echo $iduser; ?>">
                        <button>Editar perfil</button>
                    </a>
                    <a href="/config/logout.php" >
                        <button>Cerrar sesión</button>
                    </a>
                </div>
            </div>

        </header>

        <!-- Tabla de usuarios -->
        <div class="container-inferior">
            <table id="postsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta para mostrar datos en la tabla
                    $sql = "SELECT * FROM users";
                    $result = mysqli_query($conexion, $sql);
                    while ($mostrar = mysqli_fetch_array($result)) {
                    ?>
                        <tr class="postRow">
                            <td><?php echo $mostrar['iduser']; ?></td>
                            <td><?php echo $mostrar['username']; ?></td>
                            <td><?php echo $mostrar['email']; ?></td>
                            <td>
                                <!-- Botón de modificar usuario -->
                                <a href="editar-perfil.php?id=<?php echo $mostrar['iduser']; ?>" class="btn editar">
                                    <i class="fas fa-edit"></i> Editar
                                </a>

                                <!-- Botón de eliminar usuario con ícono -->
                                <a href="panel-usuarios.php?id=<?php echo $mostrar['iduser']; ?>" class="btn eliminar">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </a>

                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </main>

    <!-- Modal de confirmación -->
    <div id="deleteModal" class="deletemodal">
        <div class="deletemodal-content">
            <h3>¿Estás seguro que deseas eliminar este usuario?</h3>
            <div class="deletemodal-buttons">
            <button id="confirmDelete">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
            <button id="cancelDelete">
                <i class="fas fa-times-circle"></i> Cancelar
            </button>

            </div>
        </div>
    </div>

    <script src="../../js/panel_usuarios.js"></script>

    <?php include '../../views/layout/footer.php'; ?>
</body>

</html>
