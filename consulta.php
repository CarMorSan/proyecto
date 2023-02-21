<?php
include_once "config.php";

include "funciones.php";
$tipo_consulta=$_POST['tipo_operacion'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(!empty($_POST['tipo_operacion'])){



        if($tipo_consulta=="registrarUsuario"){
            $usuario=$_POST['usuario'];
            $email=$_POST['email'];
            $password=$_POST["password"];
            $confirmacion=$_POST["confirmacion"];
            $array = array();
            if(!empty($usuario) &&strlen($usuario)>=6&&strlen($usuario)<=15 &&!strpos($usuario, " ")){
                if(!empty($usuario)&&filter_var($email, FILTER_VALIDATE_EMAIL)){
                    if(!empty($password)&&strlen($password)>7&&strlen($password)<33){
                        if(tiene_letras($password)){
                            if(tiene_minusculas($password)){
                                if(tiene_mayusculas($password)){
                                    if(tiene_numeros($password)){
                                        /* if(tiene_caracteres($password)){ */
                                            if(tiene_espacios_final_principio($password)){
                                                if(!empty($confirmacion)){
                                                    $usuario=test_input($usuario);
                                                    $email=test_input($email);
                                                    $password=test_input($password);
                                                    
                                                    if($password===$confirmacion){
                                                        $consulta="SELECT usuario FROM usuarios WHERE usuario=?";
                                                        $sentencia=$db->prepare($consulta);
                                                        $sentencia->bind_param('s',$usuario);
                                                        if($sentencia->execute()){
                                                            $sentencia->store_result();
                                                            $numero=$sentencia->num_rows;
                                                            $sentencia->free_result();
                                                            $sentencia->close();
                                                            if($numero!==1){
                                                                $consulta="SELECT correo FROM usuarios WHERE correo=?";
                                                                $sentencia=$db->prepare($consulta);
                                                                $sentencia->bind_param('s',$email);
                                                                if($sentencia->execute()){
                                                                    $sentencia->store_result();
                                                                    $numero=$sentencia->num_rows;
                                                                    $sentencia->free_result();
                                                                    $sentencia->close();
                                                                    if($numero!==1){
                                                                        $consulta="INSERT INTO usuarios (id, correo, password, usuario)
                                                                        VALUES (NULL,'$email','$password','$usuario')";
                                                                        $sentencia=$db->prepare($consulta);
                                                                        if($sentencia->execute()){
                                                                            $sentencia->free_result();
                                                                            $sentencia->close();
                                                                            $array["correcto"]="Registrado correctamente, inicie sesion <a href='login.php'>aqui</a>.";
                                                                        } 
                                                                    }else{
                                                                        $array["email"]="Ya se encuentra este correo registrado."." ".$numero;
                                                                    }
                                                                }else{
                                                                    $array["email"]="Hubo un fallo";
                                                                }
                                                            }else{
                                                                $array["usuario"]="Ya se encuentra el nombre, seleccione otro."." ".$numero;
                                                            }
                                                        }else{
                                                            $array["usuario"]="Hubo un fallo";
                                                        }
                                                    }else{
                                                        $array["confirmacion"]="Las contraseñas no coinciden";
                                                    }
                                                }else{
                                                    $array["confirmacion"]="Debe confirmar la contraseña";
                                                }
                                            }else{
                                                $array["password"]="No debe contener espacios al principio o al final";
                                            }
                                        /* }else{
                                            $array["password"]="Debe contener al menos un caracter (!\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~)";
                                        } */
                                    }else{
                                        $array["password"]="Debe contener al menos un numero";
                                    }
                                }else{
                                $array["password"]="Debe contener al menos una letra Mayscula";
                                }
                            }else{
                                $array["password"]="Debe contener al menos una letra minuscula";
                            }
                        }else{
                            $array["password"]="Debe contener letras";
                        }
                    }else{
                        $array["password"]="Minimo 8 caracteres de longitud";
                    }
                }else{
                    $array["email"]="ingresa un email valido";
                } 
            }else{
                $array["usuario"]="ingresa un usuario valido de entre 6 y 15 caracteres de longitud sin espacios.";
            }
            
            
            echo json_encode($array);
        }

        if($tipo_consulta=="login"){
            $email=$_POST['email'];
            $password=$_POST["password"];
            $array = array();
            if(!empty($email)&&filter_var($email, FILTER_VALIDATE_EMAIL)){
                if(!empty($password)&&strlen($password)>7&&strlen($password)<33){
                    $email=test_input($email);
                    $password=test_input($password);
                    $consulta="SELECT usuario, id FROM usuarios WHERE correo=? AND password=?";
                    $sentencia=$db->prepare($consulta);
                    $sentencia->bind_param('ss',$email,$password);
                    if($sentencia->execute()){
                        $sentencia->bind_result($usuario,$id);
                        $sentencia->store_result();
                        $sentencia->fetch();

                        $numero=$sentencia->num_rows;
                        $sentencia->free_result();
                        $sentencia->close();
                        if($numero==1){
                            session_start();
                            /* $_SESSION['usuario']=$usuario; */
                            $_SESSION['idUsuario']=$id;
                            /* $_SESSION['conjunto']=""; */
                            $array["correcto"]="correo o contraseña CORRECTOS";
                        }else{
                            $array["incorrecto"]="correo o contraseña incorrectos";
                        }
                    }    

                }else{
                    $array["password"]="ingresa entre 8 y 32 caracteres de longitud.";
                }
            }else{
                $array["email"]="ingresa un email valido";
            }
            echo json_encode($array);

        }

        if($tipo_consulta=="agregarPalabra"){
            $idConjunto=$_POST['idConjunto'];
            $idUsuario=$_POST['idUsuario'];
            $termino=test_input($_POST['termino']);
            $termino=ucfirst($termino);
            $traduccion=test_input($_POST['traduccion']);
            $traduccion=ucfirst($traduccion);
            $afi=$_POST['termino'];
            $imagen=test_input($_POST['imagen']);
            $ultimo=intval($_POST['ultimo']);
            $ultimo=$ultimo+1;

            include "foneticaphp.php";
            $afi=strtolower($afi);
            $fonetica="";
            $busqueda=explode(" ",$afi);
            foreach ($busqueda as $num=>$palabra) {
                if(isset($ArrayFonetica[$palabra])){
                    $nuevo=$ArrayFonetica[$palabra][0]/* ." " */;
                    $fonetica.=$nuevo;
                }else{
                    $fonetica.=$palabra;
                }
            }
            $fonetica=test_input($fonetica);

            $consulta="INSERT INTO palabras (id,frase,afi,traduccion,imagen,id_conjunto,posicion,id_usuario)
            VALUES (NULL,'$termino','$fonetica','$traduccion','$imagen',$idConjunto,$ultimo,$idUsuario)";
            $sentencia=$db->prepare($consulta);
            if($sentencia->execute()){
                $idDevuelto=$sentencia->insert_id;
                
                $array=array(
                    "id"=>"$idDevuelto",
                    "palabra"=>"$termino",
                    "traduccion"=>"$traduccion",
                    "fonetica"=>"$fonetica",
                    "imagen"=>"$imagen"
                );
                $sentencia->free_result();
                echo json_encode($array);
            }    
        }


        if($tipo_consulta=="mostrar"){
            
            session_start();
            $idConjunto=intval($_SESSION['idConjunto']);
            $idUsuario=intval($_SESSION['idUsuario']);
            $arrayNuevo=array();
            $consulta="SELECT id, frase, afi,traduccion, imagen FROM palabras ORDER BY posicion ASC";
            $sentencia=$db->prepare($consulta);
            
            if($sentencia->execute()){
                $sentencia->bind_result($id,$frase,$afi,$traduccion,$imagen);
                while($sentencia->fetch()){
                    $array['id']=$id;
                    $array['frase']=$frase;
                    $array['traduccion']=$traduccion;
                    $array['afi']=$afi;
                    $array['imagen']=$imagen;
                    $arrayNuevo[]=$array;
                }
                echo json_encode($arrayNuevo);
                /* $array = array(
                    "funciono" => "correcto"
                );
                echo json_encode($array); */
            }else{
                $array = array(
                    "funciono" => "correcto"
                );
                echo json_encode($array);
            }
            
            
            
        }

        if($tipo_consulta=="mostrarconjuntos"){
            $usuario=intval($_POST['id']);
            $consulta="SELECT id, nombre FROM conjuntos WHERE idUsuario = $usuario";//poner order by
            $sentencia=$db->prepare($consulta);
            if($sentencia->execute()){
                $sentencia->bind_result($id,$nombre);
                while($sentencia->fetch()){
                    $array['nombre']=$nombre;
                    $array['id']=$id;
                    $arrayNuevo[]=$array;
                }
            }else{
                $arrayNuevo["funiono"]="No funciono";
            }
            
            echo json_encode($arrayNuevo);

        }
        if($tipo_consulta=="AgregarConjunto"){
            $nombre=$_POST['tabla'];
            $usuario=intval($_POST['id']);
            $array = array();
            $consulta="INSERT INTO conjuntos (id,idUsuario,nombre)
            VALUES (NULL,$usuario,'$nombre')";
            $sentencia=$db->prepare($consulta);
            if($sentencia->execute()){
                $idGenerado=$sentencia->insert_id;
                $array["funciono"]="correcto";
                $array["nombre"]=$nombre;
                $array["idGenerado"]=$idGenerado;

                echo json_encode($array);
            }
            
        }
        if($tipo_consulta=="EliminarConjunto"){
            $idConjunto=$_POST['idConjunto'];
            $consulta="DELETE FROM conjuntos WHERE id = '$idConjunto'";
            $sentencia=$db->prepare($consulta);
            if($sentencia->execute()){
                
                $array = array(
                    "funciono" => "correcto",
                    "tabla" => "$idConjunto",
                );
                echo json_encode($array);

            }
            /* $consulta="DROP TABLE $tabla";
            $sentencia=$db->prepare($consulta);
            if($sentencia->execute()){
                
                $array = array(
                    "funciono" => "correcto",
                    "tabla" => "$tabla",
                );
                echo json_encode($array);

            } */
        }
        if($tipo_consulta=="modificarConjunto"){
            $idConjunto=$_POST['idConjunto'];
            $nuevoNombre=$_POST['nuevoNombre'];
            $consulta="UPDATE conjuntos SET nombre='$nuevoNombre'  WHERE id=$idConjunto";
            $sentencia=$db->prepare($consulta);
            if($sentencia->execute()){
                
                $array = array(
                    "funciono" => "correcto",
                    "tabla" => "$nuevoNombre",
                );
                echo json_encode($array);

            }
        }
        if($tipo_consulta=="revisarConjunto"){
            $idConjunto=$_POST['idConjunto'];
            $nombreConjunto=$_POST['nombreConjunto'];  
            session_start();
            $_SESSION['idConjunto']=$idConjunto;
            $_SESSION['nombreConjunto']=$nombreConjunto;
            $array = array(
                "funciono" => "correcto",
                "id"=> $_SESSION['idConjunto'],
                "nombreConjunto"=>$_SESSION['nombreConjunto']
            );
            echo json_encode($array);

            
        }

        if($tipo_consulta=="eliminarPalabra"){
            $idUsuario=$_POST['idUsuario'];
            $idConjunto=$_POST['idConjunto'];
            $idPalabra=$_POST['idPalabra'];
            $eliminar=$_POST['posicionEliminar'];
            $ultimo=$_POST['ultimo'];
            $consulta="";

            if($ultimo==$eliminar){
                $consultaEliminar="DELETE FROM palabras WHERE id = '$idPalabra' AND id_conjunto='$idConjunto' AND id_usuario='$idUsuario'";
                $sentencia=$db->prepare($consultaEliminar);
                if($sentencia->execute()){
                    $array = array(
                        "funciono" => "correcto",
                    );
                    echo json_encode($array);
                }
            }else{
                $consultaEliminar="DELETE FROM palabras WHERE id = '$idPalabra' AND id_conjunto='$idConjunto' AND id_usuario='$idUsuario'";
                $sentencia=$db->prepare($consultaEliminar);
                if($sentencia->execute()){
                    for($i=$eliminar+1;$i<=$ultimo;$i++){
                        $consulta .= "UPDATE palabras SET posicion=$i-1 WHERE posicion=$i AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;"; 
                    }
                    /* $consulta .= "UPDATE palabras SET posicion=$ultimo WHERE posicion=0 AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;"; */
                
                    if (!$db->multi_query($consulta)) {
                        $array = array(
                            "funciono" => "Fallo la multiconsulta"
                        );
                        echo json_encode($array);
                    }else{
                        $array = array(
                            "funciono" => "correcto",
                            "consutla" => $consulta,
                            "eliminar"=> $eliminar,
                            "ultimo"=> $ultimo
                        );
                        echo json_encode($array);
                    }
                }
            }/* else{
                $consultaEliminar="DELETE FROM palabras WHERE id = '$idPalabra' AND id_conjunto='$idConjunto' AND id_usuario='$idUsuario'";
                $sentencia=$db->prepare($consultaEliminar);
                if($sentencia->execute()){
                    for($i=$eliminar-1; $i>=$ultimo; $i--){
                        $consulta .= "UPDATE palabras SET posicion=$i+1 WHERE posicion=$i AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;"; 
                    }
                    $consulta .= "UPDATE palabras SET posicion=$ultimo WHERE posicion=0 AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;";
                    if (!$db->multi_query($consulta)) {
                        $array = array(
                            "funciono" => "Fallo la multiconsulta"
                        );
                        echo json_encode($array);
                    }else{
                        $array = array(
                            "funciono" => "correcto",
                            "consutla" => $consulta,
                            "eliminar"=> $eliminar,
                            "ultimo"=> $ultimo,
                            "comienzo"=> "yes"
                        );
                        echo json_encode($array);
                    }
                }
            } */
        }

        if($tipo_consulta=="moverPalabras"){
            $idUsuario=$_POST['idUsuario'];
            $idConjunto=$_POST['idConjunto'];
            $mover=$_POST['PosicionMover_PrimerPalabra'];
            $delante=$_POST['PosicionMover_segundaPalabra'];
            $consulta="";
            if($mover>$delante){
                $consulta .= "UPDATE palabras SET posicion=0 WHERE posicion=$mover AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;";
                for($i=$mover-1;     $i>=$delante;       $i--){
                    $consulta .= "UPDATE palabras SET posicion=$i+1 WHERE posicion=$i AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;"; 
                }
                $consulta .= "UPDATE palabras SET posicion=$delante WHERE posicion=0 AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;";
            }else{
                $consulta .= "UPDATE palabras SET posicion=0 WHERE posicion=$mover AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;";
                for($i=$mover+1;$i<=$delante;$i++){
                    $consulta .= "UPDATE palabras SET posicion=$i-1 WHERE posicion=$i AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;"; 
                }
                $consulta .= "UPDATE palabras SET posicion=$delante WHERE posicion=0 AND id_conjunto=$idConjunto AND id_usuario=$idUsuario;";
            }
            if (!$db->multi_query($consulta)) {
                $array = array(
                    "funciono" => "Fallo la multiconsulta"
                );
                echo json_encode($array);
            }else{
                $array = array(
                    "funciono" => "correcto",
                    "consutla" => $consulta,
                    "mover"=> $mover,
                    "delante"=> $delante
                );
                echo json_encode($array);
            }

        }
        /* $tabla="movida";
            $mover=1;
            $delante=6;
            $consulta="";
            if($mover>$delante){
                $consulta .= "UPDATE $tabla SET posicion=0 WHERE posicion=$mover;";
                for($i=$mover-1;     $i>=$delante;       $i--){
                    $consulta .= "UPDATE $tabla SET posicion=$i+1 WHERE posicion=$i;"; 
                }
                $consulta .= "UPDATE $tabla SET posicion=$delante WHERE posicion=0;";
            }else{
                $consulta .= "UPDATE $tabla SET posicion=0 WHERE posicion=$mover;";
                for($i=$mover+1;$i<=$delante;$i++){
                    $consulta .= "UPDATE $tabla SET posicion=$i-1 WHERE posicion=$i;"; 
                }
                $consulta .= "UPDATE $tabla SET posicion=$delante WHERE posicion=0;";
            }
            echo$consulta; */

        /* $consulta="SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE table_schema = 'ingles' ORDER BY create_time DESC";
        $sentencia=$db->prepare($consulta);
        $sentencia->execute();
        $sentencia->bind_result($NombreTabla);
        $NombreTabla=str_replace("_", " ", $NombreTabla);
        while($sentencia->fetch()){
            $array['nombre']=$NombreTabla;
            $arrayNuevo[]=$array;
        }
        echo json_encode($arrayNuevo);
        */

        if($tipo_consulta=="modificarPalabra"){
            $cadena="";
            $idPalabra=$_POST['idPalabra'];
            $afi="";


            if($_POST['palabra']){
                $nuevaPalabra=ucfirst($_POST['palabra']);
                $cadena.="frase="."'".$nuevaPalabra."'".", ";
                if(!$_POST['fonetica']){
                    include "foneticaphp.php";
                    $afi=strtolower($_POST['palabra']);
                    $fonetica="";
                    $busqueda=explode(" ",$afi);
                    foreach ($busqueda as $num=>$palabra) {
                        if(isset($ArrayFonetica[$palabra])){
                            $nuevo=$ArrayFonetica[$palabra][0]." ";
                            $fonetica.=$nuevo;
                        }else{
                            $fonetica.=$palabra;
                        }
                    }
                    $nuevaFonetican=test_input($fonetica);
                    $cadena.="afi="."'".$nuevaFonetican."'".", ";
                    $afi=$nuevaFonetican;
                }
            }
            if($_POST['traduccion']){
                $nuevaTraduccion=ucfirst($_POST['traduccion']);
                $cadena.="traduccion="."'".$nuevaTraduccion."'".", ";
            }
            if($_POST['fonetica']){
                $nuevaFonetican=$_POST['fonetica'];
                $cadena.="afi="."'".$nuevaFonetican."'".", ";
            }
            if($_POST['link']){
                $nuevoLink=$_POST['link'];
                $cadena.="imagen="."'".$nuevoLink."'".", ";
            }
            $cambios=rtrim($cadena,", ");

            $consulta="UPDATE palabras SET $cambios  WHERE id=$idPalabra";
            $sentencia=$db->prepare($consulta);
            if($sentencia->execute()){
                
                $array = array(
                    "funciono" => "correcto",
                    "afi" => $afi,
                );
                echo json_encode($array);

            }
        }

        if($tipo_consulta=="practicar"){
            if(isset($_POST['idConjunto'])){
                session_start();
                $_SESSION['idConjunto']=$_POST['idConjunto'];
                $array = array(
                    "funciono" => "correcto",
                    "id"=>$_SESSION['idConjunto']
                );
                echo json_encode($array);
            }
            

           
        }



        
    }
}

?>