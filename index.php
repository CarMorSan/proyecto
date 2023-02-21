<?php
session_start();
if(!isset($_SESSION['idUsuario'])){
    header("Location: login.php");
    die();
}
$idUsuario=intval($_SESSION['idUsuario']);
$idConjunto=intval($_SESSION['idConjunto']);
$nombreConjunto=$_SESSION['nombreConjunto'];
echo"<script> const idUsuario='$idUsuario';
const idConjunto='$idConjunto';
nombreConjunto='$nombreConjunto';

</script>";



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/index.css" />
    <?php echo"<link rel='stylesheet' type='text/css' href='css/header.css' />"; ?>
</head>
<body>
    <?php include('header.php') ?>
    <div class="mover"><button class="btnMover">Mover</button></div>

    <div class="titulo">
        <div class="agregar">
            <label for="" id="titulo"><?php echo $_SESSION['nombreConjunto'];?></label>
            <input type="image" src="iconos/agregar.svg" title="Agregar palabra o frase"id="crear">
        </div>
        <!-- <div class="practicar"> -->
            <select id="voces"></select>
            <a href="practicar.php"> <button value="pepep">Practicar escritura</button></a>
            <a href="speak.php"> <button value="pepep">Practicar Speak</button></a>
            <a href="listen.php"> <button value="pepep">Practicar listen</button></a>
        <!-- </div> -->

    </div>
    
    
 
    <div class="oscuro"></div>
    <form action="" method="post"id="form">
        <div class="cajaRegistrar">
            <div class="TituloCerrar">
                <div class="accion">Agregar palabra o frase</div>
                <img src="iconos/cancelar.svg" alt="" id="cerrar">
            </div>
            
            <div class="registrar">
                <div class="tituloRegistrar">Palabra o frase:</div>
                <input type="text" name="termino" id="termino"placeholder="termino" required>
                <div class="tituloRegistrar">Traducción:</div>
                <input type="text" name="traduccion" id="traduccion"placeholder="traduccion" required>
                <!-- <div class="tituloRegistrar">Fonética:</div>
                <input type="text" name="fonetica" id="fonetica"placeholder="fonetica"> -->
                
                <div class="tituloRegistrar">Link de imagen:</div>
                <input type="text" name="imagen" id="imagen"placeholder="imagen">
               <!--  <input type="text" name="tabla" value="meses"id="tabla"placeholder="tabla" hidden> -->
                <div class="botonesEnviar">
                    <!-- <button type="submit" value="otro" name="otro" class="otro">Otro</button>
                    <input type="submit" value="Terminar"> -->
                    <button id="seguirAgregando">Seguir agregando</button>
                    <button id="terminar">Terminar</button>
                </div>
                <img src="" alt="" id="imgPreview">
            </div>

        </div>
    </form>
    <form action="" method="post"id="formMod" class="modificacion">
        <div class="TituloCerrar">
            <div class="accion">Modificar palabra o frase</div>
            <img src="iconos/cancelar.svg" alt="" id="cerrarMod">
        </div>

        <div class="filaModificacion">
            <div class="tituloRegistrar">Frase:</div>
            <div id="infoModificar1"class="infoModificar"></div>
            <input type="text"id="inputModificar1"name="palabra"class="inputModificar">
            <button type="button"id="btnModificar1"class="btnModificar">Modificar</button>
            <button type="button"id="btnCancelar1"class="btnCancelar">Cancelar</button>
        </div>

        <div class="filaModificacion">
            <div class="tituloRegistrar">Traduccion:</div>
            <div id="infoModificar2"class="infoModificar"></div>
            <input type="text"id="inputModificar2" name="traduccion"class="inputModificar">
            <button type="button"id="btnModificar2"class="btnModificar">Modificar</button>
            <button type="button"id="btnCancelar2"class="btnCancelar">Cancelar</button>
        </div>
        
        <div class="filaModificacion">
            <div class="tituloRegistrar">Fonetica:</div>
            <div id="infoModificar3"class="infoModificar"></div>
            <input type="text"id="inputModificar3" name="fonetica"class="inputModificar">
            <button type="button"id="btnModificar3"class="btnModificar">Modificar</button>
            <button type="button"id="btnCancelar3"class="btnCancelar">Cancelar</button>
        </div>
        
    
        <!-- <input type="text" name="imagen" id="imagen"placeholder="imagen"> -->
        <div class="filaModificacion">
            <div class="tituloRegistrar">LinkImg:</div>
            <div id="infoModificar4"class="infoModificar"></div>
            <input type="text"id="inputModificar4" name="link"class="inputModificar">
            <button type="button"id="btnModificar4"class="btnModificar">Modificar</button>
            <button type="button"id="btnCancelar4"class="btnCancelar">Cancelar</button>
        </div>
        

        <img src="" alt="" id="imgPreviewMod"class="imgPreview">
        <input type="text" id="idModificar" name="id"hidden>
        <input type="text" id="posicion" name="posicion"hidden>
        
        
        

        <button>Modificar</button>
    </form>
