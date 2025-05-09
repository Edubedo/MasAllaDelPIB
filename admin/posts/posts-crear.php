<?php
session_start();
include '../../config/database.php';

//funcion para comprimir imagen
function comprimirImagen($rutaOriginal, $rutaDestinoSinExtension, $maxAncho = 1200, $calidad = 90, $formatoDestino = 'webp')
{
    $info = getimagesize($rutaOriginal);
    if (!$info) return false;

    $mime = $info['mime'];
    $ancho = $info[0];
    $alto = $info[1];

    if ($ancho <= 0 || $alto <= 0) return false;

    // Cargar imagen según su formato original
    switch ($mime) {
        case 'image/jpeg':
            $imagen = imagecreatefromjpeg($rutaOriginal);
            break;
        case 'image/png':
            $imagen = imagecreatefrompng($rutaOriginal);
            imagealphablending($imagen, false);
            imagesavealpha($imagen, true);
            break;
        case 'image/webp':
            $imagen = imagecreatefromwebp($rutaOriginal);
            imagealphablending($imagen, false);
            imagesavealpha($imagen, true);
            break;
        case 'image/avif':
            if (!function_exists('imagecreatefromavif')) return false;
            $imagen = imagecreatefromavif($rutaOriginal);
            imagealphablending($imagen, false);
            imagesavealpha($imagen, true);
            break;
        default:
            return false;
    }

    // Redimensionar si es necesario
    if ($ancho > $maxAncho) {
        $nuevoAncho = $maxAncho;
        $nuevoAlto = max(1, (int)(($maxAncho / $ancho) * $alto));
        $nuevaImagen = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

        if ($formatoDestino === 'png' || $formatoDestino === 'webp' || $formatoDestino === 'avif') {
            imagealphablending($nuevaImagen, false);
            imagesavealpha($nuevaImagen, true);
        }

        imagecopyresampled($nuevaImagen, $imagen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
    } else {
        $nuevaImagen = $imagen;
    }

    // Ruta con nueva extensión
    $rutaFinal = $rutaDestinoSinExtension . '.' . $formatoDestino;

    // Guardar según formato destino
    switch ($formatoDestino) {
        case 'webp':
            imagewebp($nuevaImagen, $rutaFinal, $calidad);
            break;
        case 'avif':
            if (!function_exists('imageavif')) return false;
            imageavif($nuevaImagen, $rutaFinal, $calidad);
            break;
        default:
            return false;
    }

    imagedestroy($imagen);
    imagedestroy($nuevaImagen);

    return $rutaFinal;
}


if (isset($_POST["crear_post"])) {
    $titulo = $_POST['titulo_posts'];
    $contenido = $_POST['contenido_posts'];
    $referenciasArray = $_POST['referencias_post'];
    $referencias = implode("\n", $referenciasArray);
    $categoria = $_POST['categoria_posts'];
    $fecha = $_POST['fecha_publicacion_posts'];
    $usuario = $_SESSION['username'];

    if (strlen($titulo) < 10) {
        echo "<script>mostrarAlerta('Título inválido. Debe tener al menos 10 caracteres.');</script>";
        exit();
    }

    if (strlen($contenido) < 20) {
        echo "<script>mostrarAlerta('Contenido inválido. Debe tener al menos 20 caracteres.');</script>";
        exit();
    }

    if (strlen($referencias) < 10) {
        echo "<script>mostrarAlerta('Referencia inválida.');</script>";
        exit();
    }

    $imagen_name = $_FILES['imagen_posts']['name'];
    $imagen_tmp_name = $_FILES['imagen_posts']['tmp_name'];
    $target_dir = 'uploads/';

    // Mover el archivo a una ruta temporal antes de manipularlo
    $ruta_temp = $target_dir . "temp_" . basename($imagen_name);
    if (!move_uploaded_file($imagen_tmp_name, $ruta_temp)) {
        echo "<p style='color:red;'>Error al mover la imagen subida.</p>";
        exit();
    }

    $ruta_final = $target_dir . "comprimida_" . basename($imagen_name);

    if (comprimirImagen($ruta_temp, $ruta_final, 1200, 90)) {
        unlink($ruta_temp); // Limpieza del temporal

        $query = "INSERT INTO posts (title, content, post_date, category, image, user_creation, referencia_posts) 
                  VALUES ('$titulo', '$contenido', '$fecha', '$categoria', '$ruta_final', '$usuario', '$referencias')";

        if (mysqli_query($conexion, $query)) {
            header("Location: posts-consulta.php");
            exit();
        } else {
            echo "<p style='color:red;'>Error al crear la publicación.</p>";
        }
    } else {
        echo "<p style='color:red;'>Error al comprimir la imagen.</p>";
        unlink($ruta_temp);
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear publicación</title>

    <link rel="icon" href="../../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/crear.css">
</head>

<body>
    <div class="encabezado">
        <h1>Crear nueva publicación</h1>
    </div>

    <form id="crearForm" action="" method="post" enctype="multipart/form-data">
        <div class="contenedor-general">

            <div class="izquierdo">

                <h2>Configuracion</h2>

                <div class="categoria_div">
                    <label for="categoria">Categoría:</label>
                    <select name="categoria_posts" id="categoria" required>
                        <option value="" disabled selected hidden>Categorías</option>
                        <option value="crecimiento-economico">Crecimiento Económico</option>
                        <option value="emprendimiento-negocios">Emprendimiento Y Negocios</option>
                        <option value="mundo-laboral">Mundo laboral</option>
                    </select>
                </div>

                <div class="fecha_div">
                    <label for="fecha_publicacion">Fecha de Publicación:</label>
                    <input class="fecha" type="text" id="fecha_publicacion" name="fecha_publicacion_posts" readonly>
                </div>

                <div class="autor_div">
                    <?php if (isset($_SESSION['username'])): ?>
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

    <!-- Modal de alerta -->
    <div id="modal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000;">
        <div class="modal-contenido" style="background: white; padding: 20px; border-radius: 5px; max-width: 400px; text-align: center;">
            <p id="alert-message">Mensaje de alerta</p>
            <button onclick="cerrarAlerta()" style="padding: 8px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Aceptar</button>
        </div>
    </div>

    <!-- Previsualización de imagen -->
    <div class="preview-container" style="display: none; margin-top: 10px; text-align: center;">
        <img id="preview-imagen" style="max-width: 100%; max-height: 200px;">
    </div>

    <script src="../../js/posts-crear.js"></script>

</body>

</html>