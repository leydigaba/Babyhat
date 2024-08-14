<?php
session_start();
if ($_SESSION['role'] !== 'padre') {
    header("Location: iniciar_sesion.html");
    exit();
}

require 'conexion.php'; // Asegúrate de que la conexión a la base de datos esté correcta.

$email = $_SESSION['email'];

// Consulta para obtener los datos del bebé
$query_bebes = "SELECT * FROM bebes WHERE email = ?";
$stmt_bebes = $conn->prepare($query_bebes);
$stmt_bebes->bind_param('s', $email);
$stmt_bebes->execute();
$result_bebes = $stmt_bebes->get_result();

// Consulta para obtener el nombre del padre
$query_padre = "SELECT nombres FROM padres WHERE email = ?";
$stmt_padre = $conn->prepare($query_padre);
$stmt_padre->bind_param('s', $email);
$stmt_padre->execute();
$result_padre = $stmt_padre->get_result();
$padre = $result_padre->fetch_assoc();
$nombre_padre = $padre['nombres'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BabyChat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 90vh;
            overflow: hidden;
            background-color: #fff;
            margin: 10px auto;
        }

        .sidebar {
            width: 20%;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .profile-section {
            text-align: center;
        }

        .profile-header {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            margin-left: -15px;
        }

        button {
            display: block;
            margin: 10px auto;
            padding: 10px;
            background-color: #515c6d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 123, 255, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            width: 100%; /* Ajusta el ancho al 100% del contenedor */
            max-width: 200px; /* Define un ancho máximo si es necesario */
        }

        button:hover {
            background-color: #aebcd1;
            box-shadow: 0px 4px 12px rgba(0, 86, 179, 0.2);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            overflow: hidden;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .main-content {
            width: 75%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
            box-sizing: border-box;
            padding-bottom: 60px;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            background: #fff;
            box-shadow: 0 0 10px rgba(221, 19, 19, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .chat-container {
            position: fixed;
            height: 100vh; 
            width: 80vw;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
            padding: auto;
            box-sizing: border-box;
            bottom: 0px;
        }

        .header {
            text-align: center;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
        }

        .content h2 {
            font-size: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .content ul {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content ul li {
            margin-bottom: 15px;
            text-align: center;
        }

        .content ul li a {
            text-decoration: none;
            color: #515c6d;
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .content ul li a:hover {
            background-color: #e0e0e0;
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
            width: 100%;
            box-sizing: border-box;
        }

        .footer p {
            margin: 0;
            font-size: 12px;
            color: #777;
        }

        .input-section {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            width: 100%;
            box-sizing: border-box;
        }

        .input-section input {
            width: 80%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        .input-section button {
            padding: 10px 20px;
            border: none;
            background-color: #007BFF;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .input-section button:hover {
            background-color: #0056b3;
        }

        /* Nuevos estilos para la selección del bebé y opciones */
        .profile-info p {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .profile-info select {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0 10px;
            box-sizing: border-box;
        }

        .main-options {
            margin-top: 10px;
        }

        .main-options ul {
            list-style: none;
            padding: 0;
        }

        .main-options li {
            margin-bottom: 10px;
        }

        .main-options li a {
            text-decoration: none;
            color: #515c6d;
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .main-options li a:hover {
            background-color: #e0e0e0;
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        function logout() {
            document.getElementById('logoutForm').submit();
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <div class="profile-section">
            <div class="profile-header">
                <img src="babyChat.png" alt="Imagen de BabyChat">
            </div>
            <!-- Botón que muestra el nombre del padre -->
            <button id="newChatBtn">+ Nuevo chat</button>
            
            <div class="profile-info">
                <form>
                    <label for="bebes">Selecciona a tu bebé:</label>
                    <select id="bebes" name="bebes">
                        <?php
                        while ($row = $result_bebes->fetch_assoc()) {
                            echo "<option value='{$row['id_bebe']}'>{$row['nombres']}</option>";
                        }
                        ?>
                    </select>
                </form>
                <button id="addBabyBtn">Agregar bebé</button>
            </div>
        </div>
        <div class="main-options">
            <h2>Principal</h2>
            <ul>
                <li><a id="trackBabyBtn" ><i class="fas fa-baby"></i> Seguimiento</a></li>
                <li><a id="vacunasBabyBtn"><i class="fas fa-syringe"></i> Vacunación</a></li>
            </ul>
        </div>
        <div>
            <div>
                <button id="viewFatherModalBtn"><?php echo htmlspecialchars($nombre_padre); ?></button>
            </div>
            <div>
                <form id="logoutForm" action="cerrar_sesion.php" method="POST" style="display:none;"></form>
                <button id="logoutBtn" onclick="logout()">Cerrar sesión</button>
            </div>
        </div>
        
    </div>

    <div id="registerBabyModal" class="modal">
        <div class="modal-content">
            <iframe src="registro_bebe2.html" style="width: 100%; height: 800px; border: none;"></iframe>
        </div>
    </div>
    <div id="trackingBabyModal" class="modal">
        <div class="modal-content">
        <iframe src="seguimiento.php?id_bebe=<?php echo $selected_bebe_id; ?>" style="width: 100%; height: 800px; border: none;"></iframe>

        </div>
    </div>
    <div id="vacunasBabyModal" class="modal">
        <div class="modal-content">
            <iframe src="vacunacion.php" style="width: 100%; height: 800px; border: none;"></iframe>
        </div>
    </div>
    <div id="viewFatherModal" class="modal">
        <div class="modal-content">
            <iframe src="vista_perfil2.php" style="width: 100%; height: 800px; border: none;"></iframe>
        </div>
    </div>
    <div id="newChatModal" class="modal">
        <div class="modal-content">
            <iframe src="ollama_javascript/index2.html" style="width: 70%; height: 800px; border: none; margin-right: 0;"></iframe>
        </div>
    </div>
//aqui va el html del chatbot

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById("registerBabyModal");
        const addBabyBtn = document.getElementById("addBabyBtn");

        addBabyBtn.onclick = function() {
            modal.style.display = "block";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Escuchar mensajes del iframe
        window.addEventListener('message', function(event) {
            if (event.data === 'closeModal') {
                modal.style.display = "none";
            }
        });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById("trackingBabyModal");
        const addBabyBtn = document.getElementById("trackBabyBtn");

        addBabyBtn.onclick = function() {
            modal.style.display = "block";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Escuchar mensajes del iframe
        window.addEventListener('message', function(event) {
            if (event.data === 'closeModal') {
                modal.style.display = "none";
            }
        });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById("viewFatherModal");
        const addBabyBtn = document.getElementById("viewFatherModalBtn");

        addBabyBtn.onclick = function() {
            modal.style.display = "block";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Escuchar mensajes del iframe
        window.addEventListener('message', function(event) {
            if (event.data === 'closeModal') {
                modal.style.display = "none";
            }
        });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById("vacunasBabyModal");
        const addBabyBtn = document.getElementById("vacunasBabyBtn");

        addBabyBtn.onclick = function() {
            modal.style.display = "block";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Escuchar mensajes del iframe
        window.addEventListener('message', function(event) {
            if (event.data === 'closeModal') {
                modal.style.display = "none";
            }
        });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById("newChatModal");
        const addBabyBtn = document.getElementById("newChatBtn");

        addBabyBtn.onclick = function() {
            modal.style.display = "block";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Escuchar mensajes del iframe
        window.addEventListener('message', function(event) {
            if (event.data === 'closeModal') {
                modal.style.display = "none";
            }
        });
        });
    </script>
</body>
</html>
