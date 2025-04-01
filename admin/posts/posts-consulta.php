<?php
session_start();

include '../../config/database.php';
include "posts-eliminar.php";

// Verifica si hay una session activa y mandamos a llamar el nombre del usuario
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
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
?>

<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>

    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="css/consulta.css">
    <link rel="stylesheet" href="../../views/css/navbar.css">

</head>

<body>

    <?php 
    include ('../../views/layout/header.php')
    
    ?>

    <div id="overlay"></div> <!-- Capa oscura -->

    <!-- Modal -->
    <section id="modal">

        <h2 class="titulo-modal">¡Bienvenido al Panel de <?php echo $idtypeuser == 2 ? htmlspecialchars("Autor") : htmlspecialchars("Administrador"); ?>, <?php echo htmlspecialchars($username); ?>!</h2>
        <button id="botonContinuar">Continuar</button>
    </section>


    <!-- Panel de Administración -->
    <main id="admin-panel">
        <!-- Header de la página -->
        <header>
            <div class="container-header">
                <img id="logo_admin" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACXUlEQVR4nO2Yz0tVQRTHPykaSZtcPoI27YJAqXWYhNCiNOJB/gkqWhq+doUbzforyh9YO3cFtUhFaJ8+Nbe1KSVeUoH4YuBcuFzm+ubeGZk79T5w4HHfuefO9945Z84MNGnSxBXtQBlYAKrAT7GqXCuLTyG5A+wC9Qb2GRigQLQAswYDT9pTudc7z3IMPrKZIkybuqX1+xp8u8xnWwG7vhK77GDwkd31IWDRoYA5HwK2HQqo+hBQcyigFrqAHz4EbDkUsOlDwLxDAS98CAi+jLYBOyEvZEhXaTP4I+AWnsnTiUY2TQFokdY465ufKUo7HdFvmBNqBb9NQWmTijIntb0mtgG8lP+Uzz/LG+BATP0Oik7gMDbV1O9zBMRDTb48IBCuy7RJClBHMj2uHtIBTMk2Mv6pTWwdOCVxnsgZ0SgwArxuEO9QfIblngWJkXnw6xYL1jWJ051DfD1FlIplzJTFw1QJjXjlYPB1saUsAmxOHx7H4iw7FLCcRYDNZ78Ri3MaWE3x+wNMAiWxilzT+X6QWMYcWQg4n4h1JcWvonluJcW3i4zsWwg4o4m3p/ErafxKGr9v5OC9hYCzhi+kZCjgex4B9ywEXEzEupphCj1K8c1UQhWtFqcQvbE4KvHWjkniimESr2RNYsXlnGdBarPjvYxG3Mwh4lMRFrI4l3JMp74TaCW6sKBDEkxXDnX2MaWZUw3akkEzp07Ch2yauTTUhmNMGr1Gi934MXF6pHVO3lOLNYInzgVgEHgOvJMm7gvwC/gNfJXpl8aERsB9AqIz9C2l4m3Im3r+W/4CaYEHGi2wJakAAAAASUVORK5CYII=" alt="system-administrator-male">
                <h1><?php echo $idtypeuser == 2 ? htmlspecialchars("Autor") : htmlspecialchars("Administrador"); ?> <?php echo htmlspecialchars($username); ?></h1>
                <div class="new-post">
                    <h2 class="texto" name="crear_posts">Nueva publicación</h2>
                    <a href="posts-crear.php">
                        <button id="add-post-btn">
                            <span>+</span>
                        </button>
                    </a>
                </div>
            </div>

            <div id="userPopup">
                <p><strong>Usuario</strong></p>
                <p><span id="username"><?php echo htmlspecialchars($username); ?></span></p>
                <p><strong>Email</strong>
                <p><span id="email"><?php echo htmlspecialchars($email); ?></span></p>
                <a href="editar-perfil.php?id=<?php echo $iduser; ?>">
                    <button>Editar perfil</button>
                </a>

                <a href="/config/logout.php" >
                    <button style="background-color: red;">Cerrar sesion</button>
                </a>
            </div>
        </header>

        
      
            <div class="container-superior">
                <!-- Menú de Categorías -->
                <div class="category-menu">
                    <select class="categories" id="categoryFilter">
                        <option value="" disabled selected hidden>Categorías</option>
                        <option value="">Todas las categorías</option> 
                        <?php
                        // Obtener categorías desde la base de datos
                        $sql = "SELECT DISTINCT category FROM posts"; 
                        $result = mysqli_query($conexion, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['category'] . "'>" . ucwords(str_replace('-', ' ', $row['category'])) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Buscador General -->
           <div class="search-box">
               <input type="text" id="searchInput" placeholder="Buscar">
           </div>
        </div>

        <!-- Mostrar mensaje de eliminación si se ha ejecutado -->
        <div class="_eliminacion">
            <?php if (isset($mensaje) && $mensaje != '') {
                echo $mensaje;
            } ?>
        </div>

        <!-- Tabla de posts -->
        <div class="container-inferior">
            <table id="postsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Imagen</th>
                        <!-- <th>Referencias</th> -->
                        <th>Creador</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //Consulta para mostrar datos en la tabla
                    $sql = null;
                    if ($idtypeuser == 1) { // si el usuario es administrador
                        $sql = "SELECT * FROM posts";
                    } else if ($idtypeuser == 2) { // si el usuario es autor
                        $sql = "SELECT * FROM posts WHERE user_creation = '$username'";
                    }

                    $result = mysqli_query($conexion, $sql);
                    while ($mostrar = mysqli_fetch_array($result)) {
                    ?>
                        <tr class="postRow">
                            <td><?php echo $mostrar['Id_posts']; ?></td>
                            <td><?php echo $mostrar['title']; ?></td>
                            <td><?php echo $mostrar['category']; ?></td>
                            <td><img src="<?= $mostrar['image']; ?>" alt="Imagen del post" class="image-post"></td>
                            <!-- <td><a class="referencias-links" href="<?= htmlspecialchars($mostrar['referencia_posts']) ?>" style="color:black;"><?= htmlspecialchars($mostrar['referencia_posts']) ?></a></td> -->
                            <td><?php echo $mostrar['user_creation']; ?></td>
                            <td><?php echo $mostrar['post_date']; ?></td>
                            <td>
                                <!-- Botón de modificar post -->
                                <a href="posts-modificar.php?id=<?php echo $mostrar['Id_posts']; ?>" class="btn editar">Editar</a>
                                <!-- Botón de eliminar publicación -->
                                <a href="posts-consulta.php?id=<?php echo $mostrar['Id_posts']; ?>" class="btn eliminar" onclick="return ConfirmDelete()">Eliminar</a>
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

    <script>
       document.getElementById('categoryFilter').addEventListener('change', function() {
            let selectedCategory = this.value;
            let rows = document.querySelectorAll('.postRow');

            // Si no se seleccionó ninguna categoría o se seleccionó todas
            rows.forEach(function(row) {
                let categoryCell = row.cells[2].innerText.toLowerCase(); // Índice 2 es la columna de categorías

                if (selectedCategory === '' || selectedCategory === 'todas las categorías' || categoryCell.includes(selectedCategory.toLowerCase())) {
                    row.style.display = ''; 
                } else {
                    row.style.display = 'none'; 
                }
            });
        });


        // Función para el buscador general que filtra por cualquier palabra en cualquier columna
        document.getElementById('searchInput').addEventListener('input', function() {
            let searchTerm = this.value.toLowerCase();
            let rows = document.querySelectorAll('.postRow');

            rows.forEach(function(row) {
                let cells = row.getElementsByTagName('td');
                let matchFound = false;

                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].innerText.toLowerCase().includes(searchTerm)) {
                        matchFound = true;
                        break;
                    }
                }

                if (matchFound) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

// Función que abre ventana emergente para confirmar la eliminacion de un usuario
    <script>
        function ConfirmDelete(){
            var respuesta = confirm("¿Estas seguro que deseas eliminar posts?")
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