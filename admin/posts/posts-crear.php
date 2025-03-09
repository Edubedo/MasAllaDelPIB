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
            <h1>Crear nueva publicación</h1>
            <form action="posts-guardar.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select class="categorias" id="">
                        <option value="" disabled selected hidden>Categorías</option><!-- Placeholder -->
                        <option value="">Crecimiento económico</option>
                        <option value="">Emprendimiento Negocios</option>
                        <option value="">Sostenibilidad</option>
                        <option value="">Infraestructura</option>
                        <option value="">Educación</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contenido">Contenido:</label>
                    <textarea id="contenido" name="contenido" rows="6" required></textarea>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="fecha_publicacion">Fecha de Publicación:</label>
                    <input type="date" id="fecha_publicacion" name="fecha_publicacion" required>
                </div>
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <select class="categorias" id="">
                        <option value="" disabled selected hidden>Usuario</option><!-- Placeholder -->
                        <option value="">Pablo Alcalá</option>
                        <option value="">Eduardo Escobedo</option>
                        <option value="">Daira Pamela</option>
                        <option value="">Nancy</option>
                        <option value="">Quintero</option>
                        <option value="">Juan</option>
                    </select>
                </div>
                <button type="submit">Guardar Publicación</button>
            </form>
    </div>


</body>
</html>
