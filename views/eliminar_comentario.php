<?php
session_start();
include('../config/database.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    exit('No autorizado');
}

// Validar el ID del comentario
if (!isset($_POST['comment_id']) || !is_numeric($_POST['comment_id'])) {
    exit('ID inválido');
}

$commentId = $_POST['comment_id'];
$username = $_SESSION['username'];
$idtypeuser = $_SESSION['id_type_user'] ?? 3; // Por defecto visitante

// Verificar si el usuario es el creador del comentario o un administrador
$stmt = $pdo->prepare("SELECT user_creation FROM comments_posts WHERE id_comments = :id");
$stmt->execute(['id' => $commentId]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    exit('Comentario no encontrado');
}

if ($comment['user_creation'] !== $username && $idtypeuser != 1) {
    exit('No autorizado');
}

// Eliminar el comentario
$stmt = $pdo->prepare("DELETE FROM comments_posts WHERE id_comments = :id");
$stmt->execute(['id' => $commentId]);

echo 'ok';
