<?php
include '../../config/database.php';
$mensaje = ''; 

if (!empty($_GET["id"])) {
    $id = $_GET["id"];
    $sql = $conexion->query("DELETE FROM posts WHERE Id_posts = $id");

    if ($sql) {
        $mensaje = "<div>Post eliminado correctamente.</div>";
    } else {
        $mensaje = "<div>Error al eliminar el post.</div>";
    }
}
?>
