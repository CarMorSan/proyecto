const formulario=document.getElementById('form');
    formulario.addEventListener('submit',(e)=>{
        e.preventDefault();
        let datos=new FormData(formulario);
        datos.append('tipo_operacion', 'registrarUsuario');
        const url="consulta.php";
        fetch(url,{
            method:'POST',
            body:datos
        })
        .then(data=>data.json())
        .then(data=>{
            let usuario = document.getElementById("usuario");
            let email = document.getElementById("email");
            let password = document.getElementById("password");
            let confirmacion = document.getElementById("confirmacion");
            let mensaje=document.querySelector(".mensaje");

            if(data.usuario){
                usuario.style.outlineColor="red";
                usuario.focus();
                mensaje.innerHTML=data.usuario;
            }else if(data.email){
                email.style.outlineColor="red";
                email.focus();
                mensaje.innerHTML=data.email;
            }else if(data.password){
                password.style.outlineColor="red";
                password.focus();
                mensaje.innerHTML=data.password;
            }else if(data.confirmacion){
                confirmacion.style.outlineColor="red";
                confirmacion.focus();
                mensaje.innerHTML=data.confirmacion;
            }else if(data.correcto){
                mensaje.innerHTML=data.correcto;

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