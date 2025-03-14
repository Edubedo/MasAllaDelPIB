<?php
// Obtén el ID de la publicación desde la URL
$id = $_GET['id'];

// Cargar los posts desde el archivo JSON
$posts = json_decode(file_get_contents('../../data/posts.json'), true);

// Buscar y eliminar la publicación con el ID correspondiente
$posts = array_filter($posts, function($post) use ($id) {
    return $post['id'] != $id;
});

// Guardar los posts actualizados en el archivo JSON
file_put_contents('../../data/posts.json', json_encode(array_values($posts)));

// Redirigir de vuelta a la lista de publicaciones
header('Location: posts-consulta.php');
exit;
?>