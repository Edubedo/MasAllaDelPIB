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
$foto_perfil = $row['foto_perfil'];
$ruta = isset($foto_perfil) && !empty($foto_perfil) ? "../../views/uploads/" . $foto_perfil : "../../views/uploads/user-default2.jpeg";
?>

<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>

    <link rel="icon" href="../../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/consulta.css">
    <link rel="stylesheet" href="../../views/css/navbar.css">
    <link rel="stylesheet" href="css/userpop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <div class="fondo-overlay"></div>

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
                <div class="imagen-user">
                    <?php 
                        echo '<img id="logo_admin" src="' . $ruta . '" alt="Foto de perfil">';
                    ?>
                </div>
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
                    <div class="imagen-user">
                        <?php 
                            echo '<img id="img_user" src="' . $ruta . '" alt="Foto de perfil">';
                        ?>
                    </div>
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
                <?php
                    if ($idtypeuser == 1) {
                        echo '<select class="categories" id="userFilter">';
                        echo '<option value="" disabled selected hidden>Usuarios</option>';
                        echo '<option value="">Todos los usuarios</option>';

                        // Obtener usuarios desde la base de datos
                        $sql = "SELECT DISTINCT user_creation FROM posts"; 
                        $result = mysqli_query($conexion, $sql);
                        
                        while ($row = mysqli_fetch_array($result)) {
                            $user = ucwords(str_replace('-', ' ', $row['user_creation']));
                            echo "<option value='" . $row['user_creation'] . "'>$user</option>";
                        }
                        echo '</select>';
                    }
                ?>

            </div>

                <!-- Buscador General -->
            <div class="search-box">
                <i class="fa fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar...">
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
                                <a href="posts-consulta.php?id=<?php echo $mostrar['Id_posts']; ?>" class="btn eliminar" data-id="<?php echo $mostrar['Id_posts']; ?>">Eliminar</a>
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
            <h3>¿Estás seguro que deseas eliminar esta publicación?</h3>
            <div class="deletemodal-buttons">
                <button id="confirmDelete">Eliminar</button>
                <button id="cancelDelete">Cancelar</button>
            </div>
        </div>
    </div>
    <?php include '../../views/layout/footer.php'; ?>
    
    <script src="../../js/posts-consulta.js"></script>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const botonContinuar = document.getElementById('botonContinuar');
    if (botonContinuar) {
        botonContinuar.addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('modal').style.display = 'none';
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../../config/mark_modal_shown.php', true);
            xhr.send();
        });
    }

    // Manejar modal de eliminación con funcionalidad real
    const deleteButtons = document.querySelectorAll('.btn.eliminar');
    let postIdToDelete = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            postIdToDelete = this.getAttribute('data-id');
            document.getElementById('deleteModal').style.display = 'flex';
        });
    });

    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').style.display = 'none';
        postIdToDelete = null;
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (postIdToDelete) {
            window.location.href = `posts-consulta.php?id=${postIdToDelete}`;
        }
    });
});
</script>

    
</body>

</html>