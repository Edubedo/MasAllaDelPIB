<?php
session_start();

if (isset($_SESSION['username'])) {
    include 'database.php';

    $username = $_SESSION['username'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conexion, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
        
    

        $_SESSION['username'] = $usuario['username'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['iduser'] = $usuario['iduser'];  
    } 
}

?>
