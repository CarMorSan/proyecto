<?php
//funciones........................................................
        function test_input($data) {//Entrada de string de formularios
            $data = trim($data);//Quitar espacion en blanco al principio y al final.
            $data = addslashes($data);//Devuelve con barra invertida las comillas.
            $data = htmlspecialchars($data,ENT_NOQUOTES);//Convierte caracteres especiales en entidades HTML
            return $data;//Retorna el string libre de inyección SQL y XSS
        }



        function tiene_letras($texto){
            $letras="abcdefghyjklmnñopqrstuvwxyz";
            $texto = strtolower($texto);
            for($i=0; $i < strlen($texto); $i++){
                if(strrpos($letras, $texto[$i])!== false){
                    return true;
                }
                //return true;
            }
        return false;
        }

        function tiene_minusculas($texto){
            $letrasMinusculas="abcdefghyjklmnñopqrstuvwxyz";
            for($i=0; $i < strlen($texto); $i++){
                if (strrpos($letrasMinusculas, $texto[$i])!== false){
                    return true;
                }
            }
            return false;
        }

        function tiene_mayusculas($texto){
            $letrasMayusculas="ABCDEFGHYJKLMNÑOPQRSTUVWXYZ";
            for($i=0; $i < strlen($texto); $i++){
                if (strrpos($letrasMayusculas, $texto[$i])!== false){
                    return true;
                }
            }
            return false;
        }

        function tiene_numeros($texto){
            $numeros="0123456789";
            for($i=0; $i < strlen($texto); $i++){
                if (strrpos($numeros, $texto[$i])!== false){
                    return true;
                }
            }
            return false;
        }

        function tiene_caracteres($texto){
            $caracteres="!\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~";
            for($i=0; $i < strlen($texto); $i++){
                if (strrpos($caracteres, $texto[$i])!== false){
                    return true;
                }
            }
            return false;
        }

        function tiene_espacios_final_principio($texto){
            if($texto[0]!==" "&&$texto[strlen($texto)-1]!==" "){
                return true;
            }else{
                return false;
            }
        }


        ?>