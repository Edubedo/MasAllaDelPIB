<?php
include ('../config/database.php'); // Conexión a la base de datos
include ('megusta.php');

session_start();
$user_id = $_SESSION['user_id'] ?? null; // Obtener el ID del usuario

// Verificar si se recibió un post_id válido
if (!isset($_POST['post_id']) || !isset($_POST['vote_type'])) {
    die(json_encode(["error" => "Datos inválidos"]));
}

$post_id = htmlspecialchars($_POST['post_id']);
$vote_type = htmlspecialchars($_POST['vote_type']);

// Instanciar la clase Posts
$posts = new Posts($pdo); // Usar PDO aquí

$postVote = $posts->getPostVotes($post_id);

if ($vote_type == 1) { // Like
    $postVote['vote_up'] += 1;
} else if ($vote_type == 0) { // Dislike
    $postVote['vote_down'] += 1;
}
 
    
$postVoteData = array(
    'post_id' => $_POST['post_id'],
    'user_id' => $user_id,
    'vote_up' => $postVote['vote_up'],
    'vote_down' => $postVote['vote_down'],
);
    
$postVoted = $posts->updatePostVote($postVoteData); 

if($postVoted) {
    echo json_encode([
        'vote_up' => $postVote['vote_up'],
        'vote_down' => $postVote['vote_down'],
        'post_id' => $post_id
    ]);
} else {
    echo json_encode(["error" => "Error al registrar el voto"]);
}
?>
 