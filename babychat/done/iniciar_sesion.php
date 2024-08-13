<?php
try {
    # Establece una conexión con la base de datos MySQL usando PDO
    $conMySQL = new PDO("mysql:host=localhost;port=3306;dbname=babychat", "root", "");
    $conMySQL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conMySQL->exec("SET CHARACTER SET UTF8");

    # Sanitiza los datos del formulario para prevenir inyecciones SQL y otros ataques
    $email = htmlspecialchars(addslashes($_POST["email"]));
    $pass = htmlspecialchars(addslashes($_POST["contrasena"]));

    # Prepara una sentencia SQL para seleccionar un usuario en la tabla 'padres'
    $sentenciaSQLPadres = "SELECT * FROM padres WHERE email = :login AND contrasena = :pass";
    $sentenciaPrepPadres = $conMySQL->prepare($sentenciaSQLPadres);
    $sentenciaPrepPadres->execute(array(":login" => $email, ":pass" => $pass));

    # Prepara una sentencia SQL para seleccionar un usuario en la tabla 'admin'
    $sentenciaSQLAdmin = "SELECT * FROM admin WHERE email = :login AND contrasena = :pass";
    $sentenciaPrepAdmin = $conMySQL->prepare($sentenciaSQLAdmin);
    $sentenciaPrepAdmin->execute(array(":login" => $email, ":pass" => $pass));

    # Verifica si se encontró el usuario en alguna de las tablas
    if ($sentenciaPrepPadres->rowCount() > 0) {
        session_start();
        $_SESSION["usuario"] = $_POST["email"];
        header("Location:chat.html");  # Redirige a la zona de padres
    } elseif ($sentenciaPrepAdmin->rowCount() > 0) {
        session_start();
        $_SESSION["usuario"] = $_POST["email"];
        header("Location:zona-admin.php");  # Redirige a la zona de administradores
    } else {
        header("Location:iniciar_sesion.html");
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    $conMySQL = null;
}
?>
