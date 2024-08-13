</scrip>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - BabyChat</title>
    <style>
        body {
            height: 100vh;
            margin: 0;
            font-family: sans-serif;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .Canva1 {
            background-color: #F4F5F7;
            width: 500px;
            padding: 20px;
            border-radius: 8px;
            box-sizing: border-box; /* Asegura que el padding no afecte el ancho total */
        }


        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .header .title {
            font-size: 30px;
            text-align: center;
            margin-left: 0;
            margin-right: 30px; /* Espacio entre la imagen y el título */
        }

        .header .imagen {
            margin-left: 30px;
            width: 150px;
            height: 150px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Dos columnas de igual ancho */
            gap: 15px; /* Espacio entre columnas */
        }

        .form-item {
            display: flex;
            flex-direction: column;
        }

        .form-item label {
            font-size: 15px;
            margin-bottom: 5px;
        }

        .form-item p {
            font-size: 16px;
            padding: 8px;
            background-color: #ffffff56;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin: 0;
            box-sizing: border-box;
        }
        button {
            display: block;
            margin: 10px auto;
            padding: 10px;
            padding-right: 15px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 123, 255, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            width: 50%; /* Ajusta el ancho al 50% del contenedor */
            max-width: 200px; /* Define un ancho máximo si es necesario */
        }

        button:hover {
            background-color: #aebcd1;
            box-shadow: 0px 4px 12px rgba(0, 86, 179, 0.2);
        }

        /* Campos que ocupan el ancho completo */
        .form-item.full-width {
            grid-column: span 2;
        }

        .button-container {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 13px;
        }

        .editar {
            width: 45%;
            background-color: #515c6d;
            padding: 8px;
            color: #ffffff;
            transition: transform 0.2s;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }

        .edit {
            text-decoration: none;
            color: #ffffff;
            font-size: 14px;
        }

        .cambiar {
            width: 45%;
            background-color: #515c6d;
            padding: 8px;
            color: #ffffff;
            transition: transform 0.2s;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }

        .change {
            text-decoration: none;
            color: #ffffff;
            font-size: 14px;
        }

        .eliminar {
            width: 45%;
            background-color: #515c6d;
            padding: 8px;
            color: #ffffff;
            transition: transform 0.2s;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }

        .delete {
            text-decoration: none;
            color: #ffffff;
            font-size: 14px;
        }

        .editar:hover, .cambiar:hover, .eliminar:hover {
            transform: scale(1.05);
        }

    </style>
</head>
<body>
    <main>
        <div class="Canva1">
            <div class="header">
                <p class="title">Perfil</p>
                <img src="babyChat.png" class="imagen">
                <div>
                    <button type="button" class="cancel" onclick="closeModal()">X</button>
                </div>
                
            </div>
            <form action="ingreso.php" method="post">
                <div class="form-grid">
                    <div class="form-item">
                        <label for="nombre">Nombres:</label>
                        <p id="txtNombre"><?php echo $row['nombre']; ?></p>
                    </div><br>
                    
                    <div class="form-item">
                        <label for="primer_apellido">Primer apellido:</label>
                        <p id="txtPrimer_Apellido"><?php echo $row['primer_apellido']; ?></p>
                    </div>
                    <div class="form-item">
                        <label for="segundo_apellido">Segundo apellido:</label>
                        <p id="txtSegundo_Apellido"><?php echo $row['segundo_apellido']; ?></p>
                    </div>
                    
                    <div class="form-item">
                        <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                        <p id="txtFecha_Nacimiento"><?php echo $row['fecha_nacimiento']; ?></p>
                    </div>
                    <div class="form-item">
                        <label for="genero">Género:</label>
                        <p id="txtGenero"><?php echo $row['genero']; ?></p>
                    </div>

                    <div class="form-item full-width">
                        <label for="correo">Correo:</label>
                        <p id="txtCorreo"><?php echo $row['correo']; ?></p>
                    </div>
                </div>

                <div class="button-container">
                    <div class="Editar">
                        <a class="Edit" href="editar_perfil.html">Editar</a>
                    </div>
                    <div class="cambiar">
                        <a class="change" href="cambiar_contraseña.html">Cambiar contraseña</a>
                    </div>
                    <div class="eliminar">
                        <a class="delete" href="">Eliminar Perfil</a>
                    </div>
                </div>

            </form>
        </div>
    </main>
    <script>
        function loadProfilePicture(event) {
            const image = document.getElementById('profileImage');
            image.src = URL.createObjectURL(event.target.files[0]);
        }

        function closeModal() {
        window.parent.postMessage('closeModal', '*');
        }
    </script>
</body>
</html>