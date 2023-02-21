<?php
session_start();
if(!isset($_SESSION['idUsuario'])||!isset($_SESSION['idConjunto'])){
    header("Location: login.php");
    die();
}
$idUsuario=intval($_SESSION['idUsuario']);
$idConjunto=intval($_SESSION['idConjunto']);
echo"<script> const idUsuario='$idUsuario';
const idConjunto='$idConjunto';

</script>";



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/listen.css" />
    <link rel='stylesheet' type='text/css' href='css/header.css' />

</head>
<body>
    <?php include('header.php') ?>    
    <select id="voces"></select>
    <div class="actual"id="palabraActual"></div>
    <div class="contenedor">
        <div class="hijo">
            <input type="image" src="iconos/sonido.svg" alt="Escuchar" class="sonidoNormal">
            <input type="image" src="iconos/caracol.svg" alt="Escuchar lento" class="sonidoLento">
            <label for="" class="respuestaMostrada"></label>
            <div class="inputEscribir">
                <input type="text" class="escribirRespuesta">
                <input type="image" src="iconos/mostrar.svg" alt="" class="mostrarRespuesta">
            </div>
        </div>
    </div>

    <script src="script/listen.js"></script>
</body>
</html>
