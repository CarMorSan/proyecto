/* var nombreConjunto = localStorage.getItem("variablejs"); */
var traduccion=document.getElementById("traduccion");
var respuesta=document.getElementById("respuesta");
var ayuda=document.getElementById("ayuda");
var mostrar=document.getElementById("mostrar");
var inputTextoIngresado=document.querySelector('#texto');
var palabraActual=document.getElementById("palabraActual");
var mostrarRespuesta=false;
var equivocado=false;
var posicionPalabraActual=1;
var palabras;
//Traer informacion de la base de datos
var url = "consulta.php";
const datos = new FormData();
datos.append("idConjunto", idConjunto);
datos.append("tipo_operacion", "mostrar");
fetch(url, {
  method: "POST",
  body: datos,
})
.then((data) => data.json())
.then((data) => {
  console.log(data);
  traduccion.innerText=data[posicionPalabraActual-1]['traduccion'];
  respuesta.value=data[posicionPalabraActual-1]['frase'];
  ayuda.innerText=data[posicionPalabraActual-1]['frase'];
  palabraActual.innerText=(posicionPalabraActual)+" de "+(data.length);
  palabras=data;
})
.catch(function (error) {
  console.log("error", error);
});
//Evento de presionar enter*****************************************
inputTextoIngresado.addEventListener('keydown', (e) => {
  if (e.key === 'Enter'/* &&posicionPalabraActual<palabras.length */) {
    if( inputTextoIngresado.value.localeCompare(respuesta.value, undefined, { sensitivity: 'base' }) ==0){
      inputTextoIngresado.style.borderColor='green';
      inputTextoIngresado.style.color='green';
      document.querySelector(".caja").style.borderColor="green";
      ayuda.style.visibility = 'visible';

      let textoAEscuchar = document.querySelector(".respuesta").textContent;
      let velocidad=1;
      funcionEsuchar(textoAEscuchar,velocidad);

      setTimeout(function(){
        if(posicionPalabraActual==palabras.length){
          alert("terminaste puto");
        }else{
          posicionPalabraActual++;
        }
        console.log("Respuesta correcrta");
        
        ayuda.style.visibility = 'hidden';
        traduccion.innerText=palabras[posicionPalabraActual-1]['traduccion'];
        respuesta.value=palabras[posicionPalabraActual-1]['frase'];
        ayuda.innerText=palabras[posicionPalabraActual-1]['frase'];
        inputTextoIngresado.value="";
        inputTextoIngresado.style.borderColor='black';
        inputTextoIngresado.style.color='black';
        document.querySelector(".caja").style.borderColor="black";
        mostrarRespuesta=false;
        palabraActual.innerText=(posicionPalabraActual)+" de "+(palabras.length);
        console.log("posicion palabra acutual= "+posicionPalabraActual);
        console.log("ultima= "+palabras.length);
        
      }, 1000);
      
      
    }else{
      inputTextoIngresado.style.borderColor='red';
      inputTextoIngresado.style.color='red';
      console.log("Incorrecto la respuesta es: ",respuesta.value);
      palabras[palabras.length]=palabras[posicionPalabraActual];
      palabraActual.innerText=(posicionPalabraActual+1)+" de "+(palabras.length);
    }
   }/*else{
    inputTextoIngresado.style.color='black';
    inputTextoIngresado.style.borderColor='black';
  } */
});
//Evento Clicks de la pagina**************************************************
const caja = document.querySelector(".caja");
caja.addEventListener("click",(e) => {
  let element = e.target;
  let id = element.id;
  //Click al boton de mostrar******************************************
  if (id=="mostrar"){
    console.log("hizo click en mostrar");
    if(mostrarRespuesta==true){
      ayuda.style.visibility = 'hidden';
      mostrarRespuesta=false;
    }else{
      ayuda.style.visibility = 'visible';
      mostrarRespuesta=true;
      palabras[palabras.length]=palabras[posicionPalabraActual];
      palabraActual.innerText=(posicionPalabraActual+1)+" de "+(palabras.length);
      console.log(palabras);
      inputTextoIngresado.focus();
    }
  }
  //Click al boton de escuchar******************************************
  if (id=="btnEscuchar") {
    // Al hacer click en el boton de escuchar.........................................................................
    console.log("hizo click en escuchar");
    let textoAEscuchar = document.querySelector(".respuesta").textContent;
    let velocidad=1;
    funcionEsuchar(textoAEscuchar,velocidad);
  }
  //Click al boton de caracol******************************************
  if (id=="caracol") {
    console.log("hizo click en caracol");
    // Al hacer click en el boton de escuchar lentamente.........................................................................
    let textoAEscuchar = document.querySelector(".respuesta").textContent;
    let velocidad=0.5;
    funcionEsuchar(textoAEscuchar,velocidad);
  }

},true);
//Sonido de inputTextoIngresado en ingles............................................................................
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
posibleIndice = vocesDisponibles.findIndex((voz) =>
  IDIOMAS_PREFERIDOS[0].includes(voz.lang)
);
if (posibleIndice === -1) posibleIndice = 0;
vocesDisponibles.forEach((voz, indice) => {
  if (
    voz.lang.includes(IDIOMAS_PREFERIDOS[0]) ||
    voz.lang.includes(IDIOMAS_PREFERIDOS[1])
  ) {
    const opcion = document.createElement("option");
    opcion.value = indice;
    opcion.innerHTML = voz.name;
    opcion.selected = indice ===33/* indice === posibleIndice */;
    $voces.appendChild(opcion);
  }
});
};
cargarVoces();
// Si hay evento, entonces lo esperamos
if ("onvoiceschanged" in speechSynthesis) {
  speechSynthesis.onvoiceschanged = function () {
    cargarVoces();
  };
}
let funcionEsuchar=(textoAEscuchar,velocidad)=>{
  let mensaje = new SpeechSynthesisUtterance();
  mensaje.voice = vocesDisponibles[$voces.value];
  mensaje.volume = 1;
  mensaje.rate = velocidad;
  mensaje.text = textoAEscuchar;
  mensaje.pitch = 1;
  speechSynthesis.speak(mensaje);
}