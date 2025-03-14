<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear publicacion</title>
    
    
    <link rel="stylesheet" href="css/crear.css">
    
</head>
<body>
    
    

    <div class="container">
            <h1>Editar publicación</h1>
            <?php
                        $id = $_GET['id']; // Obtener el id de la publicación
                        $posts = json_decode(file_get_contents('../../data/posts.json'), true); // Obtener los posts
                        $post = null; // Variable para almacenar el post encontrado

                        foreach ($posts as $p) {
                            if ($p['id'] == $id) { // Buscar el post con el ID correspondiente
                                $post = $p;
                                break;
                            }
                        }
                    ?>    
            <form action="posts-guardar.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" value="<?php echo $post['title']; ?>" name="titulo" required>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select class="categorias" id="">
                
                        <option value="" disabled selected hidden><?php echo ucwords(str_replace('-', ' ', $post['category'])); ?></option><!-- Placeholder -->
                        <option value="">Crecimiento económico</option>
                        <option value="">Emprendimiento Negocios</option>
                        <option value="">Sostenibilidad</option>
                        <option value="">Infraestructura</option>
                        <option value="">Educación</option>
                    </select>
                    
                </div>
                <div class="form-group">
                    <label for="contenido">Contenido:</label>
                    <?php
                        // Verificar si el post existe y tiene una descripción
                        if (isset($post['description'])) {
                            $descripcion = htmlspecialchars($post['description']); // Escapar caracteres especiales
                        } else {
                            $descripcion = ''; // Valor por defecto si no hay descripción
                        }
                        ?>
                    <textarea id="contenido" name="contenido" rows="6" required><?php echo $descripcion; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="fecha_publicacion">Fecha de Publicación:</label>
                    <input type="date" id="fecha_publicacion" name="fecha_publicacion" value=<?php echo $post['date']; ?> required>
                </div>
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <select class="categorias" id="">
                        <option value="" disabled selected hidden><?php echo $post['user'];?></option><!-- Placeholder -->
                        <option value="Pablo Alcalá" <?php echo ($post['user'] == 'Pablo Alcalá') ? 'selected' : ''; ?>>Pablo Alcalá</option>
                        <option value="Eduardo Escobedo" <?php echo ($post['user'] == 'Eduardo Escobedo') ? 'selected' : ''; ?>>Eduardo Escobedo</option>
                        <option value="Daira Pamela" <?php echo ($post['user'] == 'Daira Pamela') ? 'selected' : ''; ?>>Daira Pamela</option>
                        <option value="Nancy" <?php echo ($post['user'] == 'Nancy') ? 'selected' : ''; ?>>Nancy</option>
                        <option value="Quintero" <?php echo ($post['user'] == 'Quintero') ? 'selected' : ''; ?>>Quintero</option>
                        <option value="Juan" <?php echo ($post['user'] == 'Juan') ? 'selected' : ''; ?>>Juan</option>
                    </select>
                </div>
                <button type="submit">Editar Publicación</button>
            </form>
    </div>


</body>
</html>
