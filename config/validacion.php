<?php
$email=$_POST['email'];
$password=$_POST['password'];
session_start();
$_SESSION ['email']=$email;

include('database.php');

$consulta = "SELECT * FROM users where email = '$email' and password = '$password'";

$resultado=mysqli_query($conexion,$consulta);

//Validacion de que los datos sean correctos
$filas=mysqli_num_rows($resultado);
if($filas){
    header("location:../index.php");   
}else{
    ?>
    <?php
    include("../views/signin.php");
    ?>
    <h1>Error en la autetificacion</h1>
    <?php
}
//Para que me muestre los resultados
mysqli_free_result($resultado);
//Para que termine la conexion
mysqli_close($conexion);
