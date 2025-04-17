<?php
session_start();
if (isset($_SESSION['username'])) {
    include 'database.php';

    $username = $_SESSION['username'];

    // Consulta SQL simple
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conexion, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        // Guardar en la sesión los datos del usuario
        $_SESSION['username'] = $usuario['username'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['iduser'] = $usuario['iduser'];
        $_SESSION['id_type_user'] = $usuario['id_type_user'];
    }
}


$tiempo_inactividad = 600;

if (isset($_SESSION['ultimo_acceso'])) {
    $inactivo = time() - $_SESSION['ultimo_acceso'];
    if ($inactivo > $tiempo_inactividad) {
        echo "<script>
            alert('Tu sesión ha expirado por inactividad.');
            window.location.href = '../auth/signin.php';
        </script>";
        session_unset();
        session_destroy();
        exit();
    }
}

$_SESSION['ultimo_acceso'] = time();

?>
