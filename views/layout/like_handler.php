<?php
session_start();
require '../../config/database.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPost = $_POST['id_post'] ?? null;
    $idUsuario = $_SESSION['username'] ?? null;

    if (!$idPost || !$idUsuario) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    // Procesar el "like" o "unlike"
    try {
        // Verificar si el usuario ya votó
        $query = "SELECT * FROM likes WHERE id_post = :id_post AND id_usuario = :id_usuario";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_post' => $idPost, ':id_usuario' => $idUsuario]);

        if ($stmt->rowCount() > 0) {
            // Si ya votó, eliminar el registro de la tabla de likes
            $query = "DELETE FROM likes WHERE id_post = :id_post AND id_usuario = :id_usuario";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_post' => $idPost, ':id_usuario' => $idUsuario]);

            // Reducir el conteo de votos en la tabla de posts
            $query = "UPDATE posts SET vote_up = vote_up - 1 WHERE Id_posts = :id_post";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_post' => $idPost]);

            echo json_encode(['success' => true, 'action' => 'unliked', 'message' => 'Like eliminado']);
        } else {
            // Si no ha votado, insertar el voto en la tabla de likes
            $query = "INSERT INTO likes (id_post, id_usuario, id_fecha_creacion) VALUES (:id_post, :id_usuario, NOW())";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_post' => $idPost, ':id_usuario' => $idUsuario]);

            // Incrementar el conteo de votos en la tabla de posts
            $query = "UPDATE posts SET vote_up = vote_up + 1 WHERE Id_posts = :id_post";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_post' => $idPost]);

            echo json_encode(['success' => true, 'action' => 'liked', 'message' => 'Like registrado']);
        }
    } catch (PDOException $e) {
        error_log("Error en like_handler.php: " . $e->getMessage()); // Registrar el error en el log del servidor
        echo json_encode(['success' => false, 'message' => 'Error al procesar el voto: ' . $e->getMessage()]);
    }
}
