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
    <!-- <link rel="stylesheet" type="text/css" href="css/index.css" /> -->
    <link rel="stylesheet" type="text/css" href="css/practicar.css" />
    <?php echo"<link rel='stylesheet' type='text/css' href='css/header.css' />"; ?>


</head>
<body>
<?php include('header.php') ?>
    <div id="title"></div> 
    <select id="voces"></select>
    <div class="actual"id="palabraActual"></div>

    <div class="caja">
        <img src="" alt="" id="imagen">
        <div class="traduccion"id="traduccion"></div>
        <div class="respuesta" id="ayuda"></div>
        <input type="text"id="texto" autofocus>
        <input type="text"id="respuesta" hidden>
        <div class="herramientas">
            <div class="img"><img src="iconos/mostrar.svg" alt="" id="mostrar"></div>
            <div class="img"><img src="iconos/caracol.svg" alt="" id="caracol"></div>
            <div class="img"><img src="iconos/sonido.svg" alt="" id="btnEscuchar"></div>
        </div>

    </div>


    
    <script src="script/practicar.js"></script>
</body>
</html>