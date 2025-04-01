<?php
session_start(); // Iniciar la sesión si no está iniciada

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio o login
header('Location: /index.php');
exit();
?>
