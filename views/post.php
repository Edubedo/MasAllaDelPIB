<?php
session_start();
include('../config/database.php');

// Verificar si se recibió un ID válido en la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de post no válido.");
}

$postId = $_GET['id'];

// Consultar la base de datos
$stmt = $pdo->prepare("SELECT * FROM posts WHERE Id_posts = :id AND status = 'ACTIVO'");
$stmt->execute(['id' => $postId]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el post existe
if (!$post) {
    die("Publicación no encontrada.");
}

// Definir imagen predeterminada si no hay imagen en la base de datos
if (!empty($post['image'])) {
    // Si la imagen ya está como ruta relativa completa (desde el admin)
    $imageSrc = "../admin/posts/" . htmlspecialchars($post['image']);
} else {
    // Imagen predeterminada
    $imageSrc = "../admin/posts/uploads/preterminada.jpg";
}

$idtypeuser = $_SESSION['id_type_user'] ?? 3; // Por defecto, tipo 3 = visitante

// buscar la foto de perfil de quien creó el post
$stmt = $pdo->prepare("SELECT foto_perfil FROM users WHERE username = :username");
$stmt->execute([':username' => $post['user_creation']]);
$userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
$foto_perfil = $userProfile['foto_perfil'] ?? null;

$imageProfile = !empty($foto_perfil) ? "/views/uploads/" . htmlspecialchars($foto_perfil) : "/views/uploads/user-default2.jpeg";

// Procesar el envío de comentarios
if (isset($_POST['submit_comment'])) {
    if (!isset($_SESSION['username'])) {
        die("Debes iniciar sesión para comentar.");
    }

    date_default_timezone_set('America/Mexico_City');
    $content = trim($_POST['content']);
    $user_creation = $_SESSION['username'];
    $date_creation = date('Y-m-d H:i:s');

    if (strlen($content) < 5) {
        echo "<p style='color:red;'>El comentario debe tener al menos 5 caracteres.</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO comments_posts (id_pubicacion, id_usuario, content, user_creation, date_creation) 
                               VALUES (:id_pubicacion, :id_usuario, :content, :user_creation, :date_creation)");
        $stmt->execute([
            ':id_pubicacion' => $postId,
            ':id_usuario' => $_SESSION['username'],
            ':content' => $content,
            ':user_creation' => $user_creation,
            ':date_creation' => $date_creation
        ]);

        // Redirigir para evitar reenvío del formulario
        header("Location: post.php?id=$postId");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasAllaDelPIB - Publicación</title>

    <script src="../js/main.js"></script>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href='./css/footer.css'>
    <link rel="stylesheet" href='./css/post.css'>

</head>

<body>
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->
    <?php include './layout/header.php'; ?>
    <!-- IMPORTAR BARRA DE NAVEGACIÓN -->

    <div class="noticia">
        <div class="titulo">
            <a href="/views/publicaciones.php">
                <button class="btn-regresar">
                    ←   Publicaciones
                </button>
            </a>
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>

            <div class="datos">
                <div class="imagen-user">
                    <img src="<?php echo htmlspecialchars($imageProfile); ?>" alt="Foto de perfil">
                    <span><?php echo htmlspecialchars($post['user_creation']); ?></span>
                </div>
                <i class="far fa-calendar"></i> <?php echo date("F d, Y", strtotime($post['post_date'])); ?>
            </div>
        </div>

        <div class="imagen-noticia">
            <img class="img" src="<?php echo htmlspecialchars($imageSrc); ?>" alt="Imagen de la noticia">
        </div>

        <div class="info">
            <div class="texto">
                <p class="texto-noticia"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            </div>
        </div>

        <div class="referencias">
            <div class="texto">
                <div class="texto-noticia">
                    <h3>Referencias</h3>
                    <?php
                    if (!empty($post['referencia_posts'])) {
                        $referencias = preg_split('/\r\n|\r|\n/', $post['referencia_posts']);
                        foreach ($referencias as $referencia) {
                            $referencia = trim($referencia);
                            if (!empty($referencia)) {
                                // Asegurarse de que la URL tenga un esquema (http o https)
                                if (!preg_match('/^https?:\/\//', $referencia)) {
                                    $referencia = 'http://' . $referencia;
                                }
                                echo "<a href='$referencia' target='_blank' style='display: block; text-align: left;'>$referencia</a>";
                            }
                        }
                    } else {
                        echo "No hay referencia disponible.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="caja-comentarios">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM comments_posts WHERE id_pubicacion = :id_pubicacion ORDER BY date_creation DESC");
        $stmt->execute([':id_pubicacion' => $postId]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="titulo-com">Comentarios <span id="contador-comentarios" class="numero-comentarios">(<?php echo count($comments); ?>)</span></div>
        <?php if (isset($_SESSION['username'])): ?>
            <form method="POST" action="">
                <textarea name="content" placeholder="Escribe tu comentario aquí..." required></textarea>
                <button type="submit" name="submit_comment">Enviar</button>
            </form>
        <?php else: ?>
            <div class="invitacion-registrarse">
                <p>Para comentar en nuestras publicaciones registrate </p>
                <a href="/views/signin.php" class="btn-iniciar-sesion">Aquí</a>
            </div>
        <?php endif; ?>

        <div class="comentarios-lista">
            <?php


            if ($comments):
                foreach ($comments as $comment):
                    // Obtener foto de perfil del usuario que comentó
                    $stmtUser = $pdo->prepare("SELECT foto_perfil FROM users WHERE username = :username");
                    $stmtUser->execute([':username' => $comment['user_creation']]);
                    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
                    $fotoPerfilComentario = $user['foto_perfil'] ?? null;
                    $commentImage = !empty($fotoPerfilComentario) ? "/views/uploads/" . htmlspecialchars($fotoPerfilComentario) : "/views/uploads/user-default2.jpeg";
            ?>
                    <div class="comentario" id="comentario-<?php echo $comment['id_comments']; ?>">
                        <div class="imagen-user">
                            <img src="<?php echo htmlspecialchars($commentImage); ?>" alt="Foto de perfil">
                        </div>

                        <div class="comentario-contenido">
                            <div class="comentario-header">
                                <p><strong><?php echo htmlspecialchars($comment['user_creation']); ?></strong></p>
                                <?php if (isset($_SESSION['username']) && ($_SESSION['username'] === $comment['user_creation'] || $_SESSION['id_type_user'] == 1)): ?>
                                    <button class="eliminarComentario" data-id="<?php echo $comment['id_comments']; ?>">Eliminar</button>
                                <?php endif; ?>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                            <p class="fecha"><?php echo date("F d, Y H:i", strtotime($comment['date_creation'])); ?></p>
                        </div>
                    </div>
            <?php
                endforeach;
            else:
                echo "<p class='no-comentarios'>No hay comentarios aún. Sé el primero en comentar.</p>";
            endif;
            ?>
        </div>
    </div>

    <!-- IMPORTAR EL FOOTER -->
    <?php include './layout/footer.php'; ?>
    <!-- IMPORTAR EL FOOTER -->
</body>

</html>