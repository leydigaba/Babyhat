<?php
include 'conexion.php';

$sql = "SELECT DATABASE()";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo "Conectado a la base de datos: " . $row['DATABASE()'];
} else {
    echo "Error en la consulta: " . $conn->error;
}

$conn->close();
?>
