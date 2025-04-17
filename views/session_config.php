<?php
// session_config.php
session_start();

// Tiempo máximo de inactividad en segundos (15 minutos = 900 segundos)
$tiempo_inactividad = 900;  // Puedes ajustar este valor

// Verificar si la variable de tiempo de la última actividad está establecida
if (isset($_SESSION['last_activity'])) {
    // Calcular el tiempo de inactividad
    $tiempo_inactivo = time() - $_SESSION['last_activity'];
    
    // Si ha pasado más del tiempo máximo de inactividad, destruir la sesión
    if ($tiempo_inactivo > $tiempo_inactividad) {
        // Establecer un mensaje de expiración
        $_SESSION['error_message'] = 'Tu sesión ha expirado debido a inactividad. Por favor, inicia sesión nuevamente.';
        
        // Destruir la sesión
        session_unset();  // Borra todas las variables de sesión
        session_destroy();  // Destruye la sesión
        header('Location: signin.php');  // Redirige al login
        exit();
    }
}

// Actualizar el tiempo de la última actividad
$_SESSION['last_activity'] = time();
?>
