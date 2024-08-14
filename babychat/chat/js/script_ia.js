async function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const chatMessages = document.getElementById('chat-messages');

    // Deshabilitar el campo de entrada y el botón de enviar
    messageInput.disabled = true;
    sendButton.disabled = true;

    // Mostrar mensaje del usuario
    const userMessage = document.createElement('div');
    userMessage.className = 'user-message';
    userMessage.textContent = messageInput.value;
    chatMessages.appendChild(userMessage);

    saveMessage('user', messageInput.value);

    // Mostrar mensaje de carga de IA
    const loadingMessage = document.createElement('div');
    loadingMessage.className = 'ai-message loading';
    loadingMessage.innerHTML = '<div class="dot"></div><div class="dot"></div><div class="dot"></div>';
    chatMessages.appendChild(loadingMessage);

    if (abortController) {
        abortController.abort(); // Abort any previous request
    }
    abortController = new AbortController(); // Create a new controller

    try {
        const response = await fetch('https://api.groq.com/openai/v1/chat/completions', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer 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',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                messages: [
                    { role: 'system', content: 'Eres Gem, una IA especializada en pediatría, únicamente respondes preguntas relacionadas al tema y respondes amablemente que no puedes ofrecer información que no se relacione, además de recordar al usuario que todo lo que proporciones deberá ser interpretado con precaución ya que las Inteligencias Artificiales pueden generar información inexacta.' },
                    { role: 'user', content: messageInput.value }
                ],
                model: 'llama3-70b-8192',
                temperature: 1,
                max_tokens: 1024,
                top_p: 1
            }),
            signal: abortController.signal
        });

        const data = await response.json();
        loadingMessage.remove();

        // Mostrar respuesta de IA
        const aiMessage = document.createElement('div');
        aiMessage.className = 'ai-message';
        aiMessage.textContent = ''; // Temporarily empty while typing
        chatMessages.appendChild(aiMessage);

        await simulateTyping(aiMessage, data.choices[0].message.content);

        saveMessage('ai', data.choices[0].message.content);

        // Limpiar entrada y desplazarse al final del chat
        messageInput.value = '';
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Mostrar el botón para guardar el contenido
        showSaveButton(aiMessage, data.choices[0].message.content);

        // Guardar conversación
        saveConversation();

    } catch (error) {
        if (error.name === 'AbortError') {
            loadingMessage.remove();
            console.log('Fetch aborted');
        } else {
            console.error('Fetch error:', error);
        }
    } finally {
        // Habilitar el campo de entrada y el botón de enviar
        messageInput.disabled = false;
        sendButton.disabled = false;
    }
}