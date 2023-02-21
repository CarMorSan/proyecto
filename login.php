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
        <div class="titulo">Inicio de sesion</div>
        <form action="" method="post" id="form">
            <label for="email">Correo electronico:</label>
            <input type="text" name="email" id="email" class="input">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" class="input">
            <button>Ingresar</button>
            <label for="" id="registro">No tiene cuenta, registrese <a href="registro.php">aqui.</a></label>
        </form>
    </div>
    <div class="mensaje"></div>

    <script>
    const formulario=document.getElementById('form');
    formulario.addEventListener('submit',(e)=>{
        e.preventDefault();
        let datos=new FormData(formulario);
        datos.append('tipo_operacion', 'login');
        const url="consulta.php";
        fetch(url,{
            method:'POST',
            body:datos
        })
        .then(data=>data.json())
        .then(data=>{
            let email = document.getElementById("email");
            let password = document.getElementById("password");
            let mensaje=document.querySelector(".mensaje");
            if(data.email){
                email.style.outlineColor="red";
                email.focus();
                mensaje.innerHTML=data.email;
            }else if(data.password){
                password.style.outlineColor="red";
                password.focus();
                mensaje.innerHTML=data.password;
            }else if(data.incorrecto){
                mensaje.innerHTML=data.incorrecto;
            }else if(data.correcto){
                mensaje.innerHTML=data.correcto;
                window.location.href="conjuntos.php";
            }
            
            
            
        })
        .catch(function(error){
            console.log('error#####',error);
        })
        document.body.addEventListener("input",(e)=>{
            let element=e.target;
            if(element.classList.contains("input")){
            document.getElementById(element.id).style.outlineColor="black";

            }
        })
    });
</script>
</body>
</html>


