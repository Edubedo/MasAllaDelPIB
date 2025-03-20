<?php
include('../../config/database.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear publicación</title>
    <link rel="stylesheet" href="css/crear.css">
</head>
<body>
    <div class="container">
        <h1>Crear nueva publicación</h1>
        <form action="#" name="crear_posts" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo_posts" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select name="categoria_posts" id="categoria" required>
                    <option value="" disabled selected hidden>Categorías</option>
                    <option value="crecimiento-economico">Crecimiento Económico</option>
                    <option value="emprendimiento-negocios">Emprendimiento Negocios</option>
                    <option value="mundo-laboral">Mundo laboral</option>
                </select>
            </div>
            <div class="form-group">
                <label for="contenido">Contenido:</label>
                <textarea id="contenido" name="contenido_posts" rows="6" required></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen_posts" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="fecha_publicacion">Fecha de Publicación:</label>
                <input type="date" id="fecha_publicacion" name="fecha_publicacion_posts" required>
            </div>
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <select name="usuario_posts" id="usuario" required>
                    <option value="" disabled selected hidden>Usuario</option>
                    <option value="Pablo Alcalá">Pablo Alcalá</option>
                    <option value="Eduardo Escobedo">Eduardo Escobedo</option>
                    <option value="Daira Pamela">Daira Pamela</option>
                    <option value="Nancy">Nancy</option>
                    <option value="Quintero">Quintero</option>
                    <option value="Juan">Juan</option>
                </select>
            </div>
            <button type="submit" name="crear_post">Guardar Publicación</button>
        </form>
    </div>


<?php
if (isset($_POST["crear_post"])) {

    $titulo = $_POST['titulo_posts'];
    $categoria = $_POST['categoria_posts'];
    $contenido = $_POST['contenido_posts'];
    $fecha = $_POST['fecha_publicacion_posts'];
    $usuario = $_POST['usuario_posts'];

    // imagen
    $imagen_name = $_FILES['imagen_posts']['name'];
    $imagen_tmp_name = $_FILES['imagen_posts']['tmp_name'];
    $imagen_size = $_FILES['imagen_posts']['size'];
    $imagen_error = $_FILES['imagen_posts']['error'];
    
    // Aqui se guardara la imagen 
    $target_dir = 'uploads/';
    $target_file = $target_dir . basename($imagen_name);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


  

    // Movera la imagen al directorio de destino
    if (move_uploaded_file($imagen_tmp_name, $target_file)) {
        // Aqui inserta los datos a la base de datos 
        $query = "INSERT INTO posts (title, content, post_date, category, image, user_creation) 
                  VALUES ('$titulo', '$contenido', '$fecha', '$categoria', '$target_file', '$usuario')";

        if (mysqli_query($conexion, $query)) {
            header("Location: posts-consulta.php");
            exit();
        } else {
            echo "<p>Error al crear la publicación.</p>";
        }
    }
}

?>

</body>
</html>
