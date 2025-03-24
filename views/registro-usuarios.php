
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresa tus datos </title>
    <link rel="stylesheet" href="css/registro-usuarios.css">
</head>
<body>
    <div class="container">
        <form action="signin.php" name="crear-usuarios" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo">Nombre completo:</label>
                <input type="text" id="id-user" name="name-user" >
            </div>
            <div class="form-group">
                <label for="categoria">Email: </label>
              <input type="text" id="id-email" name="email-user" >
            </div>
            <div class="form-group">
                <label for="contenido">Telefono: </label>
                <input type="text" id="id-telefono" name="telefono-user" >
            </div>

            <div class="form-group">
                <label for="imagen">Password: </label>
                <input type="text" id="id-password" name="password-user">
            </div>
           
            
            <button type="submit" name="registrame" >Registrarme </button>
        </form>
    </div>


    
</body>
</html>



