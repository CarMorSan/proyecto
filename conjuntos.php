<?php
session_start();
if(!isset($_SESSION['idUsuario'])){
    header("Location: login.php");
    die();
}
echo"<script> const id='".$_SESSION['idUsuario']."'</script>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/conjuntos.css" />
    <?php echo"<link rel='stylesheet' type='text/css' href='css/header.css' />"; ?>
</head>
<body>
<?php include('header.php') ?>

<div class="TituloYCreacion">
    <div class="titulo">
        <label for="" id="titulo">Conjuntos:</label>
        <input type="image" src="iconos/agregar.svg" title="Crear conjunto"id="crear">
    </div>
    <form method="post" hidden id="nueva">
        <div class="nueva">
            <input type="text" name="tabla"id="inputModificar">
            <input type="submit" value="Crear"id='inputEnviar'>
        </div>
    </form>
</div>

<div class="cajaConjuntos">
    <?php
        include_once "config.php";
        $idUsuario=intval($_SESSION['idUsuario']);
        $consulta="SELECT id, nombre FROM conjuntos WHERE idUsuario = $idUsuario";//poner order by
        $sentencia=$db->prepare($consulta);
        $template="";
        if($sentencia->execute()){
            $sentencia->bind_result($id,$nombre);
            $contador=1;
            while($sentencia->fetch()){
                $template.="
                <div class='conjuntos'id='conjunto$contador'>
                    <div class='nombre'id='nombre$contador'title='$nombre'>$nombre</div> 
                    <input type='text'class='nombreModificar' id='nombreModificar$contador' hidden required>
                    <input type='image' src='iconos/cancelar.svg' class='iconos cancelar' id='cancelar$contador' hidden value='$contador'>
                    <div class='herramientas'id='herramientas$contador'>
                        <input type='image' src='iconos/editar.svg'title='Editar nombre' class='iconos modificar'id='modificar$contador'value='$contador'hidden><input type='image' src='iconos/eliminar.svg' title='Eliminar conjunto' class='iconos eliminar' value='$id'id='eliminar$contador'hidden><input type='image' src='iconos/configuracion.svg' class='iconos herramientasMostrar' title='Herramientas'id='herramienta$contador'value='$contador'>
                    </div>
                    <div class='botones'id='botones$contador'>
                        
                        <button type='submit' class='revisar' value='$id'id='revisar$contador'>Revisar</button>
                    </div>
                    <button class='botonModificar' id='botonModificar$contador'value='$id'hidden>Modificar</button>
                </div>
                ";
                $contador++;
            }
            $sentencia->free_result();
            $sentencia->close();
            echo $template;
        }

    ?>
<template class="template">
    <div class="conjuntos"id="conjunto">
        <div class="nombre"id="nombre"title="nombre">nombre</div> 
        <input type="text"class="nombreModificar" id="nombreModificar" hidden>
        <input type="image" src="iconos/cancelar.svg" class="iconos cancelar" id="cancelar" hidden value="id">
        <div class="herramientas"id="herramientas">
            <input type="image" src="iconos/editar.svg"title="Editar nombre" class="iconos modificar"id="modificar"value="numero" hidden><input type="image" src="iconos/eliminar.svg" title="Eliminar conjunto" class="iconos eliminar" id="eliminar" value="id" hidden><input type="image" src="iconos/configuracion.svg" class="iconos herramientasMostrar" title="Herramientas"id="herramienta"value="numero">
        </div>
        <div class="botones"id="botones">
            <button type="submit" class="practicar"value="id">Practicar</button>
            <button type="submit" class="revisar" id="revisar"value="id">Revisar</button>
        </div>
        <button class="botonModificar" id="botonModificar"value="id"hidden>Modificar</button>
    </div>
</template>



    <script src="script/conjuntos.js"></script>
</body>
</html>