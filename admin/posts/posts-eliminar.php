<?php
$mensaje = '';

// Solo eliminar si hay un ID y NO hay una acción especificada
if (!empty($_GET["id"]) && !isset($_GET["action"])) {
    $id = $_GET["id"];

    if (is_numeric($id)) {
        $stmt = $conexion->prepare("DELETE FROM posts WHERE Id_posts = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $mensaje = "<div class='success'>Post eliminado correctamente.</div>";
        } else {
            $mensaje = "<div class='error'>Error al eliminar el post.</div>";
        }

        $stmt->close();
    } else {
        $mensaje = "<div class='error'>ID inválido.</div>";
    }
}
