<?php
session_start();
if(isset($_SESSION['usuario'])){
    header("Location: conjuntos.php");
    die();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="css/login.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class="contenedor">
    <div class="titulo">Registro</div>
    <form action="" method="post" id="form">
        <label for="usuario">Nombre de usuario:</label>
        <input type="text" name="usuario" id="usuario" class="input">
        <label for="email">Correo electronico:</label>
        <input type="email" name="email" id="email" class="input">
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" class="input">
        <label for="confirmacion">Confirma contraseña:</label>
        <input type="password" name="confirmacion" id="confirmacion" class="input">
        <button>Registrarse</button>
        <label for="" id="registro">¿ya tienes cuenta? inicia sesion <a href="login.php">aqui.</a></label>
    </form>
    <div class="mensaje"></div>
</div>
<script src="script/registro.js"></script>
</body>
</html>