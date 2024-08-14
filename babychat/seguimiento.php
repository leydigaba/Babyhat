<?php
session_start();
error_reporting(E_ALL); // Mostrar todos los errores

if ($_SESSION['role'] !== 'padre') {
    header("Location: iniciar_sesion.html");
    exit();
}

require 'conexion.php'; // Asegúrate de que la conexión a la base de datos esté correcta.

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$id_bebe = $_GET['id_bebe'] ?? '';

// Verificar que el bebé seleccionado pertenece al padre que inició sesión
$id_bebe_seleccionado = $_POST['bebes'] ?? '';

if (empty($id_bebe_seleccionado)) {
    die("Error: No se seleccionó ningún bebé.");
}

$email_padre = $_SESSION['email']; // Email del padre autenticado

$query_validacion = "SELECT * FROM bebes WHERE id_bebe = '$id_bebe_seleccionado' AND email = '$email_padre'";
$result_validacion = $conexion->query($query_validacion);

if (!$result_validacion) {
    die("Error en la consulta: " . $conexion->error);
}

if ($result_validacion->num_rows == 0) {
    echo "Bebé no encontrado o no pertenece al padre actual.";
    exit(); // Detener la ejecución si el bebé no es válido.
} else {
    $id_bebe = $id_bebe_seleccionado; // Usar el bebé seleccionado para las consultas.
}

// Obtener los datos más recientes de las tablas de seguimiento
$query_peso = "SELECT peso, fecha FROM seguimiento_peso WHERE id_bebe = ? ORDER BY fecha DESC LIMIT 1";
$query_estatura = "SELECT estatura, fecha FROM seguimiento_estatura WHERE id_bebe = ? ORDER BY fecha DESC LIMIT 1";
$query_imc = "SELECT imc, fecha FROM seguimiento_imc WHERE id_bebe = ? ORDER BY fecha DESC LIMIT 1";

$stmt_peso = $conexion->prepare($query_peso);
$stmt_estatura = $conexion->prepare($query_estatura);
$stmt_imc = $conexion->prepare($query_imc);

if (!$stmt_peso || !$stmt_estatura || !$stmt_imc) {
    die("Error preparando las consultas: " . $conexion->error);
}

$stmt_peso->bind_param('s', $id_bebe);
$stmt_estatura->bind_param('s', $id_bebe);
$stmt_imc->bind_param('s', $id_bebe);

$stmt_peso->execute();
$stmt_estatura->execute();
$stmt_imc->execute();

$result_peso = $stmt_peso->get_result()->fetch_assoc();
$result_estatura = $stmt_estatura->get_result()->fetch_assoc();
$result_imc = $stmt_imc->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de bebé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .data-section {
            margin-bottom: 15px;
        }
        .data-section p {
            margin: 0;
            font-size: 18px;
        }
        .volver {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .volver button {
            position: relative;
            width: 30px;
            height: 30px;
            background-color: transparent;
            border: none;
            cursor: pointer;
            outline: none;
        }
        .volver button::before, .volver button::after {
            content: '';
            position: absolute;
            background-color: #000;
            height: 2px;
            width: 100%;
            top: 50%;
            left: 0;
            transform: translateY(-50%) rotate(45deg);
        }
        .volver button::after {
            transform: translateY(-50%) rotate(-45deg);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Seguimiento de bebé</h2>

        <div class="data-section">
            <p><strong>Peso más reciente:</strong> <?php echo $result_peso['peso'] ?? 'No disponible'; ?> kg (<?php echo $result_peso['fecha'] ?? 'Fecha no disponible'; ?>)</p>
        </div>

        <div class="data-section">
            <p><strong>Estatura más reciente:</strong> <?php echo $result_estatura['estatura'] ?? 'No disponible'; ?> cm (<?php echo $result_estatura['fecha'] ?? 'Fecha no disponible'; ?>)</p>
        </div>

        <div class="data-section">
            <p><strong>IMC más reciente:</strong> <?php echo $result_imc['imc'] ?? 'No disponible'; ?> (<?php echo $result_imc['fecha'] ?? 'Fecha no disponible'; ?>)</p>
        </div>

        <div class="volver">
            <button onclick="window.history.back();"></button>
        </div>
    </div>
</body>
</html>
