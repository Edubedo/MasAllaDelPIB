<?php
session_start();

include '../../config/database.php';
include "usuario-eliminar.php";

// Verifica si hay una session activa y mandamos a llamar el nombre del usuario
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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de usuarios</title>

    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="css/panel-usuarios.css">
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

                <img id="logo_admin" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACXUlEQVR4nO2Yz0tVQRTHPykaSZtcPoI27YJAqXWYhNCiNOJB/gkqWhq+doUbzforyh9YO3cFtUhFaJ8+Nbe1KSVeUoH4YuBcuFzm+ubeGZk79T5w4HHfuefO9945Z84MNGnSxBXtQBlYAKrAT7GqXCuLTyG5A+wC9Qb2GRigQLQAswYDT9pTudc7z3IMPrKZIkybuqX1+xp8u8xnWwG7vhK77GDwkd31IWDRoYA5HwK2HQqo+hBQcyigFrqAHz4EbDkUsOlDwLxDAS98CAi+jLYBOyEvZEhXaTP4I+AWnsnTiUY2TQFokdY465ufKUo7HdFvmBNqBb9NQWmTijIntb0mtgG8lP+Uzz/LG+BATP0Oik7gMDbV1O9zBMRDTb48IBCuy7RJClBHMj2uHtIBTMk2Mv6pTWwdOCVxnsgZ0SgwArxuEO9QfIblngWJkXnw6xYL1jWJ051DfD1FlIplzJTFw1QJjXjlYPB1saUsAmxOHx7H4iw7FLCcRYDNZ78Ri3MaWE3x+wNMAiWxilzT+X6QWMYcWQg4n4h1JcWvonluJcW3i4zsWwg4o4m3p/ErafxKGr9v5OC9hYCzhi+kZCjgex4B9ywEXEzEupphCj1K8c1UQhWtFqcQvbE4KvHWjkniimESr2RNYsXlnGdBarPjvYxG3Mwh4lMRFrI4l3JMp74TaCW6sKBDEkxXDnX2MaWZUw3akkEzp07Ch2yauTTUhmNMGr1Gi934MXF6pHVO3lOLNYInzgVgEHgOvJMm7gvwC/gNfJXpl8aERsB9AqIz9C2l4m3Im3r+W/4CaYEHGi2wJakAAAAASUVORK5CYII=" alt="system-administrator-male">
                <h1><?php echo htmlspecialchars($username); ?></h1>
            </div>

            <div id="userPopup">
                <div class="imagen-pop">
                    <img id="img_user" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACXUlEQVR4nO2Yz0tVQRTHPykaSZtcPoI27YJAqXWYhNCiNOJB/gkqWhq+doUbzforyh9YO3cFtUhFaJ8+Nbe1KSVeUoH4YuBcuFzm+ubeGZk79T5w4HHfuefO9945Z84MNGnSxBXtQBlYAKrAT7GqXCuLTyG5A+wC9Qb2GRigQLQAswYDT9pTudc7z3IMPrKZIkybuqX1+xp8u8xnWwG7vhK77GDwkd31IWDRoYA5HwK2HQqo+hBQcyigFrqAHz4EbDkUsOlDwLxDAS98CAi+jLYBOyEvZEhXaTP4I+AWnsnTiUY2TQFokdY465ufKUo7HdFvmBNqBb9NQWmTijIntb0mtgG8lP+Uzz/LG+BATP0Oik7gMDbV1O9zBMRDTb48IBCuy7RJClBHMj2uHtIBTMk2Mv6pTWwdOCVxnsgZ0SgwArxuEO9QfIblngWJkXnw6xYL1jWJ051DfD1FlIplzJTFw1QJjXjlYPB1saUsAmxOHx7H4iw7FLCcRYDNZ78Ri3MaWE3x+wNMAiWxilzT+X6QWMYcWQg4n4h1JcWvonluJcW3i4zsWwg4o4m3p/ErafxKGr9v5OC9hYCzhi+kZCjgex4B9ywEXEzEupphCj1K8c1UQhWtFqcQvbE4KvHWjkniimESr2RNYsXlnGdBarPjvYxG3Mwh4lMRFrI4l3JMp74TaCW6sKBDEkxXDnX2MaWZUw3akkEzp07Ch2yauTTUhmNMGr1Gi934MXF6pHVO3lOLNYInzgVgEHgOvJMm7gvwC/gNfJXpl8aERsB9AqIz9C2l4m3Im3r+W/4CaYEHGi2wJakAAAAASUVORK5CYII=" alt="system-administrator-male">
                </div>
                <div class="nombre-pop">
                    <p>!Hola, <span id="username"><?php echo htmlspecialchars($username); ?></span>!</p>
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
                        <button>Cerrar sesion</button>
                    </a>
                </div>
            </div>

        </header>


        <!-- Mostrar mensaje de eliminación si se ha ejecutado -->
        <div class="_eliminacion">
            <?php if (isset($mensaje) && $mensaje != '') {
                echo $mensaje;
            } ?>
        </div>

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
                    //Consulta para mostrar datos en la tabla
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
                                <a href="editar-perfil.php?id=<?php echo $mostrar['iduser']; ?>" class="btn editar">Editar</a>
                                <!-- Botón de eliminar usuario -->
                                <a href="panel-usuarios.php?id=<?php echo $mostrar['iduser']; ?>" class="btn eliminar" onclick="return ConfirmDelete()">Eliminar</a>
                                
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </main>


    <script src="../../js/panel_usuarios.js"></script>

    // Función que abre ventana emergente para confirmar la eliminacion de un usuario
    <script>
        function ConfirmDelete(){
            var respuesta = confirm("¿Estas seguro que deseas eliminar usuario?")
            if(respuesta == true){
                return true;
            }
            else{
                return false;
            }
        }
    </script>

</body>

</html>