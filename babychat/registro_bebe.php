<?php
    session_start(); // Iniciar la sesión

    var_dump($_POST);

    try {
        // Verifica si el padre ha iniciado sesión
        if (!isset($_SESSION['email'])) {
            echo "<script>
                alert('Debe iniciar sesión para registrar un bebé.');
                window.location.href = 'iniciar_sesion.html'; // Redirigir a la página de inicio de sesión
            </script>";
            exit();
        }

        // Conexión a la base de datos
        $conMySQL = new PDO("mysql:host=localhost;dbname=babychat", "root", "");
        $conMySQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conMySQL->exec("SET CHARACTER SET UTF8");

        // Recolección y saneamiento de datos
        $nombres = htmlspecialchars(addslashes($_POST["nombres"]));
        $primer_apellido = htmlspecialchars(addslashes($_POST["primer_apellido"]));
        $segundo_apellido = htmlspecialchars(addslashes($_POST["segundo_apellido"]));
        $fecha_nacimiento = htmlspecialchars(addslashes($_POST["fecha_nacimiento"]));
        $genero = htmlspecialchars(addslashes($_POST["genero"]));
        $discapacidad = htmlspecialchars(addslashes($_POST["discapacidad"]));
        $alergias = htmlspecialchars(addslashes($_POST["alergias"]));
        $enfermedades = htmlspecialchars(addslashes($_POST["enfermedades"]));

        // Obtener el email del padre desde la sesión
        $email_padre = $_SESSION['email'];

        // Prepara una sentencia SQL para insertar un nuevo bebé en la tabla 'bebes'
        $sentenciaSQL = "INSERT INTO bebes (nombres, primer_apellido, segundo_apellido, fecha_nacimiento, genero, discapacidad, alergias, enfermedades, estatus, fecha_registro, email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'A', NOW(), ?)";
        $sentenciaPrep = $conMySQL->prepare($sentenciaSQL);
        $sentenciaPrep->bindParam(1, $nombres);
        $sentenciaPrep->bindParam(2, $primer_apellido);
        $sentenciaPrep->bindParam(3, $segundo_apellido);
        $sentenciaPrep->bindParam(4, $fecha_nacimiento);
        $sentenciaPrep->bindParam(5, $genero);
        $sentenciaPrep->bindParam(6, $discapacidad);
        $sentenciaPrep->bindParam(7, $alergias);
        $sentenciaPrep->bindParam(8, $enfermedades);
        $sentenciaPrep->bindParam(9, $email_padre);

        if ($sentenciaPrep->execute()) {
            header('Location: bebe_registrado.html'); // Redirigir a la página de confirmación
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