<?php
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','ingles');
define('DB_CHARSET','utf8');
$db=new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$db->set_charset(DB_CHARSET);


$consulta = "UPDATE practica SET posicion=1 WHERE nombre='a';";
$consulta .= "UPDATE practica SET posicion=2 WHERE nombre='b';";
$consulta .= "UPDATE practica SET posicion=3 WHERE nombre='c';";
$consulta .= "UPDATE practica SET posicion=4 WHERE nombre='d';";
$consulta .= "UPDATE practica SET posicion=5 WHERE nombre='e';";
$consulta .= "UPDATE practica SET posicion=6 WHERE nombre='f';";
$consulta .= "UPDATE practica SET posicion=7 WHERE nombre='g';";

if (!$db->multi_query($consulta)) {
    echo "Falló la multiconsulta: (" . $mysqli->errno . ") " . $mysqli->error;
}
    
?>