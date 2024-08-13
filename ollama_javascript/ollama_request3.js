function send_request() {
    // Leer valores del formulario "datos"
    var prompt = document.forms.datos.prompt.value;

    // Crear un tag <p></p> para mostrar el mensaje del usuario
    const responses = document.getElementById("responses");
    var userMessage = document.createElement("p");
    userMessage.classList.add("user-message");
    userMessage.innerHTML = prompt;
    responses.appendChild(userMessage);

    // Desplazar hacia abajo para mostrar el último mensaje
    responses.scrollTop = responses.scrollHeight;

    // Crear el payload para la solicitud
    const PAYLOAD = {
        messages: [
            {
                "role": "system",
                "content": "Eres una IA especializada en pediatría, únicamente respondes preguntas relacionadas al tema y respondes amablemente que no puedes ofrecer información que no se relacione, además de recordar al usuario que todo lo que proporciones deberá ser interpretado con precaución ya que las IAs pueden generar información inexacta."
            }
        ],
        model: "llama3.1",
        temperature: 1,
        prompt: prompt,
        stream: false,
        
    };

    const URL = "http://localhost:11434/api/generate";

    // Crea un objeto XMLHttpRequest para realizar solicitudes
    var request = new XMLHttpRequest();

    // Abre una conexión asíncrona
    request.open('POST', URL, true);
    // Configura los Headers
    request.setRequestHeader("Accept", "application/json");
    request.setRequestHeader("Content-Type", "application/json");

    // Envía la petición con los datos
    request.send(JSON.stringify(PAYLOAD));

    // Se ejecuta cuando la respuesta está lista
    request.onload = () => {
        // Valida el status de la respuesta
        if (request.status === 200) {
            // Almacena la respuesta del request
            const response = request.responseText;

            // Lo formatea a JSON
            const json = JSON.parse(response);

            // Crea un tag <p></p> para insertar la respuesta del prompt
            var botResponse = document.createElement("p");
            botResponse.innerHTML = json.response;

            // Lo agrega al <div> responses
            responses.appendChild(botResponse);

            // Desplazar hacia abajo para mostrar el último mensaje
            responses.scrollTop = responses.scrollHeight;
        } else {
            alert("Fallo en la conexión con el servidor");
        }
    };

    // Limpiar el input después de enviar
    document.forms.datos.prompt.value = '';
}
