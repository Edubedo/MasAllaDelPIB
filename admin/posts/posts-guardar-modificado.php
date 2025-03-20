<?php
if(!empty($_POST["modificar_post"])) {
    if(!empty($_POST["titulo_posts"]) and !empty($_POST["categoria_posts"]) and !empty($_POST["contenido_posts"]) and !empty($_POST["imagen_posts"]) and !empty($_POST["usuario_posts"])) {

        $titulo = $_POST["titulo_posts"];
        $id = $_POST["id"];
        $categoria = $_POST["categoria_posts"];
        $contenido = $_POST["contenido_posts"];
        $imagen = $_POST["imagen_posts"];
        $usuario = $_POST["usuario_posts"];

        $sql = $conexion->query("UPDATE posts SET title = '$titulo', category = '$categoria', content = '$contenido', image = '$imagen', user_creation = '$usuario' WHERE Id_posts = $id");

        if($sql) {
            header("Location: posts-consulta.php"); 
            exit();
        } else {
            echo "<div>Error al modificar la publicación.</div>";
        }
    } else {
        echo "<div>Todos los campos son obligatorios.</div>";
    }
}
?>
