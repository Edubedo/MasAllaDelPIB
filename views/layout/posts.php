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
    // buscar la foto de perfil de quien creó el post
    $stmt = $pdo->prepare("SELECT foto_perfil FROM users WHERE username = :username");
    $stmt->execute([':username' => $post['user_creation']]);
    $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
    $foto_perfil = $userProfile['foto_perfil'] ?? null;
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $ruta = !empty($foto_perfil) ? $protocol . $host . "/views/uploads/" . htmlspecialchars($foto_perfil) : $protocol . $host . "/views/uploads/user-default2.jpeg";

    if (!empty($post['image'])) {
        $imageSrc = $protocol . $host . "/admin/posts/" . htmlspecialchars($post['image']);
    } else {
        $imageSrc = $protocol . $host . "/admin/posts/uploads/preterminada.jpg";
    }

    $postLink = '/views/post.php?id=' . htmlspecialchars($post['Id_posts']);
    $titleLimit = isset($isIndex) && $isIndex ? 50 : 40;
    $contentLimit = isset($isIndex) && $isIndex ? 160 : 80;
    $title = htmlspecialchars(strlen($post['title']) > $titleLimit ? substr($post['title'], 0, $titleLimit) . "..." : $post['title']);
    $content = htmlspecialchars(strlen($post['content']) > $contentLimit ? substr($post['content'], 0, $contentLimit) . "..." : $post['content']);
    $userCreation = htmlspecialchars($post['user_creation']);
    $postDate = date("F d, Y", strtotime($post['post_date']));

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
                            <span>' . $userCreation . '</span>
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
                    <span class="options ' . ($userLiked ? 'liked' : 'not-liked') . '" data-vote-type="1" id="post_vote_up_' . htmlspecialchars($post['Id_posts']) . '">
                        <i class="fas fa-thumbs-up"></i>
                    </span>
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
        const button = $(this);
        const countElement = $(`#vote_up_count_${postId}`);
        let currentCount = parseInt(countElement.text());

        // Cambiar el estado visual inmediatamente
        if (button.hasClass('liked')) {
            button.removeClass('liked').addClass('not-liked');
            countElement.text(currentCount - 1); // Reducir contador visualmente
        } else {
            button.removeClass('not-liked').addClass('liked');
            countElement.text(currentCount + 1); // Incrementar contador visualmente
        }

        // Enviar la solicitud AJAX al backend
        $.ajax({
            url: '/views/layout/like_handler.php',
            type: 'POST',
            data: {
                id_post: postId,
                vote_type: 1 // Siempre enviamos 1 para "like"
            },
            success: function(response) {
                const res = JSON.parse(response);

                if (!res.success) {
                    // Si hubo un error, revertir el cambio visual
                    if (res.action === 'unliked') {
                        button.removeClass('not-liked').addClass('liked');
                        countElement.text(currentCount + 1); // Revertir contador
                    } else if (res.action === 'liked') {
                        button.removeClass('liked').addClass('not-liked');
                        countElement.text(currentCount - 1); // Revertir contador
                    }
                    alert(res.message); // Mostrar mensaje de error
                }
            },
            error: function() {
                // Si hubo un error en la solicitud, revertir el cambio visual
                if (button.hasClass('liked')) {
                    button.removeClass('liked').addClass('not-liked');
                    countElement.text(currentCount - 1); // Revertir contador
                } else {
                    button.removeClass('not-liked').addClass('liked');
                    countElement.text(currentCount + 1); // Revertir contador
                }
                alert('Error al procesar la solicitud.');
            }
        });
    });
</script>