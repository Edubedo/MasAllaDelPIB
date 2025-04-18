<?php
session_start();
include '../../config/database.php';

if (isset($_POST["crear_post"])) {
    $titulo = $_POST['titulo_posts'];
    $contenido = $_POST['contenido_posts'];
    $referenciasArray = $_POST['referencias_post'];
    $referencias = implode("\n", $referenciasArray);
    $categoria = $_POST['categoria_posts'];
    $fecha = $_POST['fecha_publicacion_posts'];
    $usuario = $_SESSION['username'];  

    if (strlen($titulo) < 10) {
        // Mostrar error en el modal de alerta y no redirigir
        echo "<script>mostrarAlerta('Título inválido. Debe tener al menos 10 caracteres.');</script>";
        exit();
    }

    if (strlen($contenido) < 20) {
        // Mostrar error en el modal de alerta y no redirigir
        echo "<script>mostrarAlerta('Contenido inválido. Debe tener al menos 20 caracteres.');</script>";
        exit();
    }

    if (strlen($referencias) < 10) {
        // Mostrar error en el modal de alerta y no redirigir
        echo "<script>mostrarAlerta('Referencia invalida.');</script>";
        exit();
    }

    $imagen_name = $_FILES['imagen_posts']['name'];
    $imagen_tmp_name = $_FILES['imagen_posts']['tmp_name'];
    $target_dir = 'uploads/';
    $target_file = $target_dir . basename($imagen_name);

    if (move_uploaded_file($imagen_tmp_name, $target_file)) {
        $query = "INSERT INTO posts (title, content, post_date, category, image, user_creation, referencia_posts) 
                  VALUES ('$titulo', '$contenido', '$fecha', '$categoria', '$target_file', '$usuario', '$referencias')";

        if (mysqli_query($conexion, $query)) {
            // Redirigir a la página de consulta
            header("Location: posts-consulta.php");
            exit();
        } else {
            echo "<p style='color:red;'>Error al crear la publicación.</p>";
        }
    } else {
        echo "<p style='color:red'>Error al subir la imagen.</p>";
    }
}
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
        <div class="encabezado">
            <h1>Crear nueva publicación</h1>
        </div>
        
        <form id="crearForm" action="#" name="crear_posts" method="post" enctype="multipart/form-data">
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
                        <input class="fecha" type="text" id="fecha_publicacion" name="fecha_publicacion_posts" readonly>
                    </div>

                    <div class="autor_div">
                        <?php if(isset($_SESSION['username'])): ?>
                            <label for="usuario">Usuario:</label>
                            <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="boton-div">
                        <a href="posts-consulta.php" class="btn-editar-publicacion">Regresar</a>
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
                    <div class="referenciadelpost">
                        <label for="referencias">Referencias:</label>
                        <div id="contenedorReferencias">
                            <input class="input-referencia" type="text" name="referencias_post[]" placeholder="Escribe una referencia" required>
                        </div>
                        <button class="boton-agregar-referencia" type="button" onclick="agregarReferencia()">Agregar otra referencia</button>
                    </div>
                </div>
            </div>
        </form>
        
        <script src="../../js/posts-crear.js"></script>


    </body>
</html>
