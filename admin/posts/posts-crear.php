<?php
session_start();
include '../../config/database.php';

// Verificar si GD está habilitado
function isGDExtensionAvailable()
{
    return extension_loaded('gd');
}

function comprimirImagen($rutaOriginal, $rutaDestino, $maxAncho = 900, $calidad = 85)
{
    if (!extension_loaded('gd')) {
        error_log("La extensión GD no está habilitada.");
        return false;
    }

    $info = getimagesize($rutaOriginal);
    if (!$info) {
        error_log("No se pudo obtener información de la imagen: $rutaOriginal");
        return false;
    }

    $tipo = $info['mime'];
    $ancho = $info[0];
    $alto = $info[1];

    if ($ancho <= 0 || $alto <= 0) {
        error_log("Dimensiones inválidas para la imagen: $rutaOriginal");
        return false;
    }

    // Crear la imagen desde el archivo original
    switch ($tipo) {
        case 'image/jpeg':
        case 'image/jpg':
            $imagen = @imagecreatefromjpeg($rutaOriginal);
            break;
        case 'image/png':
            $imagen = @imagecreatefrompng($rutaOriginal);
            break;
        case 'image/webp':
            $imagen = @imagecreatefromwebp($rutaOriginal);
            break;
        default:
            error_log("Formato de imagen no soportado: $tipo");
            return false;
    }

    if (!$imagen) {
        error_log("No se pudo crear la imagen desde el archivo: $rutaOriginal");
        return false;
    }

    // Redimensionar si es necesario
    $nuevaImagen = $imagen;
    if ($ancho > $maxAncho) {
        $nuevoAncho = $maxAncho;
        $nuevoAlto = max(1, (int)(($maxAncho / $ancho) * $alto));
        $nuevaImagen = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

        // Mantener transparencia para PNG y WebP
        if ($tipo == 'image/png' || $tipo == 'image/webp') {
            imagealphablending($nuevaImagen, false);
            imagesavealpha($nuevaImagen, true);
        }

        imagecopyresampled($nuevaImagen, $imagen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
        imagedestroy($imagen); // Liberar la imagen original

    }

    // Guardar la imagen en formato WebP
    $resultado = imagewebp($nuevaImagen, $rutaDestino, $calidad); // calidad 0-100

    // Liberar recursos
    imagedestroy($nuevaImagen);

    if (!$resultado) {
        error_log("No se pudo guardar la imagen comprimida en: $rutaDestino");
        return false;
    }

    return true;
}


if (isset($_POST["crear_post"])) {
    $titulo = trim($_POST['titulo_posts']);
    $contenido = trim($_POST['contenido_posts']);
    $referenciasArray = $_POST['referencias_post'];
    $referencias = implode("\n", array_map('trim', $referenciasArray));
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

    $ruta_temp = $target_dir . "temp_" . basename($imagen_name);
    if (!move_uploaded_file($imagen_tmp_name, $ruta_temp)) {
        echo "<p style='color:red;'>Error al mover la imagen subida.</p>";
        exit();
    }

    $filename = pathinfo($imagen_name, PATHINFO_FILENAME) . ".webp";
    $ruta_final = $target_dir . $filename;

    // Si GD está habilitado, comprime la imagen. Si no, simplemente mueve la imagen
    if (isGDExtensionAvailable()) {
        if (comprimirImagen($ruta_temp, $ruta_final, 1200, 90)) {
            unlink($ruta_temp);
        } else {
            echo "<p style='color:red;'>Error al comprimir la imagen.</p>";
            unlink($ruta_temp);
            exit();
        }
    } else {
        // Si GD no está disponible, solo mover la imagen sin comprimir
        if (!rename($ruta_temp, $ruta_final)) {
            echo "<p style='color:red;'>Error al mover la imagen.</p>";
            exit();
        }
    }

    // Guardar solo la ruta relativa dentro de la carpeta uploads
    $db_path = "uploads/" . $filename;

    $query = "INSERT INTO posts (title, content, post_date, category, image, user_creation, referencia_posts, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $status = 'ACTIVO';
    $stmt->bind_param("ssssssss", $titulo, $contenido, $fecha, $categoria, $db_path, $usuario, $referencias, $status);
    if ($stmt->execute()) {
        header("Location: posts-consulta.php");
        exit();
    } else {
        echo "<p style='color:red;'>Error al crear la publicación.</p>";
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
    <script src="/js/language.js"></script>
    <script src="/js/translations.js"></script>
    <link rel="stylesheet" href="../../views/css/navbar.css">

</head>

<body>
    <?php
    include('../../views/layout/header.php')
    ?>

    <div class="encabezado">
        <h1>Crear nueva publicación</h1>
    </div>

    <form id="crearForm" action="" method="post" enctype="multipart/form-data">
        <div class="contenedor-general">
            <div class="izquierdo">
                <h2><i class="fas fa-cog"></i>Configuración</h2>
                <div class="categoria_div">
                    <label for="categoria" class="texto-a-traducir">Categoría:</label>
                    <select name="categoria_posts" id="categoria" class="texto-a-traducir" required>
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
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <label for="referencias">Referencias:</label>
                        <p style="margin: 0;">(Escribe un link por linea)</p>
                    </div>
                    <div id="contenedorReferencias">
                        <input class="input-referencia" type="text" name="referencias_post[]" placeholder="Escribe una referencia" required>
                    </div>
                    <button class="boton-agregar-referencia" type="button" onclick="agregarReferencia()">Agregar otra referencia</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal de alerta -->
    <div id="modal" class="modal" style="display: none;">
        <div class="modal-contenido">
            <p id="alert-message">Mensaje de alerta</p>
            <button onclick="cerrarAlerta()">Aceptar</button>
        </div>
    </div>

    <!-- Vista previa de imagen -->
    <div class="preview-container" style="display: none;">
        <img id="preview-imagen" style="max-width: 100%; max-height: 200px;">
    </div>

    <!-- JS para validaciones y referencias -->
    <script src="../../js/posts-crear.js"></script>
</body>

</html>
<!-- #region -->