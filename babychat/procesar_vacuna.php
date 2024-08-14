<?php
include 'conexion.php';

$id_bebe = $_POST['id_bebe'];
$id_vacuna = $_POST['id_vacuna'] ?? null;
$vacuna = $_POST['vaccine-name'];
$enfermedad_previene = $_POST['disease'];
$dosis = $_POST['dose'];
$edad_frecuencia = $_POST['age-frequency'];
$fecha_aplicacion = $_POST['application-date'];
$lote = $_POST['batch-number'];

if ($id_vacuna) {
    // Actualizar registro existente
    $sql = "UPDATE seguimiento_vacunacion SET vacuna='$vacuna', enfermedad_previene='$enfermedad_previene', dosis='$dosis', edad_frecuencia='$edad_frecuencia', fecha_aplicacion='$fecha_aplicacion', lote='$lote' WHERE id_vacuna='$id_vacuna'";
} else {
    // Insertar nuevo registro
    $sql = "INSERT INTO seguimiento_vacunacion (vacuna, enfermedad_previene, dosis, edad_frecuencia, fecha_aplicacion, lote, id_bebe) VALUES ('$vacuna', '$enfermedad_previene', '$dosis', '$edad_frecuencia', '$fecha_aplicacion', '$lote', '$id_bebe')";
}

if ($conn->query($sql) === TRUE) {
    header("Location: vacunacion.html?id_bebe=$id_bebe");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
