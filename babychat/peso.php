<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Insertar registro de peso - PHP con MySQL</title>
        <style>
            body {
                font-family: Georgia;
                max-width: 1000px;
                margin: 0 auto;
                padding: 20px;
                font-size: 25px;
                font-weight: bold;
                color: #000;
                position: absolute;
                top: 15px;
                right: 750px;
            }
            .next {
                font-family: Georgia, sans-serif;
                width: 100;
                background-color: #416d5a;
                color: white;
                text-decoration: none;
                padding: 10px;
                border: none;
                border-radius: 30px;
                cursor: pointer;
                font-size: 14px;
                margin-top: 10px;
                position: absolute;
                top: 50px;
                transition: background-color 0.3s;
            }
            .next:hover {
                background-color: #799674;
            }
            .preview {
                font-family: Georgia, sans-serif;
                width: 100;
                background-color: #416d5a;
                color: white;
                text-decoration: none;
                padding: 10px;
                border: none;
                border-radius: 30px;
                cursor: pointer;
                font-size: 14px;
                margin-top: 10px;
                position: absolute;
                top: 100px;
                transition: background-color 0.3s;
            }
            .preview:hover {
                background-color: #799674;
            }
        </style>
    </head>
    <body>
        <?php
            session_start(); // Iniciar la sesión

            try {
                // Verifica si hay un bebé seleccionado
                if (!isset($_POST['id_bebe']) || empty($_POST['id_bebe'])) {
                    echo "<script>
                        alert('Debe seleccionar un bebé para registrar el peso.');
                        window.history.back();
                    </script>";
                    exit();
                }

                // Conexión a la base de datos
                $conMySQL = new PDO("mysql:host=localhost;dbname=babychat", "root", "");
                $conMySQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conMySQL->exec("SET CHARACTER SET UTF8");

                // Recolección y saneamiento de datos
                $peso = htmlspecialchars(addslashes($_POST["peso"]));
                $fecha = htmlspecialchars(addslashes($_POST["fecha"]));
                $id_bebe = htmlspecialchars(addslashes($_POST["id_bebe"]));

                // Prepara una sentencia SQL para insertar un nuevo registro de peso en la tabla 'seguimiento_peso'
                $sentenciaSQL = "INSERT INTO seguimiento_peso (peso, fecha, id_bebe) 
                    VALUES (?, ?, ?)";
                $sentenciaPrep = $conMySQL->prepare($sentenciaSQL);
                $sentenciaPrep->bindParam(1, $peso);
                $sentenciaPrep->bindParam(2, $fecha);
                $sentenciaPrep->bindParam(3, $id_bebe);

                if ($sentenciaPrep->execute()) {
                    echo "<script>
                        alert('Registro de peso guardado correctamente.');
                        window.location.href = 'chat.html'; // Redirigir a la página de chat
                    </script>";
                    exit();
                } else {
                    echo "<script>
                        alert('Error al almacenar el registro en la base de datos.');
                        window.history.back();
                    </script>";
                    exit();
                }

            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            } finally {
                $conMySQL = null;
            }
        ?>
    </body>
</html>
