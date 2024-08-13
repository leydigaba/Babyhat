const Groq = require('groq-sdk');

// Crea una instancia del cliente de Groq
const groq = new Groq({
  apiKey: 'YOUR_API_KEY'  // Reemplaza con tu clave API de Groq
});

// Función principal para enviar una solicitud de chat completion
async function main() {
  try {
    // Realiza la solicitud de chat completion
    const chatCompletion = await groq.chat.completions.create({
      messages: [
        {
          role: 'user',
          content: 'Aquí va el contenido del prompt' // Reemplaza con el contenido real
        }
      ],
      model: 'llama3-8b-8192',
      temperature: 1,
      max_tokens: 1024,
      top_p: 1,
      stream: true,
      stop: null
    });

    // Maneja la respuesta a medida que se recibe
    for await (const chunk of chatCompletion) {
      process.stdout.write(chunk.choices[0]?.delta?.content || '');
    }
  } catch (error) {
    console.error('Error en la solicitud:', error);
  }
}

// Ejecuta la función principal
main();
