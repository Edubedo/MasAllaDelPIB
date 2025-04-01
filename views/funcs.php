<?php
// Datos de la base de datos
$host = 'b5szsho9hlusg9vbkktx-mysql.services.clever-cloud.com';
$usuario = 'uaro5fqdabhzh1tk';
$clave = 'BLq5F4sNK3ubd6iZUvii';
$nombre_base_datos = 'b5szsho9hlusg9vbkktx';
$puerto = 3306;

// Establecer la conexión
$mysqli = new mysqli($host, $usuario, $clave, $nombre_base_datos, $puerto);

// Verificar la conexión
if ($mysqli->connect_error) {
    die('Error de Conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Sanitizar correo electrónico
function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Verifica si el correo existe en la base de datos
function emailExiste($email) {
    global $mysqli; 
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    return $stmt->num_rows > 0;
}

// Obtener un valor de la base de datos
function getValor($campo, $columna, $valor) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT $campo FROM users WHERE $columna = ?");
    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $stmt->bind_result($resultado);
    $stmt->fetch();
    return $resultado;
}

// Generar un token único para la recuperación de contraseña
function generaTokenPass($email) {
    global $mysqli;
    
    $token = bin2hex(random_bytes(16));  // Token aleatorio seguro
    $stmt = $mysqli->prepare("UPDATE users SET token = ? WHERE email = ?");
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();
    
    return $token;
}

// Enviar correo electrónico
function enviarEmail($email, $nombre, $asunto, $cuerpo) {
    $headers = "From: no-reply@masalladelpib.com\r\n";
    $headers .= "Reply-To: no-reply@masalladelpib.com\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    return mail($email, $asunto, $cuerpo, $headers);
}

// Mostrar errores
function resultBlock($errors) {
    if (count($errors) > 0) {
        echo '<div class="error-messages">';
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo '</div>';
    }
}
?>
