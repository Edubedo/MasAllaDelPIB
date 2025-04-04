<?php
session_start();
include ('../config/database.php'); // Conexión a la base de datos
include ('megusta.php');
$user_creation = $_SESSION['user_creation'] ?? null; // ID del usuario que está logueado
$posts = new Posts($pdo); 

if (!isset($_POST['Id_posts']) || !isset($_POST['vote_type']) || !$user_creation) {
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$Id_posts = $_POST['Id_posts'];
$vote_type = $_POST['vote_type']; // 1 = Like, 0 = Dislike

// Obtener los votos actuales
$postVote = $posts->getPostVotes($Id_posts);

// Obtener los votos actuales
$postVote = $posts->getPostVotes($Id_posts);

if (!$postVote) {
    echo json_encode(['error' => 'Publicación no encontrada']);
    exit;
}

// Verificar si el usuario ya ha votado
if ($posts->isUserAlreadyVoted($user_creation, $Id_posts)) {
    echo json_encode(['error' => 'Ya has votado este post']);
    exit;
}

// Incrementar el contador de votos
if ($vote_type == 1) {
    $postVote['vote_up'] += 1;
} else {
    $postVote['vote_down'] += 1;
}

// Actualizar los votos en la tabla `posts`
$postVoteData = [
    'Id_posts' => $Id_posts,
    'vote_up' => $postVote['vote_up'],
    'vote_down' => $postVote['vote_down']
];

$postVoted = $posts->updatePostVote($postVoteData);

if ($postVoted) {
    // Registrar el voto en la tabla `likes` (sin voto duplicado)
    $sqlVoteQuery = "INSERT INTO likes (Id_posts, user_creation, id_fecha_creacion) 
                     VALUES (:Id_posts, :user_creation, NOW())";
    $stmt = $pdo->prepare($sqlVoteQuery);
    $stmt->execute([
        ':Id_posts' => $Id_posts,
        ':user_creation' => $user_creation
    ]);

    echo json_encode([
        'vote_up' => $postVote['vote_up'],
        'vote_down' => $postVote['vote_down'],
        'Id_posts' => $Id_posts
    ]);
} else {
    echo json_encode(['error' => 'No se pudo registrar el voto']);
}
?>