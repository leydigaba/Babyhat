<?php
include 'conexion.php';

$id = $_GET['id'];
$id_bebe = $_GET['id_bebe'];

$sql = "DELETE FROM seguimiento_vacunacion WHERE id_vacuna='$id'";

if ($conn->query($sql) === TRUE) {
    header("Location: vacunacion.html?id_bebe=$id_bebe");
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
