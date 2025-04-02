<?php 
    include('../../config/database.php');
    $id = $_GET['id']; 

    $sql = $conexion->query("SELECT * FROM posts WHERE Id_posts = $id");
?>
<!DOCTYPE html>

<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar datos</title>
        <link rel="stylesheet" href="css/crear.css">
    </head>
    <body> 
        <div class="container">
            <div class="encabezado">
                <h1>Modificar publicación</h1>
            </div>

            <!-- Agregar campo oculto para pasar el ID -->
            <input type="hidden" name="id" value="<?= $_GET['id'] ?>">

            <!-- Incluir el archivo de procesamiento -->
            <?php
            include('posts-guardar-modificado.php');
            while($datos = $sql ->fetch_object()){  
            ?>

            <form action="#" name="crear_posts" method="post" enctype="multipart/form-data">
                <div class="contenedor-general">

                <div class="izquierdo">

                    <h2>Configuracion</h2>

                    <div class="categoria_div">
                        <label for="categoria">Categoría:</label>
                        <select name="categoria_posts" id="categoria" required>
                            <option value="" disabled selected hidden>Categorías</option>
                            <option value="crecimiento-economico">Crecimiento Económico</option>
                            <option value="emprendimiento-negocios">Emprendimiento Negocios</option>
                            <option value="mundo-laboral">Mundo laboral</option>
                        </select>
                    </div>

                    <div class="fecha_div">
                        <label for="fecha_publicacion">Fecha de Publicación:</label>
                        <input class="fecha" type="date" id="fecha_publicacion" name="fecha_publicacion_posts" required>
                    </div>

                    <div class="autor_div">
                        <?php if(isset($_SESSION['username'])): ?>
                            <label for="usuario">Usuario:</label>
                            <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="boton-div">
                        <button type="submit" name="crear_post">Guardar Publicación</button>
                    </div>
                    
                </div>

                <div class="derecho">
                    <div class="titulodelposts">
                        <label for="titulo">Título del post:</label>
                        <input type="text" id="titulo" name="titulo_posts" required>
                    </div>
                    <div class="contenidodelposts">
                        <label for="contenido">Contenido:</label>
                        <textarea id="contenido" name="contenido_posts" rows="6" required></textarea>
                    </div>
                    <div class="imagendelpost">
                        <label for="imagen">Imagen:</label>
                        <input type="file" id="imagen" name="imagen_posts" accept="image/*" required>
                    </div>
                </div>
            </div>
                    
            </form>

            <?php
            }
            ?>
        </div>
    </body>
</html>



