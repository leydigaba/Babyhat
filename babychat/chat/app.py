from flask import Flask, request, jsonify, render_template
import requests
import json

app = Flask(__name__)

# Configuración de la API
api_key = "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"  # Reemplaza con tu clave de API
url = 'https://api.groq.com/openai/v1/chat/completions'

headers = {
    "Authorization": f"Bearer {api_key}",  
    "Content-Type": "application/json"
}

# Ruta para servir la página HTML
@app.route('/')
def index():
    return render_template('chat.html')  # Asegúrate de tener el archivo index.html en una carpeta llamada 'templates'

@app.route('/chat', methods=['POST'])
def chat():
    print("Solicitud POST recibida en /chat")  # Añade esta línea para depurar
    user_question = request.json.get('question', '')

    # Datos de la solicitud
    data = {
        "model": "gemma2-9b-it",
        "messages": [
            {
                "role": "system",
                "content": "Eres Gem, una IA especializada en pediatría, únicamente respondes preguntas relacionadas al tema y respondes amablemente que no puedes ofrecer información que no se relacione, además de recordar al usuario que todo lo que proporciones deberá ser interpretado con precaución ya que las Inteligencias Artificiales pueden generar información inexacta."
            },
            {
                "role": "user",
                "content": user_question
            }
        ],
        "temperature": 0.6,
        "max_tokens": 1024,
        "top_p": 1,
        "stream": False
    }

    try:
        response = requests.post(url, json=data, headers=headers)
        response.raise_for_status()
        response_data = response.json()

        if 'choices' in response_data and response_data['choices']:
            answer = response_data['choices'][0]['message']['content']
            return jsonify({"answer": answer})
        else:
            return jsonify({"answer": "No se encontraron resultados en la respuesta de la API."})
    except requests.exceptions.RequestException as e:
        return jsonify({"answer": f"Error en la solicitud: {e}"})
    except json.JSONDecodeError:
        return jsonify({"answer": "Error al decodificar la respuesta JSON."})
    except KeyError as e:
        return jsonify({"answer": f"Clave faltante en la respuesta JSON: {e}"})
    except Exception as e:
        return jsonify({"answer": f"Ocurrió un error inesperado: {e}"})

if __name__ == '__main__':
    app.run(debug=True)
