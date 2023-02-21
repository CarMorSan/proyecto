<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','ingles');
define('DB_CHARSET','utf8');
$db=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$db->set_charset(DB_CHARSET);

$mover=1;
$delante=2;
$final=7;
$consultaCompleta="";
echo"mover: ".$mover;
echo" Delante de: ".$delante."<br>";
if($mover>$delante){
    $consulta = "UPDATE practica SET posicion=0 WHERE posicion=$mover";
    $db->query($consulta);
    $consultaCompleta.=$consulta."<br>";
    
    $is=$mover-1;
    for($i=$mover-1;     $i>=$delante+1;       $i--){
        echo$i;
        $consulta = "UPDATE practica SET posicion=$i+1 WHERE posicion=$i";
        $db->query($consulta);
        $consultaCompleta.=$consulta."<br>";
    }
    
    $consultaCompleta.="Modificacion de nuemero"."<br>";

    $consulta = "UPDATE practica SET posicion=$delante+1 WHERE posicion=0";
    $db->query($consulta);
    $consultaCompleta.=$consulta."<br>";
}else{

    $consulta = "UPDATE practica SET posicion=0 WHERE posicion=$mover";
    $db->query($consulta);
    $consultaCompleta.=$consulta."<br>";
    
    for($i=$mover+1;$i<=$delante;$i++){
        echo$i;
        $mod=$i-1;
        $consulta = "UPDATE practica SET posicion=$mod WHERE posicion=$i";
        $consultaCompleta.=$consulta."<br>";
        $db->query($consulta);
    }
    
    $consulta = "UPDATE practica SET posicion=$delante WHERE posicion=0";
    $db->query($consulta);
    $consultaCompleta.=$consulta."<br>";
    
    
}
echo "<p>".$consultaCompleta."</p>";





$consulta = "SELECT nombre, posicion from practica ORDER BY posicion";
if (!($sentencia=$db->prepare($consulta)) ) {
    echo "Falló la preparación: ";
}
if($sentencia->execute()){
    $sentencia->bind_result($id,$frase);
    while($sentencia->fetch()){
        echo $array['id']=$id;
        echo $array['frase']=$frase;echo"-----";
    }
}






/* if (!$db->multi_query($consulta)) {
    echo "Falló la multiconsulta: (" . $mysqli->errno . ") " . $mysqli->error;
} */

?>