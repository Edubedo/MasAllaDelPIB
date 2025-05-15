<?php
session_start();
include('../../config/database.php');


$id = $_GET['id']; // Obtener el ID de la publicación

// Obtener los datos de la publicación actual
$sql = $conexion->query("SELECT * FROM posts WHERE Id_posts = $id");
$datos = $sql->fetch_object();

//funcion para comprimir imagen
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

    // Procesar dependiendo del tipo de imagen
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

    // Asegurarse de que el archivo de destino tenga la extensión .webp
    $rutaDestino = preg_replace('/\.[a-zA-Z]+$/', '.webp', $rutaDestino);

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updates = [];

    // Verificar y actualizar el título
    if (!empty($_POST['titulo_posts'])) {
        $new_title = $conexion->real_escape_string($_POST['titulo_posts']);
        $updates[] = "title = '$new_title'";
    }

    // Verificar y actualizar el contenido
    if (!empty($_POST['contenido_posts'])) {
        $new_content = $conexion->real_escape_string($_POST['contenido_posts']);
        $updates[] = "content = '$new_content'";
    }

    // Verificar y actualizar las referencias (cambiado para manejar array)
    if (!empty($_POST['referencias_post'])) {
        $referenciasArray = $_POST['referencias_post'];
        $referenciasFiltradas = array_filter($referenciasArray, function ($ref) {
            return trim($ref) !== '';
        });
        $new_references = implode("\n", $referenciasFiltradas);
        $updates[] = "referencia_posts = '" . $conexion->real_escape_string($new_references) . "'";
    }

    // Verificar y actualizar la categoría
    if (!empty($_POST['categoria_posts'])) {
        $new_category = $conexion->real_escape_string($_POST['categoria_posts']);
        $updates[] = "category = '$new_category'";
    }

    // Verificar y actualizar la fecha de publicación
    if (!empty($_POST['fecha_publicacion_posts'])) {
        $new_date = $conexion->real_escape_string($_POST['fecha_publicacion_posts']);
        $updates[] = "post_date = '$new_date'";
    }

    // Verificar y actualizar la imagen si se ha subido una nueva
    if (!empty($_FILES['imagen_posts']['name'])) {
        $imagen_nombre = $_FILES['imagen_posts']['name'];
        $imagen_temp = $_FILES['imagen_posts']['tmp_name'];
        $filename = pathinfo($imagen_nombre, PATHINFO_FILENAME) . ".webp";
        $ruta_final = "uploads/" . $filename;
        $db_path = "uploads/" . $filename;

        // Comprimir y guardar la imagen
        if (comprimirImagen($imagen_temp, $ruta_final, 1200, 95)) {
            // Opcional: eliminar imagen anterior si existe y es diferente
            if (!empty($datos->image) && file_exists($datos->image) && $datos->image !== $db_path) {
                unlink($datos->image);
            }

            $updates[] = "image = '$db_path'";
        } else {
            echo "<p style='color:red'>Error al comprimir la imagen.</p>";
        }
    }

    // Si hay cambios, realizar la actualización en la base de datos
    if (count($updates) > 0) {
        $sql_update = "UPDATE posts SET " . implode(", ", $updates) . " WHERE Id_posts = $id";
        if ($conexion->query($sql_update)) {
            // Establecer un mensaje de éxito
            $_SESSION['success_message'] = "Publicación actualizada con éxito";
            header("Location: posts-modificar.php?id=" . $id);
            exit();
        } else {
            echo "Error al actualizar la publicación.";
        }
    } else {
        echo "No se realizaron cambios.";
    }
}
?>
<!DOCTYPE html>

<html lang="es">
<<<<<<< HEAD
=======
    <head>
        <meta charset="UTF-8">
        <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar publicacion</title>
        <link rel="stylesheet" href="css/crear.css">
    </head>
    <body> 
        <div class="container">
            <div class="encabezado">
                <h1>Modificar publicación</h1>
            </div>
>>>>>>> 22ff6e84cf9d8eafb715da2bfaf05dfae865dea9

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

        <!-- Mostrar el mensaje de éxito si está disponible -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']); // Eliminar el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>

        <form id="modificarForm" action="" name="modificar_post" method="post" enctype="multipart/form-data">
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
                            <option value="emprendimiento-negocios" <?= $datos->category == 'emprendimiento-negocios' ? 'selected' : '' ?>>Emprendimiento Y Negocios</option>
                            <option value="mundo-laboral" <?= $datos->category == 'mundo-laboral' ? 'selected' : '' ?>>Mundo Laboral</option>
                        </select>
                    </div>

                    <div class="fecha_div">
                        <label for="fecha_publicacion">Fecha de Publicación:</label>
                        <input class="fecha" type="date" id="fecha_publicacion" name="fecha_publicacion_posts" value="<?= $datos->post_date ?>" required>
                    </div>

                    <div class="autor_div">
                        <?php if (isset($_SESSION['username'])): ?>
                            <label for="usuario">Usuario:</label>
                            <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="boton-div">
                        <a href="posts-consulta.php" class="btn-editar-publicacion">Regresar</a>
                        <button type="submit" name="modificar_post">Modificar Publicación</button>
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
                        <label for="imagen_actual">Imagen actual:</label>
                        <img class="imagenActual" src="<?= htmlspecialchars($datos->image) ?>" alt="Imagen actual" width="150">
                    </div>
                    <div class="referenciadelpost">
                        <label for="referencias">Referencias:</label>
                        <div id="contenedorReferencias" data-referencias="<?= htmlspecialchars($datos->referencia_posts) ?>">
                            <!-- Los inputs se agregarán dinámicamente aquí -->
                        </div>
                        <button class="boton-agregar-referencia" type="button" onclick="agregarReferencia()">Agregar otra referencia</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal de ALERTA NO BORRAR -->
    <div id="modal" class="fondo-alerta" style="display: none;">
        <div class="alerta">
            <p id="alert-message"></p>
            <button class="boton-alerta" onclick="cerrarAlerta()">Aceptar</button>
        </div>
    </div>
    <!-- Modal de ALERTA NO BORRAR -->

    <!-- Modal de confirmación -->
    <div id="modal-modificar" class="fondo-alerta-modificar" style="display: none;">
        <div class="alerta-modificar">
            <p id="alert-message-modificar"></p>
            <button class="boton-alerta-modificar" onclick="aceptarEnvio()">Aceptar</button>
            <button class="boton-alerta-cancelar" onclick="cerrarAlerta()">Cancelar</button>
        </div>
    </div>


    <script src="../../js/post-modificar.js"></script>
</body>

</html>