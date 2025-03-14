<?php
// Ruta del archivo JSON
$jsonFile = '../../data/posts.json';

// Verifica si se enviaron los datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtiene los datos del formulario
    $titulo = $_POST['titulo'];
    $categoria = $_POST['categoria'];
    $contenido = $_POST['contenido'];
    $usuario = $_POST['usuario'];
    $fecha = $_POST['fecha_publicacion'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Verificar si se subió un archivo correctamente
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $tmpDir = '../../tmp/'; // Ruta de la carpeta donde se guardarán las imágenes
    
            // Obtener el nombre original del archivo
            $imagenNombre = time() . '_' . basename($_FILES['imagen']['name']);
            $imagenRuta = $tmpDir . $imagenNombre;
    
            // Mover la imagen a la carpeta tmp/
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $imagenRuta)) {
                echo "Imagen guardada en: " . $imagenRuta;
            } else {
                echo "Error al mover la imagen.";
            }
        } else {
            echo "No se subió ninguna imagen o hubo un error.";
        }
    }

    // Leer JSON existente
    $jsonData = file_get_contents($jsonFile);
    $publicaciones = json_decode($jsonData, true);

    // Crear un nuevo ID basado en el último elemento
    $nuevoId = end($publicaciones)['id'] + 1;
    $nuevoIdLink = end($publicaciones)['id'] + 5;

    // Crear la nueva publicación
    $nuevaPublicacion = [
        "id" => $nuevoId,
        "title" => $titulo,
        "description" => substr($contenido, 0, 100) . "...", // Resumen corto
        "image" => $imagenRuta,
        "link" => "./post.php?id=" . $nuevoId,
        "content" => $contenido,
        "category" => $categoria,
        "user" => $usuario,
        "date" => $fecha
    ];

    // Agregar la nueva publicación al array
    $publicaciones[] = $nuevaPublicacion;

    // Guardar de nuevo en el JSON
    file_put_contents($jsonFile, json_encode($publicaciones, JSON_PRETTY_PRINT));

    // Redirigir a la página principal (o donde necesites)
    header("Location: /index.php");
    exit;
}
?>
