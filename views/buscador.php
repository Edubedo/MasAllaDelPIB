<?php
// Incluir configuración de la base de datos
require_once __DIR__ . '/../config/database.php';

// Verificar si es una solicitud de búsqueda válida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["data"], $_POST["busqueda"]) && $_POST["data"] === 'buscar' && !empty(trim($_POST["busqueda"]))) {

    $tableName = 'posts';
    
    
    $searchTerm = trim($_POST["busqueda"]);
    $keywords = explode(" ", $searchTerm);
    
    // Iniciar la consulta SQL
    $sql = "SELECT * FROM `$tableName` WHERE ";
    
    // Columnas donde buscar
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
                $types .= 's'; // 's' para string
            }
            
            $conditions[] = "(" . implode(" OR ", $orConditions) . ")";
        }
    }
    
    // Unir todas las condiciones con OR
    $sql .= implode(" OR ", $conditions);
    
    // Preparar y ejecutar la consulta
    $stmt = $conexion->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Mostrar resultados
    if ($result && $result->num_rows > 0) {
        echo '<table class="col-12 m-0 p-0">
                <tbody>';
        
        while ($row = $result->fetch_assoc()) {
            $imgSrc = isset($row["img"]) && !empty($row["img"]) ? 
                      "img/" . htmlspecialchars($row["img"]) . ".jpg" : 
                      "../admin/posts/uploads/preterminada.jpg";
            
            echo '<tr>
                    <th style="width: 60px;">
                        <img src="' . $imgSrc . '" width="50" height="65px" alt="' . htmlspecialchars($row["title"]) . '">
                    </th>
                    <td style="vertical-align: middle; text-align:left;">
                        <p class="card-text">' . htmlspecialchars($row["title"]) . ' <br> ' . htmlspecialchars($row["content"]) . '€</p>
                    </td>
                  </tr>';
        }
        
        echo '</tbody></table>';
    } else {
        echo '<p class="text-muted">No se encontraron resultados para su búsqueda.</p>';
    }
    
    $stmt->close();
} else {
    echo '<p class="text-muted">Por favor ingrese un término de búsqueda válido.</p>';
}
?>