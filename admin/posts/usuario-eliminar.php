<?php
include '../../config/database.php';
$mensaje = ''; 

if (!empty($_GET["id"])) {
    $id = $_GET["id"];
    $sql = $conexion->query("DELETE FROM users WHERE iduser = $id");

    if ($sql) {
        $mensaje = "<div>Usuario eliminado correctamente.</div>";
    } else {
        $mensaje = "<div>Error al eliminar el usuario.</div>";
    }
}
?>
