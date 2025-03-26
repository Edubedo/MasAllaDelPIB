<?php 
session_start();  

include '../../config/database.php';  
include "posts-eliminar.php";  

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
    <title>Panel de Administrador</title>
    
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="css/consulta.css">
  
</head>
<body>
    
    <?php include './layout/posts-header.php'; ?>
    
    <div id="overlay"></div> <!-- Capa oscura -->

    <!-- Modal -->
    <section id="modal">
        <h2 class="titulo-modal">¡Bienvenido al Panel de Administrador <?php echo htmlspecialchars($username); ?>!</h2>
        <button id="botonContinuar">Continuar</button>
    </section>


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

        <div class="container-superior">
            <!-- Menú de Categorías -->
            <div class="category-menu">
                <select class="categories" id="categoryFilter">
                    <option value="" disabled selected hidden>Categorías</option><!-- Placeholder -->
                    <?php
                        // Obtener categorías desde la base de datos
                        $sql = "SELECT DISTINCT category FROM posts"; // Consulta para obtener categorías únicas
                        $result = mysqli_query($conexion, $sql);
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['category'] . "'>" . ucwords(str_replace('-', ' ', $row['category'])) . "</option>";
                        }
                    ?>
                </select>
            </div>

            <!-- Buscador General -->
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search">
            </div>

            <!-- Botón de Nueva Publicación -->
            <div class="new-post">
                <h2 class="texto" name="crear_posts">Nueva publicación</h2>
                <a href="posts-crear.php">
                    <button id="add-post-btn">
                        <span>+</span>
                    </button>
                </a>
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
                        <th>Titulo</th>
                        <th>Cotenido</th>
                        <th>Categoria</th>
                        <th>Imagen</th>
                        <th>Referencia</th>
                        <th>Creador</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //Consulta para mostrar datos en la tabla
                    $sql = "SELECT * FROM posts";
                    $result = mysqli_query($conexion, $sql);
                    while($mostrar = mysqli_fetch_array($result)) {
                    ?>
                    <tr class="postRow">
                        <td><?php echo $mostrar['Id_posts']; ?></td>
                        <td><?php echo $mostrar['title']; ?></td>
                        <td><?php echo $mostrar['content']; ?></td> 
                        <td><?php echo $mostrar['category']; ?></td>
                        <td><img src="<?= $mostrar['image']; ?>" alt="Imagen del post" class="image-post"></td>
                        <td><?php echo $mostrar['referencia_posts']; ?></td>
                        <td><?php echo $mostrar['user_creation']; ?></td>
                        <td><?php echo $mostrar['post_date']; ?></td>
                        <td> 
                         <!-- Botón de modificar post -->
                        <a href="posts-modificar.php?id=<?php echo $mostrar['Id_posts']; ?>" class="btn editar">Editar</a>
                        <!-- Botón de eliminar publicación -->
                        <a href="posts-consulta.php?id=<?php echo $mostrar['Id_posts']; ?>" class="btn eliminar">Eliminar</a>
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

        // Función para filtrar los posts por categoría
        document.getElementById('categoryFilter').addEventListener('change', function() {
            let selectedCategory = this.value;
            let rows = document.querySelectorAll('.postRow');
            
            rows.forEach(function(row) {
                let categoryCell = row.cells[3].innerText.toLowerCase();
                if (selectedCategory === '' || categoryCell.includes(selectedCategory.toLowerCase())) {
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
</body>
</html>
