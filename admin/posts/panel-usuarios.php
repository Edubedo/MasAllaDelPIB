<?php 
session_start();  

include '../../config/database.php';  

// Verifica si hay una session activa y mandamos a llamar el nombre del usuario
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];  
} else {
    // Si no hay usuario logueado lo va a redirigir al login
    header("Location: ../../views/signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de usuarios</title>
    
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="css/panel-usuarios.css">
  
</head>
<body>
    
    <?php include './layout/panel-usuarios-header.php'; ?>
    
    


    <!-- Panel de Administración -->
    <main id="admin-panel">
        <!-- Header de la página -->
        <header>
            <div class="container-header">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACXUlEQVR4nO2Yz0tVQRTHPykaSZtcPoI27YJAqXWYhNCiNOJB/gkqWhq+doUbzforyh9YO3cFtUhFaJ8+Nbe1KSVeUoH4YuBcuFzm+ubeGZk79T5w4HHfuefO9945Z84MNGnSxBXtQBlYAKrAT7GqXCuLTyG5A+wC9Qb2GRigQLQAswYDT9pTudc7z3IMPrKZIkybuqX1+xp8u8xnWwG7vhK77GDwkd31IWDRoYA5HwK2HQqo+hBQcyigFrqAHz4EbDkUsOlDwLxDAS98CAi+jLYBOyEvZEhXaTP4I+AWnsnTiUY2TQFokdY465ufKUo7HdFvmBNqBb9NQWmTijIntb0mtgG8lP+Uzz/LG+BATP0Oik7gMDbV1O9zBMRDTb48IBCuy7RJClBHMj2uHtIBTMk2Mv6pTWwdOCVxnsgZ0SgwArxuEO9QfIblngWJkXnw6xYL1jWJ051DfD1FlIplzJTFw1QJjXjlYPB1saUsAmxOHx7H4iw7FLCcRYDNZ78Ri3MaWE3x+wNMAiWxilzT+X6QWMYcWQg4n4h1JcWvonluJcW3i4zsWwg4o4m3p/ErafxKGr9v5OC9hYCzhi+kZCjgex4B9ywEXEzEupphCj1K8c1UQhWtFqcQvbE4KvHWjkniimESr2RNYsXlnGdBarPjvYxG3Mwh4lMRFrI4l3JMp74TaCW6sKBDEkxXDnX2MaWZUw3akkEzp07Ch2yauTTUhmNMGr1Gi934MXF6pHVO3lOLNYInzgVgEHgOvJMm7gvwC/gNfJXpl8aERsB9AqIz9C2l4m3Im3r+W/4CaYEHGi2wJakAAAAASUVORK5CYII=" alt="system-administrator-male">
                <h1>Bienvenido, <?php echo htmlspecialchars($username); ?></h1>
                <button id="cerrar-sesion-btn">Cerrar sesión</button>
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
                        <th>Contraseña</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //Consulta para mostrar datos en la tabla
                    $sql = "SELECT * FROM users";
                    $result = mysqli_query($conexion, $sql);
                    while($mostrar = mysqli_fetch_array($result)) {
                    ?>
                    <tr class="postRow">
                        <td><?php echo $mostrar['iduser']; ?></td>
                        <td><?php echo $mostrar['username']; ?></td>
                        <td><?php echo $mostrar['email']; ?></td> 
                        <td><?php echo $mostrar['password']; ?></td>
                        <td> 
                         <!-- Botón de modificar usuario -->
                        <a href="usuario-modificar.php?id=<?php echo $mostrar['iduser']; ?>" class="btn editar">Editar</a>
                        <!-- Botón de eliminar usuario -->
                        <a href="usuario-eliminar.php?id=<?php echo $mostrar['iduser']; ?>" class="btn eliminar">Eliminar</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </main>

    <script src="../../js/posts-consulta.js"></script>
    
    
</body>
</html>