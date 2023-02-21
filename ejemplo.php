<?php
include "fonetica.php";

$objeto = new stdClass();
$array=array();
$anterior = array();
$porciones = explode("\n", $fonetica);
$existe=0;
$pronunciaciones=array();

foreach ($porciones as $num=>$info) {
    $nuevo=explode(",",$info);
    $palabra=$nuevo[0];
    $fon=$nuevo[1];
    if(isset($palabra,$fon)){
        if(strpos($nuevo[1],"aw")!==false){
            $nuevo[1]=str_replace("aw","aʊ",$nuevo[1]);
            $fon=$nuevo[1];
        }
        if(strpos($nuevo[1],"ow")!==false){
            $nuevo[1]=str_replace("ow","oʊ",$nuevo[1]);
            $fon=$nuevo[1];
        }
        if(strpos($nuevo[1],"ej")!==false){
            $nuevo[1]=str_replace("ej","eɪ",$nuevo[1]);
            $fon=$nuevo[1];
        }
        if(strpos($nuevo[1],"aj")!==false){
            $nuevo[1]=str_replace("aj","aɪ",$nuevo[1]);
            $fon=$nuevo[1];
        }
        if(strpos($nuevo[1],"ɔj")!==false){
            $nuevo[1]=str_replace("ɔj","ɔɪ",$nuevo[1]);
            $fon=$nuevo[1];
        }
        if(strpos($nuevo[1],"ɚˈ")!==false){
            $nuevo[1]=str_replace("ɚˈ","ɝˈ",$nuevo[1]);
            $fon=$nuevo[1];
        }
        if(strpos($nuevo[1],"ɚˌ")!==false){
            $nuevo[1]=str_replace("ɚˌ","ɝˌ",$nuevo[1]);
            $fon=$nuevo[1];
        }




        if(strpos($nuevo[1],"ʌˈ")!==false){
            $nuevo[1]=str_replace("ʌˈ","|",$nuevo[1]);
            $fon=$nuevo[1];
        }
        if(strpos($nuevo[1],"ʌˌ")!==false){
            $nuevo[1]=str_replace("ʌˌ","&",$nuevo[1]);
            $fon=$nuevo[1];
        }




        if(strpos($nuevo[1],"ʌ")!==false){
            $nuevo[1]=str_replace("ʌ","ə",$nuevo[1]);
            $fon=$nuevo[1];
        }




        if(strpos($nuevo[1],"|")!==false){
            $nuevo[1]=str_replace("|","ʌˈ",$nuevo[1]);
            $fon=$nuevo[1];
        }
        if(strpos($nuevo[1],"&")!==false){
            $nuevo[1]=str_replace("&","ʌˌ",$nuevo[1]);
            $fon=$nuevo[1];
        }




        if(strpos($nuevo[1],"tɹ")!==false){
            $nuevo[1]=str_replace("tɹ","ʧɹ",$nuevo[1]);
            $fon=$nuevo[1];
        }
        




        if(strpos($nuevo[0],"(")!==false){//Diferentes tipos de pronunciaciones
            $pronunciaciones[$palabra]=$fon;
        }else{
            $array[$palabra]=array($fon);
            $objeto->$palabra="$fon";
        }
    }else{
        echo"<br>No existe########<br>";
        var_dump($porciones[$num]);
        echo"<br>No existe########<br>";
        $existe++;
    }
    
}

foreach ($pronunciaciones as $palabra=>$fonetica) {
    $buscar = array("(1)","(2)","(3)");
    $nombre=str_replace($buscar,'',$palabra);
    $array[$nombre][].="$fonetica";



}
$template=<<<EOT
array(<br>
EOT;
$fone='';
foreach ($array as $palabra=>$fonetica) {
    $ultimo=count($fonetica)-1;
    foreach($fonetica as $num=>$a){
        if($ultimo+1==1){
            $fone.=<<<EOT
            "$a"
            EOT;
        }else{
            if($num==$ultimo){
                $fone.=<<<EOT
                "$a"
                EOT;
            }else{
                $fone.=<<<EOT
                "$a",
                EOT;
            }
            
        }
        
    }
    $template.=<<<EOT
    "$palabra"=>array($fone),<br>
    EOT;
    $fone='';
}
$template.=<<<EOT
)
EOT;

$ejemplo = array(
    "foo" => "bar",
    "fee" => "feu",
    "multi" => array("pepe","pepo","juanito")
);

var_dump($template);


//pepepep
/* include "foneticaphp.php";

$frase="artist thirty mortal vertical bed";
$frase=strtolower($frase);
$fonetica="";
$busqueda=explode(" ",$frase);

foreach ($busqueda as $num=>$palabra) {
    if(isset($ArrayFonetica[$palabra])){
        echo"si esta <br>";
        $nuevo=$ArrayFonetica[$palabra][0]." ";
        var_dump($nuevo);
        if(strpos($nuevo,"ttɹ")!==false||strpos($nuevo,"tɹ")!==false){
            $buscar = array("ttɹ","tɹ");
            $nuevo=str_replace($buscar,"ʧɹ",$nuevo);
        }
        if(strpos($nuevo,"ɹt")!==false){
            $nuevo=str_replace("ɹt","t̟",$nuevo);
        }
         $fonetica.=$nuevo;
    }else{
        echo"No esta <br>";
        $fonetica.="<u>".$palabra."</u> ";
    }

}
echo"<br>".$frase."<br>";
echo"<br> Frase completa: ".$fonetica;
echo "ˈbʌʔn";
 */
?>