<div action="" method="post" id="formEliminar">
    <div class="cabeceraEliminar"><div class="tituloCabElim">Eliminar</div><img src="iconos/cancelar.svg" alt="" id="cancelar1"class="cancelar"></div>
    <div class="mensajeElim">Seguro que quieres eliminar la frase: <div class="fraseElim"></div></div>
    <div class="btnsElim"> <button id="fraseElim1"class="fraseElim">Si</button> <button id="cancelar2"class="cancelar">No</button></div>
</div>
<div class="mensaje"></div>
<div class="cajaDePalabras">
    <?php
    include_once "config.php";
    $consulta="SELECT id, frase, traduccion,afi, imagen FROM palabras  WHERE id_conjunto=$idConjunto AND id_usuario=$idUsuario ORDER BY posicion ASC";
    $sentencia=$db->prepare($consulta);
    if($sentencia->execute()){
        $sentencia->bind_result($id,$frase,$traduccion,$afi,$imagen);
        $template="";
        $contador=1;
        while($sentencia->fetch()){
            $comillas=strpos($frase, "'");
            $longitud=strlen($frase);
            $array = explode(" ", $frase);
            $espacios=count($array)-1/* ==1?count($array):count($array)-1 */;
            if(empty($imagen)) {
                if (/* $espacios > 1 || */($espacios == 1 && $comillas!==false) ||$longitud > 18) {
                    $template.=<<<EOT
                    <div class="palabra doble"id="palabra$contador">
                    <div class="imagen" style="display:none;"><img src="" alt=""class="img" id="imagen$contador"></div>
                    EOT;
                } else {
                    $template.=<<<EOT
                    <div class="palabra diminuta"id="palabra$contador">
                    <div class="imagen" style="display:none;"><img src="$imagen" alt=""class="img" id="imagen$contador"></div>
                    EOT;
                }
            }else{
                if ($espacios > 1 ||($espacios == 1 && $comillas!==false) ||$longitud > 18) {
                    $template.=<<<EOT
                    <div class="palabra dobleImg"id="palabra$contador">
                    <div class="imagen" style="display:block;"><img src="$imagen" alt=""class="img" id="imagen$contador"></div>
                    EOT;
                }else{
                    $template.=<<<EOT
                    <div class="palabra normal"id="palabra$contador">
                    <div class="imagen" style="display:block;"><img src="$imagen" alt=""class="img" id="imagen$contador"></div>
                    EOT;
                }
            }
            $Arrayfrase=explode(" ", $frase);
            $fraseMod="";
            $contadorFrase=1;
            foreach ($Arrayfrase as $num=>$info) {
                $fraseMod.="<span class='sonidoIndividual spanFrase$contadorFrase' >"."$info"."</span>"." ";
                $contadorFrase++;
            }
            $Arrayfonetica=explode(" ", $afi);
            $afiMod="";
            $contadorAfi=1;
            foreach ($Arrayfonetica as $num=>$info) {
                if(strlen($info)>0){
                    $afiMod.="<span class='spanAfi$contadorAfi'>"."$info"."</span>"." ";
                    $contadorAfi++;
                }
                
            }
            $template.=<<<EOT
                    <div class="frase" id="frase$contador">$fraseMod</div>
                    <div class="traduccion" id="traduccion$contador">$traduccion</div>
                    <div class="fonetica" id="fonetica$contador">$afiMod</div>
                    <div class="herramientas"id="herramientas$contador">    
                        <input type="image" src="iconos/configuracion.svg" alt="" class="conf frase config"id="conf$contador"><input type="image" src="iconos/eliminar.svg" alt="" class="elim"title="Eliminar frase"id="elim$contador"value="$id"><input type="image" src="iconos/editar.svg" alt=""class="mod"title="Modificar frase"id="mod$contador"value="$id"><input type="image" src="iconos/moverse.svg" alt=""class="mover"id="mover$contador">
                        <input type="image" src="iconos/caracol.svg" alt="" class="sonidoLento" title="Reproducir audio lentamente"value="$frase"id="sonidoLento$contador">
                        <input type="image" src="iconos/sonido.svg" alt="" class="sonido"title="Reproducir audio" value="$frase"id="sonido$contador">
                    </div>
                </div>
            EOT;
            $contador++;
        }
        echo $template;
    }

    ?>

</div>
    <template class="template">
        <div class="palabra">
            <div class="imagen"><img src="" alt=""class="img"></div>
            <div class="frase"></div>
            <div class="traduccion"></div>
            <div class="fonetica"></div>
            <div class="herramientas">    
                <input type="image" src="iconos/configuracion.svg" alt="" class="conf frase config"><input type="image" src="iconos/eliminar.svg" alt="" class="elim"title="Eliminar frase"><input type="image" src="iconos/editar.svg" alt=""class="mod"title="Modificar frase"><input type="image" src="iconos/moverse.svg" alt=""class="mover">
                <input type="image" src="iconos/caracol.svg" alt="" class="sonidoLento" title="Reproducir audio lentamente">
                <input type="image" src="iconos/sonido.svg" alt="" class="sonido"title="Reproducir audio">
            </div>
        </div>
    </template>

    

    <script src="script/contenido.js"></script>
</body>
</html>