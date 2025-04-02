<?php 
    include('../../config/database.php');
    $id = $_GET['id']; 

    $sql = $conexion->query("SELECT * FROM posts WHERE Id_posts = $id");
    $datos = $sql->fetch_object(); // Obtener el objeto antes del formulario
?>
<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar publicacion</title>
        <link rel="stylesheet" href="css/crear.css">
    </head>
    <body> 
        <div class="container">
            <div class="encabezado">
                <h1>Modificar publicación</h1>
            </div>

            <!-- Incluir el archivo de procesamiento -->
            <?php include('posts-guardar-modificado.php'); ?>

            <form action="#" name="crear_posts" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" name="usuario_posts" value="<?= htmlspecialchars($_SESSION['username']) ?>">

                <div class="contenedor-general">
                    <div class="izquierdo">
                        <h2>Configuración</h2>

                        <div class="categoria_div">
                            <label for="categoria">Categoría:</label>
                            <select name="categoria_posts" id="categoria" required>
                                <option value="" disabled hidden>Categorías</option>
                                <option value="crecimiento-economico" <?= $datos->category == 'crecimiento-economico' ? 'selected' : '' ?>>Crecimiento Económico</option>
                                <option value="emprendimiento-negocios" <?= $datos->category == 'emprendimiento-negocios' ? 'selected' : '' ?>>Emprendimiento Negocios</option>
                                <option value="mundo-laboral" <?= $datos->category == 'mundo-laboral' ? 'selected' : '' ?>>Mundo Laboral</option>
                            </select>
                        </div>

                        <div class="fecha_div">
                            <label for="fecha_publicacion">Fecha de Publicación:</label>
                            <input class="fecha" type="date" id="fecha_publicacion" name="fecha_publicacion_posts" value="<?= $datos->post_date ?>" required>
                        </div>

                        <div class="autor_div">
                            <?php if(isset($_SESSION['username'])): ?>
                                <label for="usuario">Usuario:</label>
                                <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="boton-div">
                            <button type="submit" name="modificar_post">Guardar Publicación</button>
                        </div>
                    </div>

                    <div class="derecho">
                        <div class="titulodelposts">
                            <label for="titulo">Título del post:</label>
                            <input type="text" id="titulo" name="titulo_posts" value="<?= htmlspecialchars($datos->title) ?>" required>
                        </div>
                        <div class="contenidodelposts">
                            <label for="contenido">Contenido:</label>
                            <textarea id="contenido" name="contenido_posts" rows="6" required><?= htmlspecialchars($datos->content) ?></textarea>
                        </div>
                        <div class="imagendelpost">
                            <label for="imagen">Imagen:</label>
                            <input type="file" id="imagen" name="imagen_posts" accept="image/*">
                            <p>Imagen actual:</p>
                            <img src="<?= htmlspecialchars($datos->image) ?>" alt="Imagen actual" width="150">
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Script para mostrar el popup -->
        <script>
            <?php if (isset($_SESSION['post_update_success']) && $_SESSION['post_update_success']) { ?>
                window.onload = function() {
                    alert('Publicación modificada');
                    window.location.href = 'posts-consulta.php'; // Redirigir a la página de consulta
                };
                <?php unset($_SESSION['post_update_success']); ?>  // Eliminar la bandera después de usarla
            <?php } 
            ?>
        </script>

    </body>
</html>