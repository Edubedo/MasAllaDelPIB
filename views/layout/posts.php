<?php
if (file_exists('config/database.php')) {
    require 'config/database.php';
} elseif (file_exists('../config/database.php')) {
    require '../config/database.php';
} else {
    die("Error: No se pudo encontrar el archivo de configuración de la base de datos.");
}

echo '<link rel="stylesheet" href="/views/css/posts.css">';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">';
if (!isset($postsDB)) {
    echo "<p>No se encontraron publicaciones para mostrar.</p>";
    return;
}

$idtypeuser = $_SESSION['id_type_user'] ?? 3; // visitante por defecto
$idUsuario = $_SESSION['username'] ?? null; // Usuario actual

// Obtener los likes del usuario actual
$userLikes = [];
if ($idUsuario) {
    $query = "SELECT id_post FROM likes WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id_usuario' => $idUsuario]);
    $userLikes = $stmt->fetchAll(PDO::FETCH_COLUMN); // Devuelve un array con los IDs de los posts
}

foreach ($postsDB as $post) {
    $imageSrc = !empty($post['image']) ? "/admin/posts/" . htmlspecialchars($post['image']) : "/admin/posts/uploads/preterminada.jpg";

    $postLink = '/views/post.php?id=' . htmlspecialchars($post['Id_posts']);
    $titleLimit = isset($isIndex) && $isIndex ? 80 : 68;
    $contentLimit = isset($isIndex) && $isIndex ? 350 : 200;
    $title = htmlspecialchars(strlen($post['title']) > $titleLimit ? substr($post['title'], 0, $titleLimit) . "..." : $post['title']);
    $content = htmlspecialchars(strlen($post['content']) > $contentLimit ? substr($post['content'], 0, $contentLimit) . "..." : $post['content']);
    $userCreation = htmlspecialchars($post['user_creation']);
    $postDate = date("F d, Y", strtotime($post['post_date']));
    $foto_perfil = $post['foto_perfil'] ?? null;
    $ruta = isset($foto_perfil) && !empty($foto_perfil) ? "/views/uploads/" . $foto_perfil : "/views/uploads/user-default2.jpeg";

    // Verificar si el usuario ya dio "like" a este post
    $userLiked = in_array($post['Id_posts'], $userLikes);

    echo '
    <a href="' . $postLink . '">
        <div class="p1">
            <div class="cuerpo_post">
                <div class="imagen_post">
                    <img class="imagen1" src="' . $imageSrc . '" alt="imagen de ' . htmlspecialchars($post['title']) . '">
                </div>
                <div class="info_post">
                    <h4 class="titulo1">' . $title . '</h4>
                    <div class="datos1">
                        <div class="imagen-user">
                            <img src="' . $ruta . '" alt="Foto de perfil">
                        </div>
                        <i class="far fa-calendar"></i> <span>' . $postDate . '</span>
                    </div>
                    <p class="texto1">' . $content . '</p>
                </div>
            </div>';

    if ($idtypeuser == 1 || $idtypeuser == 2) {
        echo '
            <div class="interaccion">
                <div class="likes">
                    <a class="options ' . ($userLiked ? 'liked' : 'not-liked') . '" data-vote-type="1" id="post_vote_up_' . htmlspecialchars($post['Id_posts']) . '">
                        <i class="fas fa-thumbs-up"></i>
                    </a>
                    <span class="likes_count ' . ($userLiked ? 'liked' : 'not-liked') . '" id="vote_up_count_' . htmlspecialchars($post['Id_posts']) . '">' . htmlspecialchars($post['total_likes'] ?? 0) . '</span>
                </div>
            </div>';
    }

    echo '</div></a>';
}
?>

<script>
    $(document).on('click', '.options', function(e) {
        e.preventDefault();
        const postId = $(this).attr('id').split('_').pop(); // Extraer el ID del post
        const voteType = $(this).data('vote-type'); // 1 para like, 0 para dislike
        const button = $(this);

        $.ajax({
            url: '/views/layout/like_handler.php',
            type: 'POST',
            data: {
                id_post: postId,
                vote_type: voteType
            },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.success) {
                    // Actualizar el conteo de likes en la UI
                    const countElement = $(`#vote_up_count_${postId}`);
                    countElement.text(parseInt(countElement.text()) + 1);

                    // Cambiar el estado del botón a "liked"
                    button.removeClass('not-liked').addClass('liked');
                } else {
                    alert(res.message); // Mostrar mensaje si ya votó
                }
            },
            error: function() {
                alert('Error al procesar la solicitud.');
            }
        });
    });
</script>