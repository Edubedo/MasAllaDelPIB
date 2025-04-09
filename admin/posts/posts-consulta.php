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

//Verifica si ya se mostró el modal de bienvenida
if (!isset($_SESSION["modal_mostrado"])) {
    $_SESSION["modal_mostrado"] = false;
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
    <title>Panel de Administrador</title>

    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="css/consulta.css">
    <link rel="stylesheet" href="../../views/css/navbar.css">
    <link rel="stylesheet" href="css/userpop.css">

</head>

<body>

    <?php 
        include ('../../views/layout/header.php')
    
    ?>

    <div id="overlay" style="display: <?php echo $_SESSION["modal_mostrado"] ? 'none' : 'block'; ?>;"></div>

    <section id="modal" style="display: <?php echo $_SESSION["modal_mostrado"] ? 'none' : 'block'; ?>;">
        <h2 class="titulo-modal">¡Bienvenido al Panel de <?php echo $idtypeuser == 2 ? htmlspecialchars("Autor") : htmlspecialchars("Administrador"); ?>, <?php echo htmlspecialchars($username); ?>!</h2>
        <button id="botonContinuar">Continuar</button>
    </section>


    <!-- Panel de Administración -->
    <main id="admin-panel">
        <!-- Header de la página -->
        <header>
            <div class="container-header">
                <img id="logo_admin" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACXUlEQVR4nO2Yz0tVQRTHPykaSZtcPoI27YJAqXWYhNCiNOJB/gkqWhq+doUbzforyh9YO3cFtUhFaJ8+Nbe1KSVeUoH4YuBcuFzm+ubeGZk79T5w4HHfuefO9945Z84MNGnSxBXtQBlYAKrAT7GqXCuLTyG5A+wC9Qb2GRigQLQAswYDT9pTudc7z3IMPrKZIkybuqX1+xp8u8xnWwG7vhK77GDwkd31IWDRoYA5HwK2HQqo+hBQcyigFrqAHz4EbDkUsOlDwLxDAS98CAi+jLYBOyEvZEhXaTP4I+AWnsnTiUY2TQFokdY465ufKUo7HdFvmBNqBb9NQWmTijIntb0mtgG8lP+Uzz/LG+BATP0Oik7gMDbV1O9zBMRDTb48IBCuy7RJClBHMj2uHtIBTMk2Mv6pTWwdOCVxnsgZ0SgwArxuEO9QfIblngWJkXnw6xYL1jWJ051DfD1FlIplzJTFw1QJjXjlYPB1saUsAmxOHx7H4iw7FLCcRYDNZ78Ri3MaWE3x+wNMAiWxilzT+X6QWMYcWQg4n4h1JcWvonluJcW3i4zsWwg4o4m3p/ErafxKGr9v5OC9hYCzhi+kZCjgex4B9ywEXEzEupphCj1K8c1UQhWtFqcQvbE4KvHWjkniimESr2RNYsXlnGdBarPjvYxG3Mwh4lMRFrI4l3JMp74TaCW6sKBDEkxXDnX2MaWZUw3akkEzp07Ch2yauTTUhmNMGr1Gi934MXF6pHVO3lOLNYInzgVgEHgOvJMm7gvwC/gNfJXpl8aERsB9AqIz9C2l4m3Im3r+W/4CaYEHGi2wJakAAAAASUVORK5CYII=" alt="system-administrator-male">
                <h1><?php echo htmlspecialchars($username); ?></h1>
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

        <!-- Tabla de posts -->
        <div class="container-inferior">
            <table id="postsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Contenido</th>
                        <th>Referencias</th>
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
                            <td><?php echo $mostrar['content']; ?></td>
                            <td>
                            <?php
                                $referencias = explode("\n", trim($mostrar['referencia_posts']));
                                if (!empty($referencias)) {
                                    foreach ($referencias as $ref) {
                                        $ref = trim($ref);
                                        if (!empty($ref)) {
                                            if (filter_var($ref, FILTER_VALIDATE_URL)) {
                                                echo '<a class="referencias-links" href="' . htmlspecialchars($ref) . '" target="_blank" style="color:black;">' . htmlspecialchars($ref) . '</a><br>';
                                            } else {
                                                echo htmlspecialchars($ref) . '<br>';
                                            }
                                        }
                                    }
                                } else {
                                    echo '—';
                                }
                            ?>
                            </td>
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

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables del modal
            const deleteModal = document.getElementById('deleteModal');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const cancelDeleteBtn = document.getElementById('cancelDelete');
            let deleteLink = null;

            // Función para mostrar el modal
            function showDeleteModal(link) {
                deleteLink = link; // Guardamos el enlace de eliminación
                deleteModal.style.display = 'flex'; // Mostrar el modal
                document.body.style.overflow = 'hidden'; // Evitar que el fondo se desplace
            }

            // Función para cerrar el modal
            function closeDeleteModal() {
                deleteModal.style.display = 'none'; // Ocultar el modal
                document.body.style.overflow = ''; // Restaurar el desplazamiento del fondo
            }

            // Confirmar eliminación
            confirmDeleteBtn.addEventListener('click', function() {
                if (deleteLink) {
                    // Redirigir al enlace de eliminación después de un pequeño retraso para que el modal se cierre primero
                    closeDeleteModal(); // Cerrar el modal
                    setTimeout(function() {
                        window.location.href = deleteLink.href; // Redirigir al enlace de eliminación
                    }, 200); // Retrasar para evitar que el modal desaparezca demasiado rápido
                }
            });

            // Cancelar eliminación
            cancelDeleteBtn.addEventListener('click', function() {
                closeDeleteModal(); // Cerrar el modal sin eliminar
            });

            // Asignar el evento de clic a todos los botones de eliminar
            document.querySelectorAll('.btn.eliminar').forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevenir la acción predeterminada (redirección)
                    showDeleteModal(this); // Mostrar el modal
                });
            });
        });
    </script>

    <!-- Modal de confirmación -->
    <div id="deleteModal" class="deletemodal">
        <div class="deletemodal-content">
            <h3>¿Estás seguro que deseas eliminar esta publicación?</h3>
            <div class="deletemodal-buttons">
                <button id="confirmDelete">Eliminar</button>
                <button id="cancelDelete">Cancelar</button>
            </div>
        </div>
    </div>
    <?php include '../../views/layout/footer.php'; ?>
</body>

</html>