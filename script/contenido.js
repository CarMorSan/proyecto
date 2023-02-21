document.addEventListener("DOMContentLoaded", function () {
  var ultimo;
  const formulario = document.getElementById("form");
  const formMostrar = document.getElementById("formMostrar");
  var url = "consulta.php";
  //Agregar frase .......................................................................................................
  var seguirAgregando = false;
  const btnseguirAgregando = document.getElementById("seguirAgregando"); //Al presionar boton de Agregar otra palabra
  btnseguirAgregando.addEventListener("click", (e) => {
    seguirAgregando = true;
  });
  formulario.addEventListener("submit", (e) => {
    console.log("Entre en submit");
    var padre = document.querySelector(".cajaDePalabras");
    ultimo = padre.childElementCount;
    e.preventDefault();
    const datos = new FormData(document.getElementById("form"));
    datos.append("idUsuario", idUsuario);
    datos.append("idConjunto", idConjunto);
    datos.append("tipo_operacion", "agregarPalabra");
    datos.append("ultimo", ultimo);
    console.log("Ya se que entra aqui;");
    fetch(url, {
      method: "POST",
      body: datos,
    })
      .then((data) => data.json())
      .then((data) => {
        console.log(data);
        agregarPalabra(data);
      })
      .catch(function (error) {
        console.log("error", error);
      });
  });
  const agregarPalabra = (data) => {
    var contador;
    /* let ultimo = document.querySelector(".cajaDePalabras"); */
    if (ultimo>0) {
      let numeros = [];
      let nodo=document.querySelector(".cajaDePalabras").childNodes;
      nodo.forEach(Element => {
        if(Element.id){
          let elementoId=Element.id;
          if(elementoId.includes("palabra")){
            let numero=elementoId.replace('palabra', '');
            numero=Number(numero.toString());
            numeros.push(numero);   
          }
        }
      });
      contador=Math.max(...numeros);
      contador++;
    } else {
      contador = 1;
    }
    formulario.reset();
    let cajaDePalabras = document.querySelector(".cajaDePalabras");
    let nodo = document.createElement('div');
    nodo.classList.add("palabra");
    nodo.id=`palabra${contador}`;
    let template="";
    let array = data.palabra.split(" ");
    let espacios = array.length - 1;
    let imagen = data.imagen;
    let longitud=data.palabra.length;
    if (imagen === "") {//NO hay imagen
      if (/* espacios > 1 || */(espacios == 1 && data.palabra.includes("'")) ||longitud > 18) {
        nodo.classList.add("doble");
        template+=`<div class="imagen" style="display:none;"><img src="" alt=""class="img" id="imagen${contador}"></div>`;
      } else {
        nodo.classList.add("diminuta");
        template+=`<div class="imagen" style="display:none;"><img src="${imagen}" alt=""class="img" id="imagen${contador}"></div>`;
      }
    }else{//Hay imagen
      if (espacios > 1 ||(espacios == 1 && data.palabra.includes("'")) ||longitud > 18) {
        nodo.classList.add("dobleImg");
        template+=`<div class="imagen" style="display:block;"><img src="${imagen}" alt=""class="img" id="imagen${contador}"></div>`;
      } else {
        nodo.classList.add("normal");
        template+=`<div class="imagen" style="display:block;"><img src="${imagen}" alt=""class="img" id="imagen${contador}"></div>`;
      }
    }
    let palabras=data.palabra.split(" ");
    let palabrasMod="";
    let contadorPalabras=1;
    palabras.forEach(element=>{
      palabrasMod+=`<span class="sonidoIndividual spanFrase${contadorPalabras}" >${element}</span> `;
      contadorPalabras++;
    });
    let foneticas=data.fonetica.split(" ");
    let foneticaMod="";
    let contadorAfi=1;
    foneticas.forEach(element=>{
      foneticaMod+=`<span class="spanAfi${contadorAfi}" >${element}</span> `;
      contadorAfi++;
    });
    template+=`
    <div class="frase" id="frase${contador}">${palabrasMod}</div>
    <div class="traduccion" id="traduccion${contador}">${data.traduccion}</div>
    <div class="fonetica" id="fonetica${contador}">${foneticaMod}</div>
    <div class="herramientas"id="herramientas${contador}">    
        <input type="image" src="iconos/configuracion.svg" alt="" class="conf frase config"id="conf${contador}"><input type="image" src="iconos/eliminar.svg" alt="" class="elim"title="Eliminar frase"id="elim${contador}"value="${data.id}"><input type="image" src="iconos/editar.svg" alt=""class="mod"title="Modificar frase"id="mod${contador}"value="${data.id}"><input type="image" src="iconos/moverse.svg" alt=""class="mover"id="mover${contador}">
        <input type="image" src="iconos/caracol.svg" alt="" class="sonidoLento" title="Reproducir audio lentamente"value="${data.palabra}"id="sonidoLento${contador}">
        <input type="image" src="iconos/sonido.svg" alt="" class="sonido"title="Reproducir audio" value="${data.palabra}"id="sonido${contador}">
    </div>`;
    nodo.innerHTML = template;
    cajaDePalabras.appendChild(nodo);
    document.querySelector(".mensaje").style.backgroundColor = "springgreen";

    if (seguirAgregando == true) {
      const cantidadNodos = document.querySelectorAll(".palabra").length;
      document.getElementById("palabra" + cantidadNodos).scrollIntoView();
      seguirAgregando = false;
      document.getElementById("imgPreview").style.display = "none";
    } else {
      formulario.style.display = "none";
      document.getElementById("imgPreview").style.display = "none";
      document.querySelector(".oscuro").style.display = "none";
      document.querySelector(".mensaje").style.display = "block";
      document.querySelector(".mensaje").textContent =
        "Frase agregada correctamente";
      setTimeout(function () {
        document.querySelector(".mensaje").textContent = "";
        document.querySelector(".mensaje").style.display = "none";
      }, 2000);
      const ultimoElemento =
        document.querySelector(".cajaDePalabras").lastElementChild.id;
      document.getElementById(ultimoElemento).scrollIntoView();
    }
  };
  //Evento click en la caja de palabras..................................................................
  var foco = "";
  var contadorMovida = 0;
  var mover = 0;
  var delante = 0;
  var PosicionNodoAMover = "";
  var idDelante = "";
  var posicion;
  var NumIdMover_PrimerPalabra = "";
  var PosicionMover_PrimerPalabra = "";
  var NumIdMover_segundaPalabra = "";
  var PosicionMover_segundaPalabra = "";
  let eliminar="";
  var buscarNumeros = /(\d+)/g;
  var modificar="";
  var leyendo=false;
  var leyendoLento=false;
  const palabras = document.querySelector(".cajaDePalabras");
  document.body.addEventListener("click",(e) => {
      let element = e.target;
      let id = element.id;
      if (!element.classList.contains("cajaDePalabras")) {
        
        if(!element.classList.contains("fraseElim")){
          if(NumId = id.match(buscarNumeros)){
            NumId = Number(NumId.toString());
          }
          let nodo=document.querySelector(".cajaDePalabras").childNodes;
          let contador=1;
          nodo.forEach(Element => {
            if(Element.id){
              let elementoId=Element.id;
              if(elementoId=="palabra"+NumId){
                posicion=contador;  
              }
              contador++;
            }
          });
          console.log("posicion",posicion);
        }
        
        console.log("**************************************");
        console.log("NumId: ", NumId);
        /* var padre = document.querySelector(".cajaDePalabras");
        var posicion = Array.prototype.indexOf.call(padre.children,document.getElementById("palabra" + NumId));
        posicion++; */
        //Al dar click al boton de herramientas
        console.log("posicion : ", posicion);
        if (element.classList.contains("conf") &&NumIdMover_PrimerPalabra.length==0 /* &&posicion != 0 */) {
          
          var siguiente = element.nextSibling;
          if (siguiente.offsetParent === null) {
            document.getElementById("elim" + NumId).style.display = "block";
            document.getElementById("mod" + NumId).style.display = "block";
            document.getElementById("mover" + NumId).style.display = "block";
            foco = element.id;
          } else {
            document.getElementById("elim" + NumId).style.display = "none";
            document.getElementById("mod" + NumId).style.display = "none";
            document.getElementById("mover" + NumId).style.display = "none";
            foco = "";
          }
        }
        //Al hacer click en el boton de eliminar palabra..............................................................................
        if (element.classList.contains("elim") && NumIdMover_PrimerPalabra.length==0) {
          document.getElementById("formEliminar").style.display="block";
          document.querySelector(".fraseElim").innerText=document.getElementById("frase"+NumId).textContent;
          document.getElementById("fraseElim1").value=element.value;
          document.querySelector(".oscuro").style.display="block";
          eliminar="palabra"+NumId;
        }
        //Al hacer click en cancelar modificacion..............................................................................
        if(element.classList.contains("cancelar")&&NumIdMover_PrimerPalabra.length==0){
          document.getElementById("formEliminar").style.display="none";
          document.querySelector(".oscuro").style.display="none";
        }
        //Al hacer click en confirmar eliminacion..............................................................................
        if(element.id.includes("fraseElim")&&NumIdMover_PrimerPalabra.length==0){
          let cantidad=document.querySelector(".cajaDePalabras").childElementCount;
          let datos = new FormData();
          datos.append("posicionEliminar", posicion);
          datos.append("ultimo", cantidad);
          datos.append("idUsuario", idUsuario);
          datos.append("idConjunto", idConjunto);
          datos.append("idPalabra", element.value);
          datos.append("tipo_operacion", "eliminarPalabra");
          fetch(url, {
            method: "POST",
            body: datos,
          })
            .then((data) => data.json())
            .then((data) => {
              console.log(data);
              if (data.funciono == "correcto") {
                document.querySelector(".cajaDePalabras").removeChild(document.getElementById(eliminar));
                document.getElementById("formEliminar").style.display="none";
                document.querySelector(".oscuro").style.display="none";
                document.querySelector(".mensaje").style.display = "block";
                document.querySelector(".mensaje").textContent =
                  "Frase eliminada correctamente";
                document.querySelector(".mensaje").scrollIntoView();
                foco = "";
                setTimeout(function () {
                  document.querySelector(".mensaje").textContent = "";
                  document.querySelector(".mensaje").style.display = "none";
                }, 1000);
              }
            })
            .catch(function (error) {
              console.log("error", error);
            });
        }
        // Al hacer click en el boton de escuchar.........................................................................
        if (element.classList.contains("sonido") && NumIdMover_PrimerPalabra.length==0) {
            let textoAEscuchar =element.value;
            let mensaje = new SpeechSynthesisUtterance();
            mensaje.voice = vocesDisponibles[$voces.value];
            mensaje.volume = 1;
            mensaje.rate = 1;
            mensaje.text = textoAEscuchar;
            mensaje.pitch = 1;
            speechSynthesis.speak(mensaje);
        }
        // Al hacer click en el boton de escuchar lentamente.........................................................................
        if (element.classList.contains("sonidoLento") && NumIdMover_PrimerPalabra.length==0) {
            let textoAEscuchar = element.value;
            let mensaje = new SpeechSynthesisUtterance();
            mensaje.voice = vocesDisponibles[$voces.value];
            mensaje.volume = 1;
            mensaje.rate = 0.5;
            mensaje.text = textoAEscuchar;
            mensaje.pitch = 1;
            speechSynthesis.speak(mensaje);
        }
        //Sonido indivudual
        if (element.classList.contains("sonidoIndividual") && NumIdMover_PrimerPalabra.length==0) {
          let textoAEscuchar =element.textContent;
            let mensaje = new SpeechSynthesisUtterance();
            mensaje.voice = vocesDisponibles[$voces.value];
            mensaje.volume = 1;
            mensaje.rate = 1;
            mensaje.text = textoAEscuchar;
            mensaje.pitch = 1;
            speechSynthesis.speak(mensaje);
        }
        function bordear(numero, color, fondo) {
          document.getElementById("palabra" + numero).style.borderColor = color;
          document.getElementById("palabra" + numero).style.background = fondo;
        }
        if(id.includes(NumIdMover_PrimerPalabra)&&NumIdMover_PrimerPalabra.length!=0){
          console.log("pepeppe",NumIdMover_PrimerPalabra);
          bordear(NumIdMover_PrimerPalabra, "white", "white");
          NumIdMover_PrimerPalabra="";
          PosicionMover_PrimerPalabra="";
        }
        //Al hacer click en el lugar donde se movera la palabra...............................................................
        if (NumIdMover_PrimerPalabra.length!=0) {
          NumIdMover_segundaPalabra = NumId;
          PosicionMover_segundaPalabra=posicion;
          console.log("NumIdMover_segundaPalabra: "+NumIdMover_segundaPalabra);
          console.log("PosicionMover_segundaPalabra: "+PosicionMover_segundaPalabra);
          let datos = new FormData();
          datos.append("idUsuario", idUsuario);
          datos.append("idConjunto", idConjunto);
          datos.append("NumIdMover_PrimerPalabra", NumIdMover_PrimerPalabra);
          datos.append("PosicionMover_PrimerPalabra", PosicionMover_PrimerPalabra);
          datos.append("NumIdMover_segundaPalabra", NumIdMover_segundaPalabra);
          datos.append("PosicionMover_segundaPalabra", PosicionMover_segundaPalabra);
          datos.append("tipo_operacion", "moverPalabras");
          fetch(url, {
            method: "POST",
            body: datos,
          })
            .then((data) => data.json())
            .then((data) => {
              console.log("datos de php: ", data);
              var nodoMover = document.getElementById("palabra" + NumIdMover_PrimerPalabra);
              var nodoDelante = document.getElementById("palabra" + NumIdMover_segundaPalabra);
              var padre = document.querySelector(".cajaDePalabras");
              if (PosicionMover_PrimerPalabra < PosicionMover_segundaPalabra) {
                insertAfter(nodoDelante, nodoMover);
                console.log("el movido SI es menor");
              } else {
                console.log("el movido NO es menor");
                padre.insertBefore(nodoMover, nodoDelante);
              }
              function insertAfter(e, i) {
                if (e.nextSibling) {
                  console.log("e.nextSibling=", e.nextSibling);
                  e.parentNode.insertBefore(i, e.nextSibling);
                } else {
                  e.parentNode.appendChild(i);
                }
              }
              setTimeout(function () {
                nodoMover.style.backgroundColor="white";
              }, 1000);
              NumIdMover_PrimerPalabra="";

            })
            .catch(function (error) {
              console.log("error en el php", error);
              NumIdMover_PrimerPalabra="";
            });
          
        }
        //Al hacer click en el boton de mover palabra............................................................................................
        if (element.classList.contains("mover") && NumIdMover_PrimerPalabra.length==0) {
          NumIdMover_PrimerPalabra=NumId;
          PosicionMover_PrimerPalabra=posicion;
          console.log("NumIdMover_PrimerPalabra: "+NumIdMover_PrimerPalabra);
          console.log("PosicionMover_PrimerPalabra: "+PosicionMover_PrimerPalabra);
          bordear(NumIdMover_PrimerPalabra, "green", "#00ff90");
        }
        console.log("**************************************");
        //Al hacer click en boton de modificar palabra
        if (element.classList.contains("mod") && NumIdMover_PrimerPalabra.length==0) {
          modificar=document.getElementById("mod"+NumId).value;
          document.getElementById("imgPreviewMod").src = "";
          formularioMod.querySelector(".imgPreview").style.display = "none";
          formularioMod.content;
          formularioMod.querySelector("#infoModificar1").textContent =document.getElementById("frase" + NumId).textContent;
          formularioMod.querySelector("#infoModificar2").textContent =document.getElementById("traduccion" + NumId).textContent;
          formularioMod.querySelector("#infoModificar3").textContent =document.getElementById("fonetica" + NumId).textContent;
          try {
            formularioMod.querySelector("#infoModificar4").textContent =
              document.getElementById("imagen" + NumId).getAttribute("src");
          } catch (e) {}
          formularioMod.querySelector("#idModificar").value = element.value;
          formularioMod.querySelector("#posicion").value = NumId;
          try {
            if (document.getElementById("imagen" + NumId).getAttribute("src")) {
              formularioMod.querySelector("#imgPreviewMod").src = document
                .getElementById("imagen" + NumId)
                .getAttribute("src");
              formularioMod.querySelector(".imgPreview").style.display =
                "block";
            }
          } catch (e) {}
          formularioMod.style.display = "grid";
          formularioMod.scrollIntoView();
          document.querySelector(".oscuro").style.display = "block";
        }
      }
    },true);

  //Al salir de la cajita de la palbra se ocultan configuraciones de eliminar y modificar...............................................................
  document.body.addEventListener(
    "mouseleave",
    (e) => {
      let element = e.target;
      let id = element.id;
      if (element.classList.contains("palabra") && NumIdMover_PrimerPalabra.length==0) {
        if (foco != "") {
          let num = foco.replace("conf", "");
          document.getElementById("elim" + num).style.display = "none";
          document.getElementById("mod" + num).style.display = "none";
          document.getElementById("mover" + num).style.display = "none";
          foco = "";
        }
      }
      if(element.classList.contains("sonidoIndividual")){
        element.style.color="black";
        var numeroPalabra=element.parentNode;
        numeroPalabra=numeroPalabra.id;
        numeroPalabra=numeroPalabra.replace('frase', '');
        var numeroSpan=element.classList[1];
        numeroSpan=numeroSpan.replace('spanFrase', '');
        console.log("numero span",numeroSpan);
        var fonetica=document.getElementById("fonetica"+numeroPalabra);
        var afi=fonetica.querySelectorAll('span');
        console.log(afi[numeroSpan-1]);
        afi[numeroSpan-1].style.color="rgba(0, 0, 0, 0.377)";
        afi[numeroSpan-1].style.fontWeight="normal";
      }
    },
    true
  );
  //Al poner mouse encima donde queremos mover la palabra.......................................................................................
  document.body.addEventListener("mouseenter",(e) => {
      let element = e.target;
      let id = element.id;
      if(element.classList.contains("sonidoIndividual")){
        element.style.color="blue";
        var numeroPalabra=element.parentNode;
        numeroPalabra=numeroPalabra.id;
        numeroPalabra=numeroPalabra.replace('frase', '');
        var numeroSpan=element.classList[1];
        numeroSpan=numeroSpan.replace('spanFrase', '');
        console.log("numero span",numeroSpan);
        var fonetica=document.getElementById("fonetica"+numeroPalabra);
        var afi=fonetica.querySelectorAll('span');
        console.log(afi[numeroSpan-1]);
        afi[numeroSpan-1].style.color="blue";
        afi[numeroSpan-1].style.fontWeight="bold";
      }
    },true);
  //Al presionar boton de crear palabra, se muestra el formulario de creacion.......................................................................................
  const btnCrear = document.getElementById("crear");
  btnCrear.onclick = () => {
    document.getElementById("form").style.display = "block";
    document.querySelector(".oscuro").style.display = "block";
  };
  //Al presionar boton (x) en el formulario de creacion este se oculta..............................................................................................
  const cerrar = document.getElementById("cerrar");
  cerrar.onclick = () => {
    document.getElementById("form").reset();
    document.getElementById("form").style.display = "none";
    document.querySelector(".oscuro").style.display = "none";
    document.getElementById("imgPreview").style.display = "none";
  };
  //Previsualizar imagen en el registro de palabra..............................................................................................
  const inputImagen = document.getElementById("imagen");
  const imgPreview = document.getElementById("imgPreview");
  var pegar = false;
  inputImagen.oninput = () => {
    imgPreview.src = inputImagen.value;
    imgPreview.classList.add("imgPreview");
    imgPreview.style.display = "block";
    let valor = inputImagen.value;
    if (valor.length == 0) {
      console.log("entra a valor 0");
      inputImagen.style.borderColor = "rgba(0, 0, 0, 0.664)";
      imgPreview.style.display = "none";
    }
  };
  imgPreview.onerror = () => {
    let valor = inputImagen.value;
    inputImagen.value="";
    if (valor.length != 0) {
      console.log("imagen invalida");
      document.querySelector(".mensaje").style.display = "block";
      document.querySelector(".mensaje").textContent =
        "LInk de imagen icorrecto";
      document.querySelector(".mensaje").style.backgroundColor = "red";
      setTimeout(function () {
        document.querySelector(".mensaje").textContent = "";
        document.querySelector(".mensaje").style.display = "none";
      }, 3000);
      imgPreview.style.display = "none";
      inputImagen.style.borderColor = "red";
    }
  };
  imgPreview.onload = () => {
    inputImagen.style.borderColor = "rgba(0, 0, 0, 0.664)";
  };
  var movida = false;
  const btnMover = document.querySelector(".btnMover");
  btnMover.onclick = () => {
    movida = true;
    console.log("Se aplasto boton para mover");
    console.log(movida);
  };
  //Evento al hacer clicks dentro del Formulario para modificar palabra......................................................
  var formularioMod = document.getElementById("formMod");

  formularioMod.addEventListener("click", (e) => {
    var element = e.target;
    var imagen = document.getElementById("infoModificar4").textContent;

    if (element.id.includes("btnModificar")) {
      NumId = element.id.match(buscarNumeros);
      NumId = Number(NumId.toString());
      console.log(NumId);
      element.style.display = "none";
      document.getElementById("btnCancelar" + NumId).style.display = "block";
      document.getElementById("infoModificar" + NumId).style.display = "none";
      document.getElementById("inputModificar" + NumId).style.display = "block";
      document.getElementById("inputModificar" + NumId).required = true;
      document.getElementById("inputModificar" + NumId).focus();
    }
    if (element.id.includes("btnCancelar")) {
      NumId = element.id.match(buscarNumeros);
      NumId = Number(NumId.toString());
      console.log(NumId);
      element.style.display = "none";
      document.getElementById("btnModificar" + NumId).style.display = "block";
      document.getElementById("infoModificar" + NumId).style.display = "block";
      document.getElementById("inputModificar" + NumId).required = false;
      document.getElementById("inputModificar" + NumId).value = "";
      document.getElementById("inputModificar" + NumId).style.display = "none";
      if (imagen.length > 0) {
        document.getElementById("imgPreviewMod").src = imagen;
        document.getElementById("imgPreviewMod").style.display = "block";
      } else {
        document.getElementById("imgPreviewMod").style.display = "none";
      }
    }
  });
  //click en cerrar formulario de modificacion.....................................................
  var cerrarMod = document.getElementById("cerrarMod");
  cerrarMod.addEventListener("click", (e) => {
    /* imagen=""; */
    document.querySelectorAll(".btnModificar").forEach((element) => {
      element.style.display = "block";
    });
    document.querySelectorAll(".infoModificar").forEach((element) => {
      element.style.display = "block";
    });
    document.querySelectorAll(".btnCancelar").forEach((element) => {
      element.style.display = "none";
    });
    document.querySelectorAll(".inputModificar").forEach((element) => {
      element.required = false;
      element.value = "";
      element.style.display = "none";
    });
    formularioMod.reset();
    formularioMod.style.display = "none";
    document.querySelector(".oscuro").style.display = "none";
    document.getElementById("imgPreview").style.display = "none";
  });
  //Evento de enviar forumulario para modificar palabra.....................................................
  const formMod = document.getElementById("formMod");
  formMod.addEventListener("submit", (e) => {
    e.preventDefault();
    console.log("Entra en modificar-----*******");
    var url = "consulta.php";
    const datos = new FormData(formMod);
    datos.append("idPalabra", modificar);
    datos.append("tipo_operacion", "modificarPalabra");
     fetch(url, {
      method: "POST",
      body: datos,
    })
      .then((data) => data.json())
      .then((data) => {
        console.log(data);
        if(data.afi!=""){
          document.getElementById(
            "fonetica" + datos.get("posicion")
          ).textContent = data.afi;
        }
        if (datos.get("palabra")) {
          document.getElementById("frase" + datos.get("posicion")).textContent =
          datos.get("palabra").charAt(0).toUpperCase() + datos.get("palabra").slice(1);
        }
        if (datos.get("traduccion")) {
          document.getElementById(
            "traduccion" + datos.get("posicion")
          ).textContent = datos.get("traduccion").charAt(0).toUpperCase() + datos.get("traduccion").slice(1);
        }
        if (datos.get("fonetica")) {
          document.getElementById(
            "fonetica" + datos.get("posicion")
          ).textContent = datos.get("fonetica");
        }

        document.querySelectorAll(".btnModificar").forEach((element) => {
          element.style.display = "block";
        });
        document.querySelectorAll(".infoModificar").forEach((element) => {
          element.style.display = "block";
        });
        document.querySelectorAll(".btnCancelar").forEach((element) => {
          element.style.display = "none";
        });
        document.querySelectorAll(".inputModificar").forEach((element) => {
          element.required = false;
          element.value = "";
          element.style.display = "none";
        });

        formularioMod.reset();
        formularioMod.style.display = "none";
        document.querySelector(".oscuro").style.display = "none";
        document.getElementById("imgPreview").style.display = "none";

        document.querySelector(".mensaje").style.display = "block";
        document.querySelector(".mensaje").textContent =
          "Frase Modificada correctamente";
        setTimeout(function () {
          document.querySelector(".mensaje").textContent = "";
          document.querySelector(".mensaje").style.display = "none";
        }, 2000);
        //Aqui modifico la cajita de la palabra
      })
      .catch(function (error) {
        console.log("error", error);
      });
  });

  const inputImagenMod = document.getElementById("inputModificar4");
  const imgPreviewMod = document.getElementById("imgPreviewMod");
  var pegar = false;
  inputImagenMod.oninput = () => {
    imgPreviewMod.src = inputImagenMod.value;
    imgPreviewMod.classList.add("imgPreviewMod");
    imgPreviewMod.style.display = "block";
    let valor = inputImagenMod.value;
    if (valor.length == 0) {
      console.log("entra a valor 0");
      inputImagenMod.style.borderColor = "rgba(0, 0, 0, 0.664)";
      imgPreviewMod.style.display = "none";
    }
  };
  imgPreviewMod.onerror = () => {
    let valor = inputImagenMod.value;
    inputImagenMod.value="";
    if (valor.length != 0) {
      console.log("imagen invalida");
      document.querySelector(".mensaje").style.display = "block";
      document.querySelector(".mensaje").textContent =
        "LInk de imagen icorrecto";
      document.querySelector(".mensaje").style.backgroundColor = "red";
      setTimeout(function () {
        document.querySelector(".mensaje").textContent = "";
        document.querySelector(".mensaje").style.display = "none";
      }, 3000);
      imgPreviewMod.style.display = "none";
      inputImagenMod.style.borderColor = "red";
    }
  };
  imgPreviewMod.onload = () => {
    inputImagenMod.style.borderColor = "rgba(0, 0, 0, 0.664)";
  };


  //Sonido de texto en ingles............................................................................
  const IDIOMAS_PREFERIDOS = ["en-US"];
  const $voces = document.querySelector("#voces");
  let posibleIndice = 0,
  vocesDisponibles = [];
  // Función que pone las voces dentro del select
  const cargarVoces = () => {
    if (vocesDisponibles.length > 0) {
      console.log(
        "No se cargan las voces porque ya existen: ",
        vocesDisponibles
      );
      return;
    }
    vocesDisponibles = speechSynthesis.getVoices();
    posibleIndice = vocesDisponibles.findIndex((voz) =>{
      IDIOMAS_PREFERIDOS[0].includes(voz.lang)}
    );
    console.log(posibleIndice,"-------");
    if (posibleIndice === -1) posibleIndice = 0;
    vocesDisponibles.forEach((voz, indice) => {
      if (
        voz.lang.includes(IDIOMAS_PREFERIDOS[0]) ||
        voz.lang.includes(IDIOMAS_PREFERIDOS[1])
      ) {
        console.log(indice);
        console.log(voz);
        const opcion = document.createElement("option");
        opcion.value = indice;
        opcion.innerHTML = voz.name;
        opcion.selected = indice ===33 /* posibleIndice */;
        $voces.appendChild(opcion);
      }
    });
  };
  // Si no existe la API, lo indicamos
  if (!"speechSynthesis" in window)
    return alert("Lo siento, tu navegador no soporta esta tecnología");
  cargarVoces();
  // Si hay evento, entonces lo esperamos
  if ("onvoiceschanged" in speechSynthesis) {
    speechSynthesis.onvoiceschanged = function () {
      cargarVoces();
    };
  }
});




