<?php
// Incluir configuración de la base de datos
require_once __DIR__ . '/../config/database.php';

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

    $sql = "SELECT Id_posts, title FROM `$tableName` WHERE " . implode(" OR ", $conditions);

    $stmt = $conexion->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $posts = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = [
                'Id_posts' => $row['Id_posts'],
                'title' => $row['title']
            ];
        }
    }

    echo json_encode($posts);
    $stmt->close();
} else {
    echo json_encode([]);
}
?>
