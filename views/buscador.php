<?php
include '../config/database.php';

if ($_POST["data"] == 'buscar' && $_POST["busqueda"] != '') {

    $key = explode(" ", $_POST["busqueda"]);
    $sql = "SELECT * FROM pi.posts WHERE (title LIKE '%" . $_POST["busqueda"] . "%' 
                OR content LIKE '%" . $_POST["busqueda"] . "%' 
                OR user_creation LIKE '%" . $_POST["busqueda"] . "%' 
                OR category LIKE '%" . $_POST["busqueda"] . "%')";
    
    // Añadir más filtros con el ciclo for
    for ($i = 0; $i < count($key); $i++) {
        if (!empty($key[$i])) {
            $sql .= " OR title LIKE '%" . $key[$i] . "%' 
                      OR content LIKE '%" . $key[$i] . "%' 
                      OR user_creation LIKE '%" . $key[$i] . "%' 
                      OR category LIKE '%" . $key[$i] . "%'";
        }
    }

    $row_sql = mysqli_query($conexion, $sql);

    if ($row_sql && mysqli_num_rows($row_sql) > 0) {
        echo '<table class="col-12 m-0 p-0">
                <tbody>';
        
        // Mostrar los resultados de la búsqueda
        while ($row = mysqli_fetch_assoc($row_sql)) {
            $imgSrc = isset($row["img"]) ? "img/" . $row["img"] . ".jpg" : "../admin/posts/uploads/preterminada.jpg";
            echo '<tr>
                    <th style="width: 60px;">
                        <img src="' . $imgSrc . '" width="50" height="65px">
                    </th>
                    <td style="vertical-align: middle; text-align:left;">
                        <p class="card-text">' . htmlspecialchars($row["title"]) . ' <br> ' . htmlspecialchars($row["content"]) . '€</p>
                    </td>
                  </tr>';
        }
        
        echo '</tbody></table>';
    } else {
        echo 'No se encontraron resultados para su búsqueda.';
    }
}
?>
