<?php
session_start();

include '../../config/database.php';
include "posts-eliminar.php";

// Manejo de desactivar/activar publicación
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] === 'desactivar') {
        $sql = "UPDATE posts SET status = 'INACTI' WHERE Id_posts = $id";
        if (mysqli_query($conexion, $sql)) {
            echo "<script>window.location.href = 'posts-consulta.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error al desactivar la publicación: " . mysqli_error($conexion) . "');</script>";
        }
    } elseif ($_GET['action'] === 'activar') {
        $sql = "UPDATE posts SET status = 'ACTIVO' WHERE Id_posts = $id";
        if (mysqli_query($conexion, $sql)) {
            echo "<script>window.location.href = 'posts-consulta.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error al activar la publicación: " . mysqli_error($conexion) . "');</script>";
        }
    }
}

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
    <title>Panel de Control</title>

    <link rel="icon" href="../../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/consulta.css">
    <link rel="stylesheet" href="../../views/css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <div class="fondo-overlay"></div>

    <?php
    include('../../views/layout/header.php')

    ?>

    <div id="overlay" style="display: <?php echo $_SESSION["modal_mostrado"] ? 'none' : 'block'; ?>;"></div>

    <section id="modal" style="display: <?php echo $_SESSION["modal_mostrado"] ? 'none' : 'block'; ?>;">
        <h2 class="titulo-modal">¡Te damos la bienvenida al Panel de <?php echo $idtypeuser == 2 ? htmlspecialchars("Autor") : htmlspecialchars("Administrador"); ?>, <?php echo htmlspecialchars($username); ?>!</h2>
        <button id="botonContinuar">
            <i class="fas fa-arrow-right"></i> Continuar
        </button>

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


        </header>



        <div class="container-superior">
            <!-- Menú de Categorías -->
            <div class="category-menu">
                <select class="categories" id="categoryFilter">
                    <option value="" disabled selected hidden><Categorías class="fas fa-layer-group">Categorías</option>
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

                <!-- Filtro de estado (activo/inactivo) -->
                <select class="categories" id="statusFilter">
                    <option value="" disabled selected hidden>Estado</option>
                    <option value="">Todos los estados</option>
                    <option value="ACTIVO">Activos</option>
                    <option value="INACTI">Inactivos</option>
                </select>

                <?php
                if ($idtypeuser == 1) {
                    echo '<select class="categories" id="userFilter">';
                    echo '<option value="" disabled selected hidden>Usuarios</option>';
                    echo '<option value="">Todos los usuarios</option>';

                    // Obtener usuarios desde la base de datos
                    $sql = "SELECT DISTINCT username FROM users";
                    $result = mysqli_query($conexion, $sql);

                    while ($row = mysqli_fetch_array($result)) {
                        $user = ucwords(str_replace('-', ' ', $row['username']));
                        echo "<option value='" . $row['username'] . "'>$user</option>";
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
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Contenido</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
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
                        <tr class="postRow" data-status="<?php echo $mostrar['status']; ?>" data-category="<?php echo $mostrar['category']; ?>" data-user="<?php echo $mostrar['user_creation']; ?>">
                            <td><?php echo $mostrar['title']; ?></td>
                            <td><?php echo $mostrar['category']; ?></td>
                            <td><?php echo $mostrar['content']; ?></td>
                            <td><?php echo $mostrar['user_creation']; ?></td>
                            <td><?php echo $mostrar['post_date']; ?></td>
                            <td><?php echo $mostrar['status']; ?></td>
                            <td>
                                <!-- Botón de modificar post - para todos los usuarios -->
                                <?php if ($mostrar['status'] === 'ACTIVO') { ?>
                                    <a href="posts-modificar.php?id=<?php echo $mostrar['Id_posts']; ?>" class="btn editar">
                                        <i class="fas fa-pencil-alt"></i> Editar
                                    </a>
                                <?php } ?>

                                <!-- Botón de eliminar publicación - solo para administradores -->
                                <?php if ($idtypeuser == 1) { ?>
                                    <a style="margin-right: 4px;" href="posts-consulta.php?id=<?php echo $mostrar['Id_posts']; ?>" class="btn eliminar" data-id="<?php echo $mostrar['Id_posts']; ?>">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                <?php } ?>

                                <!-- Botón de desactivar/activar publicación - para todos los usuarios -->
                                <?php if ($mostrar['status'] === 'ACTIVO') { ?>
                                    <a href="posts-consulta.php?action=desactivar&id=<?php echo $mostrar['Id_posts']; ?>" class="btn desactivar" data-id="<?php echo $mostrar['Id_posts']; ?>">
                                        <i class="fas fa-power-off"></i> Desactivar
                                    </a>
                                <?php } else { ?>
                                    <a href="posts-consulta.php?action=activar&id=<?php echo $mostrar['Id_posts']; ?>" class="btn activar" data-id="<?php echo $mostrar['Id_posts']; ?>">
                                        <i class="fas fa-check"></i> Activar
                                    </a>
                                <?php } ?>

                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>



    <!-- Modal de confirmación para eliminar -->
    <div id="deleteModal" class="deletemodal">
        <div class="deletemodal-content">
            <h3>¿Estás seguro que deseas eliminar esta publicación?</h3>
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

    <!-- Modal de confirmación para desactivar/activar -->
    <div id="statusModal" class="deletemodal">
        <div class="deletemodal-content">
            <h3 id="statusModalTitle">¿Estás seguro que deseas cambiar el estado de esta publicación?</h3>
            <div class="deletemodal-buttons">
            <button id="confirmStatus" style="background-color: rgb(23, 143, 69);">
                <i class="fas fa-check-circle"></i> Confirmar
            </button>
            <button id="cancelStatus" style="background-color: #f44336;">
                <i class="fas fa-times-circle"></i> Cancelar
            </button>

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

            // Variables para manejar los IDs y acciones
            let postIdToDelete = null;
            let postIdToChangeStatus = null;
            let statusAction = null;

            // Manejar modal de eliminación
            const deleteButtons = document.querySelectorAll('.btn.eliminar');
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

            // Manejar modal de desactivar/activar
            const statusButtons = document.querySelectorAll('.btn.desactivar, .btn.activar');
            console.log("Botones de estado encontrados:", statusButtons.length);

            statusButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log("Botón de estado clickeado");
                    postIdToChangeStatus = this.getAttribute('data-id');
                    statusAction = this.classList.contains('desactivar') ? 'desactivar' : 'activar';

                    // Actualizar el título del modal según la acción
                    const actionText = statusAction === 'desactivar' ? 'desactivar' : 'activar';
                    document.getElementById('statusModalTitle').textContent = `¿Estás seguro que deseas ${actionText} esta publicación?`;

                    document.getElementById('statusModal').style.display = 'flex';
                });
            });

            document.getElementById('cancelStatus').addEventListener('click', function() {
                document.getElementById('statusModal').style.display = 'none';
                postIdToChangeStatus = null;
                statusAction = null;
            });

            document.getElementById('confirmStatus').addEventListener('click', function() {
                if (postIdToChangeStatus && statusAction) {
                    console.log(`Redirigiendo a: posts-consulta.php?action=${statusAction}&id=${postIdToChangeStatus}`);
                    window.location.href = `posts-consulta.php?action=${statusAction}&id=${postIdToChangeStatus}`;
                }
            });

            // Filtros para la tabla
            const statusFilter = document.getElementById('statusFilter');
            const categoryFilter = document.getElementById('categoryFilter');
            const userFilter = document.getElementById('userFilter');
            const searchInput = document.getElementById('searchInput');

            function filterTable() {
                const statusValue = statusFilter ? statusFilter.value : '';
                const categoryValue = categoryFilter ? categoryFilter.value : '';
                const userValue = userFilter ? userFilter.value : '';
                const searchValue = searchInput ? searchInput.value.toLowerCase() : '';

                const rows = document.querySelectorAll('.postRow');

                rows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    const category = row.getAttribute('data-category');
                    const user = row.getAttribute('data-user');
                    const content = row.textContent.toLowerCase();

                    let showRow = true;

                    if (statusValue && status !== statusValue) {
                        showRow = false;
                    }

                    if (categoryValue && category !== categoryValue) {
                        showRow = false;
                    }

                    if (userValue && user !== userValue) {
                        showRow = false;
                    }

                    if (searchValue && !content.includes(searchValue)) {
                        showRow = false;
                    }

                    row.style.display = showRow ? '' : 'none';
                });
            }

            if (statusFilter) statusFilter.addEventListener('change', filterTable);
            if (categoryFilter) categoryFilter.addEventListener('change', filterTable);
            if (userFilter) userFilter.addEventListener('change', filterTable);
            if (searchInput) searchInput.addEventListener('input', filterTable);
        });
    </script>

    <style>
        /* Estilos para los botones de activar/desactivar */
        .btn.desactivar,
        .btn.activar {
            background-color: #FFD700 !important;
            /* Amarillo */
            color: #000 !important;
            border: 1px solid #DAA520;
        }

        .btn.desactivar:hover,
        .btn.activar:hover {
            background-color: #FFC107 !important;
        }
    </style>

</body>

</html>