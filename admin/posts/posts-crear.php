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
                    <select name="categoria" id="categoria" required>
                        <option value="" disabled selected hidden>Categorías</option>
                        <option value="crecimiento-economico">Crecimiento Económico</option>
                        <option value="emprendimiento-negocios">Emprendimiento Negocios</option>
                        <option value="mundo-laboral">Mundo laboral</option>
                        
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
                    <select name="usuario" id="usuario" required>
                        <option value="" disabled selected hidden>Usuario</option>
                        <option value="Pablo Alcalá">Pablo Alcalá</option>
                        <option value="Eduardo Escobedo">Eduardo Escobedo</option>
                        <option value="Daira Pamela">Daira Pamela</option>
                        <option value="Nancy">Nancy</option>
                        <option value="Quintero">Quintero</option>
                        <option value="Juan">Juan</option>
                    </select>
                </div>
                <button type="submit">Guardar Publicación</button>
            </form>
    </div>


</body>
</html>
