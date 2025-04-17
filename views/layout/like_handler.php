<?php
session_start();
require '../../config/database.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPost = $_POST['id_post'] ?? null;
    $voteType = $_POST['vote_type'] ?? null; // 1 para like, 0 para dislike
    $idUsuario = $_SESSION['username'] ?? null;

    if (!$idPost || !$idUsuario || !in_array($voteType, [0, 1])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    try {
        // Verificar si el usuario ya votó
        $query = "SELECT * FROM likes WHERE id_post = :id_post AND id_usuario = :id_usuario";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_post' => $idPost, ':id_usuario' => $idUsuario]);

        if ($stmt->rowCount() > 0) {
            // Si ya votó, devolver un mensaje indicando que no puede votar de nuevo
            echo json_encode(['success' => false, 'message' => 'Ya has dado like a este post.']);
            exit;
        }

        // Si no ha votado, insertar el voto en la tabla de likes
        $query = "INSERT INTO likes (id_post, id_usuario, id_fecha_creacion) VALUES (:id_post, :id_usuario, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_post' => $idPost, ':id_usuario' => $idUsuario]);

        // Incrementar el conteo de votos en la tabla de posts
        $voteColumn = $voteType == 1 ? 'vote_up' : 'vote_down';
        $query = "UPDATE posts SET $voteColumn = $voteColumn + 1 WHERE Id_posts = :id_post";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_post' => $idPost]);

        echo json_encode(['success' => true, 'message' => 'Voto registrado']);
    } catch (PDOException $e) {
        error_log("Error en like_handler.php: " . $e->getMessage()); // Registrar el error en el log del servidor
        echo json_encode(['success' => false, 'message' => 'Error al registrar el voto: ' . $e->getMessage()]);
    }
}
