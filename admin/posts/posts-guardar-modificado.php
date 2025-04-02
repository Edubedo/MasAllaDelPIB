<?php
include('../../config/database.php');

if(!empty($_POST["modificar_post"])) {
    if(!empty($_POST["titulo_posts"]) && !empty($_POST["categoria_posts"]) && !empty($_POST["contenido_posts"])) {

        $titulo = $_POST["titulo_posts"];
        $id = $_POST["id"];
        $categoria = $_POST["categoria_posts"];
        $contenido = $_POST["contenido_posts"];
        $usuario = $_SESSION['username'];
        $imagen_actual = $_POST["imagen_actual"];

        // Manejo de la imagen
        if (!empty($_FILES["imagen_posts"]["name"])) {
            $imagen_nombre = time() . "_" . basename($_FILES["imagen_posts"]["name"]);
            $ruta_destino = "../../uploads/" . $imagen_nombre;

            if (move_uploaded_file($_FILES["imagen_posts"]["tmp_name"], $ruta_destino)) {
                $nueva_imagen = $ruta_destino;
            } else {
                echo "<div>Error al subir la imagen.</div>";
                exit();
            }
        } else {
            // Si no se sube una nueva imagen, mantener la imagen actual
            $nueva_imagen = $imagen_actual;
        }

        // Actualizar en la base de datos
        $sql = $conexion->query("UPDATE posts SET title = '$titulo', category = '$categoria', content = '$contenido', image = '$nueva_imagen' WHERE Id_posts = $id");

        if ($sql) {
            $_SESSION['post_update_success'] = true;
            // Si se modificó correctamente, mostrar mensaje de éxito
            header("Location: posts-modificar.php?id=$id&success=1");
            exit(); // Detener el script aquí para evitar que se siga ejecutando
        } else {
            echo "Error al modificar la publicación.";
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }

}

?>