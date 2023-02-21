var sonidoNormal=document.querySelector(".sonidoNormal");
var sonidoLento=document.querySelector(".sonidoLento");
var respuestaMostrada=document.querySelector(".respuestaMostrada");
var escribirRespuesta=document.querySelector(".escribirRespuesta");
var mostrarRespuesta=document.querySelector(".mostrarRespuesta");
var palabras;
var posicionPalabraActual=1;


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
    opcion.selected = indice === posibleIndice;
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
  /* traduccion.innerText=data[posicionPalabraActual-1]['traduccion'];
  respuesta.value=data[posicionPalabraActual-1]['frase']; */
  sonidoNormal.value=data[posicionPalabraActual-1]['frase'];
  sonidoLento.value=data[posicionPalabraActual-1]['frase'];
  respuestaMostrada.textContent=data[posicionPalabraActual-1]['frase'];
  /* palabraActual.innerText=(posicionPalabraActual)+" de "+(data.length); */
  palabras=data;
  escribirRespuesta.focus();
  funcionEsuchar(palabras[posicionPalabraActual-1]['frase'],"1");
})
.catch(function (error) {
  console.log("error", error);
});




document.body.addEventListener("click",(e) => {
    let element = e.target;
    if(element.classList.contains("sonidoNormal")){
        console.log("Hizo click en sonido de: "+sonidoNormal.value);
        escribirRespuesta.focus();
        funcionEsuchar(palabras[posicionPalabraActual-1]['frase'],"1");
    }
    if(element.classList.contains("sonidoLento")){
        console.log("Hizo click en sonido de: "+sonidoNormal.value);
        escribirRespuesta.focus();
        funcionEsuchar(palabras[posicionPalabraActual-1]['frase'],"0.5");
    }
    if(element.classList.contains("mostrarRespuesta")){
        console.log("Hizo click en sonido de: "+sonidoNormal.value);
        escribirRespuesta.focus();
        respuestaMostrada.style.visibility="visible";
    }
});