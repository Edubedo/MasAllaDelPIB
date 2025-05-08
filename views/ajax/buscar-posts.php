<?php
// Incluir configuración de la base de datos
require_once __DIR__ . '/../../config/database.php';

// Encabezado para que el navegador sepa que devolvemos JSON
header('Content-Type: application/json');

// Verificar si es una solicitud válida (por GET ahora)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["q"]) && !empty(trim($_GET["q"]))) {

    $searchTerm = trim($_GET["q"]);
    $keywords = explode(" ", $searchTerm);

    $tableName = 'posts';
    $searchColumns = ['title', 'content', 'user_creation', 'category'];
    $conditions = [];
    $params = [];
    $types = '';

    foreach ($keywords as $keyword) {
        if (!empty(trim($keyword))) {
            $keyword = "%$keyword%";
            $orConditions = [];

            foreach ($searchColumns as $column) {
                $orConditions[] = "$column LIKE ?";
                $params[] = $keyword;
                $types .= 's';
            }

            $conditions[] = "(" . implode(" OR ", $orConditions) . ")";
        }
    }

    // Mejorar la consulta para obtener más datos relevantes
    $sql = "SELECT Id_posts, title, SUBSTRING(content, 1, 120) as excerpt, 
                   category, post_date, image, user_creation
            FROM `$tableName` 
            WHERE " . implode(" OR ", $conditions) . "
            ORDER BY 
                CASE 
                    WHEN title LIKE ? THEN 1
                    WHEN content LIKE ? THEN 2
                    ELSE 3
                END,
                post_date DESC
            LIMIT 10";

    // Añadir parámetros adicionales para ordenación
    $params[] = "%$searchTerm%";
    $types .= 's';
    $params[] = "%$searchTerm%";
    $types .= 's';

    $stmt = $conexion->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Preparar la URL de la imagen
            $imageSrc = !empty($row['image'])
                ? $protocol . $host . "/admin/posts/" . htmlspecialchars($row['image'])
                : $protocol . $host . "/admin/posts/uploads/preterminada.jpg";

            // Preparar el extracto del contenido
            $excerpt = strip_tags($row['excerpt']);
            if (strlen($excerpt) >= 120) {
                $excerpt .= '...';
            }

            // Formatear la fecha
            $date = date("d M, Y", strtotime($row['post_date']));

            $posts[] = [
                'Id_posts' => $row['Id_posts'],
                'title' => $row['title'],
                'excerpt' => $excerpt,
                'category' => $row['category'],
                'date' => $date,
                'image' => $imageSrc,
                'user_creation' => $row['user_creation']
            ];
        }
    }

    echo json_encode($posts);
    $stmt->close();
} else {
    echo json_encode([]);
}
