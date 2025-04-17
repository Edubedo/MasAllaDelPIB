<?php
echo '<link rel="stylesheet" href="/views/css/posts.css">';
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">';
if (!isset($postsDB)) {
    echo "<p>No se encontraron publicaciones para mostrar.</p>";
    return;
}

$idtypeuser = $_SESSION['id_type_user'] ?? 3; // visitante por defecto

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
                    <a class="options" data-vote-type="1" id="post_vote_up_' . htmlspecialchars($post['Id_posts']) . '">
                        <i class="fas fa-thumbs-up"></i>
                    </a>
                    <span class="likes_count" id="vote_up_count_' . htmlspecialchars($post['Id_posts']) . '">' . htmlspecialchars($post['vote_up'] ?? 0) . '</span>
                    <a class="options" data-vote-type="0" id="post_vote_down_' . htmlspecialchars($post['Id_posts']) . '">
                        <i class="fas fa-thumbs-down"></i>
                    </a>
                    <span class="likes_count" id="vote_down_count_' . htmlspecialchars($post['Id_posts']) . '">' . htmlspecialchars($post['vote_down'] ?? 0) . '</span>
                </div>
            </div>';
    }

    echo '</div></a>';
}
?>
