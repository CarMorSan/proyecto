document.addEventListener('DOMContentLoaded', function(){
    var url='consulta.php';
    //Crear nuevo conjunto en la base de datos#########################################################################################################
    const formulario=document.getElementById('nueva');
    formulario.addEventListener('submit',(e)=>{
        e.preventDefault();
        let datos=new FormData(document.getElementById('nueva'));
        datos.append('tipo_operacion', 'AgregarConjunto');
        console.log("Este es el id",id);
        datos.append('id', id);
        fetch(url,{
            method:'POST',
            body:datos
        })
        .then(data=>data.json())
        .then(data=>{
            crearConjunto(data);
        })
        .catch(function(error){
            console.log('error#####',error);
        })
    });
    const crearConjunto=data=>{
        console.log(data);
        if(data.funciono=="correcto"){
            console.log("funciono. el id es "+data.tabla);
            var cajaConjuntos = document.querySelector(".cajaConjuntos");
            var fragment = document.createDocumentFragment();
            var template = document.querySelector(".template").content;
            var ultimo=cajaConjuntos.childElementCount;
            ultimo++;
            console.log(ultimo);
            console.log(data);
            console.log(document.getElementById("inputModificar").value);
            template.querySelector('.conjuntos').id='conjunto'+ultimo;
            template.querySelector('.nombre').id="nombre"+ultimo;
            template.querySelector('.nombre').title=data.nombre;
            template.querySelector('.nombre').textContent=data.nombre;
            template.querySelector('.nombreModificar').id="nombreModificar"+ultimo;
            template.querySelector('.cancelar').id="cancelar"+ultimo;
            template.querySelector('.cancelar').value=data.idGenerado;
            template.querySelector('.herramientas').id="herramientas"+ultimo;
            template.querySelector('.modificar').id="modificar"+ultimo;
            template.querySelector('.modificar').value=ultimo;
            template.querySelector('.eliminar').id="eliminar"+ultimo;
            template.querySelector('.eliminar').value=data.idGenerado;
            template.querySelector('.herramientasMostrar').id="herramienta"+ultimo;
            template.querySelector('.herramientasMostrar').value=ultimo;
            template.querySelector('.botones').id="botones"+ultimo;
            template.querySelector('.practicar').value=data.idGenerado;
            template.querySelector('.revisar').id="revisar"+ultimo;
            template.querySelector('.revisar').value=data.idGenerado;
            template.querySelector('.botonModificar').id="botonModificar"+ultimo;
            template.querySelector('.botonModificar').value=data.idGenerado;
            const clone = template.cloneNode(true);
            fragment.appendChild(clone);
            cajaConjuntos.appendChild(fragment);
            console.log(formulario);
        }else{console.log("No funciono");}
    }
//Eventos onmouseleave
    var focoModificar="";
    var focoHerramientas="";
    document.body.addEventListener("mouseleave",(e) => {
        let element = e.target;
        let id = element.id;
        //ocultar herramientas al salir de caja de conjuntos
        if(id.includes("conjunto")){
            console.log("saliooo");
            if(focoModificar!=""){
                ocultarModificacion(focoModificar);
            }
            if(focoHerramientas!=""){
                document.getElementById("eliminar"+focoHerramientas).style.display="none";
                document.getElementById("modificar"+focoHerramientas).style.display="none";
            }
        }
        

    },true);

//Evento clicks
    document.body.addEventListener("click",(e) => {
        let element = e.target;
        let id = element.id;
        console.log(id);
        //click en agregar palabra
        if(id.includes("crear")){
            let nueva=document.getElementById('nueva');
            let inputModificar=document.getElementById('inputModificar');
            if(nueva.offsetParent === null){
                nueva.style.display='grid';
                inputModificar.focus();
            }else{
                nueva.style.display='none';
            }
            console.log("click en crear");
        }

        //Al hacer click en el boton de herramientas
        if(id.includes("herramienta")){
            console.log("click en boton herramientas");
            var anterior=element.previousSibling;
            console.log(anterior,"pop");
            if(anterior.offsetParent === null){
                anterior.style.display='block';
                anterior.previousSibling.style.display='block';
                focoHerramientas=id.replace("herramienta","");
            }else{
                anterior.style.display='none';
                anterior.previousSibling.style.display='none';
                focoHerramientas="";
            }
        }
        //Al hacer click en el boton de modificar de herramientas.
        if(id.includes("modificar")){
            console.log("click en modificar");
            mostrarModificacion(element.value);
        }
        //Al hacer click en el boton de cancelar modificacion.
        if(id.includes("cancelar")){
            console.log("click en cancelar modificacion");
            ocultarModificacion(element.value);
        }
        //Al  confirmar modificacion
        if(id.includes("botonModificar")){
            console.log("confirmar modificacion");
            var numero=id.replace("botonModificar","");
            var nombreModificar=document.getElementById('nombreModificar'+numero).value;
            console.log(nombreModificar+"----------");
            var datos = new FormData();
            datos.append('tipo_operacion', 'modificarConjunto');
            datos.append('idConjunto',element.value);
            datos.append('nuevoNombre',nombreModificar);
            fetch(url,{
                method:'POST',
                body:datos
            })
            .then(data=>data.json())
            .then(data=>{
                if(data.funciono=='correcto'){
                    document.getElementById('nombre'+numero).textContent=data.tabla;
                    document.getElementById('nombre'+numero).title=data.tabla;
                    ocultarModificacion(numero);
                }
                
            })
            .catch(function(error){
                console.log('error#####',error);
            })
        }
        //Al hacer click en el boton de eliminar de herramientas
        if(id.includes("eliminar")){
            console.log("hizo click en eliminar");
            const datos=new FormData();
            datos.append('tipo_operacion', 'EliminarConjunto');
            datos.append('idConjunto',element.value);
            fetch(url,{
                method:'POST',
                body:datos
            })
            .then(data=>data.json())
            .then(data=>{
                console.log(data);
                focoHerramientas="";
                if(data.funciono=="correcto"){
                    const eliminar=element.parentNode;
                    const nuevo=eliminar.parentNode;
                    console.log(nuevo.id);
                    const nodo=document.getElementById(nuevo.id)
                    document.querySelector('.cajaConjuntos').removeChild(nodo);
                }
            })
            .catch(function(error){
                console.log('error#####',error);
            })
        }
        
        //Al hacer click en el boton de revisar
        if(element.classList.contains("revisar")){
            let numero=element.id.replace('revisar', '');
            let nombreConjunto=document.getElementById("nombre"+numero).textContent;
            
            const datos=new FormData();
            datos.append('tipo_operacion', 'revisarConjunto');
            datos.append('idConjunto',element.value);
            datos.append('nombreConjunto',nombreConjunto);
            fetch(url,{
                method:'POST',
                body:datos
            })
            .then(data=>data.json())
            .then(data=>{
                console.log(element.value);
                console.log(data);
                if(data.funciono=="correcto"){
                    window.location="index.php";
                }
            })
            .catch(function(error){
                console.log('error#####',error);
            })
            /* localStorage.setItem("variablejs",element.value); */
            
        }
        //Al hacer click en el boton de practicar
        if(element.classList.contains("practicar")){
            const datos = new FormData();
            /* datos.append("idUsuario", idUsuario); */
            datos.append("idConjunto", element.value);
            datos.append("tipo_operacion", "practicar");
            /* datos.append("ultimo", ultimo); */
            fetch(url, {
                method: "POST",
                body: datos,
              })
                .then((data) => data.json())
                .then((data) => {
                  if(data.funciono=="correcto"){
                    console.log(data);
                    console.log("conjunto cargado correctamente");
                    window.location="practicar.php";
                  }
                })
                .catch(function (error) {
                  console.log("error", error);
                });
            

            /* console.log("click en practicar")
            localStorage.setItem("variablejs",element.value);
            window.location="practicar.html"; */
        }

    },true);

    const mostrarModificacion=(numId)=>{
        console.log("Este es el numId:",numId);
        document.getElementById('nombre'+numId).style.display="none";
        document.getElementById('herramientas'+numId).style.display="none";
        document.getElementById('nombreModificar'+numId).style.display="block";
        document.getElementById('cancelar'+numId).style.display="block";
        document.getElementById('botones'+numId).style.display="none";
        document.getElementById('botonModificar'+numId).style.display="block";
        document.getElementById('nombreModificar'+numId).focus();
        focoModificar=numId;
    };
    const ocultarModificacion=(numId)=>{
        document.getElementById('nombre'+numId).style.display="block";
        document.getElementById('herramientas'+numId).style.display="grid";
        document.getElementById("eliminar"+numId).style.display="none";
        document.getElementById("modificar"+numId).style.display="none";
        document.getElementById('nombreModificar'+numId).style.display="none";
        document.getElementById('nombreModificar'+numId).value="";
        document.getElementById('cancelar'+numId).style.display="none";
        document.getElementById('botones'+numId).style.display="grid";
        document.getElementById('botonModificar'+numId).style.display="none";
        focoModificar="";
    };
    
});