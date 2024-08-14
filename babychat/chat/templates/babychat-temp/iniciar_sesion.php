<?php
session_start();
require 'conexion.php'; // Asegúrate de que este archivo tenga la configuración de tu conexión a la base de datos.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['contrasena'];

    // Consulta para verificar si el usuario es un administrador
    $admin_query = "SELECT * FROM admin WHERE email = ? AND contrasena = ?";
    $stmt = $conn->prepare($admin_query);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $admin_result = $stmt->get_result();

    // Si es administrador, redirigir a la página de administrador
    if ($admin_result->num_rows > 0) {
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'admin';
        header("Location: admin_bienvenida.php");
        exit();
    } 

    // Consulta para verificar si el usuario es un padre
    $parent_query = "SELECT * FROM padres WHERE email = ? AND contrasena = ? AND confirmar = ?";
    $stmt = $conn->prepare($parent_query);
    $stmt->bind_param('sss', $email, $password, $password); // Confirmar la contraseña
    $stmt->execute();
    $parent_result = $stmt->get_result();

    // Si es padre, redirigir a la página de chat
    if ($parent_result->num_rows > 0) {
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'padre';
        header("Location: chat.php");
        exit();
    } else {
        // Si no encuentra al usuario, mostrar un mensaje de error
        echo "<script>alert('Correo o contraseña incorrectos.'); window.location.href='iniciar_sesion.html';</script>";
    }
}
?>
