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
        
        <h2 class="titulo-modal">BIENVENIDO AL PANEL DE ADMINISTRADOR</h2>
        
        <button id="botonContinuar" >Continuar</button>
        
    </section>

    <!-- Panel de Administración -->
    <main id="admin-panel">
        <header>
            <div class="container-header">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACXUlEQVR4nO2Yz0tVQRTHPykaSZtcPoI27YJAqXWYhNCiNOJB/gkqWhq+doUbzforyh9YO3cFtUhFaJ8+Nbe1KSVeUoH4YuBcuFzm+ubeGZk79T5w4HHfuefO9945Z84MNGnSxBXtQBlYAKrAT7GqXCuLTyG5A+wC9Qb2GRigQLQAswYDT9pTudc7z3IMPrKZIkybuqX1+xp8u8xnWwG7vhK77GDwkd31IWDRoYA5HwK2HQqo+hBQcyigFrqAHz4EbDkUsOlDwLxDAS98CAi+jLYBOyEvZEhXaTP4I+AWnsnTiUY2TQFokdY465ufKUo7HdFvmBNqBb9NQWmTijIntb0mtgG8lP+Uzz/LG+BATP0Oik7gMDbV1O9zBMRDTb48IBCuy7RJClBHMj2uHtIBTMk2Mv6pTWwdOCVxnsgZ0SgwArxuEO9QfIblngWJkXnw6xYL1jWJ051DfD1FlIplzJTFw1QJjXjlYPB1saUsAmxOHx7H4iw7FLCcRYDNZ78Ri3MaWE3x+wNMAiWxilzT+X6QWMYcWQg4n4h1JcWvonluJcW3i4zsWwg4o4m3p/ErafxKGr9v5OC9hYCzhi+kZCjgex4B9ywEXEzEupphCj1K8c1UQhWtFqcQvbE4KvHWjkniimESr2RNYsXlnGdBarPjvYxG3Mwh4lMRFrI4l3JMp74TaCW6sKBDEkxXDnX2MaWZUw3akkEzp07Ch2yauTTUhmNMGr1Gi934MXF6pHVO3lOLNYInzgVgEHgOvJMm7gvwC/gNfJXpl8aERsB9AqIz9C2l4m3Im3r+W/4CaYEHGi2wJakAAAAASUVORK5CYII=" alt="system-administrator-male">
                <h1>Nombre de Administrador</h1>
                <button id="cerrar-sesion-btn">Cerrar sesión</button>
            </div>
        </header>

        <div class="container-superior">
            <!-- Menú de Categorías -->
            <div class="category-menu">
                <select class="categories">
                    <option value="" disabled selected hidden>Categorías</option><!-- Placeholder -->
                        <?php
                            $posts = json_decode(file_get_contents('../../data/posts.json'), true); // Obtener los posts
                            $categories = array(); // Crear un array para las categorías
                            foreach ($posts as $post) {
                                $categories[] = $post['category']; // Agregar las categorías al array
                            }
                            $categories = array_unique($categories); // Eliminar duplicados
                            foreach ($categories as $category) {
                                echo "<option value='$category'>" . ucwords(str_replace('-', ' ', $category)) . "</option>";
                            }
                        ?>
                </select>
            </div>


            <!-- Botón de Nueva Publicación -->
            <div class="new-post">
                <h2 class="texto">Nueva publicación</h2>
                <a href="posts-crear.php">
                    <button id="add-post-btn">
                        <span>+</span>
                    </button>
                </a>
                
            </div>
        </div>

        
        <div class="container-inferior">
            
            <div class="elecciones">
                                
                    <!-- Ordenar las publicaciones por fecha (de más reciente a más antigua) -->
                    <?php
                        // Función para comparar fechas
                        function compararFechas($a, $b) {
                            return strtotime($b['date']) - strtotime($a['date']); // Orden descendente - strtotime, convierte las fechas en timestamps (números enteros).
                        }

                        // Ordenar el array $posts por fecha
                        usort($posts, 'compararFechas');
                    ?>


                    <!-- Elecciones de la publicación -->
                    <div class="titulos-publicacion-editor">
                        <select class="titulos">
                            <option value="" disabled selected hidden>Titulos de la publicación </option><!-- Placeholder -->
                            <?php
                                $titles = array(); // Crear un array para titulos
                                foreach ($posts as $post) {
                                    $titles[] = $post['title']; // Agregar los titulos al array
                                }
                                foreach ($titles as $title) {
                                    echo "<option value='$title'>" . ucwords(str_replace('-', ' ', $title)) . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <!-- Categoría de la publicación -->
                    <div class="categoria">
                        <select class="categorias-editor">
                            <option value="" disabled selected hidden>Categoría</option><!-- Placeholder -->
                            <?php
                                foreach ($categories as $category) {
                                    echo "<option value='$category'>" . ucwords(str_replace('-', ' ', $category)) . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <!-- Usuario de la publicación -->
                    <div class="administradores">
                        <select class="usuario">
                            <option value="" disabled selected hidden>Usuario</option><!-- Placeholder -->
                            <?php
                                $users = array(); // Crear un array para los usuarios
                                foreach ($posts as $post) {
                                    $users[] = $post['user']; // Agregar los usuarios al array
                                }
                                $users = array_unique($users); // Eliminar duplicados
                                foreach ($users as $user) {
                                    echo "<option value='$user'>" . ucwords(str_replace('-', ' ', $user)) . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="fechas">
                        <select class="fecha-publicacion">
                            <option value="" disabled selected hidden>Fecha de publicación</option><!-- Placeholder -->
                            <?php
                                $fechas = array(); // Crear un array para los usuarios
                                foreach ($posts as $post) {
                                    $fechas[] = $post['date']; // Agregar los usuarios al array
                                }
                                $fechas = array_unique($fechas); // Eliminar duplicados
                                foreach ($fechas as $fecha) {
                                    echo "<option value='$fecha'>" . $fecha . "</option>";
                                }
                            ?>
                            
                        </select>
                    </div>
                    <div class="acciones-texto">
                        <h3>Acciones</h3>

                    </div>
                    
            </div> <!-- Elecciones -->
            
            <!-- Tabla de Publicaciones -->
            <div class="publicaciones">
                <div class="grid-container">
                
        
            
                    <?php
                

                    foreach ($posts as $post) {
                        $url = 'posts-detalles.php?id=' . $post['id'];
                        echo "<div class='grid-item' data-id=" . $post['id'] .">";
                            echo "<div class='title' oncontextmenu='mostrarModal(event, " .$post['id'] . ")'>" . ucwords(str_replace('-', ' ', $post['title'])) . "</div>";

                            echo "<div class='category' oncontextmenu='mostrarModal(event, " .$post['id'] . ")'>" . ucwords(str_replace('-', ' ', $post['category'])) . "</div>";
                            
                            echo "<div class='user' oncontextmenu='mostrarModal(event, " .$post['id'] . ")'>" . ucwords(str_replace('-', ' ', $post['user'])) . "</div>";
            
                            echo "<div class='date' oncontextmenu='mostrarModal(event, " .$post['id'] . ")'>" . $post['date'] . "</div>";
                            
                        
                            echo "<div class='botones-derecha'>";
                                echo "<button id='btn-editar' onclick='editarPublicacion(" . $post['id'] . ")'>Editar</button>";
                                echo "<button id='btn-eliminar' onclick='eliminarPublicacion(" . $post['id'] . ")'>Eliminar</button>";
                            echo "</div>";
                        echo "</div>";
                    }
                        
                    
                
                    ?>
                </div>
            </div>
        </div>

            
        </div>
    </main>
    

    <script src="../../js/posts-consulta.js"></script>
</body>

</html>
