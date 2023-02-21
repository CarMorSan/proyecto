
console.log("pepep");
var recognition;
	var recognizing = false;
	if (!('webkitSpeechRecognition' in window)) {
		alert("¡API no soportada!");
	} else {
        /* console.log(recognition.lang); */
		recognition = new webkitSpeechRecognition();
		recognition.lang = "en-US";
		recognition.continuous = true;
		recognition.interimResults = true;

		recognition.onstart = function() {
			recognizing = true;
			console.log("empezando a escuchar");
		}
		recognition.onresult = function(event) {

		 for (var i = event.resultIndex; i < event.results.length; i++) {
			if(event.results[i].isFinal)
				document.getElementById("texto").innerHTML = event.results[i][0].transcript;
		    }
			
			//texto
		}
		recognition.onerror = function(event) {
		}
		recognition.onend = function() {
			recognizing = false;
			document.getElementById("procesar").innerHTML = "Hablar";
			console.log("terminó de escuchar, llegó a su fin");

		}

	}

	function procesar() {

		if (recognizing == false) {
			recognition.start();
			recognizing = true;
			document.getElementById("procesar").innerHTML = "Detener";

		} else {
			recognition.stop();
			recognizing = false;
			document.getElementById("procesar").innerHTML = "Hablar";
			document.getElementById("texto").innerHTML = "";

		}
	}



    procesar();

    document.body.addEventListener("click",(e) => {
        let element = e.target;
        console.log(element.id);
        if(element.id.includes("procesar")){
            procesar();
        }

    });


