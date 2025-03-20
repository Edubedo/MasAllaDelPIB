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
        <h1>Crear nueva publicación</h1>

        <!-- Agregar campo oculto para pasar el ID -->
        <input type="hidden" name="id" value="<?= $_GET['id'] ?>">

        <!-- Incluir el archivo de procesamiento -->
        <?php
        include('posts-guardar-modificado.php');
        while($datos = $sql ->fetch_object()){  
        ?>

            <form action="#" name="crear_posts" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo_posts" value="<?= $datos->title ?>"  required>
                </div>

                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select name="categoria_posts" id="categoria" required>
                        <option value="crecimiento-economico" <?= ($datos->category == 'crecimiento-economico') ? 'selected' : '' ?>>Crecimiento Económico</option>
                        <option value="emprendimiento-negocios" <?= ($datos->category == 'emprendimiento-negocios') ? 'selected' : '' ?>>Emprendimiento Negocios</option>
                        <option value="mundo-laboral" <?= ($datos->category == 'mundo-laboral') ? 'selected' : '' ?>>Mundo laboral</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="contenido">Contenido:</label>
                    <textarea id="contenido" name="contenido_posts" rows="6" required><?= $datos->content ?></textarea>
                </div>

                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" id="imagen" name="imagen_posts" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="fecha_publicacion">Fecha de Publicación:</label>
                    <input type="date" id="fecha_publicacion" name="fecha_publicacion_posts" value="<?= $datos->post_date ?>" required>
                </div>

                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <select name="usuario_posts" id="usuario" required>
                        <option value="Pablo Alcalá" <?= ($datos->user_creation == 'Pablo Alcalá') ? 'selected' : '' ?>>Pablo Alcalá</option>
                        <option value="Eduardo Escobedo" <?= ($datos->user_creation == 'Eduardo Escobedo') ? 'selected' : '' ?>>Eduardo Escobedo</option>
                        <option value="Daira Pamela" <?= ($datos->user_creation == 'Daira Pamela') ? 'selected' : '' ?>>Daira Pamela</option>
                        <option value="Nancy" <?= ($datos->user_creation == 'Nancy') ? 'selected' : '' ?>>Nancy</option>
                        <option value="Quintero" <?= ($datos->user_creation == 'Quintero') ? 'selected' : '' ?>>Quintero</option>
                        <option value="Juan" <?= ($datos->user_creation == 'Juan') ? 'selected' : '' ?>>Juan</option>
                    </select>
                </div>

                <button type="submit" name="modificar_post">Modificar Publicación</button>
            </form>
        <?php
        }
        ?>
    </div>
</body>
</html>